<?php
/*
----- Instantiate class -----
$media = new Media;
----- Get media data -----
$getmedia = $media->getMedia($media_id);
$media['log_id']
$media['log_filename']
$media['log_size']
$media['log_ip']
$media['log_date']
---- Get all media data ----
$getallmedia = $media->getMedia();
foreach ($getallmedia as $media)
{
    $media['log_id']
    $media['log_filename']
    $media['log_size']
    $media['log_ip']
    $media['log_date']
}
----- Display Media -----
$displaymedia = $media->displayMedia($filename)
*/
class Media extends ModulesBase
{   
    public function get( $id=null, $limit=null ){
        if(!empty($id)) {
            $mediaArray = $this->database->fetchOne('_uploads_log', array('log_id'=>$id));
        }
        else {
            $mediaArray = array();
            $mediaArray = $this->database->fetchAll('_uploads_log', array('trash'=>'0'), 'ORDER BY log_id DESC ' . $limit);
        }

        return $mediaArray;
    }
    
    public function trash($id=null) {
        if (empty($id)) {
            return false;
        } else {
            $this->database->update('_uploads_log', 
                                    array('trash'=>'1'),
                                    array('log_id'=>$id));
            $name = $this->database->fetchone('_uploads_log', array('log_id'=>$id));
            $name = $name['log_originalname'];
            $this->database->insert('_recent_activity',
                                    array('name'=>'uploads',
                                          'grouping'=>'uploads'.date("Y-m-d"),
                                          'action'=>'delete',
                                          'additional'=>$name)
                                    );
            return true;
        }
    }
    
    public function display( $filename=null ){
        $extension = strtolower(end(preg_split('/\./', $filename, -1 , PREG_SPLIT_NO_EMPTY)));
        
        if($extension == "jpg" || $extension == "gif" || $extension == "png" || $extension == "jpeg" || $extension == "pdf") {
            $media = "<img src='".RESOURCES_ROOT."uploads/$filename' alt='$filename' style='max-width:80%;max-height:90%;'>";
        }
        else if($extension == "mp4" || $extension == "ogv" || $extension == "ogg" || $extension == "webm") {
            if($extension == "mp4")
            {
                $media = "
                <video autoplay loop controls tabindex='0'>
                    <source src='".RESOURCES_ROOT."uploads/$filename' type='video/mp4; codecs=\"avc1.42E01E, mp4a.40.2\"' />
                    Video tag not supported. Download the video <a href='/upload/$filename'>here</a>.
                </video>";
            }
            else if($extension == "webm") {
                $media = "
                <video autoplay loop controls tabindex='0'>
                    <source src='".RESOURCES_ROOT."uploads/$filename' type='video/webm; codecs=\"vp8, vorbis\"' />
                    Video tag not supported. Download the video <a href='/upload/$filename'>here</a>.
                </video>";
            }
            else if($extension == "ogg" || $extension == "ogv") {
                $media = "
                <video autoplay loop controls tabindex='0'>
                    <source src='".RESOURCES_ROOT."uploads/$filename' type='video/ogg; codecs=\"theora, vorbis\"' />
                    Video tag not supported. Download the video <a href='/upload/$filename'>here</a>.
                </video>";
            }
        }
        else {
            $media = "Download this file here : <a href='".RESOURCES_ROOT."uploads/$filename'>$filename</a>";
        }
        return $media;
    }
}

if ($FieldStorage['action'] == 'media_multi') {
    if ($FieldStorage['multiAction'] == 'delete') {
        $items = explode(',', $FieldStorage['data']);
        $Media = new Media;
        foreach ($items as $item) {
            print 'success!';
            $Media->trash($item);
        }
    }
}
