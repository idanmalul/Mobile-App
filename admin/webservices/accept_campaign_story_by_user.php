<?php
require './db.class.php';

class AcceptCampaignStoryByUser extends DB {
    
    function accept_campaign_story_by_user() {
        
        if(!empty($_REQUEST['story_id']) && !empty($_REQUEST['user_id']) && !empty($_REQUEST['sent_primary_id'])){
            $flag = "";
                    $user_id = $_REQUEST['user_id'];
                    $story_id =$_REQUEST['story_id'];
                    $sent_primary_id =$_REQUEST['sent_primary_id'];
                    $where = array('user_id'=>$user_id);
                    $check_user = $this->get_record_where('users', $where);
                    if(empty($check_user)){
                       $response = array('status' => 'false', 'message' => 'User not found!','session'=>'true', 'flag'=>$flag);
                       $this->json_output($response);
                       exit();
                    }

                    $where_pk = array('user_id'=>$user_id,'pk'=>'');
                    $check_user_pk = $this->get_record_where('users', $where_pk);
                    if(!empty($check_user_pk)){
                       $response = array('status' => 'false', 'message' => 'Your session is expired. Please login again.','session'=>'false', 'flag'=>$flag);
                       $this->json_output($response);
                       exit();
                    }
                    
                    
                    if(!empty($check_user)){
                            
                            shell_exec('cd /domains/10k.tempurl.co.il/public_html/webservices');
                            //echo shell_exec('sudo chmod 777 -R /var/www/html/inst/vendor/mgp-25/instagram-php/sessions');
                            $output_including_status = shell_exec('php userLogin_v4.php '.$user_id.' approve');
                            $op = json_decode($output_including_status);
                            if(empty($op)){
                                $op = array();
                            }
                            if($op->status == "fail"){
                                $flag = "";
                                $message = $op->message;
                                if($message == 'The password you entered is incorrect. Please try again.'){
                                    $flag = "wrong_password";
                                }
//                                $response = array('status' => 'false', 'message' => $message, "ig_response"=>$op, 'user_id'=>$user_id,'session'=>'true', 'flag'=>$flag);

                                $errdata = array('created_at'=>date('Y-m-d H:i:s'));
                                $errdata['log_data'] =$output_including_status;
                                $errdata['user_id'] =$user_id;
                                $err_inserted_id = $this->insert_records('error_log', $errdata);
                                $response = array('status' => 'false', 'message' => $message, "ig_response"=>$op, 'user_id'=>$user_id,'session'=>'true', 'flag'=>$flag);
                                
                                $this->json_output($response);
                                exit();
                            }else{
                                if(!empty($op)){
            //                        if($user_login_status == 0){
                                    //$this->send_static_campaigns($user_id);
            //                    }
                                    $response = array('status' => 'true', 'message' => 'Updated!', 'user_id'=>$user_id, "ig_response"=>$op,"user_response"=>$user_details);
                                }else{
                                    $response = array('status' => 'false', 'message' => 'Something went wrong! Please try to login again.', 'user_id'=>$user_id, "ig_response"=>$op,"user_response"=>$user_details,'session'=>'true', 'flag'=>$flag);
                                    $this->json_output($response);
                                    exit();
                                }

                            }

                        }
                    
                    $query = "SELECT s.*,u.username,u.password,su.campaign_id,su.unique_send_id,su.unique_approval_update_id,c.static_campaign_status FROM story s INNER JOIN story_user su ON su.story_id=s.id INNER JOIN users u ON u.user_id=su.user_id INNER JOIN campaign c ON c.id=su.campaign_id WHERE su.user_id='$user_id' AND su.story_id='$story_id' AND su.id='$sent_primary_id' AND su.upload_status!=1 AND c.campaign_remaining_count!=0 AND c.campaign_remaining_count>0";
                    $get_story = $this->query_result($query);
                    if (!empty($get_story)) {
//                        $where = array('user_id'=>$user_id,'story_id'=>$story_id,'id'=>$sent_primary_id);
                        $static_campaign_status = $get_story[0]['static_campaign_status'];
                        $unique_approval_update_id = $get_story[0]['unique_approval_update_id'];
                        $unique_send_id = $get_story[0]['unique_send_id'];
                        $where = array('user_id'=>$user_id,'unique_send_id'=>$unique_send_id);
                        $data = array('story_accept_status'=>1);
                        $update_table = $this->update_records('story_user', $data, $where);
                        //$response = array('status' => 'true', 'message' => 'Approved successfully.');
                        /*
                        shell_exec('cd /domains/10k.tempurl.co.il/public_html/webservices');
                        //echo shell_exec('sudo chmod 777 -R /var/www/html/inst/vendor/mgp-25/instagram-php/sessions');
                        $output_including_status = shell_exec('php uploadStory.php '.$user_id.' '.$story_id.' '.$sent_primary_id.' story');

                        $op = json_decode($output_including_status);
        //                print_r($op);die();
                        if(empty($op)){
                            $op = array();
                        }

                        if($op->success == 1){
                           $response = array('status' => 'true', 'message' => 'Uploaded! ', 'user_id'=>$user_id, 'story_id'=>$story_id, "ig_response"=>$op);
                        }else{
                            $response = array('status' => 'false', 'message' => 'Uploading Failed');
                        }
                        */
//                        $op = array();
//                        $response = array('status' => 'true', 'message' => 'Approved Successfully! ', 'user_id'=>$user_id, 'story_id'=>$story_id, "ig_response"=>$op);
                        if($static_campaign_status == 1){
                            shell_exec('cd /domains/10k.tempurl.co.il/public_html/webservices');
                            //echo shell_exec('sudo chmod 777 -R /var/www/html/inst/vendor/mgp-25/instagram-php/sessions');
                            $output_including_status = shell_exec('php uploadStaticStory.php '.$user_id.' '.$story_id.' '.$sent_primary_id.' story');

                            $op = json_decode($output_including_status);
            //                print_r($op);die();
                            if(empty($op)){
                                $op = array();
                            }

                            if($op->success == 1){
                                $response = array('status' => 'true', 'message' => 'Story uploaded successfully! ', 'user_id'=>$user_id, 'story_id'=>$story_id,"ig_response"=>$op,'session'=>'true');
                            
                            }else {
                                $response = array('status' => 'false', 'message' => 'Failed', 'user_id'=>$user_id, 'story_id'=>$story_id,"ig_response"=>$op,'session'=>'true', 'flag'=>$flag);
                            }
                        }else{
                            $op = array();
                            $response = array('status' => 'true', 'message' => 'Story uploaded successfully! ', 'user_id'=>$user_id, 'story_id'=>$story_id, "ig_response"=>$op,'session'=>'true');
                        }
                        
                    }else{
                        $response = array('status' => 'false', 'message' => 'Event not found!','session'=>'true', 'flag'=>$flag);
                    }
                    
                    
                    
        } else {
            $response = array('status' => 'false', 'message' => 'Invalid request parameter','session'=>'true', 'flag'=>$flag);
        }
        $this->json_output($response);
    }
}

$accept = new AcceptCampaignStoryByUser();
$accept->accept_campaign_story_by_user();
