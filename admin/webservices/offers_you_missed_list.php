<?php
ob_start();
require './db.class.php';

class OffersYouMissedList extends DB {

    function offers_you_missed_list() {

            
            if(!empty($_REQUEST['user_id'])){
                
            $user_id = $_REQUEST['user_id'];
            $username = strtolower($_REQUEST['username']);
            $query = "Select * FROM users WHERE user_id ='$user_id'";
            $get_user = $this->query_result($query);
            if(empty($get_user)){
                $response = array('status' => 'false', 'message' => 'User not found!');
                $this->json_output($response);
                exit();
            }
//            $where = array('user_id'=>$user_id);
//            $get_story = $this->get_record_where('st', $where);
            $query = "SELECT s.*,su.id as sent_primary_id,su.campaign_id,su.story_id,su.user_id,IF(s.post_datetime IS NULL, '', s.post_datetime) as post_datetime,IF(s.post_timezone IS NULL, '', s.post_timezone) as post_timezone FROM story s INNER JOIN story_user su ON su.story_id=s.id WHERE su.story_id NOT IN(SELECT su.story_id as request_user_story FROM story s INNER JOIN story_user su ON su.story_id=s.id WHERE su.user_id='$user_id' AND su.upload_status=1 AND su.story_accept_status=1 GROUP BY su.story_id) AND su.upload_status=1 AND su.story_accept_status=1 GROUP BY su.story_id ORDER BY su.id DESC limit 6";
            $get_story = $this->query_result($query);
//            echo '<pre>';
//            print_r($get_story);
            if(!empty($get_story)){
                $story_details = array();
	    	foreach ($get_story as $key=>$value){
//                $user_id = $value["user_id"];
                $campaign_id = $value["campaign_id"];
                $story_id = $value["story_id"];
//                $query_campaign_schedule = "SELECT csr.* FROM campaign c INNER JOIN campaign_story_relationship csr ON csr.campaign_id=c.id WHERE csr.campaign_id='$campaign_id' AND csr.story_id='$story_id'";
//                $get_campaign_schedule = $this->query_result($query_campaign_schedule);
                
                // SELECT user_details.*,total_approved_user_count.total_approved_count FROM ((SELECT u.*,count(DISTINCT(su.user_id)) As approved_users_count,su.story_id FROM users u INNER JOIN story_user su ON su.user_id=u.user_id WHERE su.story_id='20' AND story_accept_status=1 AND upload_status=1 GROUP BY su.user_id LIMIT 3) as user_details,(SELECT count(DISTINCT(su.user_id)) As total_approved_count,su.story_id FROM users u INNER JOIN story_user su ON su.user_id=u.user_id WHERE su.story_id='20' AND story_accept_status=1 AND upload_status=1) as total_approved_user_count)
                $approved_query = "SELECT user_details.*,total_approved_user_count.total_approved_count FROM ((SELECT u.user_id,u.first_name,IF(u.last_name IS NULL, '', u.last_name) as last_name,u.full_name,u.username,u.profile_pic_url,count(DISTINCT(su.user_id)) As approved_users_count,su.story_id,su.created_at FROM users u INNER JOIN story_user su ON su.user_id=u.user_id WHERE su.story_id='$story_id' AND story_accept_status=1 AND upload_status=1 GROUP BY su.user_id ORDER BY su.id DESC LIMIT 3) as user_details,(SELECT count(DISTINCT(su.user_id)) As total_approved_count,su.story_id FROM users u INNER JOIN story_user su ON su.user_id=u.user_id WHERE su.story_id='$story_id' AND story_accept_status=1 AND upload_status=1) as total_approved_user_count)";
                $get_story_approved_users = $this->query_result($approved_query);
                
                        if(!empty($value['story_image'])){
                            
                            if($value['media_type'] == 2){
                                $fname = $value["story_image"];
                                $filename = pathinfo($fname, PATHINFO_FILENAME);
                                $video = STORY_IMAGES.$value['story_image'];
//                                $video = STORY_IMAGES.'story_1545479211.mp4';
                                $thumbnail = '../uploads/story_images/video_thumbs/'.$filename.'.jpg';

//                            $fileToFlv=STORY_IMAGES.'story_1545479211.mp4';
//$fileFlv='../uploads/story_images/story_1545479211.flv';
//$Flvjpg= '../uploads/story_images/video_thumbs/thumbnail.jpg';
//$videoJPGWidthheight = "120x72";
// 
//echo shell_exec("ffmpeg -i ".$fileToFlv." -ar 22050 -ab 32 -f flv -s 320x256 ".$fileFlv);
// 
//echo shell_exec("sudo chmod 777 -R /var/www/html/10k-club");
//
//echo shell_exec("ffmpeg -i ".$fileFlv." -vframes 1 -ss 00:00:06 -s 120x72 -f image2 ".$Flvjpg);die();
                            // shell command [highly simplified, please don't run it plain on your script!]
                            shell_exec("ffmpeg -i $video -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vcodec mjpeg -s 400x400 -f mjpeg $thumbnail 2>&1");
                            ob_end_clean();
                            $value['story_image'] = STORY_IMAGES.'video_thumbs/'.$filename.'.jpg';
                            }else{
                                $value['story_image']= STORY_IMAGES.$value['story_image'];
                            }
                            
                            

                        }else{
                            $value['story_image']= '';
                        }
                        
                        if(!empty($value['created_at'])){
                            $value['created_at']= date('d/m', strtotime($value['created_at']));
                        }else{
                            $value['created_at']= '';
                        }
                        
                        if(!empty($value['story_title'])){
                            $value['story_title']= $this->shorter($value['story_title'], 15);
                        }else{
                            $value['story_title']= '';
                        }
//	    		unset($value['password']);
//                        if(!empty($get_campaign_schedule)){
//                            $value['campaign_schedule_count']=count($get_campaign_schedule);
//                            $value['campaign_schedule']=$get_campaign_schedule;
//                        }else{
//                            $value['campaign_schedule_count']=0;
//                            $value['campaign_schedule']=array();
//                        }
                        if(!empty($get_story_approved_users)){
                            $value['approved_users']=$get_story_approved_users;
                        }else{
                            $value['approved_users']=array();
                        }
                        $value['rtl_status'] = 2;
	    		$story_details[] = $value;
	    	}
                
	        $available_soon = $this->AvailableSoon($user_id);
	        $response = array('status' => 'true', 'message' => 'Offers Found', 'response' => $story_details, 'available_soon'=>$available_soon);
	    } else {
	        $response = array('status' => 'false', 'message' => 'Offers Not Found!');
	    }    
        } else {
                $response = array('status' => 'false', 'message' => 'Invalid request parameter');
            }
        $this->json_output($response);
    }
    
    function AvailableSoon($user_id) {
        
        $query = "SELECT s.*,su.id as sent_primary_id,su.campaign_id,su.story_id,su.user_id,IF(s.post_datetime IS NULL, '', s.post_datetime) as post_datetime,IF(s.post_timezone IS NULL, '', s.post_timezone) as post_timezone FROM story s INNER JOIN story_user su ON su.story_id = s.id INNER JOIN users ou ON ou.user_id = su.user_id INNER JOIN campaign_story_relationship csr ON csr.campaign_id=su.campaign_id INNER JOIN campaign c ON c.id=su.campaign_id WHERE su.user_id IN (SELECT iu.user_id FROM users iu WHERE iu.follower_count >= (SELECT l.followers_amount FROM levels l WHERE l.followers_amount >= (SELECT u1.follower_count FROM users u1 WHERE u1.user_id='$user_id') LIMIT 0,1)) GROUP BY su.story_id";
            
        $get_story = $this->query_result($query);
        $story_details = array();
        if(!empty($get_story)){
                
	    	foreach ($get_story as $key=>$value){
//                $user_id = $value["user_id"];
                $campaign_id = $value["campaign_id"];
                $story_id = $value["story_id"];
                if(!empty($value['story_image'])){
                            
                            if($value['media_type'] == 2){
                                $fname = $value["story_image"];
                                $filename = pathinfo($fname, PATHINFO_FILENAME);
                                $video = STORY_IMAGES.$value['story_image'];
//                                $video = STORY_IMAGES.'story_1545479211.mp4';
                                $thumbnail = '../uploads/story_images/video_thumbs/'.$filename.'.jpg';


                            shell_exec("ffmpeg -i $video -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vcodec mjpeg -s 400x400 -f mjpeg $thumbnail 2>&1");
                            ob_end_clean();
                            $value['story_image'] = STORY_IMAGES.'video_thumbs/'.$filename.'.jpg';
                            }else{
                                $value['story_image']= STORY_IMAGES.$value['story_image'];
                            }
                            
                            

                        }else{
                            $value['story_image']= '';
                        }
                        
                        if(!empty($value['created_at'])){
                            $value['created_at']= date('d/m', strtotime($value['created_at']));
                        }else{
                            $value['created_at']= '';
                        }
                
                        $value['rtl_status'] = 2;
	    		$story_details[] = $value;
                }
                
                return $story_details;
        }else{
            return $story_details;
        }
    }

}

$madeForYou = new OffersYouMissedList();
$madeForYou->offers_you_missed_list();