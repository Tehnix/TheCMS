<?php
$module_blog_name = end(explode(DS, dirname(__file__)));
# Normal URLs
if($url_query[0] == $module_blog_name){
	$Module_blog = true;
	$blog_blog = false;
	$blog_page = false;
	$blog_article = false;
	$blog_category = false;
	$blog_tag = false;
	$blog_archive = false;
	
	if(isset($url_query[2]) and $url_query[1] != 'page') {
		if($url_query[1] == 'article') {
			$blog_article = true;
		}
		else if($url_query[1] == 'category') {
			$blog_category = true;
		}
		else if($url_query[1] == 'tag') {
			$blog_tag = true;
		}
		else if($url_query[1] == 'archive') {
			$blog_archive = true;
		}
		
		$blog_needle = $url_query[2];
	}
	else {
		$blog_blog = true;
		if(isset($url_query[3])) {
			$page_needle = $url_query[2];
			$page_ipp = $url_query[4];
		}
	}
}
# Admin URLs
$blog_admin_name = 'Module_'.$module_blog_name.'_admin';
if($$blog_admin_name){
	$blog_admin_all = false;
	$blog_admin_new = false;
	$blog_admin_update = false;
	
	if($url_query[2] != 'new' and $url_query[2] != 'update'){
		$blog_admin_all = true;
	}
	if($url_query[2] == 'new'){
		$blog_admin_new = true;
	}
	if($url_query[2] == 'update'){
		$blog_admin_update = true;
	}
}