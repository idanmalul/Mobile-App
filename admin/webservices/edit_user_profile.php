<?php
require './db.class.php';

class EditUserProfile extends DB {
    
    function edit_user_profile() {

        if(!empty($_REQUEST['user_id'])){
                    $user_id = $_REQUEST['user_id'];
                    $where = array('user_id'=>$user_id);
                    $check_user = $this->get_record_where('users', $where);
                    if(empty($check_user)){
                       $response = array('status' => 'false', 'message' => 'User not found!');
                       $this->json_output($response);
                       exit();
                    }
                    
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
                    
                    $data = array('first_name' => $first_name, 'last_name' => $last_name, 'gender'=>$gender, 'age'=>$age, 'favourites'=>$favourites);
                    
                    $update_table = $this->update_records('users', $data, $where);
                    $get_user_detail = $this->get_record_where('users', $where);
                    if($get_user_detail){
                       $user_details = $get_user_detail[0];
                       unset($user_details['user_password']);
                      
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
