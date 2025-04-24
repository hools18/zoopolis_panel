<?php $domain = $_SERVER['SERVER_NAME'];
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
$template = filemtime_r('pages');
?>
<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, viewport-fit=cover">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="theme-color" content="#fff">
<meta http-equiv="Content-Security-Policy" content="default-src * 'self' 'unsafe-inline' 'unsafe-eval' data: gap:">
	<title>Зоополис - Личный кабинет <?php echo $template;?></title>

	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo 'https://'.$domain;?>/assets/icons/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo 'https://'.$domain;?>/assets/icons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo 'https://'.$domain;?>/assets/icons/favicon-16x16.png">
	<link rel="manifest" href="<?php echo 'https://'.$domain;?>/site.webmanifest">
	<link rel="mask-icon" href="<?php echo 'https://'.$domain;?>/assets/icons/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">
	<link rel="apple-touch-icon" href="<?php echo 'https://'.$domain;?>/assets/icons/apple-touch-icon.png">
	<link rel="icon" href="<?php echo 'https://'.$domain;?>/assets/icons/favicon.ico">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo 'https://'.$domain;?>/framework7/framework7-bundle.min.css?v=<?php echo filemtime('framework7/framework7-bundle.min.css');?>">
	<link rel="stylesheet" href="<?php echo 'https://'.$domain;?>/css/zoopolis.css?v=<?php echo filemtime('css/zoopolis.css');?>">
	<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=4fb02c15-f66c-4d81-8357-70d95ccff377" type="text/javascript"></script>
</head>
<body>
	<div id="app">
		<div class="mainFrame view safe-areas">
			<div id="header" class="view-init" data-name="header" data-url="/header/"></div>
			<div id="main" class="view view-main view-init" data-name="main" data-master-detail-breakpoint="768" data-url="/" data-browser-history="true"></div>
			<div id="footer" class="view-init" data-name="footer" data-url="/footer/"></div>
		</div>
	</div>
	<script>
        var apiServer = 'https://api.sergivanov.ru/';
        var baseUrl = '/';

	</script>
	<script src="<?php echo 'https://'.$domain;?>/js/moment.min.js?v=<?php echo filemtime('js/moment.min.js');?>"></script>
	<script src="<?php echo 'https://'.$domain;?>/js/locale/ru.js?v=<?php echo filemtime('js/locale/ru.js');?>"></script>
	<script src="<?php echo 'https://'.$domain;?>/framework7/framework7-bundle.min.js?v=<?php echo filemtime('framework7/framework7-bundle.min.js');?>"></script>
	<script src="<?php echo 'https://'.$domain;?>/routes/?v=<?php echo $template;?>"></script>
	<script src="<?php echo 'https://'.$domain;?>/js/imask.min.js?v=<?php echo filemtime('js/imask.min.js');?>"></script>
	<script src="<?php echo 'https://'.$domain;?>/js/md5.min.js?v=<?php echo filemtime('js/md5.min.js');?>"></script>
	<script src="<?php echo 'https://'.$domain;?>/js/store.js?v=<?php echo filemtime('js/store.js');?>"></script>
	<script src="<?php echo 'https://'.$domain;?>/js/app.js?v=<?php echo filemtime('js/app.js');?>"></script>
	<script type="text/javascript" src="https://js.bepaid.by/widget/be_gateway.js"></script>
<!--	<link rel="stylesheet" href="--><?php //echo 'https://'.$domain;?><!--/css/">-->
</body>

</html>
