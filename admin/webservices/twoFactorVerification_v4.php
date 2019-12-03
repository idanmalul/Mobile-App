<?php
ob_start();
require './db.class.php';
set_time_limit(0);
date_default_timezone_set('UTC');

require __DIR__.'/./vendor/autoload.php';

/////// CONFIG ///////

$d = new \DB();
$user_id = $argv[1];
$verificationCode = $argv[2];
$where = array('user_id'=>$user_id); // ,'mobile_no'=>$phone_no
$check_user = $d->get_record_where($table='users', $where);
if(!empty($check_user)){
    $username = $check_user[0]['username'];
//    $password = base64_decode($check_user[0]['password']);
    $password = $d->user_dauth($check_user[0]['password'],$user_id);
    $twoFactorIdentifier = $check_user[0]['two_factor_identifier'];
}else{
    $username = '';
    $password = '';
    $twoFactorIdentifier = '';
    
}
 

//$username = 'satendra_9039';
//$password = 'S@$123';
$debug = true;
$truncatedDebug = false;
$verification_method = 0; //0 = SMS, 1 = Email

//Leave these
$user_id      = '';
$challenge_id = '';
//////////////////////

$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
 
try {
    $loginResponse=$ig->_setUser($username, $password);

    
    //if ($loginResponse !== null && $loginResponse->isTwoFactorRequired()) {
//        $twoFactorIdentifier = 'fvLD9WySKh';

        // The "STDIN" lets you paste the code via terminal for testing.
        // You should replace this line with the logic you want.
        // The verification code will be sent by Instagram via SMS.
//        $verificationCode = 326719;
//        echo "Verification Code => ".$verificationCode.'\n';
//        echo $twoFactorIdentifier;//die();
    if(!empty($twoFactorIdentifier) && !empty($verificationCode)){
        $ig->finishTwoFactorLogin($username, $password, $twoFactorIdentifier, $verificationCode);
    }
    //}
ob_end_clean();
ob_start();
} catch (\Exception $e) {
    ob_end_clean();
echo $e->getResponse();
exit(0);
}

try {
    $user = new \InstagramAPI\Request\Account($ig);
    $tttt=$ig->account->getCurrentUser($user);
//    echo '<br>';
    
    
    $user_primary = GuzzleHttp\json_decode($tttt)->user;
   // print_r(GuzzleHttp\json_decode($tttt)->user);
    
    $p = new \InstagramAPI\Request\People($ig);
    $in=$ig->people->getInfoById(GuzzleHttp\json_decode($tttt)->user->pk);
//echo '<br>';
//echo GuzzleHttp\json_decode($xxx)->user;
//echo \GuzzleHttp\json_encode(GuzzleHttp\json_decode($xxx)->user);exit();
//echo '<br>';
    
    $users = GuzzleHttp\json_decode($in)->user;
//    echo '<pre>';
    ob_end_clean();
//    print_r($users);die();
    if(!empty($check_user)){
        
        $user_id = $check_user[0]['user_id'];
        $where = array('user_id'=>$user_id);
        $data = array('pk'=>$users->pk,
            'username'=>$users->username,
                        'full_name'=> ucfirst($users->full_name),
            'profile_pic_url'=>$users->profile_pic_url,
            'media_count'=>$users->media_count,
            'follower_count'=>$users->follower_count,
            'following_count'=>$users->following_count,
            'following_tag_count'=>$users->following_tag_count,
            'birthday'=>date('Y-m-d', strtotime($user_primary->birthday)),
            'phone_number'=>$user_primary->phone_number,
            'gender'=>$user_primary->gender,
            'email'=>$user_primary->email,
                        'updated_at'=>date('Y-m-d H:i:s'),
            'is_profile_updated'=>1
            );
        $data['two_factor_send_status'] = 2;
        $d->update_records('users', $data, $where);
        
        if($ig->isMaybeLoggedIn == 1 && !empty($ig->session_id)){
        $response = array("status"=>"","success"=>1);
        }else{
        $response = array("status"=>"","success"=>2);
        }

        echo json_encode($response);exit(0);
    }
    
    //echo 'fghhhhhhhhhhhf';
//    $t =array('status'=>'true');
//    echo json_encode($t);    exit();
} catch (\Exception $e) {
//    $t =array('status'=>'false2');
//    echo json_encode($t);exit();
   // echo 'Something went wrong: '.$e->getMessage()."\n";
}

function delete_directory($dirname) {
         if (is_dir($dirname))
           $dir_handle = opendir($dirname);
     if (!$dir_handle)
          return false;
     while($file = readdir($dir_handle)) {
           if ($file != "." && $file != "..") {
                if (!is_dir($dirname."/".$file))
                     unlink($dirname."/".$file);
                else
                     delete_directory($dirname.'/'.$file);
           }
     }
     closedir($dir_handle);
     rmdir($dirname);
     return true;
}