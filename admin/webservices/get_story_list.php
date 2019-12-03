<?php

require './db.class.php';

class GetStoryList extends DB {

    function get_story_list() {

            
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
//            $where = array('user_id'=>$user_id);
//            $get_story = $this->get_record_where('st', $where);
            $current_datetime = date('Y-m-d H:i:s');
            $query = "SELECT s.*,su.id as sent_primary_id,su.campaign_id,su.story_id,su.unique_send_id,su.unique_approval_update_id FROM story s INNER JOIN story_user su ON su.story_id=s.id INNER JOIN campaign_story_relationship csr ON csr.campaign_id=su.campaign_id INNER JOIN campaign c ON c.id=su.campaign_id WHERE su.user_id='$user_id' AND su.upload_status!=1 AND c.campaign_remaining_count!=0 AND c.campaign_remaining_count>0 AND su.story_accept_status=0 AND csr.schedule_date_time >= '$current_datetime' GROUP BY su.unique_send_id";
            
            
            $get_story = $this->query_result($query);
//            echo '<pre>';
//            print_r($get_story);
            if(!empty($get_story)){
                $where = array('user_id'=>$user_id);
                $data = array('read_status'=>1);
                $this->update_records('story_user', $data, $where);
                $story_details = array();
	    	foreach ($get_story as $key=>$value){
                
                $campaign_id = $value["campaign_id"];
                $story_id = $value["story_id"];
             $query_campaign_schedule = "SELECT csr.* FROM campaign c INNER JOIN campaign_story_relationship csr ON csr.campaign_id=c.id WHERE csr.campaign_id='$campaign_id' AND csr.story_id='$story_id'";
                $get_campaign_schedule = $this->query_result($query_campaign_schedule);
                
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
//	    		unset($value['password']);
                        if(!empty($get_campaign_schedule)){
                            $value['campaign_schedule_count']=count($get_campaign_schedule);
                            $value['campaign_schedule']=$get_campaign_schedule;
                        }else{
                            $value['campaign_schedule_count']=0;
                            $value['campaign_schedule']=array();
                        }
                        $value['rtl_status'] = 2;
	    		$story_details[] = $value;
	    	}
                
	        $response = array('status' => 'true', 'message' => 'Story Found', 'response' => $story_details);
	    } else {
	        $response = array('status' => 'false', 'message' => 'Story Not Found!');
	    }    
        } else {
                $response = array('status' => 'false', 'message' => 'Invalid request parameter');
            }
        $this->json_output($response);
    }

}

$storyL = new GetStoryList();
$storyL->get_story_list();