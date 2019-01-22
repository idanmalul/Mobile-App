<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin extends CI_Controller {
    
    function __construct() {

        parent::__construct();
        
        if (!$this->session->userdata('logged_in')) {

             redirect('login');

         }
         $this->session->userdata('admin_id');
    }

    function index() {

        //________________________________Admin Dashboard_______________________________________//

        $this->load->view('admin/header');

        $this->load->view('admin/menu');

        $this->load->view('admin/index_view');

        $this->load->view('admin/footer');

    }
    
    function favourite_list() {
        $data['favourite_list'] = $this->project_model->get_column_data_where('favourites', '', $where='');
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/listing/favourite_list_view', $data);
        $this->load->view('admin/footer');
    }

    function add_favourite() {
        if ($this->input->post('submit')) {
            $data = $this->input->post();
            unset($data['submit']);
            $admin_id = $this->session->userdata('admin_id');
            $data['created_by'] = $admin_id;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['favourite_status'] = 1;
            $ch = array("favourite_name" => strtoupper($this->input->post("favourite_name")));
            $rec = $this->project_model->get_data_where_condition('favourites', $ch);
            if (empty($rec)) {
                $favourite_id = $this->project_model->insert_data('favourites', $data);
                if ($favourite_id > 0) {
                    $this->session->set_flashdata('status', 'Favourite added successfully');
                    redirect('admin/favourite_list');
                } else {
                    $this->session->set_flashdata('error', 'Error in insertion');
                    redirect('admin/favourite_list');
                }
            } else {
                $this->session->set_flashdata('error', 'Favourite Already Exist!');
                redirect('admin/favourite_list');
            }
        } else {
            $this->load->view('admin/header');
            $this->load->view('admin/menu');
            $this->load->view('admin/add/add_favourite');
            $this->load->view('admin/footer');
        }
    }

    function edit_favourite() {
        if ($this->input->post('submit')) {
            $data = $this->input->post();
            unset($data['submit']);
            unset($data['favourite_id']);
            
	    $ch = array("favourite_name" => strtoupper($this->input->post("favourite_name")), 'favourite_id !=' => $this->input->post('favourite_id'));
            $rec = $this->project_model->get_data_where_condition('favourites', $ch);
            if (empty($rec)) {
                $where = $where = array('favourite_id' => $this->input->post('favourite_id'));
                $edit = $this->project_model->update_data('favourites', $data, $where);
                $this->session->set_flashdata('status', 'Favourite updated successfully!');
                redirect('admin/favourite_list');
            } else {
                $this->session->set_flashdata('error', 'Favourite Already Exist!');
                redirect('admin/favourite_list');
            }

        } else {
            $favourite_id = $this->uri->segment(3);
            if (!empty($favourite_id)) {
                $where = array('favourite_id' => $favourite_id);
                $favourite = $this->project_model->get_column_data_where('favourites', '', $where);
                if (!empty($favourite)) {
                    $data['favourite'] = $favourite;
                    $this->load->view('admin/header');
                    $this->load->view('admin/menu');
                    $this->load->view('admin/add/add_favourite', $data);
                    $this->load->view('admin/footer');
                } else {
                    redirect('admin/favourite_list');
                }
            } else {
                redirect('admin/favourite_list');
            }
        }
    }
    
     function user_list()
    {
        $data['user_list'] = $this->project_model->get_column_data_where('users', '', $where='');
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/listing/user_list_view', $data);
        $this->load->view('admin/footer');
    }
    
    function send_story_to_user()
    {
         $data =  $this->input->post();
//         echo "<pre>"; print_r($data); die();

        if ($this->input->post())
        {
            $data = $this->input->post();
            $admin_id = $this->session->userdata('admin_id');
             unset($data['users_ids']);
            
            $data['created_by']= $admin_id;
            $data['created_at'] = date('Y-m-d H:i:s');

            $user =  $this->input->post('users_ids');
            
            
            // echo "<pre>"; print_r($u_exp); die();
            if(!empty($user))
            {
                $u_exp = explode(",", $user);
                if(!empty($u_exp)){
                    foreach($u_exp as $v)
                    { 
                        $data['user_id'] = $v;
                        // echo "<pre>"; print_r($user_data['user_id']); die();
                        $inserted_id = $this->project_model->insert_data('story_user', $data);

                        if(!empty($inserted_id)){
                            $user_detail = get_user_by_user_id($v);
                            $token = trim($user_detail->gcm_id);
                            //echo $token;die();
                            $story_detail = get_story_by_story_id($data['story_id']);
                            if(!empty($story_detail->story_image)){
                                $story_detail->story_image= base_url('uploads/story_images/').$story_detail->story_image;
                            }else{
                                $story_detail->story_image= '';
                            }
                            if(!empty($story_detail->created_at)){
                                $story_detail->created_at= date('d/m', strtotime($story_detail->created_at));
                            }else{
                                $story_detail->created_at= '';
                            }
                            $story_detail_array =  (array) $story_detail;
                            $message = $story_detail->story_title;
                           // $token = "cIqV5HNuMrw:APA91bEXUF3QjlldNpng4o7VX9AKzFD3gCbp48CIHs4ZFjsDutVr6hySTTeXjZOG8QdXCv3mN-uoCsCZER63t7gHXio_kfoPspftiT9OWfCALynT0Nx82v8oV7RgZkQCYhI2tthHj4PZ";
                           // $message = "Hello Mohan";
                           // , 'data'=> base64_encode($story_detail->story_title)
                           $query = "SELECT count(su.read_status) as badge_count FROM story_user su WHERE su.user_id='$v' AND read_status=0";
            
                           $users_notification = $this->project_model->get_query_result($query);
                           $tBadge = ($users_notification[0]->badge_count) ? $users_notification[0]->badge_count : 0 ;
                        //    ,'badge' => $tBadge
                            $noti_arr = array('title' => 'Upcoming Offer', 'body' =>  $message ,'sound'=>'Default','image'=> base_url('assets/images/LOGO.png'),'badge' => $tBadge,'icon' => 'ic_notification');
                            $custom_notification = array(
                                'title' => 'Upcoming Offer', 
                                'body' =>  $message 
                            );
                            $data_arr = array(
                                'title' => 'Upcoming Offer', 
                                'body' =>  $message,
                                'badge' => $tBadge,
                                'icon' => 'ic_notification',
                                'custom_notification' => $custom_notification,
                                'story_detail'=> $story_detail_array
                            );
                            $this->sendPushNotificationToFCMSever($token,$noti_arr,$data_arr);
                            
                        }
                    }
                    if(!empty($inserted_id)){
                        $this->session->set_flashdata('status', 'Story send successfully.');
                        redirect('users');
                    }else{
                        $this->session->set_flashdata('error', 'Error in insertion.');
                        redirect('users');
                    }
                }else{
                    $this->session->set_flashdata('error', 'Please select at least one user.');
                    redirect('users');
                }
            }else{
                    $this->session->set_flashdata('error', 'Please select at least one user.');
                    redirect('users');
                }


        } else {
            redirect('users');
        }

}
    function story()
    {
        $user['user_arr'] =  $this->input->post('user_[]');
        // echo "<pre>"; print_r($user); die();

        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/add/add_story_view',$user);
        $this->load->view('admin/footer');
    }

    function story_list()
    {
        $data['story_list'] = $this->project_model->get_column_data_where('story', '', $where='');
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/listing/story_list_view', $data);
        $this->load->view('admin/footer');
    }
    
    function add_story()
    {
        // $user =  $this->input->post('user_hidddn');
        // echo "<pre>"; print_r($user); die();

        if ($this->input->post())
        {
            $data = $this->input->post();
            $admin_id = $this->session->userdata('admin_id');
            // unset($data['submit']);
            if (!empty($_FILES["image"]["name"])) {

                $config['upload_path'] = './uploads/story_images/';

                $config['allowed_types'] = 'gif|jpg|png|jpeg|webm|ogg|mp4|3gp';

                $config['max_size'] = '';

                $config['min_width']  = '';

                $config['min_height']  = '';

                $config['max_width']  = '';

                $config['max_height']  = '';

                $file_extension = @end(explode(".", $_FILES["image"]["name"]));

                $new_extension = strtolower($file_extension);

                $today = time();

                $custom_name = "story_" . $today;

                $file_name = $custom_name . "." . $new_extension;

                $config['file_name'] = $file_name;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('image', $config)) {
                    
                $data['error'] = 'Supported Media Type - gif, jpg, png, jpeg, webm, mp4, 3gp, ogg.';
               
                
                $this->load->view('admin/header');
                $this->load->view('admin/menu');
                $this->load->view('admin/add/add_agent', $data);
                $this->load->view('admin/footer');
                return false;
                }
                
                if($file_extension == 'gif' || $file_extension == 'jpg' || $file_extension == 'png' || $file_extension == 'jpeg'){
                    $data['media_type'] = 1;
                }else{
                    $data['media_type'] = 2;
                }
                $data['story_image'] = $file_name;
            }


            

            // else{
            //     $data['error'] = 'Please upload Dealer photo.';
                
            //     $this->load->view('admin/header');
            //     $this->load->view('admin/menu');
            //     $this->load->view('admin/add/add_agent', $data);
            //     $this->load->view('admin/footer');
            //     return false;
            // }

            if(!empty($_POST['submit_schedule']))
            {
                $data['story_status'] = 1;
            }

            if(!empty($_POST['submit_post']))
            {
                $data['story_status'] = 2;
            }

            if(!empty($_POST['submit_save']))
            {
                $data['story_status'] = 3;
            }
            
            $data['created_by']= $admin_id;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['remaining_count'] = $data['approved_user_limit'];
            unset($data['user_hidden']);
            unset($data['send_user_id']);
            unset($data['submit_schedule']);
            unset($data['submit_post']);
            unset($data['submit_save']);
            $user =  $this->input->post('send_user_id');
            
            $story_id = $this->project_model->insert_data('story', $data);

            if ($story_id > 0) {

//                $user =  $this->input->post('user_hidden');
                

                //$u_exp = explode(",", $user);
                // echo "<pre>"; print_r($u_exp); die();
                if(!empty($user))
                {
                    
                    foreach($user as $v)
                    { 
                        $user_data['user_id'] = $v;
                        // echo "<pre>"; print_r($user_data['user_id']); die();

                        $user_data['story_id'] = $story_id;
                        $user_data['created_at'] = date('Y-m-d H:i:s');
                        $this->project_model->insert_data('story_user', $user_data);
                    }
                }
                
                $this->session->set_flashdata('success', 'Story added successfully.');
                redirect('admin/edit_story/'.$story_id);
                
            } else {

                $this->session->set_flashdata('error', 'Error in insertion');
                redirect("admin/edit_story");
            }

        } else {
            $this->load->view('admin/header');
            $this->load->view('admin/menu');
            $this->load->view('admin/add/add_story_view');
            $this->load->view('admin/footer');
        }

}

    function edit_story()
    {
        if ($this->input->post()) 
        {
            $data = $this->input->post();

            $admin_id = $this->session->userdata('admin_id');
            unset($data['submit']);

            if (!empty($_FILES["image"]["name"])) {

                $config['upload_path'] = './uploads/story_images/';

                $config['allowed_types'] = 'gif|jpg|png|jpeg|webm|ogg|mp4|3gp';

                $config['max_size'] = '';

                $config['min_width']  = '';

                $config['min_height']  = '';

                $config['max_width']  = '';

                $config['max_height']  = '';

                $file_extension = @end(explode(".", $_FILES["image"]["name"]));

                $new_extension = strtolower($file_extension);

                $today = time();

                $custom_name = "story_" . $today;

                $file_name = $custom_name . "." . $new_extension;

                $config['file_name'] = $file_name;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('image', $config)) {
                    
                $data['error'] = 'Supported Media Type - gif, jpg, png, jpeg, webm, mp4, 3gp, ogg.';
               
                
                $this->load->view('admin/header');
                $this->load->view('admin/menu');
                $this->load->view('admin/add/add_story_view', $data);
                $this->load->view('admin/footer');
                return false;
                }
                
                if($file_extension == 'gif' || $file_extension == 'jpg' || $file_extension == 'png' || $file_extension == 'jpeg'){
                    $data['media_type'] = 1;
                }else{
                    $data['media_type'] = 2;
                }
                $data['story_image'] = $file_name;

            }
        
            $story_id = $this->input->post('story_id');
            unset($data['story_id']);

            unset($data['user_hidden']);
            unset($data['send_user_id']);
            unset($data['submit_schedule']);
            unset($data['submit_post']);
            unset($data['submit_save']);
            $where = array('id' => $story_id);

            $story_detail = $this->project_model->get_column_data_where('story', '', $where);

            if (!empty($story_detail)) {
                $approved_user_limit = $story_detail[0]->approved_user_limit;//100;200;50
                $remaining_count = $story_detail[0]->remaining_count;//98;198;48
                $accepted_count = $story_detail[0]->accepted_count;//2;2;2
                if($data['approved_user_limit']>$accepted_count){
                    $new_remaining_count = $data['approved_user_limit'] - $accepted_count;
                    $data['remaining_count'] = $new_remaining_count;
                }elseif($data['approved_user_limit']<$accepted_count){
                    $this->session->set_flashdata('error', 'You can not set limit less than approved users which is : '.$accepted_count);
//                    $new_remaining_count = $data['approved_user_limit'] - $accepted_count;
//                    $data['remaining_count'] = $new_remaining_count;
                }
            }
            
            $where = array('id' => $story_id);

            $edit = $this->project_model->update_data('story', $data, $where);
            $user =  $this->input->post('send_user_id');

                //$u_exp = explode(",", $user);
                // echo "<pre>"; print_r($u_exp); die();
                if(!empty($user))
                {
                    $where = array('story_id'=>$story_id);
                    $this->project_model->delete_record('story_user',$where);
                    foreach($user as $v)
                    { 
                        $user_data['user_id'] = $v;
                        // echo "<pre>"; print_r($user_data['user_id']); die();

                        $user_data['story_id'] = $story_id;
                        $user_data['created_at'] = date('Y-m-d H:i:s');
                        $this->project_model->insert_data('story_user', $user_data);
                    }
                }
            $this->session->set_flashdata('success', 'Story updated successfully');
            redirect('admin/edit_story/'.$story_id);
           


        } else {

            $story_id = $this->uri->segment(3);

            if (!empty($story_id)) {

                $where = array('id' => $story_id);

                $story_detail = $this->project_model->get_column_data_where('story', '', $where);

                if (!empty($story_detail)) {

                    $data['story_detail'] = $story_detail;
                    
                    $this->load->view('admin/header');
                    $this->load->view('admin/menu');
                    $this->load->view('admin/add/add_story_view', $data);

                    $this->load->view('admin/footer');

                } else {

                    redirect('admin/story_list');

                }

            } else {

                redirect('admin/story_list');

            }

        }
    }
    
    function post_story_list()
    {
//        $where='';
//        $data['post_story_list'] = $this->project_model->get_column_data_where('post_story', '', $where);
        $query = "SELECT ps.post_id,u.user_id,u.full_name,s.id as story_id,s.media_type,s.story_image,s.ticket_link,s.story_title,s.story_description FROM story s INNER JOIN post_story ps ON ps.story_id=s.id INNER JOIN users u ON u.user_id=ps.user_id";
            
        $data['post_story_list'] = $this->project_model->get_query_result($query);
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/listing/post_story_list_view', $data);
        $this->load->view('admin/footer');
    }
    
    function logout() {

        $array_items = array('admin_id', 'admin_email', 'logged_in');

        $this->session->unset_userdata($array_items);

        redirect('login');

    }

    function change_pass() {

        if ($this->input->post('old_pass')) {

            $admin_id = $this->session->userdata('admin_id');

            $where = array('admin_id' => $admin_id, 'admin_password' => md5($this->input->post('old_pass')));

            $check_user = $this->project_model->get_records_where('admin', $where);

            if ($check_user) {

                $update_data = array('admin_password' => md5($this->input->post('new_pass')));

                $check_user = $this->project_model->update_records('admin', $where, $update_data);



                $_SESSION['success_msg'] = 'Password changed successfully';

                $this->session->mark_as_flash('success_msg');

                redirect('change-password');

            } else {

                $_SESSION['error_msg'] = 'Invalid old password';

                $this->session->mark_as_flash('error_msg');

                redirect('change-password');

            }

        } else {

            $this->load->view('admin/header');

            $this->load->view('admin/menu');

            $this->load->view('admin/change_pass');

            $this->load->view('admin/footer');

        }

    }

    function humanTiming ($time)

    {

        $time = time() - $time; // to get the time since that moment

        $tokens = array (

            31536000 => 'year',

            2592000 => 'month',

            604800 => 'week',

            86400 => 'day',

            3600 => 'hour',

            60 => 'minute',

            1 => 'second'

        );

        foreach ($tokens as $unit => $text) {

            if ($time < $unit) continue;

            $numberOfUnits = floor($time / $unit);

            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');

        }

    }

    function change_status() {

        $where_name = $this->uri->segment(3);

        $where_value = $this->uri->segment(4);

        $table = $this->uri->segment(5);

        $table_field = $this->uri->segment(6);

        $field_value = $this->uri->segment(7);

        $function = $this->uri->segment(8);

        //----------------Start Change Status--------------------//

        $where = array($where_name => $where_value);

        $data = array($table_field => $field_value);

        $this->project_model->update_data($table, $data, $where);


        //----------------End Change Status--------------------//
        
        if ($table == "favourites" && $function == "favourite_list") {
            if ($field_value != 1) {
                $message = 'Favourite deactivated successfully. ';
            } else {
                $message = 'Favourite activated successfully. ';
            }
            $this->session->set_flashdata('status', $message);
            $path = 'admin/'.$function;
            redirect($path);
        }

    }


    function send_email($from, $to, $subject, $message) {

        $this->load->library('email');

        $this->email->from($from);

        $this->email->to($to);

        $this->email->subject($subject);

        $this->email->message($message);

        if (!$this->email->send()) {

            return FALSE;

        } else {

            return TRUE;

        }

    }

    function delete() {

        $delete_type = $this->uri->segment(3);

        $delete_table = $this->uri->segment(4);

        $delete_where_name = $this->uri->segment(5);

        $delete_where_id = $this->uri->segment(6);

        $delete_where = array($delete_where_name => $delete_where_id);
                
        $delete = $this->project_model->delete_record($delete_table, $delete_where); 

        if ($delete == TRUE) {
            
            if ($delete_type == 'delete_favourite') {
                $message = 'Favourite deleted successfully!!';
                $this->session->set_flashdata('error', $message);
                redirect('admin/favourite_list');
            }
            if ($delete_type == 'delete_story') {
                $message = 'Story deleted successfully!!';
                $this->session->set_flashdata('error', $message);
                redirect('admin/story_list');
            }
            if ($delete_type == 'delete_post_story') {
                $message = 'Record deleted successfully!!';
                $this->session->set_flashdata('error', $message);
                redirect('history');
            }
        } else {

            redirect();

        }

    }

    // For android notification
    function android_notification($gcm_id='', $msg='') {
        $gcm_id = "cTegD-hspMA:APA91bEIaBwYFg2gSYmxxKZcoF8_P95yGRcZK2OsCcVeQqsjSr0AIzoclWwOJLhoc8Rer8Ykxqr35_tWiKOBHH7CFVQQTflDTH4xmmu4DEpeviW2RxV-OIr_HMa-Ay0uOLSgj34bArtK";
        $msg = "Hello Mohan";
        $GOOGLE_API_KEY = 'AAAAp8kAQbc:APA91bEICU4oO7G0MFj1gFoFRDq5KU7-wmMGRS8vwPff9I9yrzaw0_6K9-VD18dFzqHBTqHfgPSqAVP-A_4INc5wJN-orbiQSSVEGicjA9S-snZFGG9s8YcUWQWnbvz4yk4IYmAXG9Sg';
        $fields = array
            (
            'registration_ids' => array($gcm_id),
            'data' => array('data'=>$msg)
        );

        //firebase server url to send the curl request

        $url = 'https://fcm.googleapis.com/fcm/send';

        //building headers for the request

        $headers = array(

            'Authorization: key=' . $GOOGLE_API_KEY,

            'Content-Type: application/json'

        );

        //Initializing curl to open a connection

        $ch = curl_init();
        //Setting the curl url

        curl_setopt($ch, CURLOPT_URL, $url);

        //setting the method as post

        curl_setopt($ch, CURLOPT_POST, true);

        //adding headers 

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //disabling ssl support

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //adding the fields in json format 

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        //finally executing the curl request 

        $result = curl_exec($ch);

        if ($result === FALSE) {

            die('Curl failed: ' . curl_error($ch));

        }
        print_r($result);die();
        //Now close the connection
        curl_close($ch);

        //and return the result 
        return $result;
    }
    
    function sendPushNotificationToFCMSever($token='', $notification_arr=array(), $data_arr=array()) {
        //echo base_url('assets/images/LOGO.png');die();
        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
        
       // $token = "cIqV5HNuMrw:APA91bEXUF3QjlldNpng4o7VX9AKzFD3gCbp48CIHs4ZFjsDutVr6hySTTeXjZOG8QdXCv3mN-uoCsCZER63t7gHXio_kfoPspftiT9OWfCALynT0Nx82v8oV7RgZkQCYhI2tthHj4PZ";
     //   $message = "Hello Mohan";
        $GOOGLE_API_KEY = 'AAAAp8kAQbc:APA91bEICU4oO7G0MFj1gFoFRDq5KU7-wmMGRS8vwPff9I9yrzaw0_6K9-VD18dFzqHBTqHfgPSqAVP-A_4INc5wJN-orbiQSSVEGicjA9S-snZFGG9s8YcUWQWnbvz4yk4IYmAXG9Sg';
 
        $fields = array(
            'registration_ids' => array($token),
            'priority' => 10,
            'notification' => $notification_arr,
            'data' => $data_arr
        );
        // echo '<pre>';
        // print_r($fields);die();
        $headers = array(
            'Authorization:key=' . $GOOGLE_API_KEY,
            'Content-Type:application/json'
        );  
         
        // Open connection  
        $ch = curl_init(); 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post   
        $result = curl_exec($ch); 
        // Close connection      
        // print_r($result);die();
        curl_close($ch);
        return $result;
    }
        
}

