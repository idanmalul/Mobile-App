<?php
//echo json_encode($_POST);die();
require './db.class.php';

class UserStoryUpload extends DB {

    function user_story_upload() {
       // $_REQUEST['username'] = 'errahult';
        //$_REQUEST['password'] = 'rt@$1824';
        if(!empty($_REQUEST['story_id']) && !empty($_REQUEST['user_id']) && !empty($_REQUEST['sent_primary_id'])){
          $data = array();
          $story_id =$_REQUEST['story_id'];
          $user_id =$_REQUEST['user_id'];
          $sent_primary_id =$_REQUEST['sent_primary_id'];
//          $username =strtolower($_REQUEST['username']);
//          $password =$_REQUEST['password'];
          //$auth_id =$this->private_decrypt($_REQUEST['auth_id']);
          $query = "SELECT s.* FROM story s INNER JOIN story_user su ON su.story_id=s.id WHERE su.user_id='$user_id' AND su.upload_status!=1 AND s.remaining_count!=0 AND s.remaining_count>0";
            $get_story = $this->query_result($query);
//            echo '<pre>';
//            print_r($get_story);
            if(!empty($get_story)){
                
            
                shell_exec('cd /domains/10k.tempurl.co.il/public_html/webservices');
                //echo shell_exec('sudo chmod 777 -R /var/www/html/inst/vendor/mgp-25/instagram-php/sessions');
                $output_including_status = shell_exec('php uploadStory_v2.php '.$user_id.' '.$story_id.' '.$sent_primary_id.' story');
                
                $op = json_decode($output_including_status);
//                print_r($op);die();
                if(empty($op)){
                    $op = array();
                }
                
                if($op->success == 1){
                   $response = array('status' => 'true', 'message' => 'Uploaded!', 'user_id'=>$user_id, 'story_id'=>$story_id, "ig_response"=>$op);
                }else{
                    $response = array('status' => 'false', 'message' => 'Uploading Failed');
                }
    /*      $query = "SELECT s.* FROM story s INNER JOIN story_user su ON su.story_id=s.id INNER JOIN users u ON u.user_id=su.user_id WHERE su.user_id='$user_id' AND su.story_id='$story_id'";
            $get_story = $this->query_result($query);
//          print_r($check_user);die();
          //$check_user = $this->get_record_where($table='users', $where);
          if(!empty($get_story)){
             // print_r($check_sap_id);die();
//                $user_detail = $check_user[0];
               
                
	    } else {
                $response = array('status' => 'false', 'message' => 'Record not found!');
	        
	    }    */
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