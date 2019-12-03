<?php
ob_start();
 
require './db.class.php';
set_time_limit(0);
date_default_timezone_set('UTC');

require __DIR__.'/./vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/////// CONFIG ///////
$d = new \DB();
$user_id = $argv[1];
$story_id = $argv[2];
$sent_primary_id = $argv[3];
//$where = array('user_id'=>$argv[1]); // ,'mobile_no'=>$phone_no
//$check_user = $d->get_record_where($table='users', $where);
$query = "SELECT s.*,u.username,u.password,su.campaign_id FROM story s INNER JOIN story_user su ON su.story_id=s.id INNER JOIN users u ON u.user_id=su.user_id WHERE su.user_id='$user_id' AND su.story_id='$story_id' AND su.id='$sent_primary_id'";
$get_story = $d->query_result($query);
echo '<pre>';
print_r($argv);
print_r($get_story);
if(!empty($get_story)){
    $campaign_id = $get_story[0]['campaign_id'];
    $username = $get_story[0]['username'];
    $password = base64_decode($get_story[0]['password']);
    $storyFileName = '../uploads/story_images/'.$get_story[0]['story_image'];
//    $storyFileName = STORY_IMAGES.$get_story[0]['story_image'];
    $media_type = $get_story[0]['media_type'];
    $link = $get_story[0]['ticket_link'];
}else{
    $username = '';
    $password = '';
    $storyFileName = '';
    $media_type = 0;
    $link = '';
}
//$username = '';
//$password = '';
//$username = 'mohan.das.99990';//mohan.das.99990
//$password = 'xczx';//123qweasdzxc
$debug = true;
$truncatedDebug = false;
//////////////////////

/////// MEDIA ////////
//$photoFilename = '../test.jpeg';

//////////////////////
//\InstagramAPI\Utils::$ffprobeBin = '/domains/10k.tempurl.co.il/ffmpeg/bin/ffprobe';
//\InstagramAPI\Utils::$ffprobeBin = '/home/rahul/usr/bin/ffprobe';

//\InstagramAPI\Utils::$ffprobeBin = '/home/rahul/ffmpeg/bin/ffprobe';
$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
//\InstagramAPI\Utils::$ffprobeBin = '/home/rahul/usr/bin/ffprobe';
try {
    $ig->login($username, $password);
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
    exit(0);
}

//$user = new \InstagramAPI\Request\Account;
//    $tttt=$ig->account->getCurrentUser($user);
//    echo 'hhhhhhhhhhhhhhhhhhhhhhhhhhhhh';
//    echo '<br>';
//    echo '<pre>';
//    
//    print_r(GuzzleHttp\json_decode($tttt)->user->pk);die();
//    echo 'fghhhhhhhhhhhf';
// You don't have to provide hashtags or locations for your story. It is
// optional! But we will show you how to do both...

// NOTE: This code will make the hashtag area 'clickable', but YOU need to
// manually draw the hashtag or a sticker-image on top of your image yourself
// before uploading, if you want the tag to actually be visible on-screen!

// NOTE: The same thing happens when a location sticker is added. And the
// "location_sticker" WILL ONLY work if you also add the "location" as shown
// below.

// NOTE: And "caption" will NOT be visible either! Like all the other story
// metadata described above, YOU must manually draw the caption on your image.

// If we want to attach a location, we must find a valid Location object first:
try {
    $location = $ig->location->search('40.7439862', '-73.998511')->getVenues()[0];
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
}

// Now create the metadata array:
$metadata = [
    // (optional) Captions can always be used, like this:
    'caption'  => '#test This is a great API!',

    // (optional) To add a hashtag, do this:
    'hashtags' => [
        // Note that you can add more than one hashtag in this array.
        [
            'tag_name'         => 'test', // Hashtag WITHOUT the '#'! NOTE: This hashtag MUST appear in the caption.
            'x'                => 0.5, // Range: 0.0 - 1.0. Note that x = 0.5 and y = 0.5 is center of screen.
            'y'                => 0.5, // Also note that X/Y is setting the position of the CENTER of the clickable area.
            'width'            => 0.24305555, // Clickable area size, as percentage of image size: 0.0 - 1.0
            'height'           => 0.07347973, // ...
            'rotation'         => 0.0,
            'is_sticker'       => false, // Don't change this value.
            'use_custom_title' => false, // Don't change this value.
        ],
        // ...
    ],
/*
    // (optional) To add a location, do BOTH of these:
    'location_sticker' => [
        'width'         => 0.89333333333333331,
        'height'        => 0.071281859070464776,
        'x'             => 0.5,
        'y'             => 0.2,
        'rotation'      => 0.0,
        'is_sticker'    => true,
        'location_id'   => $location->getExternalId(),
    ],
    'location' => $location,
*/
    // (optional) You can use story links ONLY if you have a business account with >= 10k followers.
    // 'link' => 'https://github.com/mgp25/Instagram-API',
    'link' => $link,
];

try {
    // This example will upload the image via our automatic photo processing
    // class. It will ensure that the story file matches the ~9:16 (portrait)
    // aspect ratio needed by Instagram stories. You have nothing to worry
    // about, since the class uses temporary files if the input needs
    // processing, and it never overwrites your original file.
    //
    // Also note that it has lots of options, so read its class documentation!
    $media_status = '';
    $getResult = '';
    if($media_type == 1){
        $photo = new \InstagramAPI\Media\Photo\InstagramPhoto($storyFileName, ['targetFeed' => \InstagramAPI\Constants::FEED_STORY]);
        $getResult=$ig->story->uploadPhoto($photo->getFile(), $metadata)->asStdClass();//->getStatus();
    }elseif($media_type == 2){
        $video = new \InstagramAPI\Media\Video\InstagramVideo($storyFileName, ['targetFeed' => \InstagramAPI\Constants::FEED_STORY]);
        $getResult=$ig->story->uploadVideo($video->getFile(), $metadata)->asStdClass();//->getStatus();
    }
    ob_end_clean();
//    echo '<pre>';
    if($ig->isMaybeLoggedIn == 1 && !empty($ig->session_id)){
        if(!empty($getResult)){
            $media_taken_at = $getResult->media->taken_at;
            $media_pk = $getResult->media->pk;
            $media_id = $getResult->media->id;
            $media_device_timestamp = $getResult->media->device_timestamp;
            $media_type = $getResult->media->media_type;
            $media_upload_id = $getResult->upload_id;
            $media_status = $getResult->status;
            
            if($media_status == 'ok'){
              $current_datetime = date('Y-m-d H:i:s');
//            $user_id = $check_user[0]['user_id'];
//            $where = array('user_id'=>$user_id);
                $data = array('user_id'=>$user_id,
                              'story_id'=>$story_id,
                    'campaign_id'=>$campaign_id,
                              'post_datetime'=>$current_datetime,
                    // below are the new field added on 09-feb-2019
                    'media_taken_at'=>$media_taken_at,
                    'media_pk'=>$media_pk,
                    'media_id'=>$media_id,
                    'media_device_timestamp'=>$media_device_timestamp,
                    'media_type'=>$media_type,
                    'media_upload_id'=>$media_upload_id,
                    'media_status'=>$media_status,
                );
                $insert_id = $d->insert_records('post_story', $data);
                if(!empty($insert_id)){
                    $where = array('user_id'=>$user_id,'story_id'=>$story_id,'id'=>$sent_primary_id);
                    $u_data = array('upload_status'=>1,
                        // below are the new field added on 09-feb-2019
                        'story_uploaded_datetime'=>$current_datetime,
                        'media_taken_at'=>$media_taken_at,
                        'media_pk'=>$media_pk,
                        'media_id'=>$media_id,
                        'media_device_timestamp'=>$media_device_timestamp,
                        'media_type'=>$media_type,
                        'media_upload_id'=>$media_upload_id,
                        'media_status'=>$media_status,
                        );
                    $d->update_records('story_user', $u_data, $where);

                    /* The checks for the limit which is set by admin */
                    $query = "SELECT count(su.user_id) as accecpted_count FROM story_user su WHERE su.story_id='$story_id' AND upload_status=1";

                    $story_upload_detail = $d->query_result($query);
    //                echo '<pre>';
    //                print_r($story_upload_detail);
                    if(!empty($story_upload_detail)){
                        $no_of_accecpted_count = $story_upload_detail[0]['accecpted_count'];
                        $query = "SELECT * FROM story WHERE id='$story_id'";

                        $story_detail = $d->query_result($query);
                        if(!empty($story_detail)){
                            $approved_user_limit = $story_detail[0]['approved_user_limit'];//100;200;50
                            $old_remaining_count = $story_detail[0]['remaining_count'];//100;200;50
                            if($old_remaining_count!=0 && $old_remaining_count>0){
                                $new_remaining_count = $approved_user_limit - $no_of_accecpted_count;//98;198;48
                            }else{
                                $new_remaining_count = 0;
                            }


                            $where_s = array('id'=>$story_id);
                            $up_data = array('accepted_count'=>$no_of_accecpted_count,'remaining_count'=>$new_remaining_count);
                            $d->update_records('story', $up_data, $where_s);
                        }

                    }

                    $response = array("status"=>$media_status,"success"=>1, "message"=>"Story uploaded successfully!");
                }else{
                    $response = array("status"=>$media_status,"success"=>2, "message"=>"Error in insertion");
                }
            
            }else{
                $response = array("status"=>$media_status,"success"=>2, "message"=>"Failed to upload.Plesae try again.");
            }
        }
        
        
        
        }else{
            $response = array("status"=>$media_status,"success"=>2, "message"=>"User not logged In.");
        }

        echo json_encode($response);exit(0);
    // NOTE: Providing metadata for story uploads is OPTIONAL. If you just want
    // to upload it without any tags/location/caption, simply do the following:
    // $ig->story->uploadPhoto($photo->getFile());
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
}
