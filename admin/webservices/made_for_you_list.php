<?php

require './db.class.php';

class MadeForYouList extends DB {

    function made_for_you_list() {

            
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
            $current_datetime = date('Y-m-d H:i:s');
            $query = "SELECT c.campaign_name,c.campaign_description,c.reward,s.*,su.id as sent_primary_id,su.campaign_id,su.story_id,su.user_id,su.unique_send_id,su.unique_approval_update_id,TRIM(s.story_description) As story_description,su.upload_status,su.story_accept_status,IF(s.post_datetime IS NULL, '', s.post_datetime) as post_datetime,IF(s.post_timezone IS NULL, '', s.post_timezone) as post_timezone FROM story s INNER JOIN story_user su ON su.story_id=s.id INNER JOIN campaign_story_relationship csr ON csr.campaign_id=su.campaign_id  INNER JOIN campaign c ON c.id=su.campaign_id WHERE (su.user_id='$user_id' AND su.upload_status!=1 AND c.campaign_remaining_count!=0 AND c.campaign_remaining_count>0 AND su.story_accept_status=0 AND (csr.schedule_date_time >= '$current_datetime' OR csr.schedule_date_time IS NULL)) OR (su.user_id='$user_id' AND c.campaign_remaining_count!=0 AND c.campaign_remaining_count>0 AND (csr.schedule_date_time >= '$current_datetime' OR csr.schedule_date_time IS NULL) AND static_campaign_status = 1) GROUP BY su.unique_send_id"; 
//  AND csr.schedule_date_time >= '$current_datetime'
            $get_story = $this->query_result($query);
//            echo '<pre>';
//            print_r($get_story);
            if(!empty($get_story)){
                $where = array('user_id'=>$user_id);
                $data = array('read_status'=>1);
                $this->update_records('story_user', $data, $where);
                $story_details = array();
	    	foreach ($get_story as $key=>$value){
//                $user_id = $value["user_id"];
                $campaign_id = $value["campaign_id"];
                $story_id = $value["story_id"];
                // "SELECT csr.*,DAYNAME(DATE(csr.schedule_date_time)) as dayname,DATE_FORMAT(csr.schedule_date_time, '%W %d.%m.%Y | %h:%i %p') as schedule_info FROM campaign c INNER JOIN campaign_story_relationship csr ON csr.campaign_id=c.id WHERE csr.campaign_id='$campaign_id' AND csr.campaign_id='$campaign_id'"
                $query_campaign_schedule = "SELECT csr.*,IF(DAYNAME(DATE(csr.schedule_date_time)) IS NULL, '', DAYNAME(DATE(csr.schedule_date_time))) as dayname,IF(DATE_FORMAT(csr.schedule_date_time, '%W %d.%m.%Y | %h:%i %p') IS NULL, '', DATE_FORMAT(csr.schedule_date_time, '%W %d.%m.%Y | %h:%i %p')) as schedule_info, IF(csr.schedule_date_time IS NULL,'',csr.schedule_date_time) as schedule_date_time FROM campaign c INNER JOIN campaign_story_relationship csr ON csr.campaign_id=c.id WHERE csr.campaign_id='$campaign_id' AND csr.campaign_id='$campaign_id'";
                $get_campaign_schedule = $this->query_result($query_campaign_schedule);
                
                // SELECT user_details.*,total_approved_user_count.total_approved_count FROM ((SELECT u.*,count(DISTINCT(su.user_id)) As approved_users_count,su.story_id FROM users u INNER JOIN story_user su ON su.user_id=u.user_id WHERE su.story_id='20' AND story_accept_status=1 AND upload_status=1 GROUP BY su.user_id LIMIT 3) as user_details,(SELECT count(DISTINCT(su.user_id)) As total_approved_count,su.story_id FROM users u INNER JOIN story_user su ON su.user_id=u.user_id WHERE su.story_id='20' AND story_accept_status=1 AND upload_status=1) as total_approved_user_count)
                
                // SELECT user_details.*,total_approved_user_count.total_approved_count FROM ((SELECT u.user_id,u.first_name,u.last_name,u.full_name,u.username,u.profile_pic_url,count(DISTINCT(su.user_id)) As approved_users_count,su.story_id,su.created_at FROM users u INNER JOIN story_user su ON su.user_id=u.user_id WHERE su.story_id='20' AND story_accept_status=1 AND upload_status=1 GROUP BY su.user_id ORDER BY su.id DESC LIMIT 3) as user_details,(SELECT count(DISTINCT(su.user_id)) As total_approved_count,su.story_id FROM users u INNER JOIN story_user su ON su.user_id=u.user_id WHERE su.story_id='20' AND story_accept_status=1 AND upload_status=1) as total_approved_user_count)
                $approved_query = "SELECT user_details.*,total_approved_user_count.total_approved_count FROM ((SELECT u.user_id,u.first_name,IF(u.last_name IS NULL, '', u.last_name) as last_name,u.full_name,u.username,u.profile_pic_url,count(DISTINCT(su.user_id)) As approved_users_count,su.story_id,su.created_at FROM users u INNER JOIN story_user su ON su.user_id=u.user_id WHERE su.story_id='$story_id' AND story_accept_status=1 AND upload_status=1 GROUP BY su.user_id ORDER BY su.id DESC LIMIT 3) as user_details,(SELECT count(DISTINCT(su.user_id)) As total_approved_count,su.story_id FROM users u INNER JOIN story_user su ON su.user_id=u.user_id WHERE su.story_id='$story_id' AND story_accept_status=1 AND upload_status=1) as total_approved_user_count)";
                $get_story_approved_users = $this->query_result($approved_query);
                
                        if(!empty($value['story_image'])){
                            if($value['media_type'] == 2){
                                $value['story_video']= STORY_IMAGES.$value['story_image'];
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
                            $value['story_video_thumb'] = STORY_IMAGES.'video_thumbs/'.$filename.'.jpg';
                            
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
                        
                        if(!empty($value['story_description'])){
                            
                            $value['short_description']= $this->shorter($value['story_description'], 50);
//                            $value['short_description']= $value['story_description'];
                            
                        }else{
                            $value['short_description']= '';
                        }
                        
                        if(!empty($value['story_title'])){
                            
                            $value['story_title']= addslashes($value['story_title']);
                            
                        }else{
                            $value['story_title']= '';
                        }
                        
                        if(!empty($value['story_first_comment'])){
                            
                            $value['story_first_comment']= addslashes($value['story_first_comment']);
                            
                        }else{
                            $value['story_first_comment']= '';
                        }
//	    		unset($value['password']);
                        if(!empty($get_campaign_schedule)){
                            $value['campaign_schedule_count']=count($get_campaign_schedule);
                            $value['campaign_schedule']=$get_campaign_schedule;
                        }else{
                            $value['campaign_schedule_count']=0;
                            $value['campaign_schedule']=array();
                        }
                        if(!empty($get_story_approved_users)){
                            $value['approved_users']=$get_story_approved_users;
                        }else{
                            $value['approved_users']=array();
                        }
                        $value['rtl_status'] = 2;
                        $value['description_type'] = 2;
                        
	    		$story_details[] = $value;
	    	}
                
	        $response = array('status' => 'true', 'message' => 'Story Found', 'response' => $story_details);
	    } else {
	        $response = array('status' => 'false', 'message' => 'Story Not Found!');
	    }    
        } else {
                $response = array('status' => 'false', 'message' => 'Invalid request parameter');
            }
        $this->json_output($response);
    }
   
}

$madeForYou = new MadeForYouList();
$madeForYou->made_for_you_list();