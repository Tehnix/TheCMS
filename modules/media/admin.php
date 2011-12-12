<?php
$admin_count_array_left['Media'] = $Database->count('_uploads_log');
$admin_activity['uploads'] = array('upload'=>'Added a new file called {% ADDIT %}', 'delete'=>'Deleted a file called {% ADDIT %}');
if($$media_admin_name){
    $Media = new Media;
    $admin = new AdminGenerator;
    
    if($media_admin_all) {
        $pagination = new Paginator($url_query, $pagination_page, $pagination_ipp);
        $pagination->mid_range = 4;
        $pagination->default_ipp = 8;
        $pagination->paginate("_uploads_log", $pagination_page, $pagination_ipp);
        
        $media = '';
        foreach($Media->get('', $pagination->limit) as $item){
            $media .=
            '<tr onclick="document.location.href=\'' . URL_ROOT . ADMIN_PATH 
            . '/' . $module_media_name . '/update/' . $item['id'] . '\'">' .
            '<td>' . $item['log_originalname'] . '</td>' .
            '<td>' . number_format(($item['log_size']/100000), 2, '.', '') . ' MB</td>' .
            '<td>' . date('F d, Y', strtotime($item['log_date'])) . '</td>';
        }
        
        $style = '<style></style>';
        
        $top_right = $pagination->display_pages();
        $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_media_name . '/new">Add New Media <b>+</b></a> ';
        
        $full = '
        <table id="zebraTable">
            <thead>
                <tr>
                    <th style="width:60%;">File</th>
                    <th style="width:20%;">Size</th>
                    <th style="width:10%;">Date</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td>File</td>
                    <td>Size</td>
                    <td>Date</td>
                </tr>
            </tfoot>
            <tbody id="zebraTableBody">' 
            . $media . '
            </tbody>
        </table>';
        
        $tpl_content = new Template(Template::getAdminFile('one_col.tpl'));
        $tpl_content->set('SCRIPT', $admin->script);
        $tpl_content->set('STYLE', $style);
        $tpl_content->set('TOP_RIGHT', $top_right);
        $tpl_content->set('TOP_LEFT', $top_left);
        $tpl_content->set('FULL' , $full);
        
        $tpl_content = $tpl_content->output();
    }
    else if($media_admin_new) {
        $admin_title .= ' -> Add New';
        
        if(is_writable(UPLOAD_ROOT)){
            $admin->uploader("uploader");
            
            $style = '<style></style>';
            
            $top_right = '';
            $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_media_name . '">View All</a> ';

            $full = '
                <form>
                    <div id="uploader">
                        <p>You browser doesn\'t have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.</p>
                    </div>
                </form>
            ';
        }
        else{
            $top_right = '';
            $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_media_name . '">View All</a> ';

            $full = '
            <span style="color:red;">
                Upload folder not writable ! Please fix permissions...
            </span>';
        }
        
        $tpl_content = new Template(Template::getAdminFile('one_col.tpl'));
        $tpl_content->set('SCRIPT', $script.$admin->script);
        $tpl_content->set('STYLE', $style);
        $tpl_content->set('TOP_RIGHT', $top_right);
        $tpl_content->set('TOP_LEFT', $top_left);
        $tpl_content->set('FULL' , $full);
        
        $tpl_content = $tpl_content->output();
    }
    else if($media_admin_view){
        $error = true;
        if(isset($url_query[3])){
            $error = false;
            $media = $Media->get($url_query[3]);
            if(empty($media['log_id'])){
                $error = true;
            }
            $admin_title .= ' -> View';
            
            $displayMedia = $Media->display($media['log_filename']);
            
            $style = '<style></style>';
            
            $top_right = '';
            $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_media_name . '">View All</a>';
            
            $full =  $displayMedia;
        }
        if($error){
            $script = '';
            $style = '';
            $top = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_media_name . '">View All</a>';
            $full = '<div class="errormsg">Sorry, there wasn\'t found any data !</div>';
        }
        
        $tpl_content = new Template(Template::getAdminFile('two_col.tpl'));
        $tpl_content->set('SCRIPT', $admin->script);
        $tpl_content->set('STYLE', $style);
        $tpl_content->set('TOP_RIGHT', $top_right);
        $tpl_content->set('TOP_LEFT', $top_left);
        $tpl_content->set('FULL' , $full);

        $tpl_content = $tpl_content->output();
    }
}