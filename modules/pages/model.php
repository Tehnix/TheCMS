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
    
    public function get($id=null, $additional=null){
        if (!empty($id)) {
            $pagesArray = $this->database->execute('fetchone', 
                                                    "SELECT `p`.*, 
                                                    (SELECT `name` FROM `pages_type` 
                                                     WHERE `key` = `p`.`type`) 
                                                    AS `type_name`, 
                                                    (SELECT COUNT(*) FROM `pages_comments` 
                                                     WHERE `pages_id` = `p`.`id`) 
                                                    AS `comments_count` 
                                                    FROM `pages` AS `p` 
                                                    WHERE `p`.`id` = :id
                                                    ORDER BY `weight`",
                                                    array('id'=>$id));
            if (get_magic_quotes_gpc()) {
                $pagesArray['name'] = stripslashes($pagesArray['name']);
                $pagesArray['content'] = stripslashes($pagesArray['content']);
            }
        } else {
            $pagesArray = array();
            $pagesArray = $this->database->execute('fetchall', 
                                                    "SELECT `p`.*, 
                                                    (SELECT `name` FROM `pages_type` 
                                                     WHERE `key` = `p`.`type`) 
                                                    AS `type_name`, 
                                                    (SELECT COUNT(*) FROM `pages_comments` 
                                                     WHERE `pages_id` = `p`.`id`) 
                                                    AS `comments_count` 
                                                    FROM `pages` AS `p` 
                                                    WHERE `p`.`trash` = '0'
                                                    ORDER BY `weight`");
            if (get_magic_quotes_gpc()) {
                for ($i=0; $i<sizeof($pagesArray); $i++) {
                    $pagesArray[$i]['name'] = stripslashes($pagesArray[$i]['name']);
                    $pagesArray[$i]['content'] = stripslashes($pagesArray[$i]['content']);
                }
            }
        }
        
        return $pagesArray;
    }
    
    public function insert($name=null, $content=null, $discussion=null, $type=null, $weight='0') {
        if (empty($name)) {
            return false;
        } else {
            # Find out what position the page should have
            $result = mysql_query("SELECT MAX(position) FROM pages");
            $row = mysql_fetch_array($result);
            $position = $row['MAX(position)'] + 1;
            
            # If number of pages is $maxPages or below
            $result = mysql_query("SELECT COUNT(*) FROM pages");
            $num = mysql_result($result, 0);
            if ($num < $this->maxPages) {
                
                $this->database->insert('pages',
                                        array('name'=>$name,
                                              'content'=>$content,
                                              'position'=>$position,
                                              'discussion'=>$discussion,
                                              'type'=>$type,
                                              'weight'=>$weight,
                                              'trash'=>'0')
                                        );
                
                $this->database->insert('_recent_activity',
                                        array('name'=>'pages',
                                              'grouping'=>'pages'.date("Y-m-d"),
                                              'action'=>'insert',
                                              'additional'=>$name)
                                        );
                
                return true;
            }
        }
    }
    
    public function update($id=null, $name=null, $content=null, $discussion=null, $type=null, $weight='0') {
        if (empty($id) || empty($name)) {
            return false;
        } else {
            $this->database->update('pages', 
                                    array('name'=>$name,
                                          'content'=>$content,
                                          'discussion'=>$discussion,
                                          'type'=>$type,
                                          'weight'=>$weight),
                                    array('id'=>$id));
            
            $this->database->insert('_recent_activity',
                                    array('name'=>'pages',
                                          'grouping'=>'pages'.date("Y-m-d"),
                                          'action'=>'update',
                                          'additional'=>$name)
                                    );
            
            return true;
        }
    }
    
    public function trash($id=null) {
        if (empty($id)) {
            return false;
        } else {
            $this->database->update('pages', 
                                    array('trash'=>'1'),
                                    array('id'=>$id));
            
            $name = $this->database->fetchone('pages', array('id'=>$id));
            $name = $name['name'];
            $this->database->insert('_recent_activity',
                                    array('name'=>'pages',
                                          'grouping'=>'pages'.date("Y-m-d"),
                                          'action'=>'delete',
                                          'additional'=>$name)
                                    );
            
            return true;
        }
    }
    
    public static function getTypes() {
        global $Database;
        $typeArray = array();
        $types = $Database->fetchAll('pages_type');
        for ($i=0; $i<sizeof($types); $i++) {
            if (class_exists($types[$i]['module'])) {
                $typeArray[$i] = array('key' => $types[$i]['key'], 'name' => $types[$i]['name']);
            }
        }
        return $typeArray;
    }
    
    public static function getMenu($id=null) {
        $Pages = new Pages();
        if (!empty($id)){
            $menu = array($Pages->get($id));
        } else {
            $menu = $Pages->get();
        }
        $menuArray = array();
        $i = 0;
        foreach ($menu as $page) {
            $id = $page['id'];
            $type = $page['type'];
            $active = '';
            if (URL == null) {
                if ($id == $settings['startpage']) {
                    $active = 'class="active-link"';
                    $curPage = $page['name'];
                }
                if ($page['type'] == 'pages'){
                    $href = URL_ROOT . $page['type'] . '/' . $page['id'];
                } else {
                    $href = URL_ROOT . $page['type'];
                }
                $name = $page['name'];
            
            } elseif ($page['type'] == 'pages') {
                if (URL == $page['type'] . '/' . $page['id']) {
                    $active = 'class="active-link"';
                    $curPage = $page['name'];
                }
                $href = URL_ROOT . $page['type'] . '/' . $page['id'];
                $name = $page['name'];
            } else {
                if (URL == $page['type']) {
                    $active = 'class="active-link"';
                    $curPage = $page['name'];
                }
                $href = URL_ROOT . $page['type'];
                $name = $page['name'];
            }
            $menuArray[$i]['id'] = $id;
            $menuArray[$i]['type'] = $type;
            $menuArray[$i]['href'] = $href;
            $menuArray[$i]['name'] = $name;
            $menuArray[$i]['active'] = $active;
            $i++;
        }
        return $menuArray;
    }
    
    public static function get_cur_page($id=null) {
        global $Database;
        global $settings;
        
        if (!empty($id)){
            $menu = $Database->fetchAll('pages', array('id'=>$id));
        } else {
            $menu = $Database->fetchAll('pages', array(), 'ORDER BY weight ASC');
        }
        foreach ($menu as $page) {
            if (URL == null) {
                if ($page['id'] == $settings['startpage']) {
                    $curPage = $page['name'];
                }
            } elseif ($page['type'] == 'pages') {
                if (URL == $page['type'] . '/' . $page['id']) {
                    $curPage = $page['name'];
                }
            } else {
                if (URL == $page['type']) {
                    $curPage = $page['name'];
                }
            }
        }
        return $curPage;
    }
    
    public static function get_startpage($return='array') {
        global $settings;
        if ($return == 'array') {
            $url_query = array();
            $startpage = Pages::getMenu($settings['startpage']);
            $startpage = $startpage[0];
            if ($startpage['type'] == 'pages'){
                $url_query[0] = $startpage['type'];
                $url_query[1] = $startpage['id'];
            } else {
                $url_query[0] = $startpage['type'];
            }   
        } else if ($return == 'string') {
            $url_query = '';
            $startpage = Pages::getMenu($settings['startpage']);
            $startpage = $startpage[0];
            if ($startpage['type'] == 'pages'){
                $url_query .= $startpage['type'];
                $url_query .= '/';
                $url_query .= $startpage['id'];
            } else {
                $url_query .= $startpage['type'];
            }
        }
        return $url_query;
    }
    
}

# Handle all interaction with this modules model class
if ($FieldStorage['action'] == 'pages_newPage') {
    $Pages = new Pages;
    $Pages->insert(
                   $FieldStorage['pages_title'],
                   $FieldStorage['pages_content'],
                   $FieldStorage['pages_discussion'],
                   $FieldStorage['pages_type'],
                   $FieldStorage['pages_weight']
                  );
    header("Location: " . $FieldStorage['referer']);
} else if ($FieldStorage['action'] == 'pages_updatePage') {
    $Pages = new Pages;
    $Pages->update(
                   $FieldStorage['pages_id'],
                   $FieldStorage['pages_title'],
                   $FieldStorage['pages_content'],
                   $FieldStorage['pages_discussion'],
                   $FieldStorage['pages_type'],
                   $FieldStorage['pages_weight']
                  );
    header("Location: " . $FieldStorage['referer']);
} else if ($FieldStorage['action'] == 'pages_multi') {
    if ($FieldStorage['multiAction'] == 'delete') {
        $items = explode(',', $FieldStorage['data']);
        $Pages = new Pages;
        foreach ($items as $item) {
            $Pages->trash($item);
        }
    }
}