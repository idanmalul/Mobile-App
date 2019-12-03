<?php
ob_start();
require './db.class.php';
set_time_limit(0);
date_default_timezone_set('UTC');

require __DIR__.'/./vendor/autoload.php';

/////// CONFIG ///////
//$username = 'Getin_events';
//$password = 'Niv224422';
//$username = 'nivariel';
//$password = 'niv224422';
//print_r($argv);//die();
//echo ''.$argv[1];die();
error_reporting(E_ALL);

ini_set('display_errors', 1);
$d = new \DB();
$where = array('user_id'=>$argv[1]); // ,'mobile_no'=>$phone_no
$check_user = $d->get_record_where($table='users', $where);

if(!empty($check_user)){
    $username = $check_user[0]['username'];
    $password = base64_decode($check_user[0]['password']);
}else{
    $username = '';
    $password = '';
}
      //    print_r($check_user);die();
//$username = 'mohan.das.99990';
//$password = '123qweasdzxc';
$debug = true;
$truncatedDebug = false;
//////////////////////

$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);

try {
    $ig->login($username, $password);
    $ig->logout();
    $ig->login($username, $password);
   // echo '<pre>';
   //print_r($ig->getErrorType());
    ob_end_clean();
    ob_start();
} catch (\Exception $e) {
  //  $e->getResponse();
    //print_r($ig->getErrorType());die();
//    print_r($e->getResponse());die();
    // some statement that removes all printed/echoed items
    
ob_end_clean();
//echo 'hh';
echo $e->getResponse();
exit(0);
    echo 'Something went wrong: '.$e->getMessage()."\n";
    exit(0);
}

try {
    $user = new \InstagramAPI\Request\Account($ig);
    $tttt=$ig->account->getCurrentUser($user);
//    echo '<br>';
   // echo '<pre>';
    
    $user_primary = GuzzleHttp\json_decode($tttt)->user;
   // print_r(GuzzleHttp\json_decode($tttt)->user);
    
    $p = new \InstagramAPI\Request\People($ig);
    $in=$ig->people->getInfoById(GuzzleHttp\json_decode($tttt)->user->pk);
//echo '<br>';
//echo GuzzleHttp\json_decode($xxx)->user;
//echo \GuzzleHttp\json_encode(GuzzleHttp\json_decode($xxx)->user);exit();
//echo '<br>';
    
    $users = GuzzleHttp\json_decode($in)->user;
    ob_end_clean();
   // print_r($users);
    if(!empty($check_user)){
      //  print_r($check_user);die();
        $user_id = $check_user[0]['user_id'];
        $where = array('user_id'=>$user_id);
        $data = array('pk'=>$users->pk,
                        'full_name'=>$users->full_name,
            'profile_pic_url'=>$users->profile_pic_url,
            'media_count'=>$users->media_count,
            'follower_count'=>$users->follower_count,
            'following_count'=>$users->following_count,
            'following_tag_count'=>$users->following_tag_count,
            'birthday'=>$user_primary->birthday,
            'phone_number'=>$user_primary->phone_number,
            'gender'=>$user_primary->gender,
            'email'=>$user_primary->email,
            'username'=>$user_primary->username,
                        'updated_at'=>date('Y-m-d H:i:s'),
            'is_profile_updated'=>1
            );
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
    echo 'Something went wrong: '.$e->getMessage()."\n";
}
