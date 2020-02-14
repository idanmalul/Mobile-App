<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Login extends CI_Controller {



        function __construct() {

            parent::__construct();

            

            $this->load->helper(array('form', 'url'));

            //$this->load->model('project_model');

            $this->load->library('form_validation');

            $this->load->library('email');

            $this->load->library('session');

            if($this->session->userdata('logged_in')){

                redirect('dashboard');

            }

        }

	

	function index() {

            if($this->input->post('email')){

                $where = array('admin_email'=>$this->input->post('email'));

                

                $result = $this->project_model->get_records_where('admin', $where);

                if($result){

                    if($result[0]->admin_password != md5($this->input->post('passwd'))){

                        $_SESSION['error_msg'] = 'Invalid Password!';

                        $this->session->mark_as_flash('error_msg');

                        redirect('login');

                        return false;

                    }

                    

                    $newdata = array(

                        'admin_id' => $result[0]->admin_id,

                        'admin_email' => $result[0]->admin_email,

                        'admin_type' => $result[0]->admin_type,

                        'logged_in' => TRUE

                    );



                    $this->session->set_userdata($newdata);

                    redirect('dashboard');

                } else {

                    $_SESSION['error_msg'] = 'Invalid Email ID!';

                    $this->session->mark_as_flash('error_msg');

                    redirect('login');

                }

            } else {

                $this->load->view('admin/login_view');

            }

	}

        

        function forgot_password(){

            

        if($this->input->post('send')){

            

            $this->form_validation->set_error_delimiters('<span class="validate-has-error">', '</span>');

            if ($this->form_validation->run('forgot_pass') !== FALSE){

                $data['error'] = 'Please enter valid email id!';

                $this->load->view('admin/forgot_password_view', $data);

            } else {

                $email = $this->input->post('user_email');

                $where = array('admin_email' => $email);

                $check_email = $this->project_model->get_records_where('admin', $where);

                if(!empty($check_email)){

		    $alphnumeric = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

		    $rand_code = substr(str_shuffle($alphnumeric),0,8);

                    //  $password = time();

		    $where_id = array('admin_id' => $check_email[0]->admin_id);

                    $data = array('random_code' => $rand_code);

                    $update = $this->project_model->update_data('admin', $data, $where_id);



		    $image_path=base_url();

		    $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Untitled Document</title>

</head>



<body bgcolor="#f1f1f1">

<table cellpadding="0" cellspacing="0" width="600" style="background:#fff; border:1px solid #cbcbcb; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size:12px;">

	<thead class="header">

					    	<tr>

					        	<td style="background:#f9f9f9  ; height:90px; width:100%; padding:0px; border-bottom:1px solid #DDD;text-align:center;" valign="middle">

					            	<!--<a href="#" style="margin-left:0px;"><img width="280" src="' . $image_path . '/assets/images/logo_admin.png" alt="..."/></a>-->

                                                            <h3 style="color: #2A643C;font-size: 30px;font-weight: bold;text-align: center;">10k-Club</h3>

					<!--<div class="social-icons" style="float:right; margin-top:10px;">

						<a href="#" style="float:left; margin:0px 3px;"><img src="' . $image_path . '/assets/email_img/fb.png" alt="..."/></a>

					<a href="#" style="float:left; margin:0px 3px;"><img src="' . $image_path . '/assets/email_img/tw.png" alt="..."/></a>

					<a href="#" style="float:left; margin:0px 3px;"><img src="' . $image_path . '/assets/email_img/in.png" alt="..."/></a>

					                

					            </div>-->

					        </td>

					    </tr>

			</thead>

    <tbody style=" border-bottom:1px solid #ddd;">

    	<tr>

        	<td style="padding:10px 15px;">

            	<h1 style="margin-bottom:0px; color:#e36034;">Dear Admin</h1>

            	<h4 style="font-size:1.2em;">Forgot Password :</h4>

            	<!--<p style="font-size:1em;"><strong>Please note -</strong>  you must complete this last step to become a registered member. You will only need to click on the link once, and your account will be updated.</p>-->

            	<p style="font-size:1em;">To reset your password, click on the link below:</p>

            	<br/>

            	<!--<center><a href=" '.site_url().'/login/reset_password/'.base64_encode($check_email[0]->admin_id).'/'.base64_encode($rand_code).' ">Click here to reset</a></center>-->

		    <center><a href=" '.site_url().'/login/reset_password/'.$check_email[0]->admin_id.'/'.$rand_code.' ">Click here to reset</a></center>

            	

               	

            </td>

        </tr>

        

        <tr>

        	<td style="padding:10px 15px;">

            	<span><strong>Thanks & Regards:</strong></span><br/>

            	<span><strong>10k-Club</strong></span>

                

            </td>

        </tr>

        <tr>

        	<td style="background:#ddd; height:1px; width:100%;"></td>

        </tr>

    </tbody>

    

    <tfoot style="background:#3d3d3d; text-align:center; color:#fff;">

	<tr>

		<td style="color:#fff;"><p>Copyright © 2018 10k-Club All right reserved</p></td>

	<tr>

    </tfoot>

    

</table>

</body>

</html>';

                    $this->load->library('email');

                    $this->email->from(‘malulxyz@malul.xyz’, '10k-Club');

                    $this->email->to($email);

                    $this->email->subject('Forgot Password');

                    $this->email->message($message);

                    $this->email->set_mailtype("html");

                    if($this->email->send()){

                     $data['success'] = 'Reset Link send on your email. Please check your mail!';

                    }else{

                      //  echo $this->email->print_debugger();die();

                     $data['error'] = 'Failed to mail!';

                    }

                    $this->load->view('admin/forgot_password_view', $data);

                    

                } else {

                    $data['error'] = 'Invalid Email ID!';

                    $this->load->view('admin/forgot_password_view', $data);

                }

            }

        } else {

            $this->load->view('admin/forgot_password_view');

        }

    }

    

    function reset_password() {

        if ($this->input->post('change')) {

	   

	    $admin_id = $this->input->post('admin_id');

	    $rand_code =$this->input->post('rand_code');

		    

            $where = array('admin_id' => $admin_id,'random_code'=>$rand_code);

            $check = $this->project_model->get_records_where('admin', $where);

	    

            if ($check == FALSE) {

                $this->session->set_flashdata('error_msg', 'You have Already Forgot Password using this link.Please try again...');

                redirect('login');

            } else {

                $data = array('admin_password' => md5($this->input->post('new_pass')),'random_code'=>'');

                $where = array('admin_id' => $admin_id);

                $this->project_model->update_data('admin', $data, $where);



                $this->session->set_flashdata('success', 'Password Successfully Changed');

                redirect('login');

            }

        } else {

            $this->load->view('admin/reset_new_password_view');

        }

    }

}
