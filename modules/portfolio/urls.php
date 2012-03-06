<?php
$dir = explode(DS, dirname(__file__));
$module_portfolio_name = end($dir);
# Admin URLs
$portfolio_admin_name = 'Module_' . $module_portfolio_name . '_admin';
if (isset($$portfolio_admin_name) and $$portfolio_admin_name) {
    $portfolio_admin_all = false;
    $portfolio_admin_new = false;
    $portfolio_admin_update = false;
    $portfolio_admin_images = false;
    $portfolio_admin_image_add = false;
    $portfolio_admin_image_update = false;
    
    if ($url_query[2] != 'new' and $url_query[2] != 'update' and $url_query[2] != 'view' and $url_query[3] != 'add' and $url_query[3] != 'update') {
        $portfolio_admin_all = true;
    }
    if ($url_query[2] == 'new') {
        $portfolio_admin_new = true;
    }
    if ($url_query[2] == 'update') {
        $portfolio_admin_update = true;
    }
    if ($url_query[2] == 'view') {
        $portfolio_admin_images = true;
    }
    if ($url_query[2] == 'image' and $url_query[3] == 'add') {
        $portfolio_admin_image_add = true;
    }
    if ($url_query[2] == 'image' and $url_query[3] == 'update') {
        $portfolio_admin_image_update = true;
    }
    
}