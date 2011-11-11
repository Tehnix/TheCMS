<?php
$admin_count_array_left['Blog Posts'] = $Database->count('blog_posts');
$admin_count_array_right['Tags'] = $Database->count('tags');
$admin_count_array_right['Categories'] = $Database->count('categories');
$admin_count_array_right['Comments'] = $Database->count('comments');
$admin_activity['blog'] = array('insert'=>'Added a new blog post with title {% ADDIT %}', 'update'=>'Updated blog post {% ADDIT %}', 'delete'=>'Deleted blog post {% ADDIT %}');
if($$blog_admin_name){
	$Blog = new Blog;
	$admin = new AdminGenerator;
	
	if($blog_admin_all){		
		$pagination = new Paginator($url_query, $pagination_page, $pagination_ipp);
		$pagination->mid_range = 4;
		$pagination->default_ipp = 8;
		$pagination->paginate("blog_posts", $pagination_page, $pagination_ipp);
		
		$blog = '';
		foreach($Blog->getBlogPosts($pagination->limit) as $item){
			$blog .=
			'<tr>' .
			'<td><a href="' . URL_ROOT . 'admin/' . $module_blog_name . '/update/' 
			. $item['id'] . '">' . $item['title'] . '</a></td>' .
			'<td>' . $item['author_name'] . '</td>' .
			'<td>' . $item['comments_count'] . '</td>' .
			'<td>' . $item['date_posted'] . '</td>';
		}
		
		$style = '<style></style>';
		
		$top_right = $pagination->display_pages();
		$top_left = '<a href="' . URL_ROOT . 'admin/' . $module_blog_name . '/new">Add New Post <b>+</b></a>';
		
		$full = '
		<table id="zebraTable">
			<thead>
				<tr>
					<th style="width:60%;">Title</th>
					<th style="width:20%;">Author</th>
					<th style="width:10%;">
						<img src="' . RESOURCES_ROOT . 'img/icons/comment.png" style="width:20px;margin-left:-5px">
					</th>
					<th style="width:10%;">Date</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td>Title</td>
					<td>Author</td>
					<td>
						<img src="' . RESOURCES_ROOT . 'img/icons/comment.png" style="width:20px;margin-left:-5px">
					</td>
					<td>Date</td>
				</tr>
			</tfoot>
			<tbody>' 
			. $blog . '
			</tbody>
		</table>';
		
		$tpl_content = new Template(TEMPLATES_ROOT . 'admin/one_col.tpl');
		$tpl_content->set('SCRIPT', $admin->script);
		$tpl_content->set('STYLE', $style);
		$tpl_content->set('TOP_RIGHT', $top_right);
		$tpl_content->set('TOP_LEFT', $top_left);
		$tpl_content->set('FULL' , $full);

		$tpl_content = $tpl_content->output();
	}
	else if($blog_admin_new){
		$admin_title .= ' -> Add New';
		
		$textarea = $admin->textarea(array('name'=>'blog_post',
		                                   'class'=>'advancedEditor',
		                                   'rows'=>'20',
		                                   'cols'=>'60'));
		
		$discussion = $admin->select(array('name'=>'blog_discussion',
		                                   'style'=>'width:99%;',
		                                   'selected'=>'1'),
		                             array('0'=>'No',
		                                   '1'=>'Yes'));
		
		$title = $admin->input(array('name'=>'blog_title',
		                             'id'=>'blog_title',
		                             'class'=>'input',
		                             'style'=>'width:95%;',
		                             'type'=>'text',
		                             'placeholder'=>'Enter title here...'));
		$admin->validateField('required', array('id'=>'blog_title',
		                                        'error'=>'Please enter a title !'));
		
		$tags = $admin->input(array('name'=>'blog_tags',
		                            'id'=>'blog_tags',
		                            'class'=>'input',
		                            'style'=>'width:95%;',
		                            'type'=>'text',
		                            'placeholder'=>'#Tags, #Here'));
		
		$category = $admin->input(array('name'=>'blog_category',
		                                'id'=>'blog_category',
		                                'class'=>'input',
		                                'style'=>'width:95%;',
		                                'type'=>'text',
		                                'placeholder'=>'Categories, Here'));
		
		$submit = $admin->input(array('id'=>'blog_submit',
		                              'class'=>'button darkblue',
		                              'type'=>'submit',
		                              'value'=>'Submit'));
		
		$form = $admin->form(array('action'=>'blog_addBlogPost',
                                   'referer'=>'admin/' . $module_blog_name,
                                   'validate'=>'#blog_title'));
                             
		$style = '<style></style>';
		
		$top_right = '';
		$top_left = '<a href="' . URL_ROOT . 'admin/' . $module_blog_name . '">View All</a>';
		
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
					<td>Tags :</td>
					<td>' . $tags . '</td>
				</tr>
				<tr>
					<td>Category :</td>
					<td>' . $category . '</td>
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
		
		$tpl_content = new Template(TEMPLATES_ROOT . 'admin/two_col.tpl');
		$tpl_content->set('SCRIPT', $admin->script);
		$tpl_content->set('STYLE', $style);
		$tpl_content->set('TOP_RIGHT', $top_right);
		$tpl_content->set('TOP_LEFT', $top_left);
		$tpl_content->set('LEFT' , $left);
		$tpl_content->set('RIGHT' , $right);

		$tpl_content = $tpl_content->output();
	}
	else if($blog_admin_update){
		$error = true;
		if(isset($url_query[3])){
			$error = false;
			$blog = $Blog->getBlogPosts('', $url_query[3]);
			if(empty($blog['id'])){
				$error = true;
			}
			$admin_title .= ' -> Update';
			
			$id = $admin->input(array('name'=>'blog_id',
			                          'type'=>'hidden',
			                          'value'=>$blog['id']));
			
			$textarea = $admin->textarea(array('name'=>'blog_post',
			                                   'class'=>'advancedEditor',
			                                   'rows'=>'20',
			                                   'cols'=>'60',
			                                   'value'=>$blog['post']));

			$discussion = $admin->select(array('name'=>'blog_discussion',
			                                   'style'=>'width:99%;',
			                                   'selected'=>$page['discussion']),
			                             array('1'=>'Yes',
			                                   '0'=>'No'));

			$title = $admin->input(array('name'=>'blog_title',
			                             'id'=>'blog_title',
			                             'class'=>'input',
			                             'style'=>'width:95%;',
			                             'type'=>'text',
			                             'placeholder'=>'Enter title here...',
			                             'value'=>$blog['title']));
			$admin->validateField('required', array('id'=>'blog_title',
			                                        'error'=>'Please enter a title !'));

			$tags = $admin->input(array('name'=>'blog_tags',
			                            'id'=>'blog_tags',
			                            'class'=>'input',
			                            'style'=>'width:95%;',
			                            'type'=>'text',
			                            'placeholder'=>'#Tags, #Here',
			                            'value'=>$blog['tags']));

			$category = $admin->input(array('name'=>'blog_category',
			                                'id'=>'blog_category',
			                                'class'=>'input',
			                                'style'=>'width:95%;',
			                                'type'=>'text',
			                                'placeholder'=>'Categories, Here',
			                                'value'=>$blog['category']));

			$submit = $admin->input(array('id'=>'blog_submit',
			                              'class'=>'button darkblue',
			                              'type'=>'submit',
			                              'value'=>'Submit'));

            $form = $admin->form(array('action'=>'blog_updateBlogPost',
                                       'referer'=>'admin/' . $module_blog_name,
                                       'validate'=>'#blog_title'));
            
			$style = '<style></style>';

			$top_right = '';
			$top_left = '<a href="' . URL_ROOT . 'admin/' . $module_blog_name . '">View All</a>';

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
						<td>Tags :</td>
						<td>' . $tags . '</td>
					</tr>
					<tr>
						<td>Category :</td>
						<td>' . $category . '</td>
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
			$top_left = '<a href="' . URL_ROOT . 'admin/' . $module_blog_name . '">View All</a>';
			$left = '<div class="errormsg">Sorry, there wasn\'t found any data !</div>';
			$right = '';
		}
		
		$tpl_content = new Template(TEMPLATES_ROOT . 'admin/two_col.tpl');
		$tpl_content->set('SCRIPT', $admin->script);
		$tpl_content->set('STYLE', $style);
		$tpl_content->set('TOP_RIGHT', $top_right);
		$tpl_content->set('TOP_LEFT', $top_left);
		$tpl_content->set('LEFT' , $left);
		$tpl_content->set('RIGHT' , $right);

		$tpl_content = $tpl_content->output();
	}
}