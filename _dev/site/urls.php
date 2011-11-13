<?php
#____________________________________________________________________________#
# File: urls.php                                                             #
#____________________________________________________________________________#

$url_query = explode('/', URL);
$getmodules = $Modules->getModules();
if($url_query[0] == 'admin') {
	$Module_admin = true;
	
	foreach($getmodules as $module) {
		if(is_file(MODULE_ROOT . $module . DS . 'admin.php')) {
			if($url_query[1] == $module) {
				$module = 'Module_' . $module . '_admin';
				$$module = true;
			}
		}
	}
}

for($i = 0; $i < sizeof($url_query); $i++){
	if($url_query[$i] == 'page' and $url_query[$i+2] == 'ipp'){
		$pagination_page = $url_query[$i+1];
		$pagination_ipp = $url_query[$i+3];
	}
}