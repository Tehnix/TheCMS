<?php
if($Module_pages) {
	$pages = new Pages;
	$getPage = $pages->getPage($pages_number);
	
	$template_dir = TEMPLATES_ROOT . end(explode(DS, dirname(__file__))) . DS;
	
	try {
		$tpl_content = new Template($template_dir . 'pages.tpl');
		$tpl_content->set('ID', $getPage['id']);
		$tpl_content->set('TITLE', $getPage['name']);
		$tpl_content->set('CONTENT' , $getPage['content']);
		$tpl_content->set('DATETIME' , date("c", strtotime($getPage['modify'])));
		if($getPage['discussion'] == 1) {
			$tpl_content->set('DISCUSSION', 'comments section goes here !!');
		}
		else {
			$tpl_content->set('DISCUSSION', '');
		}

		$tpl_content = $tpl_content->output();
	} 
	catch (Exception $e) {
		print_r($getPage);
		$content = 
		'<a href="' . $getPage['id'] . '"><h2>' . $getPage['name'] . '</h2></a>' 
		. 'Modified: ' . $getPage['modify'] 
		. '<br>' 
		. $getPage['content'];
	}

}