<?php

require './db.class.php';

class GetMyEventList extends DB {

    function get_my_event_list() {

            
            if(!empty($_REQUEST['user_id'])){
                
            $user_id = $_REQUEST['user_id'];
            $username = strtolower($_REQUEST['username']);
            $query = "Select * FROM users WHERE user_id ='$user_id'";
            $get_user = $this->query_result($query);
            if(empty($get_user)){
                $response = array('status' => 'false', 'message' => 'User not found!');
                $this->json_output($response);
                exit();
            }

            $query = "SELECT s.* FROM story s INNER JOIN story_user su ON su.story_id=s.id WHERE su.user_id='$user_id' AND su.upload_status=1";
            $get_story = $this->query_result($query);

            if(!empty($get_story)){
                $story_details = array();
	    	foreach ($get_story as $key=>$value){
	    		
                        if(!empty($value['story_image'])){
                            $value['story_image']= STORY_IMAGES.$value['story_image'];
                        }else{
                            $value['story_image']= '';
                        }
                        if(!empty($value['created_at'])){
                            $value['created_at']= date('d/m', strtotime($value['created_at']));
                        }else{
                            $value['created_at']= '';
                        }
                        
	    		$story_details[] = $value;
	    	}
                
	        $response = array('status' => 'true', 'message' => 'Event Found', 'response' => $story_details);
	    } else {
	        $response = array('status' => 'false', 'message' => 'Event Not Found!');
	    }    
        } else {
                $response = array('status' => 'false', 'message' => 'Invalid request parameter');
            }
        $this->json_output($response);
    }

}

$myEvent = new GetMyEventList();
$myEvent->get_my_event_list();