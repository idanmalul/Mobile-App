<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Web extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
        function __construct() {

            parent::__construct();
        }
	public function index()
	{
            $this->login();
	}
        function login(){
            $this->load->view('web/login/header');
            $this->load->view('web/login/login');
            $this->load->view('web/login/footer');
        }
        
        function two_factor(){
            $this->load->view('web/login/header');
            $this->load->view('web/login/two_factor_OTP');
            $this->load->view('web/login/footer');
        }
        
        function challenge(){
            $this->load->view('web/login/header');
            $this->load->view('web/login/challenge');
            $this->load->view('web/login/footer');
        }

        function registration(){
            $this->load->view('web/registration/header');
            $this->load->view('web/registration/registration');
            $this->load->view('web/registration/footer');
        }
        function success(){
            $this->load->view('web/login/header');
            $this->load->view('web/registration/success');
            $this->load->view('web/login/footer');
        }
}
