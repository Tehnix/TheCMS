<?php
/*

*/

class Portfolio extends ModulesBase
{
    public function get($id=null, $additional=null) {
        if (!empty($id)) {
            $portfolioArray = $this->database->fetchOne('portfolio', array('id'=>$id));
            if (get_magic_quotes_gpc()) {
                $portfolioArray['name'] = stripslashes($portfolioArray['name']);
                $portfolioArray['description'] = stripslashes($portfolioArray['description']);
            }
        } else {
            $portfolioArray = array();
            $portfolioArray = $this->database->fetchAll('portfolio', array('trash'=>'0'), $additional);
            if (get_magic_quotes_gpc()) {
                for ($i=0; $i<sizeof($portfolioArray ); $i++) {
                    $portfolioArray[$i]['name'] = stripslashes($portfolioArray[$i]['name']);
                    $portfolioArray[$i]['description'] = stripslashes($portfolioArray[$i]['description']);
                }
            }
        }
        return $portfolioArray;
    }
    
    public function get_images($id=null, $additional=null) {
        $imgArray = array();
        if (!empty($id)) {
            $sql = "SELECT pp.*, p.name as portfolio_name, u.log_filename as img_file
            FROM portfolio_pictures AS pp 
            LEFT JOIN portfolio AS p ON pp.portfolio_id = p.id
            LEFT JOIN _uploads_log AS u ON pp.image = u.log_id
            WHERE pp.trash = :trash
            AND portfolio_id = :portfolio_id
            " . $additional;
            $imgArray = $this->database->execute('fetchall', $sql, array('portfolio_id'=>$id, 'trash'=>'0'));
        } else {
            $sql = "SELECT pp.*, p.name as portfolio_name, u.log_filename as img_file
            FROM portfolio_pictures AS pp 
            LEFT JOIN portfolio AS p ON pp.portfolio_id = p.id
            LEFT JOIN _uploads_log AS u ON pp.image = u.log_id
            WHERE pp.trash = :trash
            AND p.trash = :trash
            " . $additional;
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
    
    public function insert($name=null, $description=null, $weight=0) {
        if (empty($name)) {
            return false;
        } else {
            if (!is_numeric($weight)) {
                $weight = 0;
            }
            $this->database->insert('portfolio',
                                    array('name'=>$name,
                                          'description'=>$description,
                                          'weight'=>$weight)
                                    );
            $this->database->insert('_recent_activity',
                                    array('name'=>'portfolio',
                                          'grouping'=>'portfolio'.date("Y-m-d"),
                                          'action'=>'insert',
                                          'additional'=>$name)
                                    );
            return true;
        }
    }
    
    public function trash($id=null) {
        if (empty($id)) {
            return false;
        } else {
            $this->database->update('portfolio', 
                                    array('trash'=>'1'),
                                    array('id'=>$id));
            
            $name = $this->database->fetchone('portfolio', array('id'=>$id));
            $name = $name['name'];
            $this->database->insert('_recent_activity',
                                    array('name'=>'portfolio',
                                          'grouping'=>'portfolio'.date("Y-m-d"),
                                          'action'=>'delete',
                                          'additional'=>$name)
                                    );
            
            return true;
        }
    }
    
    public function trash_pictures($id=null) {
        if (empty($id)) {
            return false;
        } else {
            $this->database->update('portfolio_pictures', 
                                    array('trash'=>'1'),
                                    array('id'=>$id));
            
            $name = $this->database->fetchone('portfolio_pictures', array('id'=>$id));
            $name = $name['name'];
            $this->database->insert('_recent_activity',
                                    array('name'=>'portfolio_pictures',
                                          'grouping'=>'portfolio_pictures'.date("Y-m-d"),
                                          'action'=>'delete',
                                          'additional'=>$name)
                                    );
            
            return true;
        }
    }
    
    public function upload($portfolio_id=null, $_REQUEST) {
        if (!empty($portfolio_id)) {
            global $Database;
        
            # HTTP headers for no cache etc
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");

            # Settings
            $targetDir = UPLOAD_ROOT;

            #$cleanupTargetDir = false; // Remove old files
            #$maxFileAge = 60 * 60; // Temp file age in seconds

            # 5 minutes execution time
            @set_time_limit(5 * 60);

            # Uncomment this one to fake upload time
            #usleep(5000);

            # Get parameters
            $chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
            $chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
            $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

            # Clean the fileName for security reasons
            $fileName = preg_replace('/[^\w\._]+/', '', $fileName);

            # Make sure the fileName is unique but only if chunking is disabled
            if ($chunks < 2 && 
                file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)){
                $ext = strrpos($fileName, '.');
                $fileName_a = substr($fileName, 0, $ext);
                $fileName_b = substr($fileName, $ext);

                $count = 1;
                while(file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a 
                                  . '_' . $count . $fileName_b))
                    $count++;

                $fileName = $fileName_a . '_' . $count . $fileName_b;
            }

            # Create target dir
            if (!file_exists($targetDir))
                @mkdir($targetDir);

            # Look for the content type header
            if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
                $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

            if (isset($_SERVER["CONTENT_TYPE"]))
                $contentType = $_SERVER["CONTENT_TYPE"];

            # Handle non multipart uploads older WebKit versions didn't 
            # support multipart in HTML5
            if (strpos($contentType, "multipart") !== false){
                if (isset($_FILES['file']['tmp_name']) && 
                    is_uploaded_file($_FILES['file']['tmp_name'])){
                    # Open temp file
                    $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName,
                                 $chunk == 0 ? "wb" : "ab");
                    if ($out){
                        # Read binary input stream and append it to temp file
                        $in = fopen($_FILES['file']['tmp_name'], "rb");

                        if ($in){
                            while ($buff = fread($in, 4096))
                                fwrite($out, $buff);
                        } else
                            die('{"jsonrpc" : "2.0", '
                                . '"error" : {"code": 101, '
                                . '"message": "Failed to open input stream."}, '
                                . '"id" : "id"}');
                        fclose($in);
                        fclose($out);
                        @unlink($_FILES['file']['tmp_name']);
                    } else
                        die('{"jsonrpc" : "2.0", '
                            . '"error" : {"code": 102, '
                            . '"message": "Failed to open output stream."}, '
                            . '"id" : "id"}');
                } else
                    die('{"jsonrpc" : "2.0", '
                        . '"error" : {"code": 103, '
                        . '"message": "Failed to move uploaded file."}, '
                        . '"id" : "id"}');
            } else {
                # Open temp file
                $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, 
                             $chunk == 0 ? "wb" : "ab");
                if ($out) {
                    # Read binary input stream and append it to temp file
                    $in = fopen("php://input", "rb");

                    if ($in) {
                        while ($buff = fread($in, 4096))
                            fwrite($out, $buff);
                    } else
                        die('{"jsonrpc" : "2.0", '
                            . '"error" : {"code": 101, '
                            . '"message": "Failed to open input stream."}, '
                            . '"id" : "id"}');

                    fclose($in);
                    fclose($out);
                } else
                    die('{"jsonrpc" : "2.0", '
                        . '"error" : {"code": 102, '
                        . '"message": "Failed to open output stream."}, '
                        . '"id" : "id"}');
            }

            # Return JSON-RPC response
            $Database->insert('_uploads_log',
                              array('log_originalname'=>$_FILES['file']['name'],
                              'log_filename'=>$fileName,
                              'log_size'=>$_FILES['file']['size'],
                              'log_ip'=>$_SERVER['REMOTE_ADDR']));
            $sql = "INSERT INTO portfolio_pictures (portfolio_id, image, name) VALUES(:portfolio_id, (SELECT log_id FROM _uploads_log WHERE log_filename = :log_filename), (SELECT log_originalname FROM _uploads_log WHERE log_filename = :log_filename))";
            $args = array('portfolio_id'=>$portfolio_id, 'log_filename'=>$fileName);
            $Database->execute('exec', $sql, $args);
            $Database->insert('_recent_activity',
                              array('name'=>'portfolio_pictures',
                              'grouping'=>'portfolio_pictures'.date("Y-m-d"),
                              'action'=>'upload',
                              'additional'=>$_FILES['file']['name']));
        
            die('{"jsonrpc" : "2.0", '
                . '"result" : null, '
                . '"id" : "id"}');
        }
    }
}

# Handle all interaction with this modules model class
if ($FieldStorage['action'] == 'portfolio_newPortfolio') {
    $Portfolio = new Portfolio;
    $Portfolio->insert($FieldStorage['portfolio_name'],
                       $FieldStorage['portfolio_description'],
                       $FieldStorage['portfolio_weight']);
    header("Location: " . $FieldStorage['referer']);
} else if ($FieldStorage['action'] == 'portfolio_getImages') {
    $Portfolio = new Portfolio;
    $images = $Portfolio->get_images($FieldStorage['portfolio_id']);
    print json_encode($images);
} else if ($_GET['action'] == 'portfolio_addImages') {
    if (isset($_GET['portfolio_id'])) {
        $Portfolio = new Portfolio;
        $Portfolio->upload($_GET['portfolio_id'], $_REQUEST);
    }
} else if ($FieldStorage['action'] == 'portfolio_multi') {
    if ($FieldStorage['multiAction'] == 'delete') {
        $items = explode(',', $FieldStorage['data']);
        $Portfolio = new Portfolio;
        foreach ($items as $item) {
            $Portfolio->trash($item);
        }
    }
} else if ($FieldStorage['action'] == 'portfolio_pictures_multi') {
    if ($FieldStorage['multiAction'] == 'delete') {
        $items = explode(',', $FieldStorage['data']);
        $Portfolio = new Portfolio;
        foreach ($items as $item) {
            $Portfolio->trash_pictures($item);
        }
    }
}