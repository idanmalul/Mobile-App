<?php
require './db.class.php';

class EditUserProfile extends DB {
    
    function edit_user_profile() {
//        $response = array('status' => 'true', 'message' => $_REQUEST);
//        $this->json_output($response);        exit();
        if(!empty($_REQUEST['user_id'])){
                    $member_data = array();
                    $user_id = $_REQUEST['user_id'];
                    $where = array('user_id'=>$user_id);
                    $check_user = $this->get_record_where('users', $where);
                    if(empty($check_user)){
                       $response = array('status' => 'false', 'message' => 'User not found!');
                       $this->json_output($response);
                       exit();
                    }
                    
//                    if(!empty($check_user)){
//                       $response = array('status' => 'false', 'message' => 'User already exists!');
//                       $this->json_output($response);
//                       exit();
//                    }
                    
                    if(!empty($_REQUEST['email'])){
                                $email = $_REQUEST['email'];
                            }else{
                                $email = '';
                            }
                    if(!empty($_REQUEST['create_pass_type'])){
                        $create_pass_type = $_REQUEST['create_pass_type'];
                        if($create_pass_type == 1){
                            $password = $this->user_dauth($check_user[0]['password'],$user_id);
                            $member_data['u_id'] = $user_id;
                            $member_data['username'] = $check_user[0]['username'];
                            $member_data['password'] = md5($password);
                            $member_data['name'] = $check_user[0]['full_name'];
                            $member_data['email'] = $email;
                            $member_data['created_at'] = date('Y-m-d H:i:s');
                            
                        }else{
                            $member_data['u_id'] = $user_id;
                            if(!empty($_REQUEST['username'])){
                                $menber_username = $_REQUEST['username'];
                            }else{
                                $menber_username = '';
                                
                                $response = array('status' => 'false', 'message' => 'Please enter username!');
                                $this->json_output($response);
                                exit();
                                 
                            }
                            if(!empty($_REQUEST['password'])){
                                $menber_password = $_REQUEST['password'];
                            }else{
                                $menber_password = '';
                                $response = array('status' => 'false', 'message' => 'Please enter password!');
                                $this->json_output($response);
                                exit();
                            }
                            
                            
                            $member_data['username'] = $menber_username;
                            $member_data['password'] = md5($menber_password);
                            $member_data['name'] = $check_user[0]['full_name'];
                            $member_data['email'] = $email;
                            $member_data['created_at'] = date('Y-m-d H:i:s');
                        }
                        $where_member = array('u_id'=>$user_id);
                        $check_member = $this->get_record_where('members', $where_member);
                        if(empty($check_member)){
                           $inserted_id = $this->insert_records('members', $member_data);
                        }else{
//                           $this->update_records('members', $member_data, $where_member);
                                if(!empty($check_member)){
                                    $response = array('status' => 'false', 'message' => 'User already exists!');
                                    $this->json_output($response);
                                    exit();
                                }
                        }
                    }else{
//                        $first_name=  $check_user[0]['first_name'];
                    }
                    
//                    $profileimg = '';
//                    if(!empty($_REQUEST['user_profile_img'])){
//                        $user_profile_img = $_REQUEST['user_profile_img'];
//                        $profileimg = time(). ".png";
//                        $pro_path = "../uploads/user_profile_images/".$profileimg;
//                        file_put_contents($pro_path,base64_decode($user_profile_img));
//                    }else{
//                        $profileimg = $check_user[0]['user_profile_img'];
//                    }
            //print_r($check_user);die();
                    
                    if(!empty($_REQUEST['first_name'])){
                        $first_name=  $_REQUEST['first_name'];
                    }else{
                        $first_name=  $check_user[0]['first_name'];
                    }
                    if(!empty($_REQUEST['last_name'])){
                        $last_name=  $_REQUEST['last_name'];
                    }else{
                        $last_name=  $check_user[0]['last_name'];
                    }
                    if(!empty($_REQUEST['gender'])){
                        $gender=  $_REQUEST['gender'];
                    }else{
                        $gender=  $check_user[0]['gender'];
                    }
//                    if(!empty($_REQUEST['age'])){
//                        $age=  $_REQUEST['age'];
//                    }else{
//                        $age=  $check_user[0]['age'];
//                    }
//                    if(!empty($_REQUEST['favourites'])){
//                        $favourites= $_REQUEST['favourites'];
//                    }else{
//                        $favourites= $check_user[0]['favourites'];
//                    }
//                    
//                    $data = array('first_name' => $first_name, 'last_name' => $last_name, 'gender'=>$gender, 'age'=>$age, 'favourites'=>$favourites);
//                    
                    
//                    if(!empty($_REQUEST['gender'])){
//                        if(strtolower($_REQUEST['gender']) == 'male'){
//                            $gender=  1;
//                        } else if(strtolower($_REQUEST['gender']) == 'female'){
//                            $gender=  2;
//                        } else {
//                            $gender=  3;
//                        }
//                        
//                    }else{
//                        $gender=  $check_user[0]['gender'];
//                    }
                    if(!empty($_REQUEST['age'])){
                        $age=  $_REQUEST['age'];
                    }else{
                        $age=  $check_user[0]['age'];
                    }
                    if(!empty($_REQUEST['favourites'])){
                        $favourites= $_REQUEST['favourites'];
                    }else{
                        $favourites= $check_user[0]['favourites'];
                    }
                    
                    if(!empty($_REQUEST['birthday'])){
                        $var = $_REQUEST['birthday'];
                        $date = str_replace('/', '-', $var);
                        $birthday= date('Y-m-d', strtotime($date));
                    }else{
                        $birthday=  $check_user[0]['birthday'];
                    }
                    if(!empty($_REQUEST['country_code'])){
                        $country_code=  $_REQUEST['country_code'];
                    }else{
                        $country_code=  $check_user[0]['country_code'];
                    }
                    if(!empty($_REQUEST['phone_number'])){
                        $contact_no=  $_REQUEST['phone_number'];
                    }else{
                        $contact_no=  $check_user[0]['contact_no'];
                    }
                    if(!empty($_REQUEST['address'])){
                        $address=  $_REQUEST['address'];
                    }else{
                        $address=  $check_user[0]['address'];
                    }
                    
                    if(!empty($_REQUEST['postal_code'])){
                        $postal_code=  $_REQUEST['postal_code'];
                    }else{
                        $postal_code=  $check_user[0]['postal_code'];
                    }
                    
                    if(!empty($_REQUEST['city'])){
                        $city=  $_REQUEST['city'];
                    }else{
                        $city=  $check_user[0]['city'];
                    }
                    
                    if(!empty($_REQUEST['provision'])){
                        $state=  $_REQUEST['provision'];
                    }else{
                        $state=  $check_user[0]['state'];
                    }
                    
                    if(!empty($_REQUEST['country'])){
                        $country=  $_REQUEST['country'];
                    }else{
                        $country=  $check_user[0]['country'];
                    }
                    
                    if(!empty($_REQUEST['user_notification_status'])){
                        $user_notification_status=  $_REQUEST['user_notification_status'];
                    }else{
                        $user_notification_status=  $check_user[0]['user_notification_status'];
                    }
                    
                    $data = array('first_name' => $first_name, 'last_name' => $last_name, 'age'=>$age, 'favourites'=>$favourites, 'birthday'=>$birthday, 'country_code'=>$country_code, 'contact_no'=>$contact_no, 'address'=>$address, 'postal_code'=>$postal_code, 'city'=>$city, 'state'=>$state, 'country'=>$country, 'is_profile_updated'=>2);
                    
                    if($gender != 0){
                        $data['gender'] = $gender;
                    }
                    
                    if(isset($user_notification_status)){
                        $data['user_notification_status'] = $user_notification_status;
                    }
                    //$data=array("u_password"=>$u_password, "user_full_name"=>$user_full_name);
                    $update_table = $this->update_records('users', $data, $where);
                    $get_user_detail = $this->get_record_where('users', $where);
                    if($get_user_detail){
                       $user_details = $get_user_detail[0];
                       unset($user_details['user_password']);
                      // $user_details['user_profile_img']= USER_PROFILE_IMAGES.$user_details['user_profile_img'];
                    }
                    $response = array('status' => 'true', 'message' => 'Profile Successfully Updated.', 'user_details' => $user_details);
        } else {
            $response = array('status' => 'false', 'message' => 'Invalid request parameter');
        }
        $this->json_output($response);
    }
}

$edit = new EditUserProfile();
$edit->edit_user_profile();
