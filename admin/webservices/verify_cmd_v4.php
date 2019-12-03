<?php
//echo json_encode($_POST);die();
require './db.class.php';

class VerifyAccount extends DB {

    function verify_account() {
        if(!empty($_REQUEST['user_id']) && !empty($_REQUEST['verification_code'])){
          $user_id =$_REQUEST['user_id'];
          $verification_code =$_REQUEST['verification_code'];
          
          $gcm_id = '';
          if(!empty($_REQUEST['gcm_id'])){
              $gcm_id = $_REQUEST['gcm_id'];
          }
          $device_name = '';
          if(!empty($_REQUEST['device_name'])){
              $device_name = $_REQUEST['device_name'];
          }
          
          $two_factor_type = 0;
          if(!empty($_REQUEST['two_factor_type'])){
              $two_factor_type = $_REQUEST['two_factor_type'];
          }
          
          $query = "Select * FROM users WHERE user_id='$user_id'";
          $check_user = $this->query_result($query);
          if(!empty($check_user)){
            
              shell_exec('cd /domains/10k.tempurl.co.il/public_html/webservices');
                //echo shell_exec('sudo chmod 777 -R /var/www/html/inst/vendor/mgp-25/instagram-php/sessions');
                if($two_factor_type == 1){
                    $output_including_status = shell_exec('php twoFactorVerification_v4.php '.$user_id.' '.$verification_code.' verify');
                    
                    $op = json_decode($output_including_status);
                    if(empty($op)){
                        $op = array();
                    }
                    if($op->status == "fail"){
                        $message = $op->message;

                        $errdata = array('created_at'=>date('Y-m-d H:i:s'));
                        $errdata['log_data'] = $output_including_status;
                        $errdata['user_id'] =$user_id;
    //                    print_r($errdata);
                        $err_inserted_id = $this->insert_records('error_log', $errdata);
                        $response = array('status' => 'false', 'message' => $message, "ig_response"=>$op, 'user_id'=>$user_id);
                    }else{
                        $where = array('user_id'=>$user_id);
                        $get_user = $this->get_record_where($table='users', $where);
                        $user_details = array();
                        if(!empty($get_user)){
                            $user_details = $get_user[0];
                            unset($user_details['password']);
                        }

                        if(!empty($op)){
    //                        if($user_login_status == 0){
                            $this->send_static_campaigns($user_id);
    //                    }
                            $response = array('status' => 'true', 'message' => 'Verified!', 'user_id'=>$user_id, "ig_response"=>$op,"user_response"=>$user_details);
                        }else{
                            $response = array('status' => 'false', 'message' => 'Something went wrong! Please try to login again.', 'user_id'=>$user_id, "ig_response"=>$op,"user_response"=>$user_details);
                        }

                        
                    }
                }else{
                    $output_including_status = shell_exec('php verifyAccount_v3.php '.$user_id.' '.$verification_code.' verify');
                    
                    $op = json_decode($output_including_status);
                    if(empty($op)){
                        $op = array();
                    }
                    if($op->status == "fail"){
                        $message = $op->message;

                        $errdata = array('created_at'=>date('Y-m-d H:i:s'));
                        $errdata['log_data'] = $output_including_status;
                        $errdata['user_id'] =$user_id;
    //                    print_r($errdata);
                        $err_inserted_id = $this->insert_records('error_log', $errdata);
                        $response = array('status' => 'false', 'message' => $message, "ig_response"=>$op, 'user_id'=>$user_id);
                    }else{
                        $where = array('user_id'=>$user_id);
                        $get_user = $this->get_record_where($table='users', $where);
                        $user_details = array();
                        if(!empty($get_user)){
                            $user_details = $get_user[0];
                            unset($user_details['password']);
                        } 
                        if($op->success == 1){
                            $message = 'Please login after enable two factor in your instagram account.';
                            $flag = 1;
                        }
                        if($op->success == 2){
                            $message = 'Something went wrong please login again!';
                            $flag = 2;
                        }
                        if($op->success == 3){
                            $message = 'Please login after enable two factor in your instagram account.';
                            $flag = 3;
                        }
//                        $response = array('status' => 'true', 'message' => 'Verified!', 'user_id'=>$user_id, "ig_response"=>$op,"user_response"=>$user_details);
                        $response = array('status' => 'false', 'message' => $message, "ig_response"=>$op, 'user_id'=>$user_id, 'flag'=> $flag);
                        /*
                        if($user_details['pk'] !=''){
                            $response = array('status' => 'true', 'message' => 'Verified!', 'user_id'=>$user_id, "ig_response"=>$op,"user_response"=>$user_details);
                        }else{
                            
                                    shell_exec('cd /domains/10k.tempurl.co.il/public_html/webservices');
                                    //echo shell_exec('sudo chmod 777 -R /var/www/html/inst/vendor/mgp-25/instagram-php/sessions');
                                    $output_including_status_login = shell_exec('php userLogin_v4.php '.$user_id.' inserted');//die();
                                    $op_login = json_decode($output_including_status_login);
                                    if(empty($op_login)){
                                        $op_login = array();
                                    }
                                    if($op_login->status == "fail"){
                                        $message_login = $op_login->message;

                                        $where_user = array('user_id'=>$user_id);
                                        $get_user_detail = $this->get_record_where($table='users', $where_user);
//                                        if(empty($op_login->two_factor_required) && $message_login != "challenge_required"){
//                                            if(!empty($get_user_detail)){
//                                                if($get_user_detail[0]['pk'] == ''){
//                                                    $user_details = $get_user_detail[0];
//                                                     unset($user_details['password']);
//                                                    $this->delete_record($table='users', $where);
//                                                }
//                                            }
//                                        }

                                        $errdata = array('created_at'=>date('Y-m-d H:i:s'));
                                        $errdata['log_data'] =$output_including_status;
                                        $errdata['user_id'] =$user_id;
                                        $err_inserted_id = $this->insert_records('error_log', $errdata);
                                        $response = array('status' => 'false', 'message' => $message_login, "ig_response"=>$op_login, 'user_id'=>$user_id);
                                    }else{
                                        $where_user = array('user_id'=>$user_id);
                                        $get_user_detail = $this->get_record_where($table='users', $where_user);
                                        if(!empty($get_user_detail)){
                                            $user_details = $get_user_detail[0];
                                            unset($user_details['password']);
                                            if(empty($user_details['last_name'])){
                                                $user_details['last_name'] = '';
                                            }
                                            $user_login_status = $get_user_detail[0]['user_login_status'];
                                            if($user_login_status == 0){
                                                $this->send_static_campaigns($user_id);
                                            }
                                        }
                                        $response = array('status' => 'true', 'message' => 'Verified!', 'user_id'=>$user_id, "ig_response"=>$op_login,"user_response"=>$user_details,'new_user'=>1);
                                    }
                           // $response = array('status' => 'true', 'message' => 'Verified!', 'user_id'=>$user_id, "ig_response"=>$op,"user_response"=>$user_details);
                        }  */
                        
                    }
                }
                
	    } else {
                    $response = array('status' => 'false', 'message' => 'User not found!');
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

$loggedin = new VerifyAccount();
$loggedin->verify_account();