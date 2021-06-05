<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin extends CI_Controller {
	function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');
        $this->load->model('General_model');
    }
	public function index()
	{
		$this->auth_check();
		$this->login();
	}
	public function login()
	{
		if(isset($_SESSION['auth_user_id']) && !empty($_SESSION['auth_user_id']) && isset($_SESSION['auth_user_email']) && !empty($_SESSION['auth_user_email']) && isset($_SESSION['auth_role_id']) && !empty($_SESSION['auth_role_id']) && isset($_SESSION['auth_fullname']) && !empty($_SESSION['auth_fullname']) && isset($_SESSION['auth_image']) && !empty($_SESSION['auth_image'])){
			redirect(base_url('dashbord'));
		}else{
			$this->load->view('admin/login.php');	
		}		
	}
	public function logincheck()
	{
		$uname=$this->input->post('uname');
		$password=$this->input->post('password');
		if(isset($uname) && !empty($uname) && isset($password) && !empty($password)   ){
		$count=$this->General_model->has_duplicate($uname, 'master_admin','username');
			if($count>0){
				$passcheck=$this->General_model->get_row('master_admin','username',$uname);
				if( $passcheck->password=== md5($password)){
					$this->session->set_userdata('auth_user_id',$passcheck->id_master);
					$this->session->set_userdata('auth_user_email',$passcheck->username);
					$this->session->set_userdata('auth_role_id',$passcheck->role_id);
					$check=$this->General_model->has_duplicate($passcheck->id_master,'userprofile','user_id ');
					if($check > 0 ){
					$profile=$this->General_model->get_row('userprofile','user_id',$passcheck->id_master);
					$this->session->set_userdata('auth_fullname',$profile->name);
					$this->session->set_userdata('auth_image',$profile->image);
					}
					$data['successMsg'] = 'Login Successfully';
					redirect(base_url('Dashbord'));
					exit();
				}else{
					$data['errMsg'] = "Invalid password ";
					$this->load->view('admin/login',$data);
				}
				}else{
					$data['errMsg'] = "Invalid UserName And Password";
					$this->load->view('admin/login',$data);
				}
		}else{
			$data['errMsg'] = "Invalid User Name Password";
			redirect(base_url('admin/index'));
		}
	}
	public function logout()
	{
		session_destroy();
		$this->session->unset_userdata('auth_user_id');
		$this->load->view('admin/login.php');
	}
	public function auth_check(){
			$CI =& get_instance();
			$user_id = $CI->session->userdata('auth_user_id');				
			if(empty($user_id) && !isset($user_id)){
				redirect(base_url('admin/login'));
			}
	}
}