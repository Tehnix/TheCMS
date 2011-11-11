<?php

class Comments
{
    protected $database;
	
	public function __construct(){
		global $Database;
		$this->database = $Database;
	}
	
    public function getComments( $table, $field, $id, $limit = null ){
        $commentIds = $this->database->fetchAll($table, array($field=>$id));
        for($i=0; $i<sizeof($commentIds); $i++){
            $comment = $this->database->fetchOne('comments', array('id'=>$commentIds[$i]['comment_id'], 'trash'=>'0'));
            $comments[$i] = $comment;
			$author = $this->database->fetchOne('usersdb', array('id'=>$comments[$i]['author_id']));
			$comments[$i]['author_username'] = $author['username'];
			if(empty($author['first_name']) and empty($author['last_name'])){
			    $comments[$i]['author_name'] = 'Guest';
			}
			else{
			    $comments[$i]['author_name'] = $author['first_name'] . ' ' . $author['last_name'];
			}
			$comments[$i]['author_email'] = $author['email'];
			$comments[$i]['author_level'] = $author['userlevel'];
        }
		return $comments;
    }
}