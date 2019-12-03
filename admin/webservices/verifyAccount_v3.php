<?php
ob_start();
error_reporting(E_ALL);

ini_set('display_errors', 1);
require './db.class.php';
set_time_limit(0);
date_default_timezone_set('UTC');

use InstagramAPI\Exception\ChallengeRequiredException;
use InstagramAPI\Instagram;
use InstagramAPI\Response\LoginResponse;

require __DIR__.'/./vendor/autoload.php';

$d = new \DB();
$user_id = $argv[1];
$code = $argv[2];
//print_r($argv);
$where = array('user_id'=>$user_id); // ,'mobile_no'=>$phone_no
$check_user = $d->get_record_where($table='users', $where);
//print_r($check_user);
if(!empty($check_user)){
    $username = $check_user[0]['username'];
//    $password = base64_decode($check_user[0]['password']);
    $password = $d->user_dauth($check_user[0]['password'],$user_id);
    $challenge_required_response = json_decode($check_user[0]['challenge_required_response']);
//    print_r($challenge_required_response);
    if(!empty($challenge_required_response)){
        $account_id = $challenge_required_response->user_id;
        $nonce_code = $challenge_required_response->nonce_code;
    }else{
        $account_id = '';
        $nonce_code = '';
    }
}else{
    $username = '';
    $password = '';
    $account_id = '';
    $nonce_code = '';
    $response = array();
}

$debug = true;
$truncatedDebug = false;
//$code = '607894';
$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);


try {
    $dirname = dirname(__DIR__).'/webservices/vendor/mgp25/instagram-php/sessions/'.$username;
    
    //resolveChallenge($username, $password, $ig, $account_id, $nonce_code, $code);die();
    
//   echo 'fghgfhfgh';
    $ig->_setUser($username, $password);
//   $ig->_setUser('satendra_9039', 'S@$123');
//    $ig->changeUser($username, $password);
//    $ig->login($username, $password);
//    echo 'heloo';
//    print_r($ig);die();
//    echo 'hhhh => '.$new_account_id = $ig->account_id;
//    die();
//    echo "challenge/".$account_id."/".$nonce_code."/";die();
    $customResponse = $ig->request("challenge/".$account_id."/".$nonce_code."/")->setNeedsAuth(false)->addPost("security_code", $code)->getDecodedResponse();
 echo '<pre>';
        print_r($customResponse);   //die();
    if (!is_array($customResponse)) {
        //Other stuff.
    }
    if(!empty($customResponse)){
        if(!empty($customResponse['logged_in_user'])){
    if ($customResponse['status'] == "ok" && (int)$customResponse['logged_in_user']['pk'] === (int)$account_id) {
        //Other stuff.
        $where = array('user_id'=>$user_id);
        $data = array('otp_status'=>2,
                        'account_activated'=>1,
                        'otp_timestamp'=>strtotime(date('Y-m-d H:i:s')),
            );
        $d->update_records('users', $data, $where);
        $response = array("status"=>"","success"=>1, "verify_response"=> $customResponse);
//        delete_directory($dirname);
//        echo json_encode($response);
        
    }else{
        $response = array("status"=>"","success"=>2, "verify_response"=> $customResponse);
    }
        }else{
            $response = array("status"=>"","success"=>3, "verify_response"=> $customResponse);
        }
    }
    ob_end_clean();
    if(!empty($response)){
            echo json_encode($response);exit(0);
        }else{
            echo json_encode(array());exit(0);
        }
//    ob_end_clean();
//    ob_start();
} catch (Exception $Exception) {
    //Other stuff.
    echo 'Something went wrong: '.$Exception->getMessage()."\n";
//    ob_end_clean();
}

try {
    echo 'stop => after verify';die();
//    $ig->login($username, $password);
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
//    ob_end_clean();
   // print_r($users);
    if(!empty($check_user)){
      //  print_r($check_user);die();
        $user_id = $check_user[0]['user_id'];
        $is_profile_updated = $check_user[0]['is_profile_updated'];
        $where = array('user_id'=>$user_id);
        $data = array('pk'=>$users->pk,
                        'full_name'=>$users->full_name,
            'profile_pic_url'=>$users->profile_pic_url,
            'media_count'=>$users->media_count,
            'follower_count'=>$users->follower_count,
            'following_count'=>$users->following_count,
            'following_tag_count'=>$users->following_tag_count,
//            'birthday'=>$user_primary->birthday,
            'phone_number'=>$user_primary->phone_number,
//            'gender'=>$user_primary->gender,
            'email'=>$user_primary->email,
            'username'=>$user_primary->username,
                        'updated_at'=>date('Y-m-d H:i:s'),
//            'is_profile_updated'=>1
            );
        if($is_profile_updated ==0){
            $data['is_profile_updated']=1;
        }
        $d->update_records('users', $data, $where);
//        if($ig->isMaybeLoggedIn == 1 && !empty($ig->session_id)){
//        $response = array("status"=>"","success"=>1);
//        }else{
//        $response = array("status"=>"","success"=>2);
//        }
        if(!empty($response)){
            echo json_encode($response);exit(0);
        }else{
            echo json_encode(array());exit(0);
        }
        
    }
    
    //echo 'fghhhhhhhhhhhf';
//    $t =array('status'=>'true');
//    echo json_encode($t);    exit();
} catch (\Exception $e) {
//    $t =array('status'=>'false2');
//    echo json_encode($t);exit();
    echo 'Something went wrong: '.$e->getMessage()."\n";
}
 
/*
try {
    $ig->_setUser('satendra_9039', 'S@$123');
    $accountObject->setInstagramId($ig->user_id);
    $ig->changeUser($accountObject->getUsername(), $accountObject->getRawPassword());
    $customResponse = $ig->request("challenge/".$accountObject->getInstagramId()."/".$accountObject->getChallengeIdentifier()."/")->setNeedsAuth(false)->addPost("security_code", $code)->getDecodedResponse();
    echo '<pre>';
        print_r($customResponse);   die();
    if (!is_array($customResponse)) {
        //Other stuff.
    }

    if ($customResponse['status'] == "ok" && (int)$customResponse['logged_in_user']['pk'] === (int)$accountObject->getInstagramId()) {
        //Other stuff.
    }
} catch (Exception $Exception) {
    //Other stuff.
}
 
 */

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

function resolveChallenge($username, $password, $ig, $account_id, $nonce_code, $code){
    
//    $ig->client = $ig->getInstagramClient();
    $ig->_setUser($username, $password);
    $url = "challenge/".$account_id."/".$nonce_code."/";
    $customResponse = $ig->request($url)->setNeedsAuth(false)->addPost("security_code", $code)->getDecodedResponse();

    if (!is_array($customResponse)) {
        throw new Exception('Nothing new');
    }

    $ig->isMakeLogin = true;
    echo '<pre>';
    print_r($customResponse);
    // Success response of challenge verification
    if ($customResponse['status'] == "ok" && isset($customResponse['logged_in_user']) && (int)$customResponse['logged_in_user']['pk'] === (int)explode('/', $url)[1]) {
        return $customResponse; //$ig->login($username, $password);
    }

    // mgp25
    if ($customResponse['status'] === 'ok' && $customResponse['action'] === 'close') {
        return $customResponse; //$ig->login($username, $password);
    }
    
    throw new Exception(isset($customResponse['message']) ? $customResponse['message'] : json_encode($customResponse));die();
}