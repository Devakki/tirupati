<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class  UserProfile extends CI_Controller {
	function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');
        $this->load->model('General_model');
        $this->load->model('Genral_datatable');
        $this->load->database();
    }
	public function index()
	{
		$this->General_model->auth_check();
		$user_id= $_SESSION['auth_user_id'];
		$check=$this->General_model->has_duplicate($user_id,'userprofile','user_id ');
		if($check > 0){
			redirect('UserProfile/get_editfrm/'.$user_id);
		}else{
			redirect('UserProfile/get_addfrm');
		}
	}
	public function get_addfrm()
	{
		$this->General_model->auth_check();
		$data["method"]="add";
		$data['page_title']="Profile";
		$this->load->view('admin/controller/header');
		$this->load->view('admin/controller/sidebar');
		$this->load->view('admin/setting/userprofile',$data);
		$this->load->view('admin/controller/footer');
	}
	public function create()
	{
		$this->General_model->auth_check();
		$name=$this->input->post("name");
		$image=$_FILES['image']['name'];
		if(isset($name) && !empty($name) && isset($image) && !empty($image)){
			$temp=$_FILES['image']['tmp_name'];
			$path= __DIR__."../../../assets/uploads/profiles/";
			$image=time()."_".$image;
			move_uploaded_file($temp,$path.$image);
				$userprofile=['name'=>$name,
								'user_id'=>$_SESSION['auth_user_id'],
								'image'=>$image,							
								'created_at'=>date("Y-m-d h:i:s")];
			$detail=$this->db->insert('userprofile',$userprofile);
			$this->session->set_userdata('auth_fullname',$name);
			$this->session->set_userdata('auth_image',$image);
			$alert = array(
	    		        'class'  => 'alert-success',
	    		        'msg'     => 'Profile Created'
	    		);	
		}else{
			$alert = array(
	    		        'class'  => 'alert-danger',
	    		        'msg'     => 'Something is Worng'
	    		);			
		}
		$this->session->set_userdata($alert);	
		redirect('UserProfile/');
	}
    public function get_editfrm($id)
    {
    	$this->General_model->auth_check();
    	$data['method']="edit";
    	$data['page_title']="Profile";
    	$data['profile']=$this->General_model->get_row('userprofile','user_id',$id);
    	$this->load->view('admin/controller/header');
		$this->load->view('admin/controller/sidebar');
		$this->load->view('admin/setting/userprofile',$data);
		$this->load->view('admin/controller/footer');
    }
    public function update()
    {
    	$this->General_model->auth_check();
	    $name=$this->input->post("name");
		$image=$_FILES['image']['name'];
		$id=$this->input->post("id");
    	if(isset($name) && !empty($name) && isset($id) && !empty($id)){
    		$path= __DIR__."../../../assets/uploads/profiles/";
			$profile=$this->General_model->get_row('userprofile','id',$id);
			$oldimage=$profile->image;
				if(isset($image) && !empty($image)){
					@unlink($path.$oldimage);
					$temp=$_FILES['image']['tmp_name'];
					$image=time()."_".$image;
					move_uploaded_file($temp,$path.$image);
				}else{
					$image=$oldimage;
				}
	    		$userprofile=['name'=>$name,
	    					'image'=>$image
					];
	    		$this->General_model->update('userprofile',$userprofile,'id',$id);
	    		$this->session->set_userdata('auth_fullname',$name);
	    		$this->session->set_userdata('auth_image',$image);
	    		$alert = array(
	    		        'class'  => 'alert-success',
	    		        'msg'     => 'Profile Updated'
	    		);
	    	}else{
	    		$alert = array(
	    		        'class'  => 'alert-danger',
	    		        'msg'     => 'Something is Worng'
	    		);
	    	}
	    	$this->session->set_userdata($alert);			
	    	redirect('UserProfile/');
    }
}