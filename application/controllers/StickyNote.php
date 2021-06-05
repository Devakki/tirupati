<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class  StickyNote extends CI_Controller {
	function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');
        $this->load->model('General_model');
        $this->load->model('Genral_datatable');
        $this->load->database();
        $this->General_model->auth_admin();
    }	
	public function create()
	{
		$this->General_model->auth_check();
		$content=$this->input->post("content");
		$name=$this->input->post("party");
		if(isset($content) && !empty($content)){
			$detail=['name'=>$name,
						'content'=>$content,
						'created_at'=>date("Y-m-d h:i:s")];
			$detail=$this->db->insert('sticky_notes',$detail);
			$note_data = array(
			        'note_msg'  => 'Note Added',
			        'status'     => 'success',
			        'class' => 'alert-success'
			);
		}else{
			$note_data = array(
			        'note_msg'  => 'Something is Worng',
			        'status'     => 'error',
			        'class' => 'alert-warning'
			);	
		}
		$this->session->set_userdata($note_data);
		redirect('Dashbord');
	}
   public function delete($id)
    {
    	$this->General_model->auth_check();
    	if(isset($id) && !empty($id)){
    		$this->General_model->delete('sticky_notes','id',$id);
    		$data['status']="success";
    		$data['msg']="Sticky Notes Deleted";
    	}else{
    		$data['status']="error";
    		$data['msg']="Something is Worng";	
    	}
    	echo json_encode($data);
    }
    public function edit($id)
	{
		if(isset($id) && !empty($id)){
			$data['row']=$this->General_model->get_row('sticky_notes','id',$id);
			$data['status']="success";
		}else{
			$data['status']="error";
		}
		echo json_encode($data);
	}
    public function update()
    {
    	$this->General_model->auth_check();
    	$content=$this->input->post("content");
    	$name=$this->input->post("party");
    	$id=$this->input->post("id");
    	if(isset($content) && !empty($content) && isset($name) && !empty($name) &&isset($id) && !empty($id)){
    		$detail=['name'=>$name,
    					'content'=>$content,
    					'created_at'=>date("Y-m-d h:i:s")];
    		$this->General_model->update('sticky_notes',$detail,'id',$id);
    		$note_data = array(
    		        'note_msg'  => 'Note Updated',
    		        'status'     => 'success',
    		        'class' => 'alert-success'
    		);
    	}else{
    		$note_data = array(
    		        'note_msg'  => 'Something is Worng',
    		        'status'     => 'error',
    		        'class' => 'alert-warning'
    		);	
    	}
    	$this->session->set_userdata($note_data);
    	redirect('Dashbord');
    }
}