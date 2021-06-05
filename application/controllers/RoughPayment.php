<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class RoughPayment extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Genral_datatable');
        $this->load->model('General_model');
        $this->load->model('RoughModel');        
        $this->load->database();
        $this->load->library('session');
        $this->General_model->auth_admin();
    }
    public function index()
    {
        $data['page_title']="Rough Payment";
        $data['method']="add";
        $data['frm_id']="Add_frm";
        $data['party']=$this->General_model->get_data('party','status','*','1');
        $this->load->view('admin/controller/header');
        $this->load->view('admin/controller/sidebar');
        $this->load->view('admin/data/roughpayment',$data);
        $this->load->view('admin/controller/footer');
    }
    public function myFunction()
    {
        $columns = array( 
                            0 =>'id', 
                            1 =>'bill_type',
                            2=> 'party_name',
                            3=> 'date',
                            4=> 'rs',
                            5=> 'remark'
                        );
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $totalData = $this->RoughModel->allposts_count();
        $totalFiltered = $totalData; 
        if(empty($this->input->post('search')['value']))
        {            
            $posts = $this->RoughModel->allposts($limit,$start,$order,$dir);
        }
        else {
            $search = $this->input->post('search')['value']; 
            $posts =  $this->RoughModel->posts_search($limit,$start,$search,$order,$dir);
            $totalFiltered = $this->RoughModel->posts_search_count($search);
        }
        $data = array();
        if(!empty($posts))
        {
            $i=1;
            foreach ($posts as $post)
            {
                $nestedData['sr_no'] =$i;
                $nestedData['bill_type'] =(($post->bill_type=="1")?"Credit":"Debit");
                $nestedData['party'] =$post->party_name;
                $nestedData['date'] = date('d/m/Y',strtotime($post->date));
                $nestedData['rs'] = $post->rs ;
                $nestedData['remark'] = $post->remark ;
                $nestedData['button'] ='<a href="'.base_url('RoughPayment/get_editfrm/').$post->id.'"><button type="button" class="btn btn-custom waves-effect waves-light"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a> <button type="button" class="btn btn-danger waves-effect waves-light" data-id="delete" data-value="'.$post->id.'"><i class="fa fa-trash" aria-hidden="true"></i></button>';
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
        $party_id=$this->input->post("party");
        $date=$this->input->post("date");
        $date=explode("/", $date);
        $date=[$date[2],$date[1],$date[0]];
        $date=implode("-", $date);
        $rs=$this->input->post("rs");
        $remark=$this->input->post("remark");
        $bill_type=$this->input->post("bill_type");
        if(isset($party_id) && !empty($party_id) && isset($date) && !empty($date) && isset($rs) && !empty($rs) && isset($bill_type) && !empty($bill_type) ){
                $party=$this->General_model->get_row('party','id_party',$party_id);
                if(!isset($remark) && empty($remark)){
                    $remark=NULL;
                }
                $data=['party_id '=>$party_id,
                            'party_name'=>$party->name,
                            'date'=>$date,
                            'bill_type'=>$bill_type,
                            'roughpur_id'=>NULL,
                            'roughinvoice_id'=>NULL,
                            'rs'=>$rs,
                            'remark'=>$remark,
                            'created_at'=>date("Y-m-d h:i:s")];
            $detail=$this->db->insert('rough_payment',$data);
            $data['status']="success";
            $data['msg']="Party's Payment Added" ;       
        }else{
            $data['status']="error";
            $data['msg']="Something is Worng";              
        }
        echo json_encode($data);
    }
    public function get_editfrm($id)
    {
        $this->General_model->auth_check();
        $data['page_title']="Rough Payment";
        $data['method']="edit";
        $data['frm_id']="Edit_frm";
        $data['payment']=$this->General_model->get_row('rough_payment','id',$id);        
        $data['party']=$this->General_model->get_data('party','status','*','1');
        $data['readonly']="";
        $this->load->view('admin/controller/header');
        $this->load->view('admin/controller/sidebar');
        $this->load->view('admin/data/roughpayment',$data);
        $this->load->view('admin/controller/footer');
    }
    public function update()
    {
        $this->General_model->auth_check();
        $party_id=$this->input->post("party");
        $date=$this->input->post("date");
        $date=explode("/", $date);
        $date=[$date[2],$date[1],$date[0]];
        $date=implode("-", $date);
        $rs=$this->input->post("rs");
        $remark=$this->input->post("remark");
        $id_payment=$this->input->post("id_payment");
        $bill_type=$this->input->post("bill_type");
        if(isset($party_id) && !empty($party_id) && isset($date) && !empty($date) && isset($rs) && !empty($rs) && isset($bill_type) && !empty($bill_type) ){
                $party=$this->General_model->get_row('party','id_party',$party_id);
                if(!isset($remark) && empty($remark)){
                    $remark=NULL;
                }
                $data=['party_id '=>$party_id,
                            'party_name'=>$party->name,
                            'bill_type'=>$bill_type,
                            'date'=>$date,
                            'rs'=>$rs,
                            'remark'=>$remark,
                            'created_at'=>date("Y-m-d h:i:s")];
            $this->General_model->update('rough_payment',$data,'id',$id_payment);            
            $data['status']="success";
            $data['msg']="Party's Payment updated" ;       
        }else{
            $data['status']="error";
            $data['msg']="Something is Worng";              
        }
        echo json_encode($data);
    }
    public function delete()
    {
        $this->General_model->auth_check();
        $id=$this->input->post("id");
        if(isset($id) && !empty($id)){
            $this->General_model->delete('rough_payment','id',$id);           
            $data['status']="success";
            $data['msg']="Party's Payment updated" ;       
        }else{
            $data['status']="error";
            $data['msg']="Something is Worng";              
        }
        echo json_encode($data);
    } 
    public function fine_ledger()
    {
        $data['page_title']=" Fine Ledger";
        $data['method']="view";
        $data['action']=base_url('RoughPayment/fine_ledger');
        $data['party']=$this->General_model->get_data('party','status','*','1');
        $data['display']=false;
        $party_id=$this->input->post("party");
        $strt_date=$this->input->post("start");
        $end_date=$this->input->post("end");
        $data['method']="add";
        if(isset($party_id) && !empty($party_id) && isset($strt_date) && !empty($strt_date) && isset($end_date) && !empty($end_date)){
            $data['method']="edit";
            $data['btn_url']='fine_print?party='.$party_id.'&start='.$strt_date.'&end='.$end_date.'';
            $strt_date=explode("/", $strt_date);
            $strt_date=[$strt_date[2],$strt_date[1],$strt_date[0]];
            $strt_date=implode("-",$strt_date);
            $end_date=explode("/", $end_date);
            $end_date=[$end_date[2],$end_date[1],$end_date[0]];
            $end_date=implode("-",$end_date);
            $data['strt_date']=date('d/m/Y', strtotime($strt_date));
            $data['end_date']=date('d/m/Y', strtotime($end_date));
            $data['party_id']=$party_id;
            $data['display']=true;
            $party=$this->db->query("SELECT t1.*,t2.name as city_name FROM party as t1 LEFT JOIN city as t2 ON t1.city_id=t2.id WHERE t1.id_party='".$party_id."'")->row(); 
            $data['acc_name']=ucwords($party->name).", (".$party->city_name.")";
            $data['debit']=$this->db->query("SELECT t1.*,t2.invoice_no,t2.remark as parent_remark,t2.date,t3.name as item_name FROM rough_item as t1 LEFT JOIN rough_invoice as t2 ON t1.roughinvoice_id=t2.id_rough LEFT JOIN item as t3 ON t1.`item_id` = t3.id_item WHERE t2.date<='".$end_date."' AND t2.date >='".$strt_date."' AND t2.bill_type='debit' AND t2.party_id='".$party_id."' ORDER BY `t2`.`date`,t1.id ASC")->result();
            $data['credit']=$this->db->query("SELECT t1.*,t2.invoice_no,t2.date,remark as parent_remark,t3.name as item_name FROM roughpurchase_item as t1 LEFT JOIN rough_purchase as t2 ON t1.roughpurchase_id=t2.id_rough LEFT JOIN item as t3 ON t1.`item_id` = t3.id_item WHERE t2.date<='".$end_date."' AND t2.date >='".$strt_date."' AND t2.party_id='".$party_id."' ORDER BY `t2`.`date`,t1.id ASC")->result();
            $data['d_total']=$this->db->query("SELECT SUM(t1.fine) as d_total FROM rough_item as t1 LEFT JOIN rough_invoice as t2 ON t1.roughinvoice_id=t2.id_rough LEFT JOIN item as t3 ON t1.`item_id` = t3.id_item WHERE t2.date<='".$end_date."' AND t2.date >='".$strt_date."' AND t2.bill_type='debit' AND t2.party_id='".$party_id."'")->row();
            $data['c_total']=$this->db->query("SELECT SUM(t1.fine) as c_total FROM roughpurchase_item as t1 LEFT JOIN rough_purchase as t2 ON t1.roughpurchase_id=t2.id_rough LEFT JOIN item as t3 ON t1.`item_id` = t3.id_item WHERE t2.date<='".$end_date."' AND t2.date >='".$strt_date."' AND t2.party_id='".$party_id."'")->row();
        }
        $this->load->view('admin/controller/header');
        $this->load->view('admin/controller/sidebar');
        $this->load->view('admin/rough/ledger/fine_ledger',$data);
        $this->load->view('admin/controller/footer');
    }
    public function fine_print()
    {
        $party_id=$this->input->get("party");
        $strt_date=$this->input->get("start");
        $end_date=$this->input->get("end");
        $data['display']=false;
        if(isset($party_id) && !empty($party_id) && isset($strt_date) && !empty($strt_date) && isset($end_date) && !empty($end_date)){
                $strt_date=explode("/", $strt_date);
                $strt_date=[$strt_date[2],$strt_date[1],$strt_date[0]];
                $strt_date=implode("-",$strt_date);
                $end_date=explode("/", $end_date);
                $end_date=[$end_date[2],$end_date[1],$end_date[0]];
                $end_date=implode("-",$end_date);
            $data['strt_date']=date('d/m/Y', strtotime($strt_date));
            $data['end_date']=date('d/m/Y', strtotime($end_date));
            $data['display']=true;
            $party=$this->db->query("SELECT t1.*,t2.name as city_name FROM party as t1 LEFT JOIN city as t2 ON t1.city_id=t2.id WHERE t1.id_party='".$party_id."'")->row(); 
            $data['acc_name']=ucwords($party->name).", (".$party->city_name.")";
            $data['debit']=$this->db->query("SELECT t1.*,t2.invoice_no,t2.date,t2.remark as parent_remark,t3.name as item_name FROM rough_item as t1 LEFT JOIN rough_invoice as t2 ON t1.roughinvoice_id=t2.id_rough LEFT JOIN item as t3 ON t1.`item_id` = t3.id_item WHERE t2.date<='".$end_date."' AND t2.date >='".$strt_date."' AND t2.bill_type='debit' AND t2.party_id='".$party_id."' ORDER BY `t2`.`date`,t1.id ASC")->result();
            $data['credit']=$this->db->query("SELECT t1.*,t2.invoice_no,t2.date,t2.remark as parent_remark,t3.name as item_name FROM roughpurchase_item as t1 LEFT JOIN rough_purchase as t2 ON t1.roughpurchase_id=t2.id_rough LEFT JOIN item as t3 ON t1.`item_id` = t3.id_item WHERE t2.date<='".$end_date."' AND t2.date >='".$strt_date."' AND t2.party_id='".$party_id."' ORDER BY `t2`.`date`,t1.id ASC")->result();
            $data['d_total']=$this->db->query("SELECT SUM(t1.fine) as d_total FROM rough_item as t1 LEFT JOIN rough_invoice as t2 ON t1.roughinvoice_id=t2.id_rough LEFT JOIN item as t3 ON t1.`item_id` = t3.id_item WHERE t2.date<='".$end_date."' AND t2.date >='".$strt_date."' AND t2.bill_type='debit' AND t2.party_id='".$party_id."'")->row();
            $data['c_total']=$this->db->query("SELECT SUM(t1.fine) as c_total FROM roughpurchase_item as t1 LEFT JOIN rough_purchase as t2 ON t1.roughpurchase_id=t2.id_rough LEFT JOIN item as t3 ON t1.`item_id` = t3.id_item WHERE t2.date<='".$end_date."' AND t2.date >='".$strt_date."' AND t2.party_id='".$party_id."'")->row();
        }
       $data['page_title']="Fine Print";
       $this->load->view('admin/controller/header');
       $this->load->view('admin/controller/sidebar');
       $this->load->view('admin/rough/ledger/fine_print',$data);
       $this->load->view('admin/controller/footer');
    }
    public function rs_ledger()
    {
        $data['page_title']="Rs Ledger";
        $data['method']="add";
        $data['action']=base_url('RoughPayment/rs_ledger');
        $data['party']=$this->General_model->get_data('party','status','*','1');        
        $data['display']=false;
        $party_id=$this->input->post("party");
        $strt_date=$this->input->post("start");
        $end_date=$this->input->post("end");
        if(isset($party_id) && !empty($party_id) && isset($strt_date) && !empty($strt_date) && isset($end_date) && !empty($end_date)){
                $data['method']="edit";
                $data['btn_url']='rs_print?party='.$party_id.'&start='.$strt_date.'&end='.$end_date.'';
                $strt_date=explode("/", $strt_date);
                $strt_date=[$strt_date[2],$strt_date[1],$strt_date[0]];
                $strt_date=implode("-",$strt_date);
                $end_date=explode("/", $end_date);
                $end_date=[$end_date[2],$end_date[1],$end_date[0]];
                $end_date=implode("-",$end_date);
                $data['strt_date']=date('d/m/Y', strtotime($strt_date));
                $data['end_date']=date('d/m/Y', strtotime($end_date));
                $data['party_id']=$party_id;
                $data['display']=true;
            $party=$this->db->query("SELECT t1.*,t2.name as city_name FROM party as t1 LEFT JOIN city as t2 ON t1.city_id=t2.id WHERE t1.id_party='".$party_id."'")->row(); 
            $data['acc_name']=ucwords($party->name).", (".$party->city_name.")"; 
            $data['debit']=$this->db->query("SELECT t1.*,t2.invoice_no FROM rough_payment as t1 LEFT JOIN rough_invoice t2 ON t1.roughinvoice_id=t2.id_rough WHERE t1.bill_type='2' and t1.party_id='".$party_id."' and  t1.rs !='0' and t1.date >='".$strt_date."' and t1.date <= '".$end_date."' ORDER BY `t1`.`date` ASC")->result();
            $data['d_total']=$this->db->query("SELECT SUM(rs) as d_total FROM rough_payment as t1 LEFT JOIN rough_invoice t2 ON t1.roughinvoice_id=t2.id_rough WHERE t1.bill_type='2' and t1.party_id='".$party_id."' and  t1.rs !='0' and t1.date >='".$strt_date."' and t1.date <= '".$end_date."'")->row();
            $data['credit']=$this->db->query("SELECT t1.*,t2.invoice_no FROM rough_payment as t1 LEFT JOIN rough_purchase as t2 ON t1.roughpur_id=t2.id_rough WHERE t1.`party_id`='".$party_id."' and t1.rs !='0' and t1.date >='".$strt_date."' and t1.date <= '".$end_date."' and t1.bill_type='1' ORDER BY `t1`.`date` ASC")->result();
            $data['c_total']=$this->db->query("SELECT SUM(rs) as c_total FROM rough_payment as t1 LEFT JOIN rough_purchase as t2 ON t1.roughpur_id=t2.id_rough WHERE t1.`party_id`='".$party_id."' and  t1.rs !='0' and t1.date >='".$strt_date."' and t1.date <= '".$end_date."' and t1.bill_type='1'")->row();
        }
        $this->load->view('admin/controller/header');
        $this->load->view('admin/controller/sidebar');
        $this->load->view('admin/rough/ledger/rs_ledger',$data);
        $this->load->view('admin/controller/footer');
    }
    public function rs_print()
    {
        $party_id=$this->input->get("party");
        $strt_date=$this->input->get("start");
        $end_date=$this->input->get("end");
        $data['display']=false;
        if(isset($party_id) && !empty($party_id) && isset($strt_date) && !empty($strt_date) && isset($end_date) && !empty($end_date)){
                $strt_date=explode("/", $strt_date);
                $strt_date=[$strt_date[2],$strt_date[1],$strt_date[0]];
                $strt_date=implode("-",$strt_date);
                $end_date=explode("/", $end_date);
                $end_date=[$end_date[2],$end_date[1],$end_date[0]];
                $end_date=implode("-",$end_date);
            $data['strt_date']=date('d/m/Y', strtotime($strt_date));
            $data['end_date']=date('d/m/Y', strtotime($end_date));
            $data['display']=true;
            $party=$this->db->query("SELECT t1.*,t2.name as city_name FROM party as t1 LEFT JOIN city as t2 ON t1.city_id=t2.id WHERE t1.id_party='".$party_id."'")->row(); 
            $data['acc_name']=ucwords($party->name).", (".$party->city_name.")";
            $data['debit']=$this->db->query("SELECT t1.*,t2.invoice_no FROM rough_payment as t1 LEFT JOIN rough_invoice t2 ON t1.roughinvoice_id=t2.id_rough WHERE t1.bill_type='2' and t1.party_id='".$party_id."' and  t1.rs !='0' and t1.date >='".$strt_date."' and t1.date <= '".$end_date."' ORDER BY `t1`.`date` ASC")->result();
            $data['d_total']=$this->db->query("SELECT SUM(rs) as d_total FROM rough_payment as t1 LEFT JOIN rough_invoice t2 ON t1.roughinvoice_id=t2.id_rough WHERE t1.bill_type='2' and t1.party_id='".$party_id."' and  t1.rs !='0' and t1.date >='".$strt_date."' and t1.date <= '".$end_date."'")->row();
            $data['credit']=$this->db->query("SELECT t1.*,t2.invoice_no FROM rough_payment as t1 LEFT JOIN rough_purchase as t2 ON t1.roughpur_id=t2.id_rough WHERE t1.`party_id`='".$party_id."' and t1.rs !='0' and t1.date >='".$strt_date."' and t1.date <= '".$end_date."' and t1.bill_type='1' ORDER BY `t1`.`date` ASC")->result();
            $data['c_total']=$this->db->query("SELECT SUM(rs) as c_total FROM rough_payment as t1 LEFT JOIN rough_purchase as t2 ON t1.roughpur_id=t2.id_rough WHERE t1.`party_id`='".$party_id."' and  t1.rs !='0' and t1.date >='".$strt_date."' and t1.date <= '".$end_date."' and t1.bill_type='1'")->row();
        }
        $data['page_title']="Print Ledger";
        $this->load->view('admin/controller/header');
        $this->load->view('admin/controller/sidebar');
        $this->load->view('admin/rough/ledger/rs_print',$data);
        $this->load->view('admin/controller/footer');
    }
    public function final_report()
    {
       $partys=$this->db->query("SELECT t1.party_id,t2.id_party,t2.name FROM rough_payment as t1 LEFT JOIN party as t2 ON t1.party_id = t2.id_party GROUP BY t1.party_id")->result();
       $data['page_title']="Final Report";
       $data['display']=true;
       $i=1;
       foreach ($partys as  $party) {
           $p_fine=$this->db->query("SELECT SUM(t2.t_fine) as p_fine FROM rough_payment as t1 LEFT JOIN rough_purchase as t2 ON t1.roughpur_id=t2.id_rough where t1.party_id='".$party->party_id."' and t1.bill_type='1' GROUP BY t1.party_id")->row();
           $i_fine=$this->db->query("SELECT SUM(t2.t_fine) as i_fine FROM rough_payment as t1 LEFT JOIN rough_invoice as t2 ON t1.roughinvoice_id=t2.id_rough where t1.party_id='".$party->party_id."' and t1.bill_type='2' GROUP BY t1.party_id")->row();
           $c_total=$this->db->query("SELECT SUM(rs) as c_total FROM `rough_payment` WHERE `bill_type`='1' and `party_id`='".$party->party_id."'")->row();
           $d_total=$this->db->query("SELECT SUM(rs) as d_total FROM `rough_payment` WHERE `bill_type`='2' and `party_id`='".$party->party_id."'")->row();
           $pfine=((isset($p_fine->p_fine) && !empty($p_fine->p_fine))?($p_fine->p_fine):0);
           $ifine=((isset($i_fine->i_fine) && !empty($i_fine->i_fine))?($i_fine->i_fine):0);
           $ctotal=((isset($c_total->c_total) && !empty($c_total->c_total))?($c_total->c_total):0);
           $dtotal=((isset($d_total->d_total) && !empty($d_total->d_total))?($d_total->d_total):0);
           $fine=(($pfine>= $ifine)?($pfine-$ifine):($ifine-$pfine));
           $fine_closing=(($pfine>= $ifine)?"Cr":"DB");
           $rs=(($ctotal >= $dtotal)?($ctotal-$dtotal):($dtotal-$ctotal));
           $rs_closing=(($ctotal >= $dtotal)?"Cr":"DB");
           $party_data[]=['sr_no'=>$i,                    
                        'party_id'=>$party->party_id,
                        'party_name'=>$party->name,
                        'fine'=>$fine,
                        'fine_closing'=>$fine_closing,
                        'rs'=>$rs,
                        'rs_closing'=>$rs_closing
                    ];
            $i++;
       }
       if(isset($party_data) && !empty($party_data)){
        $data['party_data']=$party_data;
       }else{
        $data['party_data']=[];
       }
       $this->load->view('admin/controller/header');
       $this->load->view('admin/controller/sidebar');
       $this->load->view('admin/rough/ledger/final_print',$data);
       $this->load->view('admin/controller/footer');
    }
}