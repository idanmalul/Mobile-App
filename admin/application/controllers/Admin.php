<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin extends CI_Controller {
    
    function __construct() {

        parent::__construct();
        
        if (!$this->session->userdata('logged_in')) {

             redirect('login/index');

         }
         $this->session->userdata('admin_id');
//          update_user_info();
    }

    function index() {

        //________________________________Admin Dashboard_______________________________________//

        $this->load->view('admin/header');

        $this->load->view('admin/menu');

        $this->load->view('admin/index_view');

        $this->load->view('admin/footer');

    }

    function copy_users_into_members() {
        
        echo 'stop';die();
        $ch = array("user_id not in(723)" => NULL);
        $users = $this->project_model->get_data_where_condition('users', $ch);
        if (!empty($users)) {
            foreach ($users as $key => $value) {
                $data['u_id'] = $value->user_id;
                $data['name'] = $value->full_name;
                $data['email'] = $value->email;
                $data['username'] = $value->username;
             echo  $password = user_dauth($value->password, $value->user_id);
                $data['password'] = md5($password);
             echo '<br>';
                $data['created_at'] = date('Y-m-d H:i:s');
               $inserted_id = $this->project_model->insert_data('members', $data);
            }die();
            
        } else {
            // $this->session->set_flashdata('error', 'Favourite Already Exist!');
            // redirect('admin/favourite_list');
        }
}
    
    function favourite_list() {
        $data['favourite_list'] = $this->project_model->get_column_data_where('favourites', '', $where='',$orderby='created_at',$orderFormat="DESC");
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
            
            if (!empty($_FILES["favourite_image"]["name"])) {

                $config['upload_path'] = './uploads/favourite_images/';

                $config['allowed_types'] = 'gif|jpg|png|jpeg';

                $config['max_size'] = '';

                $config['min_width']  = '';

                $config['min_height']  = '';

                $file_extension = @end(explode(".", $_FILES["favourite_image"]["name"]));

                $new_extension = strtolower($file_extension);

                $today = time();

                $custom_name = "favourite_" . $today;

                $file_name = $custom_name . "." . $new_extension;

                $config['file_name'] = $file_name;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('favourite_image', $config)) {
                    
                    $data['error'] = 'Supported Media Type - gif, jpg, png, jpeg.';

                    $this->load->view('admin/header');
                    $this->load->view('admin/menu');
                    $this->load->view('admin/add/add_favourite', $data);
                    $this->load->view('admin/footer');
                    return false;
                }
                $data['favourite_image'] = $file_name;
            }else{
                $data['error'] = 'Please select favourite image.';
                
                $this->load->view('admin/header');
                $this->load->view('admin/menu');
                $this->load->view('admin/add/add_favourite', $data);
                $this->load->view('admin/footer');
                return false;
            }
            
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
            $favourite_id = $this->input->post('favourite_id');
            unset($data['submit']);
            unset($data['favourite_id']);
            
            if (!empty($_FILES["favourite_image"]["name"])) {

                $config['upload_path'] = './uploads/favourite_images/';

                $config['allowed_types'] = 'gif|jpg|png|jpeg';

                $config['max_size'] = '';

                $config['min_width']  = '';

                $config['min_height']  = '';

                $file_extension = @end(explode(".", $_FILES["favourite_image"]["name"]));

                $new_extension = strtolower($file_extension);

                $today = time();

                $custom_name = "favourite_" . $today;

                $file_name = $custom_name . "." . $new_extension;

                $config['file_name'] = $file_name;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('favourite_image', $config)) {
                    
//                    $data['error'] = 'Supported Media Type - gif, jpg, png, jpeg.';
//
//                    $this->load->view('admin/header');
//                    $this->load->view('admin/menu');
//                    $this->load->view('admin/add/add_favourite', $data);
//                    $this->load->view('admin/footer');
//                    return false;
                    
                    $this->session->set_flashdata('error', 'Supported Media Type - gif, jpg, png, jpeg.');
//                    redirect('admin/favourite_list');
                    redirect('admin/edit_favourite/'.$favourite_id);
                    
                }
                $data['favourite_image'] = $file_name;
            }
            
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
    
    /* Company Section : Start */
    
        function company_list() {
        $data['company_list'] = $this->project_model->get_column_data_where('company', '', $where='',$orderby='created_at',$orderFormat="DESC");
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/listing/company_list', $data);
        $this->load->view('admin/footer');
    }

    function add_company() {
        if ($this->input->post('submit')) {
            $data = $this->input->post();
            unset($data['submit']);
            $admin_id = $this->session->userdata('admin_id');
            /*
            if (!empty($_FILES["favourite_image"]["name"])) {

                $config['upload_path'] = './uploads/favourite_images/';

                $config['allowed_types'] = 'gif|jpg|png|jpeg';

                $config['max_size'] = '';

                $config['min_width']  = '';

                $config['min_height']  = '';

                $file_extension = @end(explode(".", $_FILES["favourite_image"]["name"]));

                $new_extension = strtolower($file_extension);

                $today = time();

                $custom_name = "favourite_" . $today;

                $file_name = $custom_name . "." . $new_extension;

                $config['file_name'] = $file_name;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('favourite_image', $config)) {
                    
                    $data['error'] = 'Supported Media Type - gif, jpg, png, jpeg.';

                    $this->load->view('admin/header');
                    $this->load->view('admin/menu');
                    $this->load->view('admin/add/add_favourite', $data);
                    $this->load->view('admin/footer');
                    return false;
                }
                $data['favourite_image'] = $file_name;
            }else{
                $data['error'] = 'Please select favourite image.';
                
                $this->load->view('admin/header');
                $this->load->view('admin/menu');
                $this->load->view('admin/add/add_favourite', $data);
                $this->load->view('admin/footer');
                return false;
            }
            */
            $data['created_by'] = $admin_id;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['company_status'] = 1;
            $ch = array("company_name" => strtoupper($this->input->post("company_name")));
            $rec = $this->project_model->get_data_where_condition('company', $ch);
            if (empty($rec)) {
                $company_id = $this->project_model->insert_data('company', $data);
                if ($company_id > 0) {
                    $this->session->set_flashdata('status', 'Company added successfully');
                    redirect('admin/company_list');
                } else {
                    $this->session->set_flashdata('error', 'Error in insertion');
                    redirect('admin/company_list');
                }
            } else {
                $this->session->set_flashdata('error', 'Company Already Exist!');
                redirect('admin/company_list');
            }
        } else {
            $this->load->view('admin/header');
            $this->load->view('admin/menu');
            $this->load->view('admin/add/add_company');
            $this->load->view('admin/footer');
        }
    }

    function edit_company() {
        if ($this->input->post('submit')) {
            $data = $this->input->post();
            $company_id = $this->input->post('company_id');
            unset($data['submit']);
            unset($data['company_id']);
            /*
            if (!empty($_FILES["favourite_image"]["name"])) {

                $config['upload_path'] = './uploads/favourite_images/';

                $config['allowed_types'] = 'gif|jpg|png|jpeg';

                $config['max_size'] = '';

                $config['min_width']  = '';

                $config['min_height']  = '';

                $file_extension = @end(explode(".", $_FILES["favourite_image"]["name"]));

                $new_extension = strtolower($file_extension);

                $today = time();

                $custom_name = "favourite_" . $today;

                $file_name = $custom_name . "." . $new_extension;

                $config['file_name'] = $file_name;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('favourite_image', $config)) {
                    
//                    $data['error'] = 'Supported Media Type - gif, jpg, png, jpeg.';
//
//                    $this->load->view('admin/header');
//                    $this->load->view('admin/menu');
//                    $this->load->view('admin/add/add_favourite', $data);
//                    $this->load->view('admin/footer');
//                    return false;
                    
                    $this->session->set_flashdata('error', 'Supported Media Type - gif, jpg, png, jpeg.');
//                    redirect('admin/favourite_list');
                    redirect('admin/edit_favourite/'.$favourite_id);
                    
                }
                $data['favourite_image'] = $file_name;
            }
            */
	    $ch = array("company_name" => strtoupper($this->input->post("company_name")), 'id !=' => $this->input->post('company_id'));
            $rec = $this->project_model->get_data_where_condition('company', $ch);
            if (empty($rec)) {
                $where = $where = array('id' => $this->input->post('company_id'));
                $edit = $this->project_model->update_data('company', $data, $where);
                $this->session->set_flashdata('status', 'Company updated successfully!');
                redirect('admin/company_list');
            } else {
                $this->session->set_flashdata('error', 'Company Already Exist!');
                redirect('admin/company_list');
            }

        } else {
            $company_id = $this->uri->segment(3);
            if (!empty($company_id)) {
                $where = array('id' => $company_id);
                $company = $this->project_model->get_column_data_where('company', '', $where);
                if (!empty($company)) {
                    $data['company'] = $company;
                    $this->load->view('admin/header');
                    $this->load->view('admin/menu');
                    $this->load->view('admin/add/add_company', $data);
                    $this->load->view('admin/footer');
                } else {
                    redirect('admin/company_list');
                }
            } else {
                redirect('admin/company_list');
            }
        }
    }
    /* Company Section : End */
    
     function user_list()
    {

        $data['user_list'] = $this->project_model->get_column_data_where('users', '', $where='',$orderby='created_at',$orderFormat="DESC");
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/listing/user_list_view', $data);
        $this->load->view('admin/footer');
    }

    function member_list()
    {
//         user_eauth_all();die();
//        $where = array('user_id'=>34);
//        $data['user_list'] = $this->project_model->get_column_data_where('users', '', $where='',$orderby='created_at',$orderFormat="DESC");
        /*
        $user_id = $data['user_list'][0]->user_id;
        $id1 = $data['user_list'][0]->username;
        $plaintext = base64_decode($data['user_list'][0]->password);
        $ecytext = $data['user_list'][0]->password;
        $id2 = $data['user_list'][0]->username;
//        user_eauth($plaintext,$user_id);
        user_dauth($ecytext);
        */
//       foreach ($data['user_list'] as $value) {
//           
//       }
        
//        get_encrypted_key($id1, $plaintext, $id2);
        $query = "SELECT u.*,m.name as member_name,m.username as member_username,m.email as member_email FROM users u INNER JOIN members m ON u.user_id=m.u_id ORDER BY m.id DESC";
            
        $data['user_list'] = $this->project_model->get_query_result($query);
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/listing/member_list_view', $data);
        $this->load->view('admin/footer');
    }
    
    function edit_user() {
        if ($this->input->post('submit')) {
            $data = $this->input->post();
            $user_id = $this->input->post('user_id');
            unset($data['submit']);
            unset($data['user_id']);
            
	    
            $where = $where = array('user_id' => $this->input->post('user_id'));
            $edit = $this->project_model->update_data('users', $data, $where);
            $this->session->set_flashdata('status', 'User updated successfully!');
            redirect('admin/user_list');


        } else {
            $user_id = $this->uri->segment(3);
            if (!empty($user_id)) {
                $where = array('user_id' => $user_id);
                $users = $this->project_model->get_column_data_where('users', '', $where);
                if (!empty($users)) {
                    $data['users'] = $users;
                    $this->load->view('admin/header');
                    $this->load->view('admin/menu');
                    $this->load->view('admin/edit/edit_user', $data);
                    $this->load->view('admin/footer');
                } else {
                    redirect('admin/user_list');
                }
            } else {
                redirect('admin/user_list');
            }
        }
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
        $data['story_list'] = $this->project_model->get_column_data_where('story', '', $where='',$orderby='created_at',$orderFormat="DESC");
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
            $video_duration = $this->input->post('video_duration');
            unset($data['video_duration']);
            // unset($data['submit']);
            if (!empty($_FILES["image"]["name"])) {
                
                if($video_duration > "00:15") {
//                    $this->session->set_flashdata('error', 'Please upload less than 15 seconds video only');
//                    redirect('admin/edit_story/'.$story_id);
                    $data['error'] = 'Please upload less than 15 seconds video only';
               
                
                $this->load->view('admin/header');
                $this->load->view('admin/menu');
                $this->load->view('admin/add/add_story_view', $data);
                $this->load->view('admin/footer');
                return false;
                }

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
            $story_id = $this->input->post('story_id');
            unset($data['story_id']);
            $video_duration = $this->input->post('video_duration');
            unset($data['video_duration']);

            if (!empty($_FILES["image"]["name"])) {
                
                if($video_duration > "00:15") {
                    $this->session->set_flashdata('error', 'Please upload less than 15 seconds video only');
                    redirect('admin/edit_story/'.$story_id);
                }

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
                    
//                $data['error'] = 'Supported Media Type - gif, jpg, png, jpeg, webm, mp4, 3gp, ogg.';
//               
//                
//                $this->load->view('admin/header');
//                $this->load->view('admin/menu');
//                $this->load->view('admin/add/add_story_view', $data);
//                $this->load->view('admin/footer');
//                return false;
                    $this->session->set_flashdata('error', 'Supported Media Type - gif, jpg, png, jpeg, webm, mp4, 3gp, ogg.');
                    redirect('admin/edit_story/'.$story_id);
                }
                
                if($file_extension == 'gif' || $file_extension == 'jpg' || $file_extension == 'png' || $file_extension == 'jpeg'){
                    $data['media_type'] = 1;
                }else{
                    $data['media_type'] = 2;
                }
                $data['story_image'] = $file_name;

            }
        
            

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
        $query = "SELECT ps.post_id,u.user_id,u.full_name,s.id as story_id,s.media_type,s.story_image,s.ticket_link,s.story_title,s.story_description,ps.story_viewer_count FROM story s INNER JOIN post_story ps ON ps.story_id=s.id INNER JOIN users u ON u.user_id=ps.user_id ORDER BY post_id DESC";
            
        $data['post_story_list'] = $this->project_model->get_query_result($query);
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/listing/post_story_list_view', $data);
        $this->load->view('admin/footer');
    }
    
    
    /* Start campaign section */
    
    function campaign_list()
    {
//        $current_datetime = date('Y-m-d H:i:s');
        $where= array('static_campaign_status'=>0);
        $data['campaign_list'] = $this->project_model->get_column_data_where('campaign', '', $where,$orderby='created_at',$orderFormat="DESC");
//        $current_datetime = date('Y-m-d H:i:s');
//        $query = "SELECT DISTINCT(csr.campaign_id) as active_camp,c.* FROM campaign c INNER JOIN campaign_story_relationship csr ON csr.campaign_id=c.id WHERE csr.schedule_date_time >= '$current_datetime' ORDER BY created_at DESC";
//        $data['campaign_list'] = $this->project_model->get_query_result($query);
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/listing/campaign_list_view', $data);
        $this->load->view('admin/footer');
    }

    function add_campaign()
    {
//         $user =  $this->input->post('user_hidddn');
//         echo "<pre>"; print_r($user); die();

        if ($this->input->post())
        {
            $data = $this->input->post();
//            echo '<pre>';
//            print_r($data);
            $story_arr =  $this->input->post('story_id_arr');
            $schedule_date_arr =  $this->input->post('schedule_date_arr');
            $schedule_time_arr =  $this->input->post('schedule_time_arr');
//            foreach($story_arr as $key => $v)
//                    { 
//                echo $v.'   '.$schedule_date_arr[$key].'    '.$schedule_time_arr[$key];
//                echo '<br/>';
//                        
//                    }
//            die();
            $admin_id = $this->session->userdata('admin_id');
            unset($data['submit']);
            unset($data['story_id_arr']);
            unset($data['schedule_date_arr']);
            unset($data['schedule_time_arr']);

            $data['created_by']= $admin_id;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['campaign_remaining_count'] = $data['campaign_approved_user_limit'];
            $campaign_id = $this->project_model->insert_data('campaign', $data);

            if ($campaign_id > 0) {

    //                $user =  $this->input->post('user_hidden');


                //$u_exp = explode(",", $user);
                // echo "<pre>"; print_r($u_exp); die();
                if(!empty($story_arr))
                {

                    foreach($story_arr as $key => $v)
                    { 
                        if(!empty($v) && !empty($schedule_date_arr[$key]) && !empty($schedule_time_arr[$key])){
                            $schedule_date_time = $schedule_date_arr[$key].' '.$schedule_time_arr[$key];
                            $campaign_data['story_id'] = $v;
                            $campaign_data['schedule_date_time'] = date('Y-m-d H:i:s', strtotime($schedule_date_time));
                            $campaign_data['campaign_id'] = $campaign_id;
                            $campaign_data['created_by'] = $admin_id;
                            $campaign_data['created_at'] = date('Y-m-d H:i:s');
                            $this->project_model->insert_data('campaign_story_relationship', $campaign_data); 
                        }
                        
                    }
                }

                $this->session->set_flashdata('status', 'Campaign added successfully.');
                //redirect('admin/edit_campaign/'.$campaign_id);
                redirect('admin/campaign_list');

            } else {

                $this->session->set_flashdata('error', 'Error in insertion');
                redirect("admin/campaign_list");
            }

        } else {
            $this->load->view('admin/header');
            $this->load->view('admin/menu');
            $this->load->view('admin/add/add_campaign_view');
            $this->load->view('admin/footer');
        }

    }
    
    function edit_campaign()
    {
        if ($this->input->post()) 
        {
            $data = $this->input->post();
//            echo '<pre>';
//            print_r($data);die();
            $campaign_id = $this->input->post('campaign_id');
            
            $admin_id = $this->session->userdata('admin_id');
            unset($data['submit']);
            unset($data['story_id_arr_update']);
            unset($data['schedule_date_arr_update']);
            unset($data['schedule_time_arr_update']);
            unset($data['story_id_arr']);
            unset($data['schedule_date_arr']);
            unset($data['schedule_time_arr']);
            unset($data['campaign_id']);
            
            $where_ = array('id' => $campaign_id);

            $camp_detail = $this->project_model->get_column_data_where('campaign', '', $where);

            if (!empty($camp_detail)) {
                $approved_user_limit = $camp_detail[0]->campaign_approved_user_limit;//100;200;50
                $remaining_count = $camp_detail[0]->campaign_remaining_count;//98;198;48
                $accepted_count = $camp_detail[0]->campaign_accepted_count;//2;2;2
                if($data['campaign_approved_user_limit']>$accepted_count){
                    $new_remaining_count = $data['campaign_approved_user_limit'] - $accepted_count;
                    $data['campaign_remaining_count'] = $new_remaining_count;
                }elseif($data['campaign_approved_user_limit']<$accepted_count){
                    $this->session->set_flashdata('error', 'You can not set limit less than approved users which is : '.$accepted_count);
//                    $new_remaining_count = $data['approved_user_limit'] - $accepted_count;
//                    $data['remaining_count'] = $new_remaining_count;
                }
            }
            
            $story_arr_update =  $this->input->post('story_id_arr_update');
            $schedule_date_arr_update =  $this->input->post('schedule_date_arr_update');
            $schedule_time_arr_update =  $this->input->post('schedule_time_arr_update');
            
            $story_arr =  $this->input->post('story_id_arr');
            $schedule_date_arr =  $this->input->post('schedule_date_arr');
            $schedule_time_arr =  $this->input->post('schedule_time_arr');
            
            $where = array('id' => $campaign_id);
            $edit = $this->project_model->update_data('campaign',$data,$where);
            $user =  $this->input->post('send_user_id');

                //$u_exp = explode(",", $user);
                // echo "<pre>"; print_r($u_exp); die();
                $campaign_schedules = get_campaign_schedule_by_camp_id($campaign_id);
                if(!empty($campaign_schedules))
                {

                    foreach($campaign_schedules as $key => $v_update)
                    { 
                        // !empty($story_arr_update[$key]) && 
                        if(!empty($schedule_date_arr_update[$key]) && !empty($schedule_time_arr_update[$key])){
                            $schedule_date_time_update = $schedule_date_arr_update[$key].' '.$schedule_time_arr_update[$key];
//                            $campaign_update_data['story_id'] = $story_arr_update[$key];
                            $campaign_update_data['schedule_date_time'] = date('Y-m-d H:i:s', strtotime($schedule_date_time_update));
//                            $campaign_update_data['campaign_id'] = $campaign_id;
//                            $campaign_update_data['created_by'] = $admin_id;
//                            $campaign_data['created_at'] = date('Y-m-d H:i:s');
                            $where_update = array('id'=>$v_update->id);
//                            $this->project_model->update_records('campaign_story_relationship', $where_update, $campaign_update_data); 
                            $this->project_model->update_data('campaign_story_relationship',$campaign_update_data,$where_update);
                        }
                        
                    }
                }
                
                if(!empty($story_arr))
                {

                    foreach($story_arr as $key => $v)
                    { 
                        if(!empty($v) && !empty($schedule_date_arr[$key]) && !empty($schedule_time_arr[$key])){
                            $schedule_date_time = $schedule_date_arr[$key].' '.$schedule_time_arr[$key];
                            $campaign_data['story_id'] = $v;
                            $campaign_data['schedule_date_time'] = date('Y-m-d H:i:s', strtotime($schedule_date_time));
                            $campaign_data['campaign_id'] = $campaign_id;
                            $campaign_data['created_by'] = $admin_id;
                            $campaign_data['created_at'] = date('Y-m-d H:i:s');
                            $this->project_model->insert_data('campaign_story_relationship', $campaign_data); 
                        }
                        
                    }
                }
            $this->session->set_flashdata('success', 'Campaign updated successfully');
            redirect('admin/edit_campaign/'.$campaign_id);
           


        } else {

            $campaign_id = $this->uri->segment(3);

            if (!empty($campaign_id)) {

                $where = array('id' => $campaign_id);

                $campaign_detail = $this->project_model->get_column_data_where('campaign', '', $where);

                if (!empty($campaign_detail)) {

                    $data['campaign_detail'] = $campaign_detail;
                    
                    $this->load->view('admin/header');
                    $this->load->view('admin/menu');
                    $this->load->view('admin/edit/edit_campaign_view', $data);

                    $this->load->view('admin/footer');

                } else {

                    redirect('admin/campagin_list');

                }

            } else {

                redirect('admin/campagin_list');

            }

        }
    }
    
    function send_campaign_to_user()
    {
//         $data =  $this->input->post();
//         echo "<pre>"; print_r($data); 
         
//         print_r($result);
//         die();
        $flag =0;
        if ($this->input->post())
        {
            $data = $this->input->post();
            $campaign_id = $this->input->post('campaign_id');
            $campaign_stories = get_story_by_campaign_id($campaign_id);
            $admin_id = $this->session->userdata('admin_id');
             unset($data['users_ids']);
            
            $data['created_by']= $admin_id;
            $data['created_at'] = date('Y-m-d H:i:s');

            $user =  $this->input->post('users_ids');
            
            
            // echo "<pre>"; print_r($u_exp); die();
            if(!empty($user))
            {
                $alphnumeric = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                $rand_code = substr(str_shuffle($alphnumeric),0,8);
                $unique_id = $rand_code.'_'.time();
                $u_exp = explode(",", $user);
//                print_r($u_exp);//die();
                if(!empty($u_exp)){
                    foreach($u_exp as $v)
                    { 
                        $data['user_id'] = $v;
                        
                        if(!empty($campaign_stories)){
                            $ch = array("campaign_id" => $campaign_id,'user_id'=>$v);
                            $rec = $this->project_model->get_data_where_condition('story_user', $ch);
                            if (!empty($rec)) {
//                                $this->session->set_flashdata('error', 'Campaign already sent to this user!');
//                                redirect('users');
                                $flag =1;
                            } else {
                                $flag =2;
//                            }
//                            print_r($campaign_stories);die();
                            $first_story_id = $campaign_stories[0]->story_id;
                            $reward = $campaign_stories[0]->reward;
                            $campaign_description = $campaign_stories[0]->campaign_description;
                            foreach ($campaign_stories as $k => $s) {
                                $unique_approval_update_id = $rand_code.'_'.time().'_'.$s->campaign_id.'_'.$s->story_id;
                                $data['story_id'] = $s->story_id;
                                $data['campaign_story_relationship_id'] = $s->campaign_story_relationship_id;
                                $data['unique_send_id'] = $unique_id;
                                $data['unique_approval_update_id'] = $unique_approval_update_id;
                                $inserted_id = $this->project_model->insert_data('story_user', $data);
                                if($k == 0){
                                    $first_inserted_id = $inserted_id;
                                }
                                
                            }
                            
                            if(!empty($inserted_id)){
                                    $user_detail = get_user_by_user_id($v);
                                    $token = trim($user_detail->gcm_id);
                                    //echo $token;die();
                                    $story_detail = get_story_by_story_id($first_story_id);
                                    if(!empty($story_detail->story_image)){
//                                        $story_detail->story_image= base_url('uploads/story_images/').$story_detail->story_image;
                                        if($story_detail->media_type == 2){
                                            $story_detail->story_video= base_url('uploads/story_images/').$story_detail->story_image;
                                $fname = $story_detail->story_image;
                                $filename = pathinfo($fname, PATHINFO_FILENAME);
                                $video = base_url('uploads/story_images/').$story_detail->story_image;
//                                $video = STORY_IMAGES.'story_1545479211.mp4';
                                $thumbnail = './uploads/story_images/video_thumbs/'.$filename.'.jpg';


                            shell_exec("ffmpeg -i $video -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vcodec mjpeg -s 400x400 -f mjpeg $thumbnail 2>&1");
//                            ob_end_clean();
                            $story_detail->story_video_thumb = base_url('uploads/story_images/').'video_thumbs/'.$filename.'.jpg';
                            $story_detail->story_image= base_url('uploads/story_images/').$story_detail->story_image;
                            }else{
                                $story_detail->story_image= base_url('uploads/story_images/').$story_detail->story_image;
                            }
                                    }else{
                                        $story_detail->story_image= '';
                                    }
                                    if(!empty($story_detail->created_at)){
                                        $story_detail->created_at= date('d/m', strtotime($story_detail->created_at));
                                    }else{
                                        $story_detail->created_at= '';
                                    }
                                    $story_detail->rtl_status= 2;
                                    $story_detail->description_type= 1;
                                    $story_detail->sent_primary_id=$first_inserted_id;
                                    $story_detail->campaign_id=$campaign_id;
                                    $story_detail->story_id=$first_story_id;
                                    
                                    $story_detail->reward=$reward;
                                    $story_detail->campaign_description=$campaign_description;
                                    
                                    $query_campaign_schedule = "SELECT csr.*,DAYNAME(DATE(csr.schedule_date_time)) as dayname,DATE_FORMAT(csr.schedule_date_time, '%W %d.%m.%Y | %h:%i %p') as schedule_info FROM campaign c INNER JOIN campaign_story_relationship csr ON csr.campaign_id=c.id WHERE csr.campaign_id='$campaign_id' AND csr.story_id='$first_story_id'";
                $get_campaign_schedule = $this->project_model->get_query_result($query_campaign_schedule);
                
                $approved_query = "SELECT user_details.*,total_approved_user_count.total_approved_count FROM ((SELECT u.user_id,u.first_name,u.last_name,u.full_name,u.username,u.profile_pic_url,count(DISTINCT(su.user_id)) As approved_users_count,su.story_id,su.created_at FROM users u INNER JOIN story_user su ON su.user_id=u.user_id WHERE su.story_id='$first_story_id' AND story_accept_status=1 AND upload_status=1 GROUP BY su.user_id ORDER BY su.id DESC LIMIT 3) as user_details,(SELECT count(DISTINCT(su.user_id)) As total_approved_count,su.story_id FROM users u INNER JOIN story_user su ON su.user_id=u.user_id WHERE su.story_id='$first_story_id' AND story_accept_status=1 AND upload_status=1) as total_approved_user_count)";
                $get_story_approved_users = $this->project_model->get_query_result($approved_query);
                
                        if(!empty($story_detail->story_description)){
                            
                            $story_detail->short_description= $this->shorter($story_detail->story_description, 50);
                            $story_detail->story_description = $this->shorter($story_detail->story_description, 50);
                            
                        }else{
                            $story_detail->short_description;
                        }
//	    		unset($story_detail->story_description);
                        if(!empty($get_campaign_schedule)){
                            $story_detail->campaign_schedule_count =count($get_campaign_schedule);
                            $story_detail->campaign_schedule=$get_campaign_schedule;
                        }else{
                            $story_detail->campaign_schedule_count =0;
                            $story_detail->campaign_schedule=array();
                        }
                        if(!empty($get_story_approved_users)){
                            $story_detail->approved_users=$get_story_approved_users;
                        }else{
                            $story_detail->approved_users=array();
                        }
//                        $value['rtl_status'] = 2;
                
                                    $story_detail_array =  (array) $story_detail;
                                    $notification_title = "Upcoming Event";
                                    $message = $story_detail->story_title;
                                   // $token = "cIqV5HNuMrw:APA91bEXUF3QjlldNpng4o7VX9AKzFD3gCbp48CIHs4ZFjsDutVr6hySTTeXjZOG8QdXCv3mN-uoCsCZER63t7gHXio_kfoPspftiT9OWfCALynT0Nx82v8oV7RgZkQCYhI2tthHj4PZ";
                                   // $message = "Hello Mohan";
                                   // , 'data'=> base64_encode($story_detail->story_title)
                                   $query = "SELECT count(su.read_status) as badge_count FROM story_user su WHERE su.user_id='$v' AND read_status=0";

                                   $users_notification = $this->project_model->get_query_result($query);
                                   $tBadge = ($users_notification[0]->badge_count) ? $users_notification[0]->badge_count : 0 ;
                                //    ,'badge' => $tBadge
                                    $noti_arr = array('title' => $notification_title, 'body' =>  $message ,'sound'=>'Default','image'=> base_url('assets/images/LOGO.png'),'badge' => $tBadge,
                                        'show_in_background'=> true,'show_in_foreground'=> true);
                                    $custom_notification = array(
                                        'title' => $notification_title, 
                                        'body' =>  $message 
                                    );
                                    $data_arr = array(
                                        'title' => $notification_title, 
                                        'body' =>  $message,
                                        'badge' => $tBadge,
                                        //'icon' => 'ic_launcher',
                                        'custom_notification' => $custom_notification,
                                        'story_detail'=> $story_detail_array,
                                        'show_in_background'=> true,
                                        'show_in_foreground'=> true
                                    );
                                    
                                    $notification_data=array('notification_user_id'=>$data['user_id'],'notification_story_id'=>$first_story_id,'notification_campaign_id'=>$campaign_id,'notification_title'=>$notification_title,'notification_message'=>$message,'notification_datetime'=>date('Y-m-d H:i:s'));
                                    $notification_id = $this->project_model->insert_data('notification', $notification_data);
                                    if($user_detail->user_notification_status == 1){
                                        $res = $this->sendPushNotificationToFCMSever($token,$noti_arr,$data_arr);
                                        $errdata = array('created_at'=>date('Y-m-d H:i:s'));
                                        $errdata['log_data'] =$res;
                                        $errdata['user_id'] =$data['user_id'];
                                        $err_inserted_id = $this->project_model->insert_data('notification_error_log', $errdata);
                                    }
//                                    echo '<pre>';
//                                    print_r($noti_arr);
//                                    print_r($data_arr);die();
                                }
                                } // end of if condition for duplication
                        }
                        // echo "<pre>"; print_r($user_data['user_id']); die();
                        
                    }
                    if(!empty($inserted_id)){
                        
                        $this->session->set_flashdata('status', 'Story send successfully.');
                        redirect('users');
                    }else{
                        if($flag == 1){
                            $this->session->set_flashdata('error', 'Campaign already sent to this user.');
                            redirect('users');
                        }else{
                            $this->session->set_flashdata('error', 'Error in insertion.');
                            redirect('users');
                        }
//                        $this->session->set_flashdata('error', 'Error in insertion.');
//                        redirect('users');
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
    function campaign_detail_list()
    {
    $campaign_id = $this->uri->segment(3);
    //$query = "SELECT su.*,c.campaign_name,c.campaign_description,s.story_title,s.story_image,s.story_description,s.media_type,u.first_name,u.last_name,u.full_name,u.username,u.profile_pic_url,csr.schedule_date_time FROM story s INNER JOIN story_user su ON su.story_id=s.id INNER JOIN users u ON u.user_id=su.user_id INNER JOIN campaign_story_relationship csr ON csr.campaign_id=su.campaign_id INNER JOIN campaign c ON c.id=su.campaign_id WHERE su.campaign_id='$campaign_id' ORDER BY su.id DESC";
    $query = "SELECT su.*,c.campaign_name,c.campaign_description,c.reward_number,c.reward_currency,c.reward,s.story_title,s.story_image,s.story_description,s.media_type,u.first_name,u.last_name,u.full_name,u.username,u.profile_pic_url,u.paypal_email,csr.schedule_date_time,u.follower_count FROM story s INNER JOIN story_user su ON su.story_id=s.id INNER JOIN users u ON u.user_id=su.user_id INNER JOIN campaign_story_relationship csr ON csr.id=su.campaign_story_relationship_id INNER JOIN campaign c ON c.id=su.campaign_id WHERE su.campaign_id='$campaign_id' ORDER BY su.id DESC";
            
        $data['campaign_detail_list'] = $this->project_model->get_query_result($query);
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/listing/campaign_detail_list_view', $data);
        $this->load->view('admin/footer');
    }
    /*
    function create_payout()
    {
//        create_payouts();die();
        $sent_primary_id = $this->uri->segment(3);
    
        $query = "SELECT su.*,c.campaign_name,c.campaign_description,c.reward_number,c.reward_currency,c.reward,s.story_title,s.story_image,s.story_description,s.media_type,u.first_name,u.last_name,u.full_name,u.username,u.profile_pic_url,csr.schedule_date_time,u.follower_count,u.paypal_email,(su.story_viewer_count*c.reward_number) as total_reward FROM story s INNER JOIN story_user su ON su.story_id=s.id INNER JOIN users u ON u.user_id=su.user_id INNER JOIN campaign_story_relationship csr ON csr.id=su.campaign_story_relationship_id INNER JOIN campaign c ON c.id=su.campaign_id WHERE su.id='$sent_primary_id' ORDER BY su.id DESC";
            
        $user_campaign_detail = $this->project_model->get_query_result($query);
//        echo '<pre>';
//        print_r($user_campaign_detail);die();
        if(!empty($user_campaign_detail)){
            $user_id = $user_campaign_detail[0]->user_id;
            $campaign_id = $user_campaign_detail[0]->campaign_id;
                $sent_primary_id = $user_campaign_detail[0]->id;
                $email = $user_campaign_detail[0]->paypal_email;
                $amount = $user_campaign_detail[0]->total_reward;
                $currency = $user_campaign_detail[0]->reward_currency;
               $response = create_payouts($sent_primary_id, $email, $amount, $currency, $user_id, $campaign_id);
               if($response>0){
                    $this->session->set_flashdata('status', 'Payout created successfully.');
                    redirect('admin/campaign_detail_list/'.$campaign_id);
               }else{
                   $this->session->set_flashdata('error', 'Something went wrong during payout creation.');
                   redirect('admin/campaign_detail_list/'.$campaign_id);
               }
        }
        
    }
    
    */
    
    function create_payout()
    {
        $data = $this->input->post();
        if (!empty($data))
        {
            $sent_primary_id = $this->input->post('sent_primary_id');
            $amount = $this->input->post('amount');
            
            $query = "SELECT su.*,c.campaign_name,c.campaign_description,c.reward_number,c.reward_currency,c.reward,s.story_title,s.story_image,s.story_description,s.media_type,u.first_name,u.last_name,u.full_name,u.username,u.profile_pic_url,csr.schedule_date_time,u.follower_count,u.paypal_email,(su.story_viewer_count*c.reward_number) as total_reward FROM story s INNER JOIN story_user su ON su.story_id=s.id INNER JOIN users u ON u.user_id=su.user_id INNER JOIN campaign_story_relationship csr ON csr.id=su.campaign_story_relationship_id INNER JOIN campaign c ON c.id=su.campaign_id WHERE su.id='$sent_primary_id' ORDER BY su.id DESC";
            
        $user_campaign_detail = $this->project_model->get_query_result($query);

        if(!empty($user_campaign_detail)){
            $user_id = $user_campaign_detail[0]->user_id;
            $campaign_id = $user_campaign_detail[0]->campaign_id;
                $sent_primary_id = $user_campaign_detail[0]->id;
                $email = $user_campaign_detail[0]->paypal_email;
               // $amount = $user_campaign_detail[0]->total_reward;
                $currency = $user_campaign_detail[0]->reward_currency;
               $response = create_payouts($sent_primary_id, $email, $amount, $currency, $user_id, $campaign_id);
               if($response>0){
                    $this->session->set_flashdata('status', 'Payout created successfully.');
                    redirect('admin/campaign_detail_list/'.$campaign_id);
               }else{
                   $this->session->set_flashdata('error', 'Something went wrong during payout creation.');
                   redirect('admin/campaign_detail_list/'.$campaign_id);
               }
        }
        
        }else{
            $this->session->set_flashdata('error', 'Something went wrong during payout creation.');
            redirect('admin/campaign_list/');
        }

        
    }
    /* End campaign section */

    /* Start static campaign section */
    
    function static_campaign_list()
    {
//        $current_datetime = date('Y-m-d H:i:s');
        $where= array('static_campaign_status'=>1);
        $data['campaign_list'] = $this->project_model->get_column_data_where('campaign', '', $where,$orderby='created_at',$orderFormat="DESC");
//        $query = "SELECT DISTINCT(csr.campaign_id) as active_camp,c.* FROM campaign c INNER JOIN campaign_story_relationship csr ON csr.campaign_id=c.id WHERE csr.schedule_date_time >= '$current_datetime' ORDER BY created_at DESC";
//        $data['campaign_list'] = $this->project_model->get_query_result($query);
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/listing/static_campaign_list_view', $data);
        $this->load->view('admin/footer');
    }

    function add_static_campaign()
    {
        if ($this->input->post())
        {
            $data = $this->input->post();
            $story_arr =  $this->input->post('story_id_arr');
//            $schedule_date_arr =  $this->input->post('schedule_date_arr');
//            $schedule_time_arr =  $this->input->post('schedule_time_arr');

            $admin_id = $this->session->userdata('admin_id');
            unset($data['submit']);
            unset($data['story_id_arr']);
            unset($data['schedule_date_arr']);
            unset($data['schedule_time_arr']);

            $data['created_by']= $admin_id;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['campaign_remaining_count'] = $data['campaign_approved_user_limit'];
            $data['static_campaign_status'] = 1;
            $data['country_code'] = 'IN';
            
            $where = array('static_campaign_status'=>1);
            $get_static_campaigns = $this->project_model->get_column_data_where('campaign', '', $where);
            if(count($get_static_campaigns)>=3){
                    $data['error'] = 'You have permission to set three static campaings not more than that.';
//                    $this->load->view('admin/header');
//                    $this->load->view('admin/menu');
//                    $this->load->view('admin/add/add_static_campaign_view', $data);
//                    $this->load->view('admin/footer');
                    $this->session->set_flashdata('error', 'You have permission to set three static campaings not more than that.');
                    redirect("admin/static_campaign_list");
                    return false;
            }
            $campaign_id = $this->project_model->insert_data('campaign', $data);

            if ($campaign_id > 0) {

                if(!empty($story_arr))
                {
                    foreach($story_arr as $key => $v)
                    { 
                        if(!empty($v)){
                           // $schedule_date_time = $schedule_date_arr[$key].' '.$schedule_time_arr[$key];
                            $campaign_data['story_id'] = $v;
                       //     $campaign_data['schedule_date_time'] = date('Y-m-d H:i:s', strtotime($schedule_date_time));
                            $campaign_data['campaign_id'] = $campaign_id;
                            $campaign_data['created_by'] = $admin_id;
                            $campaign_data['created_at'] = date('Y-m-d H:i:s');
                            $this->project_model->insert_data('campaign_story_relationship', $campaign_data); 
                        }
                        
                    }
                }

                $this->session->set_flashdata('status', 'Campaign added successfully.');
                redirect('admin/static_campaign_list');

            } else {

                $this->session->set_flashdata('error', 'Error in insertion');
                redirect("admin/static_campaign_list");
            }

        } else {
            $this->load->view('admin/header');
            $this->load->view('admin/menu');
            $this->load->view('admin/add/add_static_campaign_view');
            $this->load->view('admin/footer');
        }

    }
    
    function edit_static_campaign()
    {
        if ($this->input->post()) 
        {
            $data = $this->input->post();
            $campaign_id = $this->input->post('campaign_id');
            $admin_id = $this->session->userdata('admin_id');
            unset($data['submit']);
            unset($data['story_id_arr_update']);
//            unset($data['schedule_date_arr_update']);
//            unset($data['schedule_time_arr_update']);
            unset($data['story_id_arr']);
//            unset($data['schedule_date_arr']);
//            unset($data['schedule_time_arr']);
            unset($data['campaign_id']);
            
            $where_ = array('id' => $campaign_id);

            $camp_detail = $this->project_model->get_column_data_where('campaign', '', $where);

            if (!empty($camp_detail)) {
                $approved_user_limit = $camp_detail[0]->campaign_approved_user_limit;//100;200;50
                $remaining_count = $camp_detail[0]->campaign_remaining_count;//98;198;48
                $accepted_count = $camp_detail[0]->campaign_accepted_count;//2;2;2
                if($data['campaign_approved_user_limit']>$accepted_count){
                    $new_remaining_count = $data['campaign_approved_user_limit'] - $accepted_count;
                    $data['campaign_remaining_count'] = $new_remaining_count;
                }elseif($data['campaign_approved_user_limit']<$accepted_count){
                    $this->session->set_flashdata('error', 'You can not set limit less than approved users which is : '.$accepted_count);
//                    $new_remaining_count = $data['approved_user_limit'] - $accepted_count;
//                    $data['remaining_count'] = $new_remaining_count;
                }
            }
            
            $story_arr_update =  $this->input->post('story_id_arr_update');
//            $schedule_date_arr_update =  $this->input->post('schedule_date_arr_update');
//            $schedule_time_arr_update =  $this->input->post('schedule_time_arr_update');
            
            $story_arr =  $this->input->post('story_id_arr');
            $schedule_date_arr =  $this->input->post('schedule_date_arr');
            $schedule_time_arr =  $this->input->post('schedule_time_arr');
            
            $where = array('id' => $campaign_id);
            $edit = $this->project_model->update_data('campaign',$data,$where);
            $user =  $this->input->post('send_user_id');

                
                $campaign_schedules = get_campaign_schedule_by_camp_id($campaign_id);
                if(!empty($campaign_schedules))
                {

                    foreach($campaign_schedules as $key => $v_update)
                    { 
                        // !empty($story_arr_update[$key]) && 
                        if(!empty($story_arr_update[$key])){
//                            $schedule_date_time_update = $schedule_date_arr_update[$key].' '.$schedule_time_arr_update[$key];
                            $campaign_update_data['story_id'] = $story_arr_update[$key];
//                            $campaign_update_data['schedule_date_time'] = date('Y-m-d H:i:s', strtotime($schedule_date_time_update));
//                            $campaign_update_data['campaign_id'] = $campaign_id;
//                            $campaign_update_data['created_by'] = $admin_id;
//                            $campaign_data['created_at'] = date('Y-m-d H:i:s');
                            $where_update = array('id'=>$v_update->id);
//                            $this->project_model->update_records('campaign_story_relationship', $where_update, $campaign_update_data); 
                            $this->project_model->update_data('campaign_story_relationship',$campaign_update_data,$where_update);
                        }
                        
                    }
                }
                
                if(!empty($story_arr))
                {

                    foreach($story_arr as $key => $v)
                    { 
                        if(!empty($v)){
//                            $schedule_date_time = $schedule_date_arr[$key].' '.$schedule_time_arr[$key];
                            $campaign_data['story_id'] = $v;
//                            $campaign_data['schedule_date_time'] = date('Y-m-d H:i:s', strtotime($schedule_date_time));
                            $campaign_data['campaign_id'] = $campaign_id;
                            $campaign_data['created_by'] = $admin_id;
                            $campaign_data['created_at'] = date('Y-m-d H:i:s');
                            $this->project_model->insert_data('campaign_story_relationship', $campaign_data); 
                        }
                        
                    }
                }
            $this->session->set_flashdata('success', 'Campaign updated successfully');
            redirect('admin/edit_static_campaign/'.$campaign_id);

        } else {

            $campaign_id = $this->uri->segment(3);

            if (!empty($campaign_id)) {

                $where = array('id' => $campaign_id);

                $campaign_detail = $this->project_model->get_column_data_where('campaign', '', $where);

                if (!empty($campaign_detail)) {

                    $data['campaign_detail'] = $campaign_detail;
                    
                    $this->load->view('admin/header');
                    $this->load->view('admin/menu');
                    $this->load->view('admin/edit/edit_static_campaign_view', $data);

                    $this->load->view('admin/footer');

                } else {

                    redirect('admin/static_campagin_list');

                }

            } else {

                redirect('admin/static_campagin_list');

            }

        }
    }
    
    function static_campaign_detail_list()
    {
    $campaign_id = $this->uri->segment(3);
    
    $query = "SELECT su.*,c.campaign_name,c.campaign_description,c.reward_number,c.reward_currency,c.reward,s.story_title,s.story_image,s.story_description,s.media_type,u.first_name,u.last_name,u.full_name,u.username,u.profile_pic_url,u.paypal_email,csr.schedule_date_time,u.follower_count FROM story s INNER JOIN story_user su ON su.story_id=s.id INNER JOIN users u ON u.user_id=su.user_id INNER JOIN campaign_story_relationship csr ON csr.id=su.campaign_story_relationship_id INNER JOIN campaign c ON c.id=su.campaign_id WHERE su.campaign_id='$campaign_id' ORDER BY su.id DESC";
            
        $data['campaign_detail_list'] = $this->project_model->get_query_result($query);
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/listing/static_campaign_detail_list_view', $data);
        $this->load->view('admin/footer');
    }
    /* End static campaign section */
    
/* Start Levels Section */
    
    function level_list() {
        $data['level_list'] = $this->project_model->get_column_data_where('levels', '', $where='',$orderby='created_at',$orderFormat="DESC");
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/listing/level_list_view', $data);
        $this->load->view('admin/footer');
    }

    function add_level() {
        if ($this->input->post('submit')) {
            $data = $this->input->post();
            unset($data['submit']);
            $admin_id = $this->session->userdata('admin_id');
            
            if (!empty($_FILES["level_icon"]["name"])) {

                $config['upload_path'] = './uploads/level_icons/';

                $config['allowed_types'] = 'gif|jpg|png|jpeg';

                $config['max_size'] = '';

                $config['min_width']  = '';

                $config['min_height']  = '';

                $file_extension = @end(explode(".", $_FILES["level_icon"]["name"]));

                $new_extension = strtolower($file_extension);

                $today = time();

                $custom_name = "level_icon_" . $today;

                $file_name = $custom_name . "." . $new_extension;

                $config['file_name'] = $file_name;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('level_icon', $config)) {
                    
                    $data['error'] = 'Supported Media Type - gif, jpg, png, jpeg.';

                    $this->load->view('admin/header');
                    $this->load->view('admin/menu');
                    $this->load->view('admin/add/add_level_view', $data);
                    $this->load->view('admin/footer');
                    return false;
                }
                $data['level_icon'] = $file_name;
            }else{
                $data['error'] = 'Please select level icon.';
                
                $this->load->view('admin/header');
                $this->load->view('admin/menu');
                $this->load->view('admin/add/add_level_view', $data);
                $this->load->view('admin/footer');
                return false;
            }
            $data['created_by'] = $admin_id;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['level_status'] = 1;
            $ch = array("level_name" => strtoupper($this->input->post("level_name")));
            $rec = $this->project_model->get_data_where_condition('levels', $ch);
            if (empty($rec)) {
                $level_id = $this->project_model->insert_data('levels', $data);
                if ($level_id > 0) {
                    $this->session->set_flashdata('status', 'Level added successfully');
                    redirect('admin/level_list');
                } else {
                    $this->session->set_flashdata('error', 'Error in insertion');
                    redirect('admin/level_list');
                }
            } else {
                $this->session->set_flashdata('error', 'Level Already Exist!');
                redirect('admin/level_list');
            }
        } else {
            $this->load->view('admin/header');
            $this->load->view('admin/menu');
            $this->load->view('admin/add/add_level_view');
            $this->load->view('admin/footer');
        }
    }

    function edit_level() {
        if ($this->input->post('submit')) {
            $data = $this->input->post();
            $level_id = $this->input->post('level_id');
            unset($data['submit']);
            unset($data['level_id']);
            
            if (!empty($_FILES["level_icon"]["name"])) {

                $config['upload_path'] = './uploads/level_icons/';

                $config['allowed_types'] = 'gif|jpg|png|jpeg';

                $config['max_size'] = '';

                $config['min_width']  = '';

                $config['min_height']  = '';

                $file_extension = @end(explode(".", $_FILES["level_icon"]["name"]));

                $new_extension = strtolower($file_extension);

                $today = time();

                $custom_name = "level_icon_" . $today;

                $file_name = $custom_name . "." . $new_extension;

                $config['file_name'] = $file_name;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('level_icon', $config)) {
                    
//                    $data['error'] = 'Supported Media Type - gif, jpg, png, jpeg.';
//
//                    $this->load->view('admin/header');
//                    $this->load->view('admin/menu');
//                    $this->load->view('admin/add/add_favourite', $data);
//                    $this->load->view('admin/footer');
//                    return false;
                    
                    $this->session->set_flashdata('error', 'Supported Media Type - gif, jpg, png, jpeg.');
//                    redirect('admin/favourite_list');
                    redirect('admin/edit_level/'.$level_id);
                    
                }
                $data['level_icon'] = $file_name;
            }
	    $ch = array("level_name" => strtoupper($this->input->post("level_name")), 'id !=' => $this->input->post('level_id'));
            $rec = $this->project_model->get_data_where_condition('levels', $ch);
            if (empty($rec)) {
                $where = array('id' => $this->input->post('level_id'));
                $edit = $this->project_model->update_data('levels', $data, $where);
                $this->session->set_flashdata('status', 'Level updated successfully!');
                redirect('admin/level_list');
            } else {
                $this->session->set_flashdata('error', 'Level Already Exist!');
                redirect('admin/level_list');
            }

        } else {
            $level_id = $this->uri->segment(3);
            if (!empty($level_id)) {
                $where = array('id' => $level_id);
                $level_detail = $this->project_model->get_column_data_where('levels', '', $where);
                if (!empty($level_detail)) {
                    $data['level_detail'] = $level_detail;
                    $this->load->view('admin/header');
                    $this->load->view('admin/menu');
                    $this->load->view('admin/add/add_level_view', $data);
                    $this->load->view('admin/footer');
                } else {
                    redirect('admin/level_list');
                }
            } else {
                redirect('admin/level_list');
            }
        }
    }
    /* End Levels Section */
    
    /* Start Push Notification Section */
    function send_push() {
        $data = $this->input->post();
        if(!empty($data)){
            unset($data['usersids']);
//            $where = array("pk !='' " => NULL); // ,"user_id in(2,34) "=>NULL
            $user =  $this->input->post('usersids');
            if(!empty($user))
            {
                $where = array("pk !='' " => NULL, "user_id in($user) "=>NULL);
            }else{
                $where = array("pk !='' " => NULL);
            }
            $user_detail = $this->project_model->get_column_data_where('users', '', $where);
            if (!empty($user_detail)) {
//                echo '<pre>';
//                print_r($user);
//                print_r($user_detail);die();
                foreach ($user_detail as $value) {
                                    
                   $token = trim($value->gcm_id);
                   $notification_title = "New Message!";
                   $message = $this->input->post('push_message');

                   $query = "SELECT count(n.read_status) as badge_count FROM notification n WHERE n.notification_user_id='$value->user_id' AND read_status=0";

                   $users_notification = $this->project_model->get_query_result($query);
                   $tBadge = ($users_notification[0]->badge_count) ? $users_notification[0]->badge_count : 0 ;
//                    $tBadge = 0;
                //    ,'badge' => $tBadge
                    $noti_arr = array('title' => $notification_title, 'body' =>  $message ,'sound'=>'Default','image'=> base_url('assets/images/LOGO.png'),'badge' => $tBadge,
                        'show_in_background'=> true);
                    $custom_notification = array(
                        'title' => $notification_title, 
                        'body' =>  $message 
                    );
                    $data_arr = array(
                        'title' => $notification_title, 
                        'body' =>  $message,
                        'badge' => $tBadge,
                        //'icon' => 'ic_launcher',
                        'custom_notification' => $custom_notification,
                        'story_detail'=> 1,
                        'show_in_background'=> true
                    );

                    $notification_data=array('notification_user_id'=>$value->user_id,'notification_title'=>$notification_title,'notification_message'=>$message,'notification_datetime'=>date('Y-m-d H:i:s'),'notification_type'=>1);
                    $notification_id = $this->project_model->insert_data('notification', $notification_data);
                    if($value->user_notification_status == 1){
                        $res = $this->sendPushNotificationToFCMSever($token,$noti_arr,$data_arr);
                        
                        $errdata = array('created_at'=>date('Y-m-d H:i:s'));
                        $errdata['log_data'] =$res;
                        $errdata['user_id'] =$value->user_id;
                        $err_inserted_id = $this->project_model->insert_data('notification_error_log', $errdata);
                    }

                }
                $this->session->set_flashdata('status', 'Notification sent successfully.');
                redirect('users');
                
            } else {
                redirect('users');
            }
        } else {
                redirect('users');
            }
    }
    /* End Push Notification Section */
    function logout() {

        $array_items = array('admin_id', 'admin_email', 'logged_in');

        $this->session->unset_userdata($array_items);

        redirect('login/index');

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
        if ($table == "levels" && $function == "level_list") {
            if ($field_value != 1) {
                $message = 'Level deactivated successfully. ';
            } else {
                $message = 'Level activated successfully. ';
            }
            $this->session->set_flashdata('status', $message);
            $path = 'admin/'.$function;
            redirect($path);
        }
        
        if ($table == "company" && $function == "company_list") {
            if ($field_value != 1) {
                $message = 'Company deactivated successfully. ';
            } else {
                $message = 'Company activated successfully. ';
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
            if ($delete_type == 'delete_level') {
                $message = 'Level deleted successfully!!';
                $this->session->set_flashdata('error', $message);
                redirect('level_list');
            }
            if ($delete_type == 'delete_campaign') {
                $message = 'Campaign deleted successfully!!';
                $this->session->set_flashdata('error', $message);
                redirect('admin/campaign_list');
            }
            if ($delete_type == 'delete_static_campaign') {
                $message = 'Campaign deleted successfully!!';
                $this->session->set_flashdata('error', $message);
                redirect('admin/static_campaign_list');
            }
            
            if ($delete_type == 'delete_company') {
                $message = 'Company deleted successfully!!';
                $this->session->set_flashdata('error', $message);
                redirect('admin/company_list');
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
//         print_r($result);die();
        curl_close($ch);
        return $result;
    }
    
    function shorter($text, $chars_limit)
    {
        // Check if length is larger than the character limit
        if (strlen($text) > $chars_limit)
        {
            // If so, cut the string at the character limit
//            $new_text = substr($text, 0, $chars_limit);
            $new_text = mb_substr($text,0,$chars_limit,'utf-8');
            // Trim off white space
            $new_text = trim($new_text);
            // Add at end of text ...
            return $new_text . "...";
        }
        // If not just return the text as is
        else
        {
        return $text;
        }
    }
        
}

