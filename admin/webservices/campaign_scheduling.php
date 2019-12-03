<?php
//ob_start();
require './db.class.php';
set_time_limit(0);
date_default_timezone_set('Asia/Jerusalem');

use InstagramAPI\Instagram;
use InstagramAPI\Response\LoginResponse;

require __DIR__.'/./vendor/autoload.php';

/////// CONFIG ///////
$d = new \DB();
$debug = true;
$truncatedDebug = false;
$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
\InstagramAPI\Media\Video\FFmpeg::$defaultBinary = 600; // 10 minute timeout
$currentdatetime_new = date('Y-m-d H:i');
$plus_one_hour_datetime = date('Y-m-d H:i:s', strtotime('+5 minute', strtotime($currentdatetime_new)));

$query = "SELECT s.*,u.username,u.password,su.campaign_id,su.id as sent_primary_id,csr.schedule_date_time,su.user_id,su.story_id FROM story s INNER JOIN story_user su ON su.story_id=s.id INNER JOIN users u ON u.user_id=su.user_id INNER JOIN campaign_story_relationship csr ON csr.id=su.campaign_story_relationship_id WHERE su.media_pk='' AND su.media_status='' AND su.upload_status=0 AND insta_delete_status=0 AND su.story_accept_status=1";

$get_story_result = $d->query_result($query);
//echo '<pre>';
//print_r($get_story_result);
if(!empty($get_story_result)){
    /* Loop Start */
    foreach ($get_story_result as $key => $value) {
        $user_id = $value['user_id'];
        $story_id = $value['story_id'];
        $campaign_id = $value['campaign_id'];
        $timezone_query = "SELECT z.country_code,z.zone_name FROM `campaign` c INNER JOIN `zone` z ON z.country_code = c.country_code WHERE c.id='$campaign_id'";

        $get_timezone = $d->query_result($timezone_query);
        if(!empty($get_timezone)){
            /* Convert Date and Time from One Time Zone to Another in PHP (29-may-2019) */
            $zone_name=$get_timezone[0]['zone_name'];
            echo $current_timezone = date_default_timezone_get();
//            $current_timezone = (string) $current_timezone;
                $date = new DateTime($currentdatetime_new, new DateTimeZone($current_timezone));
                $current_datetime2 = $date->format('Y-m-d H:i');
                echo $current_datetime2. "<br><br>";
                $date->setTimezone(new DateTimeZone($zone_name));
                $current_datetime3 = $date->format('Y-m-d H:i');
                echo $current_datetime3. "<br><br>";
            /* Convert Date and Time from One Time Zone to Another in PHP */
            /* Start: old timezone conversion till date - 28-may-2019 */
         /*   $zone_name=$get_timezone[0]['zone_name'];
            date_default_timezone_set($zone_name);
//            $current_datetime = date('Y-m-d H:i');
            
            $date = new DateTime($currentdatetime_new);
            $current_datetime2 = $date->format('Y-m-d H:i');

            $date->setTimezone(new DateTimeZone($zone_name));
            $current_datetime3 = $date->format('Y-m-d H:i');
//            die(); */
            /* End */
            $plus_one_hour_datetime = date('Y-m-d H:i:s', strtotime('+5 minute', strtotime($currentdatetime_new)));
        }else{
            $zone_name='';
        }

        $username = $value['username'];
//        $password = base64_decode($value['password']);
        $password = $d->user_dauth($value['password'],$user_id);
        $sent_primary_id = $value['sent_primary_id']; 
        $storyFileName = '../uploads/story_images/'.$value['story_image'];
        $media_type = $value['media_type'];
        $link = $value['ticket_link'];
        $schedule_date_time = date('Y-m-d H:i', strtotime($value['schedule_date_time']));
        $story_caption = $value['story_caption'];
        $story_first_comment = $value['story_first_comment'];
        
        try {
//            echo $current_datetime .'>='. $schedule_date_time .'&&'. $schedule_date_time.'<='.$plus_one_hour_datetime;
            echo $current_datetime3 .'=='. $schedule_date_time .'';
            echo '<br/>';
//            if($current_datetime >= $schedule_date_time && $schedule_date_time<=$plus_one_hour_datetime)            {
            if($current_datetime3 == $schedule_date_time){
                //echo 'yes =>  '.$story_caption.'    \n';die();
                $ig->login($username, $password);
                // ob_end_clean();
                // Now create the metadata array:
//                if(empty($story_caption))
                    $story_caption='10kclub';
                $metadata = [
                    // (optional) Captions can always be used, like this:
                    'caption'  => '#'.$story_caption,//'#test This is a great API!',

                    // (optional) To add a hashtag, do this:
                    'hashtags' => [
                        // Note that you can add more than one hashtag in this array.
                        [
                            'tag_name'         => '10kclub', // Hashtag WITHOUT the '#'! NOTE: This hashtag MUST appear in the caption.
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
//    ob_end_clean();
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
                $data = array('user_id'=>$user_id,
                              'story_id'=>$story_id,
                              'campaign_id'=>$campaign_id,
                              'post_datetime'=>$current_datetime,
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
                    /*
                    $query = "SELECT count(su.user_id) as accecpted_count FROM story_user su WHERE su.story_id='$story_id' AND upload_status=1";

                    $story_upload_detail = $d->query_result($query);
 
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
                    */
                    /* The checks for the limit which is set by admin for the campaign */
                    
                    $query = "SELECT count(DISTINCT(su.unique_send_id)) as accecpted_count FROM story_user su WHERE su.campaign_id='$campaign_id' AND upload_status=1";

                    $story_upload_detail = $d->query_result($query);
 
                    if(!empty($story_upload_detail)){
                        $no_of_accecpted_count = $story_upload_detail[0]['accecpted_count'];
                        $query = "SELECT * FROM campaign WHERE id='$campaign_id'";

                        $camp_detail = $d->query_result($query);
                        if(!empty($camp_detail)){
                            $approved_user_limit = $camp_detail[0]['campaign_approved_user_limit'];//100;200;50
                            $old_remaining_count = $camp_detail[0]['campaign_remaining_count'];//100;200;50
                            if($old_remaining_count!=0 && $old_remaining_count>0){
                                $new_remaining_count = $approved_user_limit - $no_of_accecpted_count;//98;198;48
                            }else{
                                $new_remaining_count = 0;
                            }

                            $where_s = array('id'=>$campaign_id);
                            $up_data = array('campaign_accepted_count'=>$no_of_accecpted_count,'campaign_remaining_count'=>$new_remaining_count);
                            $d->update_records('campaign', $up_data, $where_s);
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
            }
            
        } catch (\Exception $e) {
            echo 'Something went wrong: '.$e->getMessage()."\n";
            $json = $e->getResponse();
            $errdata = array('created_at'=>date('Y-m-d H:i:s'));
                    $errdata['log_data'] =$json;
                    $errdata['user_id'] =$user_id;
                    $err_inserted_id = $d->insert_records('error_log', $errdata);
//            exit(0);
           
        }
        
    }
    /* Loop End */
    

}
    





