<?php
if($Module_blog) {
    $pagination = new Paginator($url_query, $page_needle, $page_ipp);
	$pagination->mid_range = 2;
	$pagination->default_ipp = 4;
	$pagination->paginate("blog_posts", $page_needle, $page_ipp);
	
	$blog = new Blog;
	$getPosts = $blog->getBlogPosts($pagination->limit, $blog_needle, $category_needle, $tag_needle, $archive_needle);
	
	$template_dir = TEMPLATES_ROOT . end(explode(DS, dirname(__file__))) . DS;
	
	if($blog_blog or $blog_category or $blog_tag or $blog_archive) {
		foreach($getPosts as $post){
		    $footerInfo = array();
		    if(!empty($post['tags'])){
		        $footerInfo[] = $post['tags'];
		    }
		    if(!empty($post['category'])){
		        $footerInfo[] = $post['category'];
		    }
		    $blogHtml .= '
		    <article class="blogPost">
		        <header>
		            <a href="' . URL_ROOT . 'blog/article/' . $post['id'] . '"><h2>' . $post['title'] . '</h2></a>
		            <p>Posted on <time datetime="' 
		            . date('c', strtotime($post['date_posted'])) . '">' 
		            . date('F d, Y', strtotime($post['date_posted'])) 
		            . '</time> by ' . $post['author_name'] . ' with ' 
		            . $post['comments_count'] . ' comments</p>
		        </header>
		        <p class="blogPostContent">' . $post['post'] . '</p>
		        <div class="blogPostFooter">
		            ' . implode(' :: ', $footerInfo) . '
		        </div>
		    </article>';
		}
		try {
			$tpl_content = new Template($template_dir . 'blog.tpl');
			$tpl_content = $tpl_content->output();
		} 
		catch (Exception $e) {
			$tpl_content = $blogHtml . '<br>' . $pagination->display_pages();
		}
	}
	else if($blog_article) {
	    if(isset($getPosts['id'])){
	        $footerInfo = array();
		    if(!empty($getPosts['tags'])){
		        $footerInfo[] = $getPosts['tags'];
		    }
		    if(!empty($getPosts['category'])){
		        $footerInfo[] = $getPosts['category'];
		    }
	        $blogHtml .= '
    	    <article class="blogPost">
    	        <header>
    	            <h2>' . $getPosts['title'] . '</h2>
    	            <p>Posted on <time datetime="' 
    	            . date('c', strtotime($getPosts['date_posted'])) . '">' 
    	            . date('F d, Y', strtotime($getPosts['date_posted'])) 
    	            . '</time> by ' . $getPosts['author_name'] . ' with ' 
    	            . $getPosts['comments_count'] . ' comments</p>
    	        </header>
    	        <p class="blogPostContent">' . $getPosts['post'] . '</p>
    	        <div class="blogPostFooter">
		            ' . implode(' :: ', $footerInfo) . '
		        </div>
    	    </article>';
	    }
	    else{
	        $blogHtml = '';
	    }
	    if($getPosts['discussion'] == 1) {
			$Comments = new Comments;
			$getComments = $Comments->getComments('blog_post_comments', 'blog_post_id', $getPosts['id']);
			$blogComments = '<section id="blogComments">';
			foreach($getComments as $comment){
			    $blogComments .= '
			    <article class="blogComments">
			        <header>
			            ' . $comment['author_name'] . ' on <time datetime="' 
            	        . date('c', strtotime($comment['time'])) . '">' 
            	        . date('F d, Y', strtotime($comment['time'])) 
            	        . '</time>
			        </header>
			        <p>' . $comment['comment'] . '</p>
			    </article>';
			}
			$blogComments .= '</section>';
		}
		else {
			$blogComments = 'None';
		}
	    # Make the layout
		try {
			$tpl_content = new Template($template_dir . 'article.tpl');
			$tpl_content = $tpl_content->output();
		} 
		catch (Exception $e) {
			$tpl_content = '
			<section id="blogPosts">' . $blogHtml . '
			</section>'
			. $blogComments;
		}
	}

}