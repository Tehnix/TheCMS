<?php
/*
----- Instatiate class -----
$pages = new Pages;
----- To get page -----
$getpage = $pages->getPage($page_id);
$getpage['id'];
$getpage['name'];
$getpage['content'];
$getpage['discussion'];
$getpage['editable'];
$getpage['display'];
$getpage['app'];
$getpage['modified'];
----- To get all pages -----
$getallpages = $pages->getPage();
foreach ($getallpages as $pages)
{
    $page['id'];
    $page['name'];
    $page['content'];
    $page['discussion'];
    $page['editable'];
    $page['display'];
    $page['app'];
    $page['modified'];
}
----- To add new page -----
$newpage = $pages->newPage($title, $content, $discussion, $type);
----- To update a page -----
$updatepage = $pages->updatePage($updateId, $title, $content, $discussion, $type);
*/
class Pages extends ModulesBase
{
    protected $maxPages = 100;
    
    public function getPage( $id=null, $additional = null ){
        if(!empty($id)) {
            $pagesArray = $this->database->fetchOne('pages', array('id'=>$id));
            if(get_magic_quotes_gpc()){
                $pagesArray['name'] = stripslashes($pagesArray['name']);
                $pagesArray['content'] = stripslashes($pagesArray['content']);
            }
            $pagesArray['comments_count'] = $this->database->count('pages_comments', array('pages_id'=>$id));
        }
        else {
            $pagesArray = array();
            $pagesArray = $this->database->fetchAll('pages', array('trash'=>'0'), $additional);
            for($i=0; $i<sizeof($pagesArray); $i++){
                if(get_magic_quotes_gpc()){
                    $pagesArray[$i]['name'] = stripslashes($pagesArray[$i]['name']);
                    $pagesArray[$i]['content'] = stripslashes($pagesArray[$i]['content']);
                }
                $pagesArray[$i]['comments_count'] = $this->database->count('pages_comments', 
                                                                           array('pages_id'=>$pagesArray[$i]['id']));
            }
        }
        
        return $pagesArray;
    }
    
    public function newPage( $name=null, $content=null, $discussion=null, $type=null ){
        if(empty($name))
        {
            return false;
        }
        else
        {
            # Find out what position the page should have
            $result = mysql_query("SELECT MAX(position) FROM pages");
            $row = mysql_fetch_array($result);
            $position = $row['MAX(position)'] + 1;
            
            # If number of pages is $maxPages or below
            $result = mysql_query("SELECT COUNT(*) FROM pages");
            $num = mysql_result($result, 0);
            if($num < $this->maxPages)
            {
                
                $this->database->insert('pages', array('name'=>$name, 'content'=>$content, 'position'=>$position,
                'discussion'=>$discussion, 'type'=>$type, 'trash'=>'0'));
                
                $this->database->insert('_recent_activity', array('name'=>'pages', 'grouping'=>'pages'.date("Y-m-d"), 
                'action'=>'insert', 'additional'=>$name));
                
                return true;
            }
        }
    }
    
    public function updatePage( $id=null, $name=null, $content=null, $discussion=null, $type=null ){
        if(empty($id) || empty($name)) {
            return false;
        }
        else {
            $this->database->update('pages', array('name'=>$name, 'content'=>$content,
             'discussion'=>$discussion, 'type'=>$type), array('id'=>$id));
            
            $this->database->insert('_recent_activity', array('name'=>'pages', 'grouping'=>'pages'.date("Y-m-d"), 
            'action'=>'update', 'additional'=>$name));
            
            return true;
        }
        
    }
    
    public static function getPageTypes(){
        global $Database;
        $typeArray = array();
        $types = $Database->fetchAll('pages_type');
        for($i=0; $i<sizeof($types); $i++){
            if(class_exists($types[$i]['module'])){
                $typeArray[$i] = array('key' => $types[$i]['key'], 'name' => $types[$i]['name']);
            }
        }
        return $typeArray;
    }
    
    public static function getPageMenu( $id=null ){
        global $Database;
        if(!empty($id)){
            $types = $Database->fetchOne('pages', array('id'=>$id));
        }
        else{
            $types = $Database->fetchAll('pages', array(), 'ORDER BY id ASC');
        }
        return $types;
    }
}


# Handle all interaction with this modules model class
if($FieldStorage['action'] == 'pages_newPage'){
    $Pages = new Pages;
    $Pages->newPage(
                    $FieldStorage['pages_title'],
                    $FieldStorage['pages_content'],
                    $FieldStorage['pages_discussion'],
                    $FieldStorage['pages_type']
                   );
    header("Location: " . $FieldStorage['referer'] . "");
}
else if($FieldStorage['action'] == 'pages_updatePage'){
    $Pages = new Pages;
    $Pages->updatePage(
                       $FieldStorage['pages_id'],
                       $FieldStorage['pages_title'],
                       $FieldStorage['pages_content'],
                       $FieldStorage['pages_discussion'],
                       $FieldStorage['pages_type']
                      );
    header("Location: " . $FieldStorage['referer'] . "");
}