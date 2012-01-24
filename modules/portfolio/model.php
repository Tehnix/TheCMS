<?php
/*

*/

class Portfolio extends ModulesBase
{
    public function get($id) {
        if (!empty($id)) {
            $portfolioArray = $this->database->fetchOne('portfolio', array('id'=>$id));
            if (get_magic_quotes_gpc()) {
                $portfolioArray['name'] = stripslashes($portfolioArray['name']);
                $portfolioArray['description'] = stripslashes($portfolioArray['description']);
            }
        } else {
            $portfolioArray = array();
            $portfolioArray = $this->database->fetchAll('portfolio', array('trash'=>'0'), ' ORDER BY weight');
            if (get_magic_quotes_gpc()) {
                for ($i=0; $i<sizeof($portfolioArray ); $i++) {
                    $portfolioArray[$i]['name'] = stripslashes($portfolioArray[$i]['name']);
                    $portfolioArray[$i]['description'] = stripslashes($portfolioArray[$i]['description']);
                }
            }
        }
        return $portfolioArray;
    }
    
    public function get_images($id) {
        $imgArray = array();
        if (!empty($id)) {
            $sql = "SELECT pp.*, p.name as portfolio_name, u.log_filename as img_file
            FROM portfolio_pictures AS pp 
            LEFT JOIN portfolio AS p ON pp.portfolio_id = p.id
            LEFT JOIN _uploads_log AS u ON pp.image = u.log_id
            WHERE pp.trash = :trash
            AND portfolio_id = :portfolio_id
            ORDER BY weight";
            $imgArray = $this->database->execute('fetchall', $sql, array('portfolio_id'=>$id, 'trash'=>'0'));
        } else {
            $sql = "SELECT pp.*, p.name as portfolio_name, u.log_filename as img_file
            FROM portfolio_pictures AS pp 
            LEFT JOIN portfolio AS p ON pp.portfolio_id = p.id
            LEFT JOIN _uploads_log AS u ON pp.image = u.log_id
            WHERE pp.trash = :trash
            AND p.trash = :trash
            ORDER BY weight";
            $imgArray = $this->database->execute('fetchall', $sql, array('trash'=>'0'));
        }
        if (get_magic_quotes_gpc()) {
            for ($i=0; $i<sizeof($imgArray ); $i++) {
                    $imgArray[$i]['name'] = stripslashes($imgArray[$i]['name']);
                    $imgArray[$i]['description'] = stripslashes($imgArray[$i]['description']);
            }
        }
        
        return $imgArray;
    }
    
    public function insert() {
        
    }
}

# Handle all interaction with this modules model class
if ($FieldStorage['action'] == 'portfolio_getImages') {
    $portfolio = new Portfolio;
    $images = $portfolio->get_images($FieldStorage['portfolio_id']);
    print json_encode($images);
}