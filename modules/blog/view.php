<?php
if(isset($Module_blog) and $Module_blog) {
    $page_needle = (!isset($page_needle)) ? '' : $page_needle;
    $page_ipp = (!isset($page_ipp)) ? '' : $page_ipp;
    $blog_needle = (!isset($blog_needle)) ? '' : $blog_needle;
    $category_needle = (!isset($category_needle)) ? '' : $category_needle;
    $tag_needle = (!isset($tag_needle)) ? '' : $tag_needle;
    $archive_needle = (!isset($archive_needle)) ? '' : $archive_needle;
    
    $pagination = new Paginator($url_query, $page_needle, $page_ipp);
    $pagination->mid_range = 2;
    $pagination->default_ipp = 4;
    $pagination->paginate("blog_posts", $page_needle, $page_ipp);
    
    $blog = new Blog;
    $getPosts = $blog->get($pagination->limit, $blog_needle, $category_needle, $tag_needle, $archive_needle);
    
    $dir = explode(DS, dirname(__file__));
    $template_dir = TEMPLATES_ROOT . end($dir) . DS;
    $blogHtml = '';
    if($blog_blog or $blog_category or $blog_tag or $blog_archive) {
        foreach($getPosts as $post){
            $footerInfo = array();
            if(!empty($post['tags'])){
                $footerInfo[] = $post['tags'];
            }
            if(!empty($post['category'])){
                $footerInfo[] = $post['category'];
            }
            if (AJAX) {
                $link = '<a href="#!/blog/article/' . $post['id'] . '"><h2>' . $post['title'] . '</h2></a>';
            } else {
                $link = '<a href="' . URL_ROOT . 'blog/article/' . $post['id'] . '"><h2>' . $post['title'] . '</h2></a>';
            }
            $blogHtml .= '
            <article class="blogPost">
                <header>
                    ' . $link . '
                    <p>Posted on <time datetime="' 
                    . date('c', strtotime($post['date_posted'])) . '">' 
                    . date('F d, Y', strtotime($post['date_posted'])) 
                    . '</time> by ' . $post['author_name'] . ' with ' 
                    . $post['comments_count'] . ' comments</p>
                </header>
                <div class="blogPostContent">' . $post['post'] . '</div>
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
                <div class="blogPostContent">' . $getPosts['post'] . '</div>
                <div class="blogPostFooter">
                    ' . implode(' :: ', $footerInfo) . '
                </div>
            </article>';
        }
        else{
            $blogHtml = '';
        }
        if($getPosts['discussion'] == 1 and class_exists('Comments')) {
            $Comments = new Comments;
            $getComments = $Comments->get('blog_post_comments', 'blog_post_id', $getPosts['id']);
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
            $blogComments = '';
        }
        # Make the layout
        try {
            $tpl_content = new Template($template_dir . 'article.tpl');
            $tpl_content = $tpl_content->output();
        } 
        catch (Exception $e) {
            $tpl_content = '
            <section id="blogPosts">' . $blogHtml . '
            </section>
            <h2 class="blogCommentsTitle">Comments</h2>'
            . $blogComments;
        }
    }

}