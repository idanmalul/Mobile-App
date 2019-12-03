<?php
ob_start();
require './db.class.php';
set_time_limit(0);
date_default_timezone_set('UTC');

use InstagramAPI\Instagram;
use InstagramAPI\Response\LoginResponse;

require __DIR__.'/./vendor/autoload.php';


/////// CONFIG ///////
$d = new \DB();
$debug = true;
$truncatedDebug = false;
$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
//$user_id = $argv[1];
//$story_id = $argv[2];
$current_datetime = date('Y-m-d H:i:s');
//$where = array('user_id'=>$argv[1]); // ,'mobile_no'=>$phone_no
//$check_user = $d->get_record_where($table='users', $where);
//$query = "SELECT su.*,u.username,u.password,u.user_id FROM story s INNER JOIN story_user su ON su.story_id=s.id INNER JOIN users u ON u.user_id=su.user_id WHERE su.media_pk!='' AND su.media_status='ok' AND su.upload_status=1 AND insta_delete_status=0 ORDER BY su.id DESC"; //AND su.user_id in(35,11)";    AND su.user_id in(60)  AND su.campaign_id in(116)

$query = "SELECT su.*,u.username,u.password,u.user_id FROM story s INNER JOIN story_user su ON su.story_id=s.id INNER JOIN users u ON u.user_id=su.user_id WHERE su.media_pk!='' AND su.media_status='ok' AND su.upload_status=1 AND insta_delete_status=0 AND su.story_uploaded_datetime > DATE_SUB(CURDATE(), INTERVAL 2 DAY) ORDER BY su.id DESC";

$get_story_result = $d->query_result($query);
echo '<pre>';
//print_r($argv);
print_r($get_story_result);
//echo $password = $d->user_dauth($get_story_result[0]['password'],$get_story_result[0]['user_id']);
//die();
if(!empty($get_story_result)){
    foreach ($get_story_result as $key => $value) {
        $user_id = $value['user_id'];
        $username = $value['username'];
//        $password = base64_decode($value['password']);
        echo $password = $d->user_dauth($value['password'],$user_id);
        echo '<br>';
        $offer_send_id = $value['id']; 
        $media_pk = trim($value['media_pk']);
        $story_uploaded_datetime = $value['story_uploaded_datetime'];
        $media_taken_at = $value['media_taken_at'];
        $story_expire_datetime = date('Y-m-d H:i:s', strtotime('+48 hours', strtotime($story_uploaded_datetime)));
//        $story_expire_datetime = date('Y-m-d H:i:s', strtotime('+24 hours', $media_taken_at));
        echo $story_expire_datetime;//die();
        
        try {
//            echo 'try catch enter';die();
//            if($story_expire_datetime >= $current_datetime){
//                echo 'yes';die();
                $ig->login($username, $password);
                ob_end_clean();
               // $pk = get_story_data_from_id($media_pk,$ig);
//                echo '<br><br>';
//                echo 'rahultest';
//                echo $pk;die();
                $getTotalViewerCount = $ig->story->getStoryItemViewers(
                $media_pk,//'1975484203919264711',//'1974615765508294974',//'1974610159804227913',
                    $maxId = null)->getUserCount();//getTotalViewerCount();
    // echo $getTotalViewerCount;die();
    //print_r($getTotalViewerCount);die();
                $where = array('id'=>$offer_send_id);
                $u_data = array(
                    'story_viewer_count'=>$getTotalViewerCount,
                    );
                $d->update_records('story_user', $u_data, $where);
                $where_post = array('media_pk'=>$media_pk);
                $u_postdata = array(
                    'story_viewer_count'=>$getTotalViewerCount,
                    );
                $d->update_records('post_story', $u_postdata, $where_post);
                //$ig->story->getStatus();
            //    ob_end_clean();
    //            echo '<pre>';
                //echo $getStatus;
    //            print_r($getResult);
    //            echo '<br>';die();
//            }
            
        } catch (\Exception $e) {
//            echo 'Something went wrong: '.$e->getMessage()."\n";
//            exit(0);
           // echo $e->getMessage();die();
//           echo $e->getMessage();die();
            if($e->getMessage()=="InstagramAPI\Response\ReelMediaViewerResponse: Cannot access media viewer info."){
//                echo 'tttttttttttt';die();
                $where = array('id'=>$offer_send_id);
                $u_data = array(
                    'insta_delete_status'=>1,
                    );
                $d->update_records('story_user', $u_data, $where);
            }
        }
        /*
        try {
            $userId = $ig->people->getUserIdForName($username);
            //    $getResult=$ig->story->getUserStoryFeed('9268585104')->getReel();
            //    $getResult = $ig->story->followUser($userId);
            //    $getResult = $ig->story->getArchivedStoriesFeed();
            $getResult = $ig->story->getStoryItemViewers(
            '1975484203919264711',//'1974615765508294974',//'1974610159804227913',
            $maxId = null)->getTotalViewerCount();
            //$ig->story->getStatus();
            //    ob_end_clean();
            echo '<pre>';
            //echo $getStatus;
            print_r($getResult);
            echo '<br>';die();

            // NOTE: Providing metadata for story uploads is OPTIONAL. If you just want
            // to upload it without any tags/location/caption, simply do the following:
            // $ig->story->uploadPhoto($photo->getFile());
        } catch (\Exception $e) {
            echo 'Something went wrong: '.$e->getMessage()."\n";
        }  */
    }
    
//    $storyFileName = '../uploads/story_images/'.$get_story_result[0]['story_image'];
//    $storyFileName = STORY_IMAGES.$get_story_result[0]['story_image'];
//    $media_type = $get_story_result[0]['media_type'];
//    $link = $get_story_result[0]['ticket_link'];
}

function get_story_data_from_id ($id_archiveday,$ig){
//    echo '';
    echo '<pre>';
//    $highlights = $ig->highlight->getUserFeed('9180101752');
//    print_r($highlights);
//    $getResult=$ig->story->getUserStoryFeed('43862313')->getReel();
//    print_r($getResult);
    $result = $ig->story->getArchivedStoriesFeed();
    print_r($result);die();
    $ar = "archiveDay:$id_archiveday";
//    $feedList = ["'".$ar."'"];
    $feedList = ['archiveDay:17962748410241286'];
//    print_r($feedList);
    $reelsMediaFeed = $ig->story->getReelsMediaFeed($feedList);
    $mediaPk = $reelsMediaFeed->getReels()['archiveDay:17962748410241286']->getItems();//->getItems()[0]->getPk();
    
    print_r($mediaPk);die();
    $json = json_encode($reelsMediaFeed);
    preg_match("/pk\":(\d{19,19})/", $json, $output_array);    
    echo $pk =  $output_array[1];die();
    return $mediaPk;
//    return  $ig->story->getStoryItemViewers($pk);
}
    





