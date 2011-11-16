<?php
$module_pages_name = end(explode(DS, dirname(__file__)));
# Normal URLs
if($url_query[0] == $module_pages_name) {
    $Module_pages = true;
    
    if(isset($url_query[1])) {
        $pages_number = $url_query[1];
    }
    else {
        $pages_number = 1;
    }
}
# Admin URLs
$pages_admin_name = 'Module_'.$module_pages_name.'_admin';
if($$pages_admin_name){
    $pages_admin_all = false;
    $pages_admin_new = false;
    $pages_admin_update = false;
    
    if($url_query[2] != 'new' and $url_query[2] != 'update'){
        $pages_admin_all = true;
    }
    if($url_query[2] == 'new'){
        $pages_admin_new = true;
    }
    if($url_query[2] == 'update'){
        $pages_admin_update = true;
    }
}