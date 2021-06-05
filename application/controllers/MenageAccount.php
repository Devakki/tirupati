<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MenageAccount extends CI_Controller {
    function __construct() {
        parent::__construct();        
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');
        $this->load->model('General_model');
        $this->load->database();
        $this->General_model->auth_admin();
    }
    public function index()
    {
        $this->General_model->auth_check();
        $data['page_title']="Menage Account";
        $data['method']="view";
        $data['party']=$this->General_model->get_data('party','status','*','1');
        $this->load->view('admin/controller/header');
        $this->load->view('admin/controller/sidebar');
        $this->load->view('admin/rough/menage/account',$data);
        $this->load->view('admin/controller/footer');
    }
    public function clear()
    {
        $this->General_model->auth_check();
        $party_id=$this->input->post("party");
        $strt_date=$this->input->post("start");
        $end_date=$this->input->post("end");
        if(isset($party_id) && !empty($party_id) && isset($strt_date) && !empty($strt_date) && isset($end_date) && !empty($end_date)){
            $dateto=$strt_date." To ".$end_date;
            $strt_date=explode("/", $strt_date);
            $strt_date=[$strt_date[2],$strt_date[1],$strt_date[0]];
            $strt_date=implode("-",$strt_date);
            $end_date=explode("/", $end_date);
            $end_date=[$end_date[2],$end_date[1],$end_date[0]];
            $end_date=implode("-",$end_date);
            $party=$this->db->query("SELECT * FROM `party` WHERE  id_party='".$party_id."' ")->row();
            $count=$this->db->query("SELECT * FROM `rough_payment` WHERE  party_id='".$party_id."' AND   date<='".$end_date."' AND date >='".$strt_date."'")->num_rows();

            if($count>0){
                $query=$this->db->query("SELECT * FROM `rough_payment` WHERE  party_id='".$party_id."' AND   date<='".$end_date."' AND date >='".$strt_date."'")->result();
                foreach ($query as $key => $value) {
                    if(isset($value->roughpur_id) && !empty($value->roughpur_id)){
                        $this->General_model->delete('rough_purchase','id_rough',$value->roughpur_id);
                        $this->General_model->delete('roughpurchase_item','roughpurchase_id',$value->roughpur_id);
                    }
                    if(isset($value->roughinvoice_id) && !empty($value->roughinvoice_id)){
                        $this->General_model->delete('rough_invoice','id_rough',$value->roughinvoice_id);
                        $this->General_model->delete('rough_bag','roughinvoice_id',$value->roughinvoice_id);
                        $this->General_model->delete('rough_item','roughinvoice_id',$value->roughinvoice_id);
                    }  
                    $this->General_model->delete('rough_payment','id',$value->id);          
                }
                $data['status']="success";
                $data['msg']="clear ".$party->name."'s data ".$dateto;
            }else{
                $data['status']="error";
                $data['msg']="Don't Exist ".$party->name."'s data ".$dateto; 
            }
        }else{
            $data['status']="error";
            $data['msg']="Something is Worng";
        }
        echo json_encode($data);
    }
}