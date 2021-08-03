<?php
/*
Plugin Name: Blox Page Builder
Plugin URI: http://blox-builder.com
Description: Blox Page Builder, the ultimate page builder for WordPress
Author: Blox Themes
Version: 1.0.64
Author URI: http://blox-builder.com
*/

//ini_set("display_errors", "on");
//ini_set("error_reporting", E_ALL);

if(!defined("BLOXBUILDER_INC"))
	define("BLOXBUILDER_INC", true);

$mainFilepath = __FILE__;
$currentFolder = dirname($mainFilepath);
$pathProvider = $currentFolder."/provider/";


//phpinfo();
try{
	$pathAltLoader = $pathProvider."provider_alt_loader.php";
	if(file_exists($pathAltLoader)){
		require $pathAltLoader;
	}else{
	require_once $currentFolder.'/includes.php';
	
	require_once  GlobalsUC::$pathProvider."core/provider_main_file.php";
	}
	
}catch(Exception $e){
	$message = $e->getMessage();
	$trace = $e->getTraceAsString();
	echo "<br>";
	echo $message;
	echo "<pre>";
	print_r($trace);
}


