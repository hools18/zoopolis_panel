<?php
header("Content-Type: application/javascript");
header("X-Content-Type-Options: nosniff");
header("Content-Type: application/javascript; X-Content-Type-Options: nosniff;");

$allowedExtensions = array('html');
function filemtime_r($path){
    global $allowedExtensions;    
    if (!file_exists($path))
        return 0;
	$tmp = explode(".", $path);
    $extension = end($tmp);     
    if (is_file($path) && in_array($extension, $allowedExtensions))
        return filemtime($path);
    $ret = 0;    
	foreach (glob($path."/*") as $fn){
		if (filemtime_r($fn) > $ret)
			$ret = filemtime_r($fn);    
	}
    return $ret;    
}
$template = filemtime_r('../pages/');
?>
var routes = [
    // Index page
    {
        path: "/",
        componentUrl: "./pages/home.html?v=<?php echo $template;?>",
        name: "home",
		options: {
			animate: false,
		}
    },

	{
        path: "/header",
        componentUrl: "./pages/header.html?v=<?php echo $template;?>"
    },
	{
        path: "/footer",
        componentUrl: "./pages/footer.html?v=<?php echo $template;?>"
    },
	
	{
        path: "/account",
        componentUrl: "./pages/user/profile.html?v=<?php echo $template;?>"
    },
	{
        path: "/login",
        componentUrl: "./pages/user/login.html?v=<?php echo $template;?>",
		options: {
			animate: false,
		}
    },

	{
        path: "/logout",
        componentUrl: "./pages/user/logout.html?v=<?php echo $template;?>"
    },

	
	{
        path: "/subscription",
        componentUrl: "./pages/subscription/info.html?v=<?php echo $template;?>",
		name: "subscription"
    },
	{
        path: "/subscribing",
        componentUrl: "./pages/subscription/subscribing.html?v=<?php echo $template;?>",
		name: "subscribing"
    },
	{
        path: "/nosub",
        componentUrl: "./pages/subscription/nosub.html?v=<?php echo $template;?>",
		name: "nosub"
    },
	{
        path: "/promo",
        componentUrl: "./pages/promo/promo.html?v=<?php echo $template;?>",
		name: "promo"
    },
	{
        path: "/promoedit/:id/",
        componentUrl: "./pages/promo/edit.html?v=<?php echo $template;?>",
		name: "promo"
    },

	{
        path: "/services",
        componentUrl: "./pages/services/services.html?v=<?php echo $template;?>",
		name: "services"
    },
	{
        path: "/services/:service/",
        componentUrl: "./pages/services/service.html?v=<?php echo $template;?>",
		name: "service"
    },


	{
        path: "/clients",
        componentUrl: "./pages/clients/list.html?v=<?php echo $template;?>",
		name: "clients"
    },
	{
        path: "/client/:userId/",
        componentUrl: "./pages/clients/client.html?v=<?php echo $template;?>",
		name: "client"
    },
	
	{
        path: "/veterinaryclinics",
        componentUrl: "./pages/veterinaryclinics/veterinaryclinics.html?v=<?php echo $template;?>",
		name: "veterinaryclinics"
    },
	{
        path: "/veterinaryclinic/:id/",
        componentUrl: "./pages/veterinaryclinics/clinic.html?v=<?php echo $template;?>",
		name: "clinic"
    },
	
	{
        path: "/missinganimal",
        componentUrl: "./pages/missinganimal/missinganimal.html?v=<?php echo $template;?>",
		name: "missinganimal"
    },
	{
        path: "/missinganimal/:id/",
        componentUrl: "./pages/missinganimal/info.html?v=<?php echo $template;?>",
    },
	

	{
        path: "/uikit",
        componentUrl: "./pages/uikit.html?v=<?php echo $template;?>",
		options: {
			animate: false,
		},
		on: {
			pageInit: function (e, page) {
				app.store.state.menu = 'uikit';
				app.views[0].router.refreshPage();	
			},
		}
    },
    // Default route (404 page). MUST BE THE LAST
    {
        path: "(.*)",
        componentUrl: "./pages/404.html?v=<?php echo $template;?>",
    },
];
