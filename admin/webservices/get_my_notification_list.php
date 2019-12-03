<?php

require './db.class.php';

class GetMyNotificationList extends DB {

    function get_my_notification_list() {
            
            if(!empty($_REQUEST['user_id'])){
                
            $user_id = $_REQUEST['user_id'];
            $query = "Select * FROM users WHERE user_id ='$user_id'";
            $get_user = $this->query_result($query);
            if(empty($get_user)){
                $response = array('status' => 'false', 'message' => 'User not found!');
                $this->json_output($response);
                exit();
            }
            $where = array('notification_user_id'=>$user_id);
            $get_notifications = $this->get_record_where('notification', $where, $column = '', $group_by = '', $order_by = 'notification_id', $order_by_type = 'DESC', $limit = '');

            if(!empty($get_notifications)){
                $where = array('notification_user_id'=>$user_id);
                $data = array('read_status'=>1);
                $this->update_records('notification', $data, $where);
	        $response = array('status' => 'true', 'message' => 'Notification Found', 'response' => $get_notifications);
	    } else {
	        $response = array('status' => 'false', 'message' => 'Notification Not Found!');
	    }    
        } else {
                $response = array('status' => 'false', 'message' => 'Invalid request parameter');
            }
        $this->json_output($response);
    }

}

$myEvent = new GetMyNotificationList();
$myEvent->get_my_notification_list();