<?php
$admin_count_array_left['Pages'] = $Database->count('pages', array('trash'=>'0'));
$admin_activity['pages'] = array('insert'=>'Added a new page called {% ADDIT %}', 'update'=>'Updated page {% ADDIT %}', 'delete'=>'Deleted the page {% ADDIT %}');
if(isset($$pages_admin_name) and $$pages_admin_name){
    $Pages = new Pages;
    $admin = new AdminGenerator;
    
    if($pages_admin_all){       
        $pagination = new Paginator($url_query, $pagination_page, $pagination_ipp);
        $pagination->mid_range = 4;
        $pagination->default_ipp = 8;
        $pagination->paginate("pages", $pagination_page, $pagination_ipp, array('trash' => 0));
        
        $pages = '';
        foreach($Pages->get('', 'ORDER BY id DESC '.$pagination->limit) as $item){
            $checkBox = $admin->input(array('name'=>'multiSelect',
                                            'id'=>'multiSelect',
                                            'style'=>'margin:0 10px 0 -5px;',
                                            'type'=>'checkbox',
                                            'value'=>$item['id']));
            $onclick = 'onclick="document.location.href=\'' . URL_ROOT . ADMIN_PATH 
            . '/' . $module_pages_name . '/update/' . $item['id'] . '\'"';
            $pages .=
            '<tr>' .
            '<td> ' . $checkBox . '</td>' .
            '<td ' . $onclick . '>' . $item['name'] . '</td>' .
            '<td ' . $onclick . '>' . $item['type_name'] . '</td>' .
            '<td ' . $onclick . '>' . $item['comments_count'] . '</td>' .
            '<td ' . $onclick . '>' . date('F d, Y', strtotime($item['modify'])) . '</td>';
        }
        
        $multiForm = $admin->multi_form(array('referer'=>ADMIN_PATH . '/' . $module_pages_name,
                                              'module'=>'pages_multi'));
        
        $style = '<style></style>';
        
        $top_right = $pagination->display_pages();
        $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_pages_name . '/new">Add New Page <b>+</b></a>';
        
        $full = $multiForm . '
        <table id="zebraTable">
            <thead>
                <tr>
                    <th style="width:10px;"></th>
                    <th style="width:60%;">Title</th>
                    <th style="width:20%;">Type</th>
                    <th style="width:10%;">
                        <img src="' . RESOURCES_ROOT . 'img/icons/comment.png" style="width:20px;margin-left:-5px">
                    </th>
                    <th style="width:10%;">Date</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td></td>
                    <td>Title</td>
                    <td style="width:20%;">Type</td>
                    <td>
                        <img src="' . RESOURCES_ROOT . 'img/icons/comment.png" style="width:20px;margin-left:-5px">
                    </td>
                    <td>Date</td>
                </tr>
            </tfoot>
            <tbody>' 
            . $pages . '
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
    else if($pages_admin_new){
        $textarea = $admin->textarea(array('name'=>'pages_content',
                                           'class'=>'advancedEditor',
                                           'rows'=>'20',
                                           'cols'=>'60'));
        
        $discussion = $admin->select(array('name'=>'pages_discussion',
                                           'style'=>'width:99%;',
                                           'selected'=>'0'),
                                     array('0'=>'No',
                                           '1'=>'Yes'));      
        $typesArray = array();
        foreach(Pages::getTypes() as $types){
            $typesArray[$types['key']] = $types['name'];
        }
        $type = $admin->select(array('name'=>'pages_type',
                                     'style'=>'width:99%;',
                                     'selected'=>'pages'),
                               $typesArray);
        
        $title = $admin->input(array('name'=>'pages_title',
                                     'id'=>'pages_title',
                                     'class'=>'input',
                                     'style'=>'width:95%;',
                                     'type'=>'text',
                                     'placeholder'=>'Enter title here...'));
        $admin->validateField('required', array('id'=>'pages_title',
                                                'error'=>'Please enter a title !'));
        $weight = $admin->input(array('name'=>'pages_weight',
                                      'id'=>'pages_weight',
                                      'class'=>'input',
                                      'style'=>'width:95%;',
                                      'type'=>'text'));
        
        $submit = $admin->input(array('id'=>'pages_submit',
                                      'class'=>'button darkblue',
                                      'type'=>'submit',
                                      'value'=>'Submit'));
        
        $form = $admin->form(array('action'=>'pages_newPage',
                                   'referer'=>ADMIN_PATH . '/' . $module_pages_name,
                                   'validate'=>'#pages_title'));
                             
        $style = '<style></style>';
        
        $top_right = 'New';
        $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_pages_name . '">View All</a>';
        
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
                    <td>Title :</td>
                    <td>' . $title . '</td>
                </tr>
                <tr>
                    <td>Type :</td>
                    <td>' . $type . '</td>
                </tr>
                <tr>
                    <td>Weight :</td>
                    <td>' . $weight . '</td>
                </tr>
                <tr>
                    <td>Allow comments :</td>
                    <td>'. $discussion .'</td>
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
    }
    else if($pages_admin_update){
        $error = true;
        if(isset($url_query[3])){
            $error = false;
            $page = $Pages->get($url_query[3]);
            if(empty($page['id'])){
                $error = true;
            }
            
            $id = $admin->input(array('name'=>'pages_id',
                                      'type'=>'hidden',
                                      'value'=>$page['id']));
            
            $textarea = $admin->textarea(array('name'=>'pages_content',
                                               'class'=>'advancedEditor',
                                               'rows'=>'20',
                                               'cols'=>'60',
                                               'value'=>$page['content']));

            $discussion = $admin->select(array('name'=>'pages_discussion',
                                               'style'=>'width:99%;',
                                               'selected'=>$page['discussion']),
                                         array('0'=>'No',
                                               '1'=>'Yes'));
            $typesArray = array();
            foreach(Pages::getTypes() as $types){
                $typesArray[$types['key']] = $types['name'];
            }
            $type = $admin->select(array('name'=>'pages_type',
                                         'style'=>'width:99%;',
                                         'selected'=>$page['type']),
                                   $typesArray);
            
            $title = $admin->input(array('name'=>'pages_title',
                                         'id'=>'pages_title',
                                         'class'=>'input',
                                         'style'=>'width:95%;',
                                         'type'=>'text',
                                         'placeholder'=>'Enter title here...',
                                         'value'=>$page['name']));
            $admin->validateField('required', array('id'=>'pages_title',
                                                    'error'=>'Please enter a title !'));
            $weight = $admin->input(array('name'=>'pages_weight',
                                          'id'=>'pages_weight',
                                          'class'=>'input',
                                          'style'=>'width:95%;',
                                          'type'=>'text',
                                          'value'=>$page['weight']));
            

            $submit = $admin->input(array('id'=>'pages_submit',
                                          'class'=>'button darkblue',
                                          'type'=>'submit',
                                          'value'=>'Submit'));
            
            $form = $admin->form(array('action'=>'pages_updatePage',
                                       'referer'=>ADMIN_PATH . '/' . $module_pages_name,
                                       'validate'=>'#pages_title'));
            
            $style = '<style></style>';
            
            $top_right = 'Update';
            $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_pages_name . '">View All</a>';

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
                        <td>Title :</td>
                        <td>' . $title . '</td>
                    </tr>
                    <tr>
                        <td>Type :</td>
                        <td>' . $type . '</td>
                    </tr>
                    <tr>
                        <td>Weight :</td>
                        <td>' . $weight . '</td>
                    </tr>
                    <tr>
                        <td>Allow comments :</td>
                        <td>'. $discussion .'</td>
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
            $top_left = '<a href="' . URL_ROOT . ADMIN_PATH . '/' . $module_pages_name . '">View All</a>';
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
    }
}