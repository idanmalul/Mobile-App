<?php

require './db.class.php';

class GetUserProfile extends DB {

    function get_user_profile() {
//        $response = array('status' => 'false', 'message' => $_REQUEST);
//            
//        $this->json_output($response);exit();
            if(!empty($_REQUEST['username'])){
                $username = $_REQUEST['username'];
//	    $where = array('username' => $_REQUEST['username']);
//	    $get_user = $this->get_record_where('users', $where);
                //$query = "Select * FROM users WHERE username='$username' or email='$username'";
                $query = "Select u.*,m.username as member_username FROM members m INNER JOIN users u ON u.user_id = m.u_id WHERE u.username='$username' or u.email='$username'";
           $get_user = $this->query_result($query);
	    if($get_user){
	    	foreach ($get_user as $key=>$value){
	    		$userid = $value->user_id;
//                        if(!empty($value['user_profile_img'])){
//                        $value['user_profile_img']= USER_PROFILE_IMAGES.$value['user_profile_img'];
//                        }else{
//                            $value['user_profile_img']= '';
//                        }
	    		unset($value['password']);
                        $value['full_name'] = ucfirst($value['full_name']);
                        if(!empty($value['birthday'])){
                            $value['birthday'] = date('d/m/Y', strtotime($value['birthday']));
                        }
                        
//                        echo $value['birthday'];die();
                        $e=explode(' ', $value['full_name']);
                        if(!empty($e)){
                            if(empty($value['first_name']))
                            $value['first_name'] = ucfirst($e[0]);
                            if(empty($value['last_name']))
                            $value['last_name'] = ucfirst($e[1]);
                        }
                        $e=explode(',', $value['favourites']);
                        if(!empty($e)){
                            $value['favourites'] = $e;
                           // $value['last_name'] = $e[1];
                        }else{
                            
                        }
                        if(empty($value['last_name'])){
                            $value['last_name'] = '';
                        }
                        
                        $query2 = "SELECT su.*,c.campaign_name,c.campaign_description,c.reward_number,c.reward_currency,c.reward,s.story_title,s.story_image,s.story_description,s.media_type,u.first_name,u.last_name,u.full_name,u.username,u.profile_pic_url,csr.schedule_date_time,u.follower_count,u.paypal_email,SUM(su.story_viewer_count*c.reward_number) as total_reward FROM story s INNER JOIN story_user su ON su.story_id=s.id INNER JOIN users u ON u.user_id=su.user_id INNER JOIN campaign_story_relationship csr ON csr.id=su.campaign_story_relationship_id INNER JOIN campaign c ON c.id=su.campaign_id WHERE su.user_id='$userid' AND su.upload_status=1 AND su.media_status='ok' ORDER BY su.id DESC";
                        $get_account_balance = $this->query_result($query2);
                         if(!empty($get_account_balance)){
                             $value['account_balance'] = $get_account_balance[0]['total_reward'];
                         }
	    		$user_details = $value;
	    	}
                $favourites = array();
                $where = array('favourite_status' => 1);
                $favourites_data = $this->get_record_where('favourites', $where);
                if(!empty($favourites_data)){
                    foreach ($favourites_data as $key=>$value){
                        if(!empty($value['favourite_image'])){
                            $value['favourite_image']= FAVOURITE_IMAGES.$value['favourite_image'];
                        }else{
                            $value['favourite_image']= '';
                        }
                        $favourites[] = $value;
                    }
                }
                
	        $response = array('status' => 'true', 'message' => 'User Found', 'user_details' => $user_details, 'favourites'=>$favourites);
	    } else {
	        $response = array('status' => 'false', 'message' => 'User Not Found!');
	    }    
        } else {
                $response = array('status' => 'false', 'message' => 'Invalid request parameter');
            }
        $this->json_output($response);
    }

}

$usr = new GetUserProfile();
$usr->get_user_profile();