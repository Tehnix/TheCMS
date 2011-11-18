<?php
/*

*/

class References extends ModulesBase
{
    public function get($id){
        if(!empty($id)){
            $refArray = $this->database->fetchOne(
                                                  'references',
                                                  array('id'=>$id)
                                                 );
            if(get_magic_quotes_gpc()){
                $refArray['description'] = stripslashes($refArray['description']);
            }
            $refArray['comments_count'] = $this->database->count(
                                                                 'references_comments',
                                                                 array('references_id'=>$id)
                                                                );
        }
        else{
            $refArray = array();
            $refArray = $this->database->fetchAll(
                                                  'references',
                                                  array('trash'=>'0'),
                                                  $additional
                                                 );
            for($i=0; $i<sizeof($refArray ); $i++){
                if(get_magic_quotes_gpc()){
                    $refArray[$i]['description'] = stripslashes($refArray[$i]['description']);
                }
                $refArray[$i]['comments_count'] = $this->database->count(
                                                                         'references_comments',
                                                                         array('references_id'=>$refArray[$i]['id'])
                                                                        );
            }
        }
        return $refArray;
    }
       
    public function insert(){
        
    }
}