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
$newpage = $pages->newPage($title, $content, $discussion, $app);
----- To update a page -----
$updatepage = $pages->updatePage($updateId, $title, $content, $discussion, $app);
*/
class Pages
{
public $database;
public $maxPages = 100;

	public function __construct(){
		global $Database;
		$this->database = $Database;
	}
	
	public function getPage( $id=null, $additional = null ){
		if(!empty($id)) {
			$pagesArray = $this->database->fetchOne('pages', array('id'=>$id));
			$pagesArray['comments_count'] = $this->database->count('pages_comments', array('pages_id'=>$id));
		}
		else {
			$pagesArray = array();
			$pagesArray = $this->database->fetchAll('pages', array('trash'=>'0'), $additional);
			for($i=0; $i<sizeof($pagesArray); $i++){
				$pagesArray[$i]['comments_count'] = $this->database->count('pages_comments', 
				array('pages_id'=>$pagesArray[$i]['id']));
			}
		}
		
		return $pagesArray;
	}
	
	public function newPage( $name=null, $content=null, $discussion=null, $app=null, $editable=null ){
		if(empty($name))
		{
			return false;
		}
		else
		{
			// Find out what position the page should have
			$result = mysql_query("SELECT MAX(position) FROM pages");
			$row = mysql_fetch_array($result);
			$position = $row['MAX(position)'] + 1;
			
			// If number of pages is $maxPages or below
			$result = mysql_query("SELECT COUNT(*) FROM pages");
			$num = mysql_result($result, 0);
			if($num < $this->maxPages)
			{
				if(empty($app) OR $app == "Text")
				{
					$app = "Text";
					$editable = "1";
				}
				else
				{
					$editable = "0";
				}
				
				$this->database->insert('pages', array('name'=>$name, 'content'=>$content, 'position'=>$position,
				'discussion'=>$discussion, 'editable'=>$editable, 'app'=>$app, 'trash'=>'0'));
				
				$this->database->insert('_recent_activity', array('name'=>'pages', 'grouping'=>'pages'.date("Y-m-d"), 
				'action'=>'insert', 'additional'=>$name));
				
				return true;
			}
		}
		
	}
	
	public function updatePage( $id=null, $name=null, $content=null, $discussion=null, $app=null ){		
		// Error Checking
		if(empty($id) || empty($name) || empty($content)) {
			return false;
		}
		// If all is fine, proceed
		else {
			$this->database->update('pages', array('name'=>$name, 'content'=>$content,
			 'discussion'=>$discussion, 'app'=>$app), array('id'=>$id));
			
			$this->database->insert('_recent_activity', array('name'=>'pages', 'grouping'=>'pages'.date("Y-m-d"), 
			'action'=>'update', 'additional'=>$name));
			
			return true;
		}
		
	}
	
}
if($FieldStorage['action'] == 'pages_newPage'){
	$Pages = new Pages;
	$Pages->newPage($FieldStorage['pages_title'],
					$FieldStorage['pages_content'],
					$FieldStorage['pages_discussion'],
					$FieldStorage['pages_type']);
	header("Location: " . $FieldStorage['referer'] . "");
}
if($FieldStorage['action'] == 'pages_updatePage'){
	$Pages = new Pages;
	$Pages->updatePage($FieldStorage['pages_id'],
					   $FieldStorage['pages_title'],
					   $FieldStorage['pages_content'],
					   $FieldStorage['pages_discussion'],
					   $FieldStorage['pages_type']);
	header("Location: " . $FieldStorage['referer'] . "");
}