<?php
require './db.class.php';

class AddPaymentMethod extends DB {
    
    function add_payment_method() {

        if(!empty($_REQUEST['user_id']) && !empty($_REQUEST['paypal_email'])){
                    $user_id = $_REQUEST['user_id'];
                    $paypal_email = $_REQUEST['paypal_email'];
                    $where = array('user_id'=>$user_id);
                    $check_user = $this->get_record_where('users', $where);
                    if(empty($check_user)){
                       $response = array('status' => 'false', 'message' => 'User not found!');
                       $this->json_output($response);
                       exit();
                    }
                    
                    $data = array('paypal_email' => $paypal_email);
                    $update_table = $this->update_records('users', $data, $where);
                    $response = array('status' => 'true', 'message' => 'Payment method added successfully!');
        } else {
            $response = array('status' => 'false', 'message' => 'Invalid request parameter');
        }
        $this->json_output($response);
    }
}

$payment_method = new AddPaymentMethod();
$payment_method->add_payment_method();