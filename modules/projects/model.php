<?php
/*

*/

class Projects extends ModulesBase
{
    public function get($id){
        if(!empty($id)){
            $projectsArray = $this->database->fetchOne(
                                                       'projects',
                                                       array('id'=>$id)
                                                      );
            if(get_magic_quotes_gpc()){
                $projectsArray['description'] = stripslashes($projectsArray['description']);
            }
            $projectsArray['comments_count'] = $this->database->count(
                                                                      'projects_comments',
                                                                      array('projects_id'=>$id)
                                                                     );
        }
        else{
            $projectsArray = array();
            $projectsArray = $this->database->fetchAll(
                                                       'projects',
                                                       array('trash'=>'0'),
                                                       $additional
                                                      );
            for($i=0; $i<sizeof($projectsArray ); $i++){
                if(get_magic_quotes_gpc()){
                    $projectsArray[$i]['description'] = stripslashes($projectsArray[$i]['description']);
                }
                $projectsArray[$i]['comments_count'] = $this->database->count(
                                                                              'projects_comments',
                                                                              array('projects_id'=>$projectsArray[$i]['id'])
                                                                             );
            }
        }
        return $projectsArray;
    }
       
    public function insert(){
        
    }
}