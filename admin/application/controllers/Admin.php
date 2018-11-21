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
            
        } else {

            redirect();

        }

    }

    // For android notification
    function android_notification($gcm_id, $msg) {

        $GOOGLE_API_KEY = '';
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

        //Now close the connection
        curl_close($ch);

        //and return the result 
        return $result;
    }
        
}

