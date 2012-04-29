<?php
$admin_count_array_left['Media'] = $Database->count('_uploads_log', array('trash'=>'0'));
$admin_activity['uploads'] = array('upload'=>'Added a new file called {% ADDIT %}', 'delete'=>'Deleted a file called {% ADDIT %}');
if(isset($$media_admin_name) and $$media_admin_name){
    $Media = new Media;
    $admin = new AdminGenerator;
    
    if($media_admin_all) {
        $pagination = new Paginator($url_query, $pagination_page, $pagination_ipp);
        $pagination->mid_range = 4;
        $pagination->default_ipp = 8;
        $pagination->paginate("_uploads_log", $pagination_page, $pagination_ipp, array('trash' => 0));
        
        $media = '';
        foreach($Media->get('', $pagination->limit) as $item){
            $checkBox = $admin->input(array('name'=>'multiSelect',
                                            'id'=>'multiSelect',
                                            'style'=>'margin:0 10px 0 -5px;',
                                            'type'=>'checkbox',
                                            'value'=>$item['log_id']));
            $onclick = 'onclick="document.location.href=\'' . URL_ROOT . ADMIN_PATH 
            . '/' . $module_media_name . '/view/' . $item['log_id'] . '\'"';
            $media .=
            '<tr>' .
            '<td> ' . $checkBox . '</td>' .
            '<td ' . $onclick . '>' . $item['log_originalname'] . '</td>' .
            '<td ' . $onclick . '>' . number_format(($item['log_size']/100000), 2, '.', '') . ' MB</td>' .
            '<td ' . $onclick . '>' . date('F d, Y', strtotime($item['log_date'])) . '</td>';
        }
        
        $multiForm = $admin->multi_form(array('referer'=>ADMIN_PATH . '/' . $module_pages_name,
                                              'module'=>'media_multi'));
        
        $style = '<style></style>';
        
        $top_right = $pagination->display_pages();
        $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_media_name . '/new">Upload New Media <b>+</b></a> ';
        
        $full = $multiForm . '
        <table id="zebraTable">
            <thead>
                <tr>
                    <th style="width:10px;"></th>
                    <th style="width:70%;">File</th>
                    <th style="width:20%;">Size</th>
                    <th style="width:10%;">Date</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td></td>
                    <td>File</td>
                    <td>Size</td>
                    <td>Date</td>
                </tr>
            </tfoot>
            <tbody id="zebraTableBody">' 
            . $media . '
            </tbody>
        </table>
        </form>
        <br>
        <p>With selected : <input type="button" value="Delete" id="multiDeleteButton"></p>
        ';
        
        $tpl_content = new Template(Template::getAdminFile('one_col.tpl'));
        $tpl_content->set('SCRIPT', $admin->script);
        $tpl_content->set('STYLE', $style);
        $tpl_content->set('TOP_RIGHT', $top_right);
        $tpl_content->set('TOP_LEFT', $top_left);
        $tpl_content->set('FULL' , $full);
        
        $tpl_content = $tpl_content->output();
    }
    else if($media_admin_new) {
        if(is_writable(UPLOAD_ROOT)){
            $admin->uploader("uploader");
            
            $style = '<style></style>';
            
            $top_right = 'Upload';
            $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_media_name . '">View All</a> ';

            $full = '
                <form>
                    <div id="uploader" style="height: 330px;">
                        <p>You browser doesn\'t have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.</p>
                    </div>
                </form>
            ';
        }
        else {
            $top_right = 'Upload';
            $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_media_name . '">View All</a> ';

            $full = '
            <span style="color:red;">
                Upload folder not writable ! Please fix permissions...
            </span>';
        }
        
        $tpl_content = new Template(Template::getAdminFile('one_col.tpl'));
        $tpl_content->set('SCRIPT', $script . $admin->script);
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
            $displayMedia = $Media->display($media['log_filename']);
            
            $style = '<style></style>';
            
            $top_right = 'View';
            $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_media_name . '">View All</a>';
            
            $full =  $displayMedia;
        }
        if($error){
            $script = '';
            $style = '';
            $top = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_media_name . '">View All</a>';
            $full = '<div class="errormsg">Sorry, there wasn\'t found any data !</div>';
        }
        
        $tpl_content = new Template(Template::getAdminFile('one_col.tpl'));
        $tpl_content->set('SCRIPT', $admin->script);
        $tpl_content->set('STYLE', $style);
        $tpl_content->set('TOP_RIGHT', $top_right);
        $tpl_content->set('TOP_LEFT', $top_left);
        $tpl_content->set('FULL' , $full);

        $tpl_content = $tpl_content->output();
    }
}