<?php

require './db.class.php';

class ULogin extends DB {

    function u_login() {
        
        if(!empty($_REQUEST['username']) && !empty($_REQUEST['password'])){
          $data = array();  
          $op = array();
          $user_details = array();
          $user_id = '';
          $username =strtolower($_REQUEST['username']);
          $password = md5($_REQUEST['password']);
          $gcm_id = '';
          if(!empty($_REQUEST['gcm_id'])){
              $gcm_id = $_REQUEST['gcm_id'];
          }
          $device_name = '';
          if(!empty($_REQUEST['device_name'])){
              $device_name = $_REQUEST['device_name'];
          }
          $where = array('username'=>$username);
          
          $query = "Select u.*,m.username as member_username FROM members m INNER JOIN users u ON u.user_id = m.u_id WHERE (m.username='$username' or m.email='$username') AND m.password='$password' AND u.pk!=''";
          $check_user = $this->query_result($query);
//          echo json_encode($_REQUEST);die();
          if(!empty($check_user)){
                
                $user_details = $check_user[0];
                $user_id = $user_details['user_id'];
                unset($user_details['password']);
                if(empty($user_details['last_name'])){
                    $user_details['last_name'] = '';
                }
                $data['gcm_id'] =$gcm_id;
                $data['device_name'] =$device_name;
                $u_where = array('user_id'=>$user_id);
                $this->update_records('users', $data, $u_where);
                
                $this->send_static_campaigns($user_id);
                $response = array('status' => 'true', 'message' => 'Logged In Successfully!', 'user_id'=>$user_id, "ig_response"=>$op,"user_response"=>$user_details);
                
                
	    } else {
                $response = array('status' => 'false', 'message' => 'Invalid username and password.', 'user_id'=>$user_id, "ig_response"=>$op,"user_response"=>$user_details);
	    }    
    }else{
         $response = array('status' => 'false', 'message' => 'Invalid request parameter');
    }
        $this->json_output($response);
    }
    
    function send_static_campaigns($user_id) {
          $query = "Select * FROM campaign WHERE static_campaign_status=1";
          $static_campaigns = $this->query_result($query);
//          echo '<pre>';
//          print_r($static_campaigns);
            if (!empty($static_campaigns))
            {
            $data = array();
            foreach ($static_campaigns as $camp) {
                
            $campaign_id = $camp['id'];
            $campaign_stories = $this->get_story_by_campaign_id($campaign_id);
//            print_r($campaign_stories);
            $admin_id = $this->get_admin_id();
            
            $data['created_by']= $admin_id;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['user_id'] = $user_id;
            $data['campaign_id'] = $campaign_id;
            // echo "<pre>"; print_r($u_exp); die();
            
            $alphnumeric = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            $rand_code = substr(str_shuffle($alphnumeric),0,8);
            $unique_id = $rand_code.'_'.time();
               
            if(!empty($campaign_stories)){
//                            print_r($campaign_stories);die();
                    $first_story_id = $campaign_stories[0]['story_id'];
                    $reward = $campaign_stories[0]['reward'];
                    $campaign_description = $campaign_stories[0]['campaign_description'];
                    foreach ($campaign_stories as $k => $s) {
                        $check_is_user_camp = $this->check_user_have_the_campaign_or_not($user_id,$campaign_id);
                        if(empty($check_is_user_camp)){
                        
                        $unique_approval_update_id = $rand_code.'_'.time().'_'.$s['campaign_id'].'_'.$s['story_id'];
                        $data['story_id'] = $s['story_id'];
                        $data['campaign_story_relationship_id'] = $s['campaign_story_relationship_id'];
                        $data['unique_send_id'] = $unique_id;
                        $data['unique_approval_update_id'] = $unique_approval_update_id;
//                        print_r($data);
                        $inserted_id = $this->insert_records('story_user', $data);
//                        echo $inserted_id;
//                        if(!empty($inserted_id)){
//                            
//                        }
                        
                        }
                    }

                    
                }
                
                }
                if(!empty($inserted_id)){
                    $this->update_user_login_status($user_id);
                }
                
                
        }
    }
    
    function check_user_have_the_campaign_or_not($userid = 0,$campaignid = 0)
    {
            $query = "SELECT * FROM story_user su WHERE su.user_id='$userid' AND su.campaign_id='$campaignid'";
            
            $result = $this->query_result($query);
            return $result;
    }
    function get_story_by_campaign_id($id = 0)
    {
            $query = "SELECT s.id as story_id,s.story_title,csr.id as campaign_story_relationship_id,csr.campaign_id,c.reward,c.campaign_description FROM story s INNER JOIN campaign_story_relationship csr ON csr.story_id=s.id INNER JOIN campaign c ON c.id=csr.campaign_id WHERE csr.campaign_id='$id'";
            
            $stories = $this->query_result($query);
            return $stories;
    } 
    function get_admin_id()
    {
            $query = "SELECT admin_id FROM admin WHERE admin_type=1 limit 1";
            
            $admin = $this->query_result($query);
            if(!empty($admin))
                return $admin[0]['admin_id'];
            else 
                return 1;
    }
    
    function update_user_login_status($user_id)
    {
            $where = array('user_id'=>$user_id);
            $data = array('user_login_status' => 1);
            $this->update_records('users', $data, $where);
    }
    

}

$userin = new ULogin();
$userin->u_login();