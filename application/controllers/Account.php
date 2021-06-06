<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class  Account extends CI_Controller {
	public function __construct() {
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
		$data['page_title']="Account";
		$data["method"]="add";
		$data['frm_id']="Add_frm";
		$data["city"]=$this->General_model->get_data('city','status','*','1');
		$data["state"]=$this->General_model->get_data('state','status','*','1');
		$this->load->view('admin/controller/header');
		$this->load->view('admin/controller/sidebar');
		$this->load->view('admin/data/account',$data);
		$this->load->view('admin/controller/footer');
	}
	
	public function create()
	{
		$this->General_model->auth_check();
		$name=trim(ucwords($this->input->post("name")));
		$mobile=$this->input->post("mobile");
		$address=trim(ucwords($this->input->post("address")));
		$city_id=$this->input->post("city_id");
		$state_id=$this->input->post("state_id");
		if(isset($name) && !empty($name) && isset($city_id) && !empty($city_id) && isset($state_id) && !empty($state_id)){
			
			if(!isset($address) && empty($address)){
				$address=NULL;
			}
			if(!isset($mobile) && empty($mobile)){
				$mobile=NULL;
			}
			$count=$this->General_model->has_duplicate($name,'account','name');
			if($count>0){
				$data['status']="error";
				$data['msg']="Party Already Exist" ;	
			}else{
				$city=$this->General_model->get_row('city','id',$city_id);
				$detail=['name'=>$name,
					'mobile'=>$mobile,
					'address'=>$address,
					'city_id'=>$city_id,
					'city_name'=>$city->name,
					'state_id'=>$state_id,
					'status'=>'1',
					'created_at'=>date("Y-m-d h:i:s")];
				$detail=$this->db->insert('account',$detail);
				$data['status']="success";
				$data['msg']="Party Added";	

			}
				
		}else{
			$data['status']="error";
			$data['msg']="Something is Worng";				
		}
		echo json_encode($data);
	}
	public function getLists(){
		$this->General_model->auth_check();
		$table='account';
		$order_column_array=array('id_account', 'name','mobile','address','city_name','status','created_at');
		$search_order= array( 'name','mobile','address','city_name');
		$order_by_array= array('id_account' => 'ASC');
        $data = $row = array();
        $Master_Data = $this->Genral_datatable->getRows($_POST,$table,$order_column_array,$search_order,$order_by_array);
        $i = $_POST['start'];
        foreach($Master_Data as $m_data){
            $i++;
            $created = date( 'jS M Y', strtotime($m_data->created_at));
            $status = ($m_data->status == 1)?'Active':'Inactive';
            $data[] = 	[$i,	        				
    					$m_data->name,
    					$m_data->mobile,
    					$m_data->address,
    					$m_data->city_name,
    					'<a href="'.base_url('account/get_editfrm/').$m_data->id_account.'"><button type="button" class="btn btn-custom waves-effect waves-light"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>&nbsp;<button type="button" class="btn btn-danger waves-effect waves-light" data-id="delete" data-value="'.$m_data->id_account.'"><i class="fa fa-trash" aria-hidden="true"></i></button>'];
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
        $data['page_title']="Account";
		$data['result']=$this->General_model->get_row('account','id_account',$id);
		$data["city"]=$this->General_model->get_data('city','status','*','1');
		$data["state"]=$this->General_model->get_data('state','status','*','1');
		$this->load->view('admin/controller/header');
		$this->load->view('admin/controller/sidebar');
		$this->load->view('admin/data/account',$data);
		$this->load->view('admin/controller/footer');
	}
    public function update()
    {
    	$this->General_model->auth_check();
    	$name=trim(ucwords($this->input->post("name")));
		$mobile=$this->input->post("mobile");
		$address=trim(ucwords($this->input->post("address")));
		$city_id=$this->input->post("city_id");
		$state_id=$this->input->post("state_id");
		$status=$this->input->post("status");
    	$id=$this->input->post("id");
    	if(isset($name) && !empty($name) && isset($city_id) && !empty($city_id) &&  isset($state_id) && !empty($state_id) && isset($id) && !empty($id)){

    		if(!isset($address) && empty($address)){
    			$address=NULL;
    		}
    		if(!isset($mobile) && empty($mobile)){
    			$mobile=NULL;
    		}
    		$count=$this->General_model-> has_duplicate_query("select name from account where name ='".$name."' and id_account !='".$id."'");
    		if($count>0){
    			$data['status']="error";
				$data['msg']="Party Already Exist" ;

    		}else{
    			$city=$this->General_model->get_row('city','id',$city_id);
    			$detail=['name'=>$name,
					'mobile'=>$mobile,
					'address'=>$address,
					'city_id'=>$city_id,
					'city_name'=>$city->name,
					'state_id'=>$state_id,					
					'status'=>$status
					];
    		$this->General_model->update('account',$detail,'id_account',$id);
    		$data['status']="success";
    		$data['msg']="Party Updated";

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
    		$detail=$this->General_model->delete('account','id_account',$id);
    		$data['status']="success";
    		$data['msg']="Party Deleted";
    	}else{
    		$data['status']="error";
    		$data['msg']="Something is Worng";				
    	}
    	echo json_encode($data);
    }
    
}