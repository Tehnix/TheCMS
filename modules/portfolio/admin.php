<?php
$admin_count_array_left['Portfolios'] = $Database->count('portfolio', array('trash'=>'0'));
$admin_count_array_right['Portfolio Pictures'] = $Database->count('portfolio_pictures', array('trash'=>'0'));
$admin_activity['portfolio'] = array('insert'=>'Added a new portfolio called {% ADDIT %}', 'update'=>'Updated portfolio {% ADDIT %}', 'delete'=>'Deleted the portfolio {% ADDIT %}');
$admin_activity['portfolio_pictures'] = array('insert'=>'Added a new portfolio picture called {% ADDIT %}', 'update'=>'Updated portfolio picture {% ADDIT %}', 'delete'=>'Deleted the portfolio picture {% ADDIT %}', 'upload'=>'Uploaded portfolio picture {% ADDIT %}');

if($$portfolio_admin_name){
    $Portfolio = new portfolio;
    $admin = new AdminGenerator;
    
    if($portfolio_admin_all){
        $pagination = new Paginator($url_query, $pagination_page, $pagination_ipp);
        $pagination->mid_range = 4;
        $pagination->default_ipp = 8;
        $pagination->paginate("portfolio", $pagination_page, $pagination_ipp);
        
        $portfolio = '';
        foreach($Portfolio->get('', 'ORDER BY weight ASC '.$pagination->limit) as $item){
            $checkBox = $admin->input(array('name'=>'multiSelect',
                                            'id'=>'multiSelect',
                                            'style'=>'margin:0 10px 0 -5px;',
                                            'type'=>'checkbox',
                                            'value'=>$item['id']));
            $onclick = 'onclick="document.location.href=\'' . URL_ROOT . ADMIN_PATH 
            . '/' . $module_portfolio_name . '/view/' . $item['id'] . '\'"';
            $portfolio .=
            '<tr>' .
            '<td> ' . $checkBox . '</td>' .
            '<td ' . $onclick . '>' . $item['name'] . '</td>' .
            '</tr>';
        }
        
        $multiForm = $admin->multi_form(array('referer'=>ADMIN_PATH . '/' . $module_portfolio_name,
                                              'module'=>'portfolio_multi'));
        
        $style = '<style></style>';
        
        $top_right = $pagination->display_pages();
        $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_portfolio_name . '/new">Add New Portfolio <b>+</b></a>';
        
        $full = $multiForm . '
        <table id="zebraTable">
            <thead>
                <tr>
                    <th style="width:10px;"></th>
                    <th style="width:100%;">Title</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td></td>
                    <td>Title</td>
                </tr>
            </tfoot>
            <tbody>' 
            . $portfolio . '
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
    } else if($portfolio_admin_new){
        
    } else if($portfolio_admin_update){
        
    } else if($portfolio_admin_images){
        $error = true;
        if(isset($url_query[3])){
            $error = false;
            $pagination = new Paginator($url_query, $pagination_page, $pagination_ipp);
            $pagination->mid_range = 4;
            $pagination->default_ipp = 8;
            $pagination->paginate("portfolio_pictures", $pagination_page, $pagination_ipp);
        
            $portfolio = '';
            foreach($Portfolio->get_images($url_query[3], 'ORDER BY weight ASC '.$pagination->limit) as $item){
                $checkBox = $admin->input(array('name'=>'multiSelect',
                                                'id'=>'multiSelect',
                                                'style'=>'margin:0 10px 0 -5px;',
                                                'type'=>'checkbox',
                                                'value'=>$item['id']));
                $onclick = 'onclick="document.location.href=\'' . URL_ROOT . ADMIN_PATH 
                . '/' . $module_portfolio_name . '/image/update/' . $item['id'] . '\'"';
                $portfolio .=
                '<tr>' .
                '<td> ' . $checkBox . '</td>' .
                '<td ' . $onclick . '>' . $item['name'] . '</td>' .
                '</tr>';
            }
        
            $multiForm = $admin->multi_form(array('referer'=>ADMIN_PATH . '/' . $module_portfolio_name,
                                                  'module'=>'portfolio_pictures_multi'));
        
            $style = '<style></style>';
        
            $top_right = $pagination->display_pages();
            $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_portfolio_name . '/image/add/' . $url_query[3] . '">Add New Images <b>+</b></a>';
        
            $full = $multiForm . '
            <table id="zebraTable">
                <thead>
                    <tr>
                        <th style="width:10px;"></th>
                        <th style="width:100%;">Title</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td></td>
                        <td>Title</td>
                    </tr>
                </tfoot>
                <tbody>' 
                . $portfolio . '
                </tbody>
            </table>
            </form>
            <br>
            <p>With selected : <input type="button" value="Delete" id="multiDeleteButton"></p>
            ';
        }
        if($error){
            $style = '';
            $top_right = '';
            $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_portfolio_name . '">View All</a>';
            $full = '<div class="errormsg">Sorry, there wasn\'t found any data !</div>';
        }
        
        $tpl_content = new Template(Template::getAdminFile('one_col.tpl'));
        $tpl_content->set('SCRIPT', $admin->script);
        $tpl_content->set('STYLE', $style);
        $tpl_content->set('TOP_RIGHT', $top_right);
        $tpl_content->set('TOP_LEFT', $top_left);
        $tpl_content->set('FULL' , $full);

        $tpl_content = $tpl_content->output();
    } else if($portfolio_admin_image_add){
        $error = true;
        if(isset($url_query[4])){
            $error = false;
            if(is_writable(UPLOAD_ROOT)){
                $admin->uploader('uploader', 'portfolio_addImages', '&portfolio_id='.$url_query[4]);
            
                $style = '<style></style>';
            
                $top_right = 'Upload';
                $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_portfolio_name . '">View All</a> ';

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
                $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_portfolio_name . '">View All</a> ';

                $full = '
                <span style="color:red;">
                    Upload folder not writable ! Please fix permissions...
                </span>';
            }
        }
        if($error){
            $style = '';
            $top_right = '';
            $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_portfolio_name . '">View All</a>';
            $full = '<div class="errormsg">Sorry, there wasn\'t found any data !</div>';
        }
        
        $tpl_content = new Template(Template::getAdminFile('one_col.tpl'));
        $tpl_content->set('SCRIPT', $script . $admin->script);
        $tpl_content->set('STYLE', $style);
        $tpl_content->set('TOP_RIGHT', $top_right);
        $tpl_content->set('TOP_LEFT', $top_left);
        $tpl_content->set('FULL' , $full);
        
        $tpl_content = $tpl_content->output();
    } else if($portfolio_admin_image_update){
        
    }
}