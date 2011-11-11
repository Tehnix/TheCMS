<?php
$module_media_name = end(explode(DS, dirname(__file__)));
# Normal URLs
if($url_query[0] == $module_media_name) {
	$Module_media = true;
	
}
# Admin URLs
$media_admin_name = 'Module_'.$module_media_name.'_admin';
if($$media_admin_name){
	$media_admin_all = false;
	$media_admin_new = false;
	$media_admin_update = false;
	
	if($url_query[2] != 'new' and $url_query[2] != 'view'){
		$media_admin_all = true;
	}
	if($url_query[2] == 'new'){
		$media_admin_new = true;
	}
	if($url_query[2] == 'view'){
		$media_admin_view = true;
	}
}