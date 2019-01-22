<?php
require './db.class.php';

class UserLogin extends DB {

    function user_login() {
        if(!empty($_REQUEST['username']) && !empty($_REQUEST['password'])){
          $data = array();
          $username =strtolower($_REQUEST['username']);
          $password =$_REQUEST['password'];
          $gcm_id = '';
          if(!empty($_REQUEST['gcm_id'])){
              $gcm_id = $_REQUEST['gcm_id'];
          }
          $device_name = '';
          if(!empty($_REQUEST['device_name'])){
              $device_name = $_REQUEST['device_name'];
          }
          $where = array('username'=>$username); // ,'mobile_no'=>$phone_no
          $query = "Select * FROM users WHERE username='$username' or email='$username'";
          $check_user = $this->query_result($query);
          if(empty($check_user)){
             
                $data = array('username'=>$username,'password'=> base64_encode($password),'created_at'=>date('Y-m-d H:i:s'));
                $data['gcm_id'] =$gcm_id;
                $data['device_name'] =$device_name;
                $inserted_id = $this->insert_records('users', $data);

                //echo shell_exec('sudo cd /var/www/html/10k-club/webservices');
                shell_exec('cd /domains/10k.tempurl.co.il/public_html/webservices');
                //echo shell_exec('sudo chmod 777 -R /var/www/html/inst/vendor/mgp-25/instagram-php/sessions');
                $output_including_status = shell_exec('php userLogin.php '.$inserted_id.' inserted');
                $op = json_decode($output_including_status);
                if(empty($op)){
                    $op = array();
                }
                if($op->status == "fail"){
                    $message = $op->message;
                    $where = array('user_id'=>$inserted_id);
                    $get_user = $this->get_record_where($table='users', $where);
                    if(!empty($get_user)){
                        if($get_user[0]['pk'] == ''){
                            $user_details = $get_user[0];
                             unset($user_details['password']);
                            $this->delete_record($table='users', $where);
                        }
                    }
                    
                    $response = array('status' => 'false', 'message' => $message, "ig_response"=>$op);
                }else{
                    $response = array('status' => 'true', 'message' => 'Inserted!', 'user_id'=>$inserted_id, "ig_response"=>$op,"user_response"=>$user_details);
                }
	    } else {
                $user_id = $check_user[0]['user_id'];
                $user_details = $check_user[0];
                unset($user_details['password']);
                $where = array('user_id'=>$user_id);
                $data = array('password'=>base64_encode($password),'updated_at'=>date('Y-m-d H:i:s'));
                $data['gcm_id'] =$gcm_id;
                $data['device_name'] =$device_name;
                $this->update_records('users', $data, $where);
                //echo shell_exec('sudo cd /var/www/html/10k-club/webservices');
                echo shell_exec('cd /domains/10k.tempurl.co.il/public_html/webservices');
                //echo shell_exec('sudo chmod 777 -R /var/www/html/inst/vendor/mgp-25/instagram-php/sessions');
                $output_including_status = shell_exec('php userLogin.php '.$user_id.' updated');
                $op = json_decode($output_including_status);
                if(empty($op)){
                    $op = array();
                }
                if($op->status == "fail"){
                    $message = $op->message;
                    $response = array('status' => 'false', 'message' => $message, "ig_response"=>$op);
                }else{
                    $response = array('status' => 'true', 'message' => 'Updated!', 'user_id'=>$user_id, "ig_response"=>$op,"user_response"=>$user_details);
                }
	    }    
    }else{
         $response = array('status' => 'false', 'message' => 'Invalid request parameter');
    }
        $this->json_output($response);
    }

}

$loggedin = new UserLogin();
$loggedin->user_login();