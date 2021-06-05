<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MasterAdmin extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Genral_datatable');
        $this->load->model('General_model');
        $this->load->model('UserModel');
        $this->load->database();
        $this->load->library('session');
        $this->General_model->auth_admin();
    }
    public function index()
    {
        $data['page_title']="Users";
        $data['method']="add";
        $data['frm_id']="Add_frm";
        $data['role']=$this->General_model->get_data('role','status','*','1');
        $this->load->view('admin/controller/header');
        $this->load->view('admin/controller/sidebar');
        $this->load->view('admin/setting/master_admin',$data);
        $this->load->view('admin/controller/footer');
    }
    public function myFunction()
    {
        $columns = array( 
                            0 =>'id_master', 
                            1 =>'username',
                            2=> 'role_id',
                            3=> 'status',
                        );
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $totalData = $this->UserModel->allposts_count();
        $totalFiltered = $totalData; 
        if(empty($this->input->post('search')['value']))
        {            
            $posts = $this->UserModel->allposts($limit,$start,$order,$dir);
        }
        else {
            $search = $this->input->post('search')['value']; 
            $posts =  $this->UserModel->posts_search($limit,$start,$search,$order,$dir);
            $totalFiltered = $this->UserModel->posts_search_count($search);
        }
        $data = array();
        if(!empty($posts))
        {
            $i=1;
            foreach ($posts as $post)
            {
                $nestedData['sr_no'] =$i;
                $nestedData['username'] =$post->username;
                $nestedData['phone'] =$post->phone;
                $nestedData['role'] = ($post->role_id == 1)?'admin':'master-admin';
                $nestedData['button'] ='<a href="'.base_url('MasterAdmin/get_editfrm/').$post->id_master.'"><button type="button" class="btn btn-custom waves-effect waves-light"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-id="delete" data-value="'.$post->id_master.'"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data = array(
                    "draw"            => intval($this->input->post('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
        echo json_encode($json_data);
    }
    public function create()
    {
        $this->General_model->auth_check();
        $username=$this->input->post("username");
        $password=$this->input->post("password");
        $mobile=$this->input->post("mobile");
        $role=$this->input->post("role_id");
        if(isset($username) && !empty($username) && isset($password) && !empty($password) &&  isset($mobile) && !empty($mobile) && isset($role) && !empty($role)){
            $count=$this->General_model-> has_duplicate($username,'master_admin','username');
            if($count>0){
                $data['status']="error";
                $data['msg']="User Name Already Exist" ;    
            }else{
                $password=md5($password);
                $user=['username '=>$username,
                            'password'=>$password,
                            'phone'=>$mobile,
                            'role_id'=>$role,
                            'status'=>1,
                            'created_at'=>date("Y-m-d h:i:s")];
            $detail=$this->db->insert('master_admin',$user);
            $data['status']="success";
            $data['msg']="User Added" ;     
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
        $data['page_title']="Users";
        $data['method']="edit";
        $data['frm_id']="Edit_frm";
        $data['user']=$this->General_model->get_row('master_admin','id_master',$id);
        $data['role']=$this->General_model->get_data('role','status','*','1');
        $this->load->view('admin/controller/header');
        $this->load->view('admin/controller/sidebar');
        $this->load->view('admin/setting/master_admin',$data);
        $this->load->view('admin/controller/footer');
    }
    public function update()
    {
        $this->General_model->auth_check();
        $username=$this->input->post("username");
        $mobile=$this->input->post("mobile");
        $role=$this->input->post("role_id");
        $id=$this->input->post("id");
        if(isset($username) && !empty($username) &&  isset($mobile) && !empty($mobile) && isset($role) && !empty($role) && isset($id) && !empty($id)){
            $count=$this->General_model-> has_duplicate_query("select username  from master_admin where username ='".$username."' and id_master  !='".$id."'");
                if($count>0){
                    $data['status']="error";
                    $data['msg']="User Already Exist";
                }else{
                    $user=['username '=>$username,
                            'phone'=>$mobile,
                            'role_id'=>$role,
                            'status'=>'1'
                        ];
                    $this->General_model->update('master_admin',$user,'id_master',$id);
                    $data['status']="success";
                    $data['msg']="User Updated";
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
            $detail=$this->General_model->delete('master_admin','id_master',$id);
            $data['status']="success";
            $data['msg']="User Deleted";
        }else{
            $data['status']="error";
            $data['msg']="Something is Worng";              
        }
        echo json_encode($data);
    }
}