<?php
/**
 * On-the-fly CSS Compression Script
 * Copyright (c) 2009 and onwards, Manas Tungare.
 * Creative Commons Attribution, Share-Alike.
 *
 * In order to minimize the number and size of HTTP requests for CSS content,
 * this script combines multiple CSS files into a single file and compresses
 * it on-the-fly.
 * 
 * To use this in your HTML, link to it in the usual way:
 * <link rel="stylesheet" type="text/css" media="screen, print, projection" href="/css/compressed.css.php" />
 */

/* Add your CSS files to this array */
if(isset($_GET['type']) and $_GET['type'] != 'admin'){
	$type = '.' . $_GET['type'];
}
elseif($_GET['type'] == 'admin'){
    $type = $_GET['type'];
}
else{
    $type = '';
}

if($type != 'admin'){
    require('../../settings.php');
    require('../../manage.php');
    $getmodules = $Modules->getModules();
    foreach($getmodules as $module) {
        if(file_exists(MODULE_ROOT . $module . DS . 'style.css')){
            $cssFiles[] = MODULE_ROOT . $module . DS . 'style.css';
        }
    }
    $file = 'style' . $type . '.css';
    if(file_exists(TEMPLATES_ROOT . 'site' . DS . $settings['theme'] . DS 
       . $file)){
        $cssFiles[] = TEMPLATES_ROOT . 'site' . DS . $settings['theme'] . DS . $file;
    }
    elseif(file_exists(TEMPLATES_ROOT . 'site' . DS . 'default' . DS 
           . $file)){
        $cssFiles[] = TEMPLATES_ROOT . 'site' . DS . 'default' . DS . $file;
    }
}
else{
    $dir = '.';
    $cssFolder = scandir($dir, 0);
    $exclude = array('.', '..', '.DS_Store', 'compressed.php');
    $cssFiles = array();
    foreach($cssFolder as $file) {
        if(!in_array($file, $exclude)){
            $cssFiles[] = $file;
        }
    }
}

/**
 * Ideally, you wouldn't need to change any code beyond this point.
 */
$buffer = "";
foreach ($cssFiles as $cssFile) {
  $buffer .= file_get_contents($cssFile);
}

// Remove comments
$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);

// Remove space after colons
$buffer = str_replace(': ', ':', $buffer);

// Remove whitespace
$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);

// Enable GZip encoding.
ob_start("ob_gzhandler");

// Enable caching
header('Cache-Control: public'); 

// Expire in one week
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 604800) . ' GMT'); 

// Set the correct MIME type, because Apache won't set it for us
header("Content-type: text/css");

// Write everything out
echo($buffer);