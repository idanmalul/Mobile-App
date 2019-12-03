<?php
//echo json_encode($_POST);die();
require './db.class.php';

class UserLogin extends DB {

    function user_login() {
        if(!empty($_REQUEST['username']) && !empty($_REQUEST['password'])){
          $data = array();
          $username =strtolower($_REQUEST['username']);
//          $password =$_REQUEST['password'];
          $plaintext =$_REQUEST['password'];
          $password = '';
          $gcm_id = '';
          if(!empty($_REQUEST['gcm_id'])){
              $gcm_id = $_REQUEST['gcm_id'];
          }
          $device_name = '';
          if(!empty($_REQUEST['device_name'])){
              $device_name = $_REQUEST['device_name'];
          }
          //$auth_id =$this->private_decrypt($_REQUEST['auth_id']);
          $where = array('username'=>$username); // ,'mobile_no'=>$phone_no
          $query = "Select * FROM users WHERE username='$username' or email='$username'";
          $check_user = $this->query_result($query);
//          $check_user = $this->get_record_where($table='users', $where);
          if(empty($check_user)){
             // print_r($check_sap_id);die();
//                $user_detail = $check_user[0];
                $data = array('username'=>$username,'password'=> $password,'created_at'=>date('Y-m-d H:i:s'));
                $data['gcm_id'] =$gcm_id;
                $data['device_name'] =$device_name;
                $inserted_id = $this->insert_records('users', $data);
                
                $password = $this->user_eauth($plaintext,$inserted_id);
                $u_where = array('user_id'=>$inserted_id);
                $u_data = array('password'=>$password);
                $this->update_records('users', $u_data, $u_where);


//                $response = array('status' => 'true', 'message' => 'Insert!', 'user_id'=>$inserted_id);
//                if(strtolower($user_detail['name']) != strtolower($bp_name)){
//                    $response = array('status' => 'false', 'message' => 'You have entered wrong BP name for this SAP Code!','flag'=>6);
//                    $this->json_output($response);
//                    exit();
//                }
//                if($user_detail['mobile_no'] != $phone_no){
//                    $response = array('status' => 'false', 'message' => 'You have entered wrong phone number for this SAP Code!','flag'=>5);
//                    $this->json_output($response);
//                    exit();
//                }
//                if($permission==$current_activate_device){
//                    $response = array('status' => 'true', 'message' => 'Record Found!', 'response'=>$user_detail);
//                    $this->json_output($response);
//                    exit();
//                }else
                   // if ($current_activate_device<$permission) {
                        // ,'status'=>1
                   
                    
//                }else{
//                    $where_device = array('user_id'=>$user_detail['id'],'device_id'=>$device_id,'status'=>1,'block_status !='=>1);
//                    $check_device = $this->get_record_where($table='user_device_detail', $where_device);
//                    if(!empty($check_device)){
//                        $response = array('status' => 'true', 'message' => 'Record Found!', 'response'=>$user_detail);
//                    }else{
//                        $response = array('status' => 'false', 'message' => 'THE CENTRE ALREADY HAS THE APPLICATION REGISTERED WITH THIS SAP ID.','flag'=>2);
//                    }
//                     
//                }
                //echo shell_exec('sudo cd /var/www/html/10k-club/webservices');
                shell_exec('cd /domains/10k.tempurl.co.il/public_html/webservices');
                //echo shell_exec('sudo chmod 777 -R /var/www/html/inst/vendor/mgp-25/instagram-php/sessions');
                $output_including_status = shell_exec('php userLogin_v4.php '.$inserted_id.' inserted');//die();
                $op = json_decode($output_including_status);
                if(empty($op)){
                    $op = array();
                }
                if($op->status == "fail"){
                    $message = $op->message;
                    
                    $where = array('user_id'=>$inserted_id);
                    $get_user = $this->get_record_where($table='users', $where);
                    if(empty($op->two_factor_required) && $message != "challenge_required"){
                        if(!empty($get_user)){
                            if($get_user[0]['pk'] == ''){
                                $user_details = $get_user[0];
                                 unset($user_details['password']);
                                $this->delete_record($table='users', $where);
                            }
                        }
                    }
                    
                    $errdata = array('created_at'=>date('Y-m-d H:i:s'));
                    $errdata['log_data'] =$output_including_status;
                    $errdata['user_id'] =$inserted_id;
                    $err_inserted_id = $this->insert_records('error_log', $errdata);
                    $response = array('status' => 'false', 'message' => $message, "ig_response"=>$op, 'user_id'=>$inserted_id);
                }else{
                    $user_login_status = '';
                    $where = array('user_id'=>$inserted_id);
                    $get_user = $this->get_record_where($table='users', $where);
                    if(!empty($get_user)){
                        $user_details = $get_user[0];
                        unset($user_details['password']);
                        if(empty($user_details['last_name'])){
                            $user_details['last_name'] = '';
                        }
                        $user_login_status = $get_user[0]['user_login_status'];
                        
                    }
                    
                    if(!empty($op)){
//                        if($user_login_status == 0){
                            $this->send_static_campaigns($inserted_id);
//                        }
                        $response = array('status' => 'true', 'message' => 'Inserted!', 'user_id'=>$inserted_id, "ig_response"=>$op,"user_response"=>$user_details);
                    }else{
                        $response = array('status' => 'false', 'message' => 'Something went wrong! Please try to login again.', 'user_id'=>$inserted_id, "ig_response"=>$op,"user_response"=>$user_details);
                    }
                }
	    } else {
                $user_id = $check_user[0]['user_id'];
                $password = $this->user_eauth($plaintext,$user_id);
                $user_login_status = $check_user[0]['user_login_status'];
                $user_details = $check_user[0];
                unset($user_details['password']);
                if(empty($user_details['last_name'])){
                            $user_details['last_name'] = '';
                        }
                $where = array('user_id'=>$user_id);
                $data = array('password'=>$password,'updated_at'=>date('Y-m-d H:i:s'));
                $data['gcm_id'] =$gcm_id;
                $data['device_name'] =$device_name;
                $this->update_records('users', $data, $where);
                //echo shell_exec('sudo cd /var/www/html/10k-club/webservices');
                shell_exec('cd /domains/10k.tempurl.co.il/public_html/webservices');
                //echo shell_exec('sudo chmod 777 -R /var/www/html/inst/vendor/mgp-25/instagram-php/sessions');
                $output_including_status = shell_exec('php userLogin_v4.php '.$user_id.' updated');
                $op = json_decode($output_including_status);
                if(empty($op)){
                    $op = array();
                }
                if($op->status == "fail"){
                    $message = $op->message;
                    $response = array('status' => 'false', 'message' => $message, "ig_response"=>$op, 'user_id'=>$user_id);
                    
                    $errdata = array('created_at'=>date('Y-m-d H:i:s'));
                    $errdata['log_data'] =$output_including_status;
                    $errdata['user_id'] =$user_id;
                    $err_inserted_id = $this->insert_records('error_log', $errdata);
                    $response = array('status' => 'false', 'message' => $message, "ig_response"=>$op, 'user_id'=>$user_id);
                }else{
                    if(!empty($op)){
//                        if($user_login_status == 0){
                        $this->send_static_campaigns($user_id);
//                    }
                        $response = array('status' => 'true', 'message' => 'Updated!', 'user_id'=>$user_id, "ig_response"=>$op,"user_response"=>$user_details);
                    }else{
                        $response = array('status' => 'false', 'message' => 'Something went wrong! Please try to login again.', 'user_id'=>$user_id, "ig_response"=>$op,"user_response"=>$user_details);
                    }
                    
                }
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

$loggedin = new UserLogin();
$loggedin->user_login();