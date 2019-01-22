<?php
require './db.class.php';

class UserStoryUpload extends DB {

    function user_story_upload() {
       
        if(!empty($_REQUEST['story_id']) && !empty($_REQUEST['user_id'])){
          $data = array();
          $story_id =$_REQUEST['story_id'];
          $user_id =$_REQUEST['user_id'];

          $query = "SELECT s.* FROM story s INNER JOIN story_user su ON su.story_id=s.id WHERE su.user_id='$user_id' AND su.upload_status!=1 AND s.remaining_count!=0 AND s.remaining_count>0";
            $get_story = $this->query_result($query);

            if(!empty($get_story)){
                
            
                shell_exec('cd /domains/10k.tempurl.co.il/public_html/webservices');
                //echo shell_exec('sudo chmod 777 -R /var/www/html/inst/vendor/mgp-25/instagram-php/sessions');
                $output_including_status = shell_exec('php uploadStory.php '.$user_id.' '.$story_id.' story');
                
                $op = json_decode($output_including_status);
                if(empty($op)){
                    $op = array();
                }
                
                if($op->success == 1){
                   $response = array('status' => 'true', 'message' => 'Uploaded!', 'user_id'=>$user_id, 'story_id'=>$story_id, "ig_response"=>$op);
                }else{
                    $response = array('status' => 'false', 'message' => 'Uploading Failed');
                }
    
                }else{
                    $response = array('status' => 'false', 'message' => 'Event not found!');
                }
    }else{
         $response = array('status' => 'false', 'message' => 'Invalid request parameter');
    }
        $this->json_output($response);
    }

}

$second = new UserStoryUpload();
$second->user_story_upload();