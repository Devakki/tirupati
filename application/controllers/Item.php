<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class  Item extends CI_Controller {
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
	public function index()
	{
		$this->General_model->auth_check();
		$data['page_title']="Item";
		$data["method"]="add";
		$data['frm_id']="Add_frm";
		$this->load->view('admin/controller/header');
		$this->load->view('admin/controller/sidebar');
		$this->load->view('admin/data/item',$data);
		$this->load->view('admin/controller/footer');
	}
	public function create()
	{
		$this->General_model->auth_check();
		$name=ucwords(trim($this->input->post("name")));
		if(isset($name) && !empty($name)){
			$count=$this->General_model-> has_duplicate($name,'item','name');
			if($count>0){
				$data['status']="error";
				$data['msg']="Item Already Exist" ;	
			}else{
				$detail=['name'=>$name,
							'status'=>'1',
							'created_at'=>date("Y-m-d h:i:s")];
			$detail=$this->db->insert('item',$detail);
			$data['status']="success";
			$data['msg']="Item Added" ;		
		}
		}else{
			$data['status']="error";
			$data['msg']="Something is Worng";				
		}
		echo json_encode($data);
	}
	function getLists(){
			$this->General_model->auth_check();
			$table='item';
			$order_column_array=array('id_item', 'name','status');
			$search_order= array('name');
			$order_by_array= array('id_item' => 'ASC');
	        $data = $row = array();
	        $Master_Data = $this->Genral_datatable->getRows($_POST,$table,$order_column_array,$search_order,$order_by_array);
	        $i = $_POST['start'];
	        foreach($Master_Data as $m_data){
	            $i++;
	            $created = date( 'jS M Y', strtotime($m_data->created_at));
	            $status = ($m_data->status == 1)?'Active':'Inactive';
	            $data[] = 	[$i,	        				
	    					$m_data->name,
	    					$status, 
	    					'<a href="'.base_url('item/get_editfrm/').$m_data->id_item.'"><button type="button" class="btn btn-custom waves-effect waves-light"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>'];
	        }
	        $output = array(
	            "draw" => $_POST['draw'],
	            "recordsTotal" => $this->Genral_datatable->countAll($table),
	            "recordsFiltered" => $this->Genral_datatable->countFiltered($_POST,$table,$order_column_array,$search_order,$order_by_array),
	            "data" => $data,
	        );
	        echo json_encode($output);
	    }
	    public function get_editfrm($id)
	    {
	    	$this->General_model->auth_check();
	    	$data['method']="edit";
       		$data['frm_id']="Edit_frm";
       		$data['page_title']="Item";
	    	$data['result']=$this->General_model->get_row('item','id_item',$id);
	    	$this->load->view('admin/controller/header');
			$this->load->view('admin/controller/sidebar');
			$this->load->view('admin/data/item',$data);
			$this->load->view('admin/controller/footer');
	    }
	    public function update()
	    {
	    	$this->General_model->auth_check();
		    $name=$this->input->post("name");
			$hsn=$this->input->post("hsn_code");
			$status=$this->input->post("status");
			$id=$this->input->post("id");
	    	if(isset($name) && !empty($name)){
				$count=$this->General_model-> has_duplicate_query("select name from item where name ='".$name."' and id_item !='".$id."'");
				if($count>0){
					$data['status']="error";
		    		$data['msg']="Item Already Exist";
				}else{
		    		$detail=['name'=>$name,
						'status'=>$status,
						];
		    		$this->General_model->update('item',$detail,'id_item',$id);
		    		$data['status']="success";
		    		$data['msg']="Item Updated";
		    		}
		    	}else{
		    		$data['status']="error";
		    		$data['msg']="Something is Worng";				
		    	}
		    	echo json_encode($data);
	    }
	    public function delete($id)
	    {
	    	$this->General_model->auth_check();
	    	if(isset($id) && !empty($id)){
	    		$detail=$this->General_model->delete('item','id_item',$id);
	    		$data['status']="success";
	    		$data['msg']="Item Deleted";
	    	}else{
	    		$data['status']="error";
	    		$data['msg']="Something is Worng";				
	    	}
	    	echo json_encode($data);
	    }
}