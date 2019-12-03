<?php

require './db.class.php';

class GetPaymentReceivedList extends DB {

    function get_payment_received_list() {

            
            if(!empty($_REQUEST['user_id'])){
                
            $user_id = $_REQUEST['user_id'];
            $query = "Select * FROM users WHERE user_id ='$user_id'";
            $get_user = $this->query_result($query);
            if(empty($get_user)){
                $response = array('status' => 'false', 'message' => 'User not found!');
                $this->json_output($response);
                exit();
            }
//            $where = array('user_id'=>$user_id);
//            $get_payment = $this->get_record_where('st', $where);
            $query = "SELECT p.user_id,p.campaign_id,p.sent_primary_id,p.receipient_type,p.amount,p.currency,p.receiver,DATE_FORMAT(p.created_at, '%d/%m/%Y') as payment_date,u.full_name,u.paypal_email FROM payment p INNER JOIN users u ON u.user_id = p.user_id WHERE p.user_id='$user_id'";
            $get_payment = $this->query_result($query);
            if(!empty($get_payment)){
//                $story_details = array();
//	    	foreach ($get_payment as $key=>$value){
//	    		
//                        if(!empty($value['story_image'])){
//                            $value['story_image']= STORY_IMAGES.$value['story_image'];
//                        }else{
//                            $value['story_image']= '';
//                        }
//                        if(!empty($value['created_at'])){
//                            $value['created_at']= date('d/m', strtotime($value['created_at']));
//                        }else{
//                            $value['created_at']= '';
//                        }
////	    		unset($value['password']);
//                        
//	    		$story_details[] = $value;
//	    	}
                
	        $response = array('status' => 'true', 'message' => 'Record Found', 'response' => $get_payment);
	    } else {
	        $response = array('status' => 'false', 'message' => 'Record Not Found!');
	    }    
        } else {
                $response = array('status' => 'false', 'message' => 'Invalid request parameter');
            }
        $this->json_output($response);
    }

}

$paymentReceived = new GetPaymentReceivedList();
$paymentReceived->get_payment_received_list();