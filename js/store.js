

var createStore = Framework7.createStore;
const store = createStore({
    state: {
        profile: false,
		address: false,
		pets: false,
    },
	getters: {
		products({ state }) {
			return state.products;
		}
	},
    actions: {
		msgalert({ state }, {err, title}) {
			if(Array.isArray(err)){			
				app.dialog.alert(err.join('<br/>'), title);
			} else {
				app.dialog.alert(err, title);
			}			
		},
		toast({ state }, {text}) {
			toast = app.toast.create({
				text: text,
				closeTimeout: 2000
			});
			toast.open();
		},
		validemail({ state }, {email}) {
			let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			return regex.test(email);
		},
		
		pass({ state }, {pass}) {
			return pass;
		},

		
		createNewContest({ state }) {
			app.dialog.prompt('Введите название конкурса', function (name) {
				app.dialog.confirm('Создать новый конкурс - "' + name + '"?', function () {
					app.preloader.show();
					app.request.postJSON(apiServer+'contest/new', {name: name}).then(function (res) {
						console.log(res);
					});
					setTimeout(() => {
						app.preloader.hide();
						app.dialog.alert('Ok, your name is ' + name);
					}, 1000);
				});
			});
		},


		checkToken({ state }) {
			return new Promise((resolve, reject) => {
				if(app.form.getFormData('token')){
					// Токен записан в памяти браузера. Проверим его
					app.request.postJSON(apiServer+'user/token').then(function (res) {
						if(res.data) {
							if(res.data.error) {
								// Недействительный токен. Сбросим.
								app.store.dispatch('toast', {
									text: 'Недействительный токен. Сбросим.'
								});
								app.form.removeFormData('token');
								app.request.setup({
									headers: {
										'Authorization': false
									}
								});
								resolve(false);
							} else {
								// Токен верный. Получим пользовательские данные.
								app.store.state.profile = res.data;
								if(res.data.address){
									app.store.state.address = res.data.address;
								} else {
									app.store.state.address = [];
								};
								if(res.data.pets){
									app.store.state.pets = res.data.pets;
								} else {
									app.store.state.pets = [];
								};
								// app.store.dispatch('toast', {
								// 	text: 'Токен верный. Получаем пользовательские данные.'
								// });
								resolve(true);
							}
						} else {
							// Сервер выдал ошибку. Сбросим токен из памяти?
							app.store.dispatch('toast', {
								text: 'Неизвестная ошибка от сервера.'
							});
							return false;
						}
					});
				} else {					
					
					// Токена нет
					app.request.setup({
						headers: {
							'Authorization': false
						}
					});
					
					resolve(false);
				}
			});

		},
		getNoun({ state }, {number, one, two, five}) {
			return new Promise((resolve, reject) => {
				let n = Math.abs(number);
				n %= 100;
				if(n >= 5 && n <= 20) {
					resolve(five);
				}
				n %= 10;
				if(n === 1) {
					resolve(one);
				}
				if(n >= 2 && n <= 4) {
					resolve(two);
				}
				resolve(five);
			});
		},

	

		
    },
	getters: {
        usersLoading: ({ state }) => state.usersLoading,
        users: ({ state }) => state.users,
		status: ({ state }) => state.status,
		topchart: ({ state }) => state.topchart,
		lastnews: ({ state }) => state.lastnews,
		newsList: ({ state }) => state.newsList,
		newpl: ({ state }) => state.newpl,
		history: ({ state }) => state.history,
		
		courses: ({ state }) => state.courses,
		geo: ({ state }) => state.geo,
    },
})

/* * * * * * * * * * * * * * * * *
 * Pagination
 * * * * * * * * * * * * * * * * */
const Pagination = {
    code: '',
    Extend: function(data) {
        data = data || {};
        Pagination.size = data.size || 300;
        Pagination.page = data.page || 1;
        Pagination.step = data.step || 1;
    },
    // add pages by number (from [s] to [f])
    Add: function(s, f) {
        for (var i = s; i < f; i++) {
            Pagination.code += '<a data-page="'+i+'" >'+i+'</a>';
        }
    },
    // add last page with separator
    Last: function() {
        Pagination.code += '<i>...</i><a>' + Pagination.size + '</a>';
    },

    // add first page with separator
    First: function() {
        Pagination.code += '<a>1</a><i>...</i>';
    },
    // --------------------
    // Handlers
    // --------------------

    // change page
    Click: function() {
        Pagination.page = +this.innerHTML;
		console.log(Pagination.page);
		app.store.state.pagination = Pagination.page;
        Pagination.Start();
    },

    // previous page
    Prev: function() {
        Pagination.page--;
        if (Pagination.page < 1) {
            Pagination.page = 1;
        }
		app.store.state.pagination = Pagination.page;
        Pagination.Start();
    },
    // next page
    Next: function() {
        Pagination.page++;
        if (Pagination.page > Pagination.size) {
            Pagination.page = Pagination.size;
        }
		app.store.state.pagination = Pagination.page;
        Pagination.Start();
    },
    // --------------------
    // Script
    // --------------------

    // binding pages
    Bind: function() {
        var a = Pagination.e.getElementsByTagName('a');
        for (var i = 0; i < a.length; i++) {
            if (+a[i].innerHTML === Pagination.page) a[i].className = 'current';
            a[i].addEventListener('click', Pagination.Click, false);
        }
    },
    // write pagination
    Finish: function() {
        Pagination.e.innerHTML = Pagination.code;
        Pagination.code = '';
        Pagination.Bind();
    },
    // find pagination type
    Start: function() {
        if (Pagination.size < Pagination.step * 2 + 6) {
            Pagination.Add(1, Pagination.size + 1);
        }
        else if (Pagination.page < Pagination.step * 2 + 1) {
            Pagination.Add(1, Pagination.step * 2 + 4);
            Pagination.Last();
        }
        else if (Pagination.page > Pagination.size - Pagination.step * 2) {
            Pagination.First();
            Pagination.Add(Pagination.size - Pagination.step * 2 - 2, Pagination.size + 1);
        }
        else {
            Pagination.First();
            Pagination.Add(Pagination.page - Pagination.step, Pagination.page + Pagination.step + 1);
            Pagination.Last();
        }
        Pagination.Finish();
    },
    // --------------------
    // Initialization
    // --------------------
    // binding buttons
    Buttons: function(e) {
        var nav = e.getElementsByTagName('a');
        nav[0].addEventListener('click', Pagination.Prev, false);
        nav[1].addEventListener('click', Pagination.Next, false);
    },
    // create skeleton
    Create: function(e) {
        var html = [
            '<a><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 16 16"><path fill="#05103D" fill-rule="evenodd" d="m10.234 15.64 1.536-1.28-5.451-6.532 5.433-6.167-1.5-1.322-6.567 7.454 6.549 7.848Z" clip-rule="evenodd"/></svg></a>', // previous button
            '<span></span>',  // pagination container
            '<a><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 16 16"><path stroke="#05103D" stroke-width="2" d="m5 15 6-7.19L5 1"/></svg></a>'  // next button
        ];
        e.innerHTML = html.join('');
        Pagination.e = e.getElementsByTagName('span')[0];
        Pagination.Buttons(e);
    },
    // init
    Init: function(e, data) {
        Pagination.Extend(data);
        Pagination.Create(e);
        Pagination.Start();
    }
};