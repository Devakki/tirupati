<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class State extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Genral_datatable');
        $this->load->model('General_model');
        $this->load->database();
        $this->load->library('session');
    }
    public function index()
    {
        $data['page_title']="State";
        $data['method']="add";
        $data['frm_id']="Add_frm";
        $this->load->view('admin/controller/header');
        $this->load->view('admin/controller/sidebar');
        $this->load->view('admin/setting/state',$data);
        $this->load->view('admin/controller/footer');
    }
    function getLists(){
        $table='state';
        $order_column_array=array('id', 'name','code','country','status');
        $search_order= array('name','code','country');
        $order_by_array= array('id' => 'ASC');
        $data = $row = array();
        $Master_Data = $this->Genral_datatable->getRows($_POST,$table,$order_column_array,$search_order,$order_by_array);
        $i = $_POST['start'];
        foreach($Master_Data as $m_data){
            $i++;
            $status = ($m_data->status == 1)?'Active':'Inactive';
            $data[] =   [$i,
                        $m_data->name, 
                        $m_data->code,
                        $m_data->country,
                        $status,
                        '<a href="'.base_url('State/get_editfrm/').$m_data->id.'"><button type="button" class="btn btn-custom waves-effect waves-light"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>'
                        ];
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Genral_datatable->countAll($table),
            "recordsFiltered" => $this->Genral_datatable->countFiltered($_POST,$table,$order_column_array,$search_order,$order_by_array),
            "data" => $data,
        );
        // Output to JSON format
        echo json_encode($output);
    }
    public function create()
    {
        $this->General_model->auth_check();
        $statename=ucfirst($this->input->post("name"));
        $code=$this->input->post("code");
        $country=$this->input->post("country");
        if(isset($statename) && !empty($statename) && isset($country) && !empty($country)){
            $count=$this->General_model-> has_duplicate($statename,'state','name');
            if($count>0){
                $data['status']="error";
                $data['msg']="State Already Exist" ;
            }else{
                $state=['name '=>$statename,
                            'code'=>$code,
                            'status'=>1,
                            'country'=>$country,
                            'created_at'=>date("Y-m-d h:i:s")];
                $detail=$this->db->insert('state',$state);
                $data['status']="success";
                $data['msg']="State Added" ;
            }
        }else{
            $data['status']="error";
            $data['msg']="Something is Worng";              
        }
        echo json_encode($data);
    }
    public function get_editfrm($id)
    {
        $this->General_model->auth_check();
        $data['page_title']="State";
        $data['method']="edit";
        $data['frm_id']="Edit_frm";
        $data['state']=$this->General_model->get_row('state','id',$id);        
        $this->load->view('admin/controller/header');
        $this->load->view('admin/controller/sidebar');
        $this->load->view('admin/setting/state',$data);
        $this->load->view('admin/controller/footer');
    }
    public function update()
    {
        $this->General_model->auth_check();
        $statename=ucfirst($this->input->post("name"));
        $code=$this->input->post("code");
        $status=$this->input->post("status");
        $id=$this->input->post("id");
        $country=$this->input->post("country");
        if(isset($statename) && !empty($statename) &&  isset($id) && !empty($id) && isset($country) && !empty($country)){
            $count=$this->General_model-> has_duplicate_query("select name  from state where name ='".$statename."' and id  !='".$id."'");
                if($count>0){
                    $data['status']="error";
                    $data['msg']="State Already Exist"; 
                }else{
                    $state=['name '=>$statename,
                            'code'=>$code,                            
                            'status'=>$status
                        ];
                    $this->General_model->update('state',$state,'id',$id);
                    $data['status']="success";
                    $data['msg']="State Updated";
                }
                }else{
                    $data['status']="error";
                    $data['msg']="Something is Worng";              
                }
            echo json_encode($data);
    }
}