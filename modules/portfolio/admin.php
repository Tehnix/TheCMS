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
            '<td><a href="' . URL_ROOT . ADMIN_PATH 
            . '/' . $module_portfolio_name . '/update/' . $item['id'] . '">Edit</a></td>' .
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
                    <th style="width:85%;">Title</th>
                    <th style="width:20px;"></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td></td>
                    <td>Title</td>
                    <td></td>
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
        $textarea = $admin->textarea(array('name'=>'portfolio_description',
                                           'class'=>'advancedEditor',
                                           'rows'=>'20',
                                           'cols'=>'60'));
        
        $name = $admin->input(array('name'=>'portfolio_name',
                                     'id'=>'portfolio_name',
                                     'class'=>'input',
                                     'style'=>'width:95%;',
                                     'type'=>'text',
                                     'placeholder'=>'Enter name here...'));
        $admin->validateField('required', array('id'=>'portfolio_name',
                                                'error'=>'Please enter a name !'));
        $weight = $admin->input(array('name'=>'portfolio_weight',
                                      'id'=>'portfolio_weight',
                                      'class'=>'input',
                                      'style'=>'width:95%;',
                                      'type'=>'text'));
        
        $submit = $admin->input(array('id'=>'portfolio_submit',
                                      'class'=>'button darkblue',
                                      'type'=>'submit',
                                      'value'=>'Submit'));
        
        $form = $admin->form(array('action'=>'portfolio_newPortfolio',
                                   'referer'=>ADMIN_PATH . '/' . $module_portfolio_name,
                                   'validate'=>'#portfolio_name'));
                             
        $style = '<style></style>';
        
        $top_right = 'New';
        $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_portfolio_name . '">View All</a>';
        
        $left = $form . $textarea;
        
        $right = '
        <table style="width:100%;">
            <thead>
                <tr>
                    <th style="width:40%;"></th>
                    <th style="width:60%;"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Name :</td>
                    <td>' . $name . '</td>
                </tr>
                <tr>
                    <td>Weight :</td>
                    <td>' . $weight . '</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align:right;">'. $submit .'</td>
                </tr>
            </tbody>
        </table>
        </form>';
        
        $tpl_content = new Template(Template::getAdminFile('two_col.tpl'));
        $tpl_content->set('SCRIPT', $admin->script);
        $tpl_content->set('STYLE', $style);
        $tpl_content->set('TOP_RIGHT', $top_right);
        $tpl_content->set('TOP_LEFT', $top_left);
        $tpl_content->set('LEFT' , $left);
        $tpl_content->set('RIGHT' , $right);

        $tpl_content = $tpl_content->output();
    } else if($portfolio_admin_update){
        $error = true;
        if(isset($url_query[3])){
            $error = false;
            $portfolio = $Portfolio->get($url_query[3]);
            if(empty($portfolio['id'])){
                $error = true;
            }
            $id = $admin->input(array('name'=>'portfolio_id',
                                      'type'=>'hidden',
                                      'value'=>$portfolio['id']));
            
            $textarea = $admin->textarea(array('name'=>'portfolio_description',
                                               'class'=>'advancedEditor',
                                               'rows'=>'20',
                                               'cols'=>'60',
                                               'value'=>$portfolio['description']));
        
            $name = $admin->input(array('name'=>'portfolio_name',
                                         'id'=>'portfolio_name',
                                         'class'=>'input',
                                         'style'=>'width:95%;',
                                         'type'=>'text',
                                         'value'=>$portfolio['name'],
                                         'placeholder'=>'Enter name here...'));
            $admin->validateField('required', array('id'=>'portfolio_name',
                                                    'error'=>'Please enter a name !'));
            $weight = $admin->input(array('name'=>'portfolio_weight',
                                          'id'=>'portfolio_weight',
                                          'class'=>'input',
                                          'style'=>'width:95%;',
                                          'type'=>'text',
                                          'value'=>$portfolio['weight']));
        
            $submit = $admin->input(array('id'=>'portfolio_submit',
                                          'class'=>'button darkblue',
                                          'type'=>'submit',
                                          'value'=>'Submit'));
        
            $form = $admin->form(array('action'=>'portfolio_updatePortfolio',
                                       'referer'=>ADMIN_PATH . '/' . $module_portfolio_name,
                                       'validate'=>'#portfolio_name'));
                             
            $style = '<style></style>';
        
            $top_right = 'New';
            $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_portfolio_name . '">View All</a>';
        
            $left = $form . $id . $textarea;
        
            $right = '
            <table style="width:100%;">
                <thead>
                    <tr>
                        <th style="width:40%;"></th>
                        <th style="width:60%;"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Name :</td>
                        <td>' . $name . '</td>
                    </tr>
                    <tr>
                        <td>Weight :</td>
                        <td>' . $weight . '</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align:right;">'. $submit .'</td>
                    </tr>
                </tbody>
            </table>
            </form>';
        }
        if($error){
            $script = '';
            $style = '';
            $top_right = '';
            $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_portfolio_name . '">View All</a>';
            $left = '<div class="errormsg">Sorry, there wasn\'t found any data !</div>';
            $right = '';
        }
        
        
        $tpl_content = new Template(Template::getAdminFile('two_col.tpl'));
        $tpl_content->set('SCRIPT', $admin->script);
        $tpl_content->set('STYLE', $style);
        $tpl_content->set('TOP_RIGHT', $top_right);
        $tpl_content->set('TOP_LEFT', $top_left);
        $tpl_content->set('LEFT' , $left);
        $tpl_content->set('RIGHT' , $right);

        $tpl_content = $tpl_content->output();
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