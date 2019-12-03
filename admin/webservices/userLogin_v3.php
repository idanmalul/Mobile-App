<?php
ob_start();
require './db.class.php';
set_time_limit(0);
date_default_timezone_set('UTC');

require __DIR__.'/./vendor/autoload.php';
error_reporting(E_ALL);

ini_set('display_errors', 1);
$d = new \DB();
$where = array('user_id'=>$argv[1]); // ,'mobile_no'=>$phone_no
$check_user = $d->get_record_where($table='users', $where);

if(!empty($check_user)){
    $username = $check_user[0]['username'];
    $password = base64_decode($check_user[0]['password']);
    $otp_status = $check_user[0]['otp_status'];
    $account_activated = $check_user[0]['account_activated'];
    $otp_timestamp = $check_user[0]['otp_timestamp'];
}else{
    $username = '';
    $password = '';
}
      //    print_r($check_user);die();
//$username = 'mohan.das.99990';
//$password = '123qweasdzxc';
$debug = true;
$truncatedDebug = false;
$verification_method = 0; //0 = SMS, 1 = Email
//////////////////////

$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);

try {
    if($otp_status==1 && $account_activated == 2){
    $dirname = dirname(__DIR__).'/webservices/vendor/mgp25/instagram-php/sessions/'.$username;
    delete_directory($dirname);
    }
    
    $ig->login($username, $password);
    $ig->logout();
    $loginResponse = $ig->login($username, $password);
    
    if ($loginResponse !== null && $loginResponse->isTwoFactorRequired()) {
        $twoFactorIdentifier = $loginResponse->getTwoFactorInfo()->getTwoFactorIdentifier();

        // The "STDIN" lets you paste the code via terminal for testing.
        // You should replace this line with the logic you want.
        // The verification code will be sent by Instagram via SMS.
        $verificationCode = trim(fgets(STDIN));
        $ig->finishTwoFactorLogin($username, $password, $twoFactorIdentifier, $verificationCode);
    }
   // echo '<pre>';
   //print_r($ig->getErrorType());
    ob_end_clean();
    ob_start();
} catch (\Exception $e) { 
  //  $e->getResponse();
    //print_r($ig->getErrorType());die();
//    print_r($e->getResponse());die();
    // some statement that removes all printed/echoed items
/*    
ob_end_clean();
//echo 'hh';
echo $e->getResponse();
exit(0);
    echo 'Something went wrong: '.$e->getMessage()."\n";
    exit(0);
 */
    
    if ($e instanceof InstagramAPI\Exception\ChallengeRequiredException) {
        sleep(5);
        
        
            if(!empty($check_user)){
                $choice = 1;
                if(!empty($check_user[0]['phone_number'])){
                    $choice = 0;
                }else{
                    if (!empty($check_user[0]['email'])) {
                    $choice = 1;
                    }
                }
//                if (!empty($check_user[0]['email'])) {
//                    $choice = 1;
//                    }
                    
                    /* Start : When first time the challenge error by the user */
                    /*
                    if($otp_status == 0 && $account_activated == 0 ){
                    $customResponse = $ig->request(substr($e->getResponse()->getChallenge()->getApiPath(), 1))->setNeedsAuth(false)->addPost("choice", $choice)->getDecodedResponse();
                    echo '<pre>';
                    print_r($customResponse); 
                    if (is_array($customResponse)) {
                        $json_encode = json_encode($customResponse);
                        //  print_r($check_user);die();
                        $user_id = $check_user[0]['user_id'];
                        $where = array('user_id'=>$user_id);
                        $data = array('challenge_required_response'=>$json_encode,'challenge_required_datetime'=> date('Y-m-d H:i:s'));
                        $data['otp_status'] = 1;
                        $data['account_activated'] = 2;
                        $d->update_records('users', $data, $where);
    //                    echo json_encode($response);exit(0);
                            //            $accountObject->setInstagramId($customResponse['user_id']);
            //            $accountObject->setChallengeIdentifier($customResponse['nonce_code']);
                        //Other stuff.
                    } else {
                        //Other stuff.
                    }
                }
                */
                    /* End : When first time the challenge error occured to user */
                
                /* Start : When second time the challenge error occured to user */
//                if($account_activated == 2 ){
                    $customResponse = $ig->request(substr($e->getResponse()->getChallenge()->getApiPath(), 1))->setNeedsAuth(false)->addPost("choice", $choice)->getDecodedResponse();
                    echo '<pre>';
                    print_r($customResponse); 
                    if (is_array($customResponse)) {
                        $json_encode = json_encode($customResponse);
                        //  print_r($check_user);die();
                        $user_id = $check_user[0]['user_id'];
                        $where = array('user_id'=>$user_id);
                        $data = array('challenge_required_response'=>$json_encode,'challenge_required_datetime'=> date('Y-m-d H:i:s'));
                        $data['otp_status'] = 1;
                        $data['account_activated'] = 2;
                        $d->update_records('users', $data, $where);
    //                    echo json_encode($response);exit(0);
                            //            $accountObject->setInstagramId($customResponse['user_id']);
            //            $accountObject->setChallengeIdentifier($customResponse['nonce_code']);
                        //Other stuff.
                    } else {
                        //Other stuff.
                    }
//                }
                /* End : When first time the challenge error by the user */
            }else{
                
            }
            ob_end_clean();
            echo $e->getResponse();
            exit(0);
            


    } else {
        //Other stuff.
    }
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