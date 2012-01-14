<?php
/*
----- Instantiate class -----
$blog = new Blog;
----- To retrieve one blogpost -----
$post = $blog->getBlogPosts('LIMIT $num', $blogid);
$post['id'];
$post['title'];
$post['post'];
$post['category'];
$post['tags'];
$post['archive'];
$post['author_id'];
$post['author_name'];
$post['author_username'];
$post['author_email'];
$post['author_level'];
$post['date_posted'];
$post['discussion'];
$post['trash'];
----- To retrieve all blogposts -----
$blogposts = $blog->getBlogPosts() or $blog->getBlogPosts('LIMIT $num')
foreach ($blogposts as $post)
{
    $post['id'];
    $post['title'];
    $post['post'];
    $post['category'];
    $post['tags'];
    $post['archive'];
    $post['author_id'];
    $post['author_name'];
    $post['author_username'];
    $post['author_email'];
    $post['author_level'];
    $post['date_posted'];
    $post['discussion'];
    $post['trash'];
}
----- To add blogposts -----
$addblogpost = $blog->addBlogPost(addslashes($title), addslashes($post), $author_id, $category, $tags, $discussion);
----- To update blogposts -----
$updateblogpost = $blog->updateBlogPost($updateId, $title, $post, $author_id, $category, $tags, $discussion);
*/
class Blog extends ModulesBase
{   
    public function get($inLimit=null, $inId=null, $inCategoryId=null, $inTagId=null, $inArchiveId=null){
        if(!empty($inId)){
            $sql = "SELECT b.*, u.username, u.first_name, u.last_name, u.email, u.userlevel,
            (SELECT COUNT(*) FROM `blog_post_comments` WHERE `blog_post_id` = `b`.`id`) AS `comments_count`  
            FROM blog_posts AS b 
            LEFT JOIN usersdb AS u ON b.author_id = u.id
            WHERE b.id = :id
            AND b.trash = :trash";
            $posts = $this->database->execute('fetchone', $sql, array('id'=>$inId, 'trash'=>'0'));
            if(get_magic_quotes_gpc()){
                $posts['title'] = stripslashes($posts['title']);
                $posts['post'] = stripslashes($posts['post']);
            }
            $posts['date_posted'] = $posts['date_posted'];
            $posts['category'] = $this->getCategory($post['id']);
            $posts['tags'] = $this->getTags($post['id']);
            $posts['archive'] = $this->getArchive($post['id']);
            $posts['author_username'] = $posts['username'];
            if(empty($posts['first_name']) and empty($posts['last_name'])){
                $posts['author_name'] = 'Guest';
            }
            else{
                $posts['author_name'] = $posts['first_name'] . ' ' . $posts['last_name'];
            }
            $posts['author_email'] = $posts['email'];
            $posts['author_level'] = $posts['userlevel'];
        }
        else{
            if (!empty($inTagId))
            {
                $sql = 'SELECT b.*, u.username, u.first_name, u.last_name, u.email, u.userlevel,
                (SELECT COUNT(*) FROM `blog_post_comments` WHERE `blog_post_id` = `b`.`id`) AS `comments_count` 
                FROM blog_post_tags AS bt
                LEFT JOIN blog_posts AS b ON bt.blog_post_id = b.id
                LEFT JOIN usersdb AS u ON b.author_id = u.id 
                WHERE bt.tag_id = :inTagId
                ORDER BY b.id DESC';
                $posts = $this->database->execute('fetchall', $sql, array('inTagId'=>$inTagId));
            }
            else if (!empty($inCategoryId))
            {
                $sql = 'SELECT b.*, u.username, u.first_name, u.last_name, u.email, u.userlevel,
                (SELECT COUNT(*) FROM `blog_post_comments` WHERE `blog_post_id` = `b`.`id`) AS `comments_count` 
                FROM blog_post_categories AS bc
                LEFT JOIN blog_posts AS b ON bc.blog_post_id = b.id
                LEFT JOIN usersdb AS u ON b.author_id = u.id 
                WHERE bc.category_id = :inCategoryId
                ORDER BY b.id DESC';
                $posts = $this->database->execute('fetchall', $sql, array('inCategoryId'=>$inCategoryId));
            }
            else if (!empty($inArchiveId))
            {
                $sql = 'SELECT b.*, u.username, u.first_name, u.last_name, u.email, u.userlevel,
                (SELECT COUNT(*) FROM `blog_post_comments` WHERE `blog_post_id` = `b`.`id`) AS `comments_count` 
                FROM blog_post_archive AS ba
                LEFT JOIN blog_posts AS b ON ba.blog_post_id = b.id
                LEFT JOIN usersdb AS u ON b.author_id = u.id 
                WHERE ba.archive_id = :inArchiveId
                ORDER BY b.id DESC';
                $posts = $this->database->execute('fetchall', $sql, array('inArchiveId'=>$inArchiveId));
            }
            else{
                $sql = "SELECT b.*, u.username, u.first_name, u.last_name, u.email, u.userlevel,
                (SELECT COUNT(*) FROM `blog_post_comments` WHERE `blog_post_id` = `b`.`id`) AS `comments_count` 
                FROM blog_posts AS b 
                LEFT JOIN usersdb AS u ON b.author_id = u.id
                WHERE b.trash = :trash
                ORDER BY b.id DESC " . $inLimit;
                $posts = $this->database->execute('fetchall', $sql, array('trash'=>'0'));
            }
            for($i=0; $i<sizeof($posts); $i++){
                if(get_magic_quotes_gpc()){
                    $posts[$i]['title'] = stripslashes($posts[$i]['title']);
                    $posts[$i]['post'] = stripslashes($posts[$i]['post']);
                }
                $posts[$i]['date_posted'] = $posts[$i]['date_posted'];
                $posts[$i]['category'] = $this->getCategory($posts[$i]['id']);
                $posts[$i]['tags'] = $this->getTags($posts[$i]['id']);
                $posts[$i]['archive'] = $this->getArchive($posts[$i]['id']);
                $posts[$i]['author_username'] = $posts[$i]['username'];
                if(empty($posts[$i]['first_name']) and empty($posts[$i]['last_name'])){
                    $posts[$i]['author_name'] = 'Guest';
                }
                else{
                    $posts[$i]['author_name'] = $posts[$i]['first_name'] . ' ' . $posts[$i]['last_name'];
                }
                $posts[$i]['author_email'] = $posts[$i]['email'];
                $posts[$i]['author_level'] = $posts[$i]['userlevel'];
            }
        }
        return $posts;
    }
    
    public function insert( $title, $post, $author_id, $category=null, $tags=null, $discussion=null ){
        // Check if basic values are submitted, else print error
        if(empty($title) || empty($post)) {
            return false;
        }
        else {
            $this->database->insert('blog_posts', array('title'=>$title, 'post'=>$post, 
            'author_id'=>$author_id, 'discussion'=>$discussion));
            
            $blogInsertId = Database::lastInsertId();
            
            $this->database->insert('_recent_activity', array('name'=>'blog', 'grouping'=>'blog'.date("Y-m-d"), 
            'action'=>'insert', 'additional'=>$title));
            // Adds the current action to the _recent_activity table
            /*
            addArchive($blogInsertId);  
            addTags($tags, $blogInsertId);
            addCategory($category, $blogInsertId);
            */
            
            return true;
        }
    }
    
    public function update( $id=null, $title=null, $post=null, $author_id=null, $category=null, $tags=null, $discussion=null ){
        if(empty($id) || empty($title) || empty($post)) {
            return false;
        }
        else {
            $this->database->update('blog_posts', array('title'=>$title, 'post'=>$post, 'author_id'=>$author_id, 
            'discussion'=>$discussion), array('id'=>$id));
            
            $blogInsertId = Database::lastInsertId();
            
            # Adds the current action to the _recent_activity table
            $this->database->insert('_recent_activity', array('name'=>'blog', 'grouping'=>'blog'.date("Y-m-d"), 
            'action'=>'update', 'additional'=>$title));
            /*
            addArchive($blogInsertId);  
            addTags($tags, $blogInsertId);
            addCategory($category, $blogInsertId);
            */
            
            return true;
        }
    }
    
    public function trash($id=null) {
        if (empty($id)) {
            return false;
        } else {
            $this->database->update('blog_posts', 
                                    array('trash'=>'1'),
                                    array('id'=>$id));
            $name = $this->database->fetchone('blog_posts', array('id'=>$id));
            $name = $name['title'];
            $this->database->insert('_recent_activity',
                                    array('name'=>'blog',
                                          'grouping'=>'blog'.date("Y-m-d"),
                                          'action'=>'delete',
                                          'additional'=>$name)
                                    );
            return true;
        }
    }
    
    public function addArchive( $insertid=null ){
        # create archive value from current Month and year and add to database
        $archive = Date('F y');

        $archive = $this->database->fetchOne('archive', array('name'=>$archive), 'ORDER BY id');
        $archiveId = $archive['id'];
        $archiveName = $archive['name'];
        
        if(!empty($archiveId)) {    
            $query = "SELECT COUNT(*) FROM blog_post_archive (blog_post_id, archive_id) WHERE 
            blog_post_id = '$insertid' AND archive_id = '$archiveId'";
            $result = mysql_query($query);
            $count = mysql_result($result, 0);
            
            if($count == 0) {
                $this->database->insert('blog_post_archive', array('blog_post_id'=>$insertid, 'archive_id'=>$archiveId));
            }
        }
        else {
            $this->database->insert('archive', array('name'=>$archive));

            $this->database->insert('blog_post_archive', array('blog_post_id'=>$insertid, 
            'archive_id'=>Database::lastInsertId()));
        }
    }

    public function getArchive( $inId ){
        $query = $this->database->execute('fetchall', 'SELECT archive.* FROM blog_post_archive 
            LEFT JOIN (archive) ON (blog_post_archive.archive_id = archive.id) 
            WHERE blog_post_archive.blog_post_id = :inId GROUP BY name', array('inId'=>$inId));
        $tmp_array = array();
        $IDArray = array();
        foreach($query as $item){
            array_push($tmp_array, $item["name"]);
            array_push($IDArray, $item["id"]);
        }
        if(empty($tmp_array) or $tmp_array[0] == ''){
            $array = '';
        }
        else{
            $array = implode(', ', $tmp_array);
        }
        
        return $array;
    }
    
    public function addCategory( $category, $insertid=null ){
        if(!empty($subCategory)) {
            $subCategory = mysql_real_escape_string($subCategory);
            // insert $subCategory into array if more categories?
            $subCategory = preg_split("/[\s]*[,][\s]*/", $subCategory);
            // in the meanwhile functions like tags
            foreach($subCategory as $arrayCategory) {
                $category = $this->database->fetchAll('categories', array('name'=>$arrayCategory), 'ORDER BY id');
                $categoryId = $category['id'];
                $categoryName = $category['name'];
                
                if(!empty($categoryId)) {
                    $query = "SELECT COUNT(*) FROM blog_post_categories (blog_post_id, category_id) WHERE 
                    blog_post_id = '$insertid' AND category_id = '$categoryId'";
                    $result = mysql_query($query);
                    $count = mysql_result($result, 0);
                    
                    if($count == 0) {
                        $this->database->insert('blog_post_categories', array('blog_post_id'=>$insertid, 
                        'category_id'=>$categoryId));
                    }
                }
                else {
                    $this->database->insert('categories', array('name'=>$arrayCategory));
                    
                    $this->database->insert('blog_post_categories', array('blog_post_id'=>$insertid, 
                    'category_id'=>Database::lastInsertId()));
                }
                    
            }
        }
    }
    
    public function getCategory( $inId ){
        $query = $this->database->execute('fetchall', 'SELECT categories.* FROM blog_post_categories 
            LEFT JOIN (categories) ON (blog_post_categories.category_id = categories.id) 
            WHERE blog_post_categories.blog_post_id = :inId GROUP BY name', array('inId'=>$inId));
        $tmp_array = array();
        $IDArray = array();
        foreach($query as $item){
            array_push($tmp_array, $item["name"]);
            array_push($IDArray, $item["id"]);
        }
        if(empty($tmp_array)){
            $array = '';
        }
        else{
            $array = implode(', ', $tmp_array);
        }
        
        return $array;
    }
    
    public function addTags( $subTags, $insertid=null ){
        if(!empty($subTags)) {
            $subTags = preg_split("/[\s]*[,][\s]*/", $subTags);
            foreach($subTags as $arrayTags) {
                $tags = $this->database->fetchAll('tags', array('name'=>$arrayTags), 'ORDER BY id');
                $tagId = $tags['id'];
                $tagName = $tags['name'];
                
                if(!empty($tagId)) {
                    $query = "SELECT COUNT(*) FROM blog_post_tags (blog_post_id, tag_id) WHERE blog_post_id = '$insertid' 
                    AND tag_id = '$tagId'";
                    $result = mysql_query($query);
                    $count = mysql_result($result, 0);
                    
                    if($count == 0) {
                        $this->database->insert('blog_post_tags', array('blog_post_id'=>$insertid, 
                        'tag_id'=>$tagId));
                    }
                }
                else {
                    $this->database->insert('tags', array('name'=>$arrayTags));
                    
                    $this->database->insert('blog_post_tags', array('blog_post_id'=>$insertid, 
                    'tag_id'=>Database::lastInsertId()));
                }
                    
            }
        }
    }
    
    public function getTags( $inId ){
        $query = $this->database->execute('fetchall', 'SELECT tags.* FROM blog_post_tags 
            LEFT JOIN (tags) ON (blog_post_tags.tag_id = tags.id) 
            WHERE blog_post_tags.blog_post_id = :inId GROUP BY name', array('inId'=>$inId));
        $tmp_array = array();
        $IDArray = array();
        foreach($query as $item){
            array_push($tmp_array, $item["name"]);
            array_push($IDArray, $item["id"]);
        }
        if(empty($tmp_array)){
            $array = '';
        }
        else{
            $array = implode(', ', $tmp_array);
        }
        
        return $array;
    }
}
if($FieldStorage['action'] == 'blog_addBlogPost'){
    $Blog = new Blog;
    if(isset($Session->id)){
        $author_id = $Session->id;
    }
    else{
        $author_id = 0;
    }
    $Blog->insert(
                  $FieldStorage['blog_title'],
                  $FieldStorage['blog_post'],
                  $author_id,
                  $FieldStorage['blog_category'],
                  $FieldStorage['blog_tags'],
                  $FieldStorage['blog_discussion']
                 );
    header("Location: " . $FieldStorage['referer']);
}
if($FieldStorage['action'] == 'blog_updateBlogPost'){
    $Blog = new Blog;
    if(isset($Session->id)){
        $author_id = $Session->id;
    }
    else{
        $author_id = 0;
    }
    $Blog->update(
                  $FieldStorage['blog_id'],
                  $FieldStorage['blog_title'],
                  $FieldStorage['blog_post'],
                  $author_id,
                  $FieldStorage['blog_category'],
                  $FieldStorage['blog_tags'],
                  $FieldStorage['blog_discussion']
                 );
    header("Location: " . $FieldStorage['referer']);
} else if ($FieldStorage['action'] == 'blog_multi') {
    if ($FieldStorage['multiAction'] == 'delete') {
        $items = explode(',', $FieldStorage['data']);
        $Blog = new Blog;
        foreach ($items as $item) {
            print 'success!';
            $Blog->trash($item);
        }
    }
}