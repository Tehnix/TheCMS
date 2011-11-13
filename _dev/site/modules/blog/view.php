<?php
if($Module_blog) {
	$blog = new Blog;
	$getPosts = $blog->getBlogPosts('', $blog_needle, $category_needle, $tag_needle, $archive_needle);
	
	$template_dir = TEMPLATES_ROOT . end(explode(DS, dirname(__file__))) . DS;
	
	if($blog_blog) {
		print $page_needle;
		print $page_ipp;
		
		try {
			$tpl_content = new Template($template_dir . 'blog.tpl');
			$tpl_content->set('ID', $getPosts['id']);
			$tpl_content->set('TITLE', $getPosts['title']);
			$tpl_content->set('CONTENT' , $getPosts['post']);
			$tpl_content->set('AUTHOR' , $getPosts['author']);
			$tpl_content->set('POSTED' , $getPosts['date_posted']);
			$tpl_content = $tpl_content->output();
		} 
		catch (Exception $e) {
			print_r($getPosts);
		}
	}
	else if($blog_article) {
		print 'article';
		try {
			$tpl_content = new Template($template_dir . 'article.tpl');
			$tpl_content->set('ID', $getPosts['id']);
			$tpl_content->set('TITLE', $getPosts['title']);
			$tpl_content->set('CONTENT' , $getPosts['post']);
			$tpl_content->set('AUTHOR' , $getPosts['author']);
			$tpl_content->set('POSTED' , $getPosts['date_posted']);
			if($getPosts['discussion'] == 1) {
				$tpl_content->set('DISCUSSION', 'comments section goes here !!');
			}
			else {
				$tpl_content->set('DISCUSSION', '');
			}
			$tpl_content = $tpl_content->output();
		} 
		catch (Exception $e) {
			print_r($getPosts);
		}
	}
	else if($blog_category) {
		print 'category';
		try {
			$tpl_content = new Template($template_dir . 'article.tpl');
			$tpl_content->set('ID', $getPosts['id']);
			$tpl_content->set('TITLE', $getPosts['title']);
			$tpl_content->set('CONTENT' , $getPosts['post']);
			$tpl_content->set('AUTHOR' , $getPosts['author']);
			$tpl_content->set('POSTED' , $getPosts['date_posted']);
			$tpl_content = $tpl_content->output();
		} 
		catch (Exception $e) {
			print_r($getPosts);
		}
	}
	else if($blog_tag) {
		print 'tag';
		try {
			$tpl_content = new Template($template_dir . 'article.tpl');
			$tpl_content->set('ID', $getPosts['id']);
			$tpl_content->set('TITLE', $getPosts['title']);
			$tpl_content->set('CONTENT' , $getPosts['post']);
			$tpl_content->set('AUTHOR' , $getPosts['author']);
			$tpl_content->set('POSTED' , $getPosts['date_posted']);
			$tpl_content = $tpl_content->output();
		} 
		catch (Exception $e) {
			print_r($getPosts);
		}
	}
	else if($blog_archive) {
		try {
			$tpl_content = new Template($template_dir . 'article.tpl');
			$tpl_content->set('ID', $getPosts['id']);
			$tpl_content->set('TITLE', $getPosts['title']);
			$tpl_content->set('CONTENT' , $getPosts['post']);
			$tpl_content->set('AUTHOR' , $getPosts['author']);
			$tpl_content->set('POSTED' , $getPosts['date_posted']);
			$tpl_content = $tpl_content->output();
		} 
		catch (Exception $e) {
			print_r($getPosts);
		}
	}

}