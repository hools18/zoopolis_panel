<?php
header("Content-Type: text/css");
header("X-Content-Type-Options: nosniff");
header("Content-Type: text/css;X-Content-Type-Options: nosniff;");
require "lessc.inc.php";
$mc = new Memcached();
$mc->addServer("127.0.0.1", 11211);

$allowedExtensions = array('less');
function filemtime_r($path){
    global $allowedExtensions;
    
    if (!file_exists($path))
        return 0;
    
    $extension = end(explode(".", $path));     
    if (is_file($path) && in_array($extension, $allowedExtensions))
        return filemtime($path);
    $ret = 0;    
	foreach (glob($path."/*") as $fn){
		if (filemtime_r($fn) > $ret)
			$ret = filemtime_r($fn);    
	}
    return $ret;    
}
$styleless = filemtime_r('less');

if($mc->get("styleless") == $styleless){ 
	echo '/* '.$styleless.' */';
} else {
    unlink('zoopolis.css');
    $less = new lessc;
    $less->setFormatter("compressed");
    $less->checkedCompile("less/style.less", "zoopolis.css");
    $mc->set("styleless", $styleless, time() + 86400);
	
	include('zoopolis.css');
}
