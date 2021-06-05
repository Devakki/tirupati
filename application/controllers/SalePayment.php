<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class SalePayment extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Genral_datatable');
        $this->load->model('General_model');
        $this->load->model('SellModel');
        $this->load->database();
        $this->load->library('session');
        $this->General_model->auth_master();
    }
    public function index()
    {
        $data['page_title']="Sale Payment";
        $data['method']="add";
        $data['frm_id']="Add_frm";
        $data['customer']=$this->General_model->get_data('customer','status','*','1');
        $this->load->view('admin/controller/header');
        $this->load->view('admin/controller/sidebar');
        $this->load->view('admin/data/salepayment',$data);
        $this->load->view('admin/controller/footer');
    }
    public function myFunction()
    {
        $columns = array( 
                            0 =>'id', 
                            1 =>'bill_type',
                            2=> 'customer_name',
                            3=> 'date',
                            4=> 'rs',
                            5=> 'remark'
                        );
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $totalData = $this->SellModel->allposts_count();
        $totalFiltered = $totalData; 
        if(empty($this->input->post('search')['value']))
        {            
            $posts = $this->SellModel->allposts($limit,$start,$order,$dir);
        }
        else {
            $search = $this->input->post('search')['value']; 
            $posts =  $this->SellModel->posts_search($limit,$start,$search,$order,$dir);
            $totalFiltered = $this->SellModel->posts_search_count($search);
        }
        $data = array();
        if(!empty($posts))
        {
            $i=1;
            foreach ($posts as $post)
            {
                $nestedData['sr_no'] =$i;
                $nestedData['bill_type'] =(($post->bill_type=="1")?"Credit":"Debit");
                $nestedData['customer'] =$post->customer_name;
                $nestedData['date'] = date('d/m/Y',strtotime($post->date));
                $nestedData['rs'] = $post->rs ;
                $nestedData['remark'] = $post->remark ;
                $nestedData['button'] ='<a href="'.base_url('SalePayment/get_editfrm/').$post->id.'"><button type="button" class="btn btn-custom waves-effect waves-light"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                        <button type="button" class="btn btn-danger waves-effect waves-light" data-id="delete" data-value="'.$post->id.'"><i class="fa fa-trash" aria-hidden="true"></i></button>';
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
        $customer_id=$this->input->post("customer");
        $date=$this->input->post("date");
        $date=explode("/", $date);
        $date=[$date[2],$date[1],$date[0]];
        $date=implode("-", $date);
        $rs=$this->input->post("rs");
        $remark=$this->input->post("remark");
        $bill_type=$this->input->post("bill_type");
        if(isset($customer_id) && !empty($customer_id) && isset($date) && !empty($date) && isset($rs) && !empty($rs) && isset($bill_type) && !empty($bill_type) ){
                $customer=$this->General_model->get_row('customer','id_customer',$customer_id);
                if(!isset($remark) && empty($remark)){
                    $remark=NULL;
                }
                $data=['bill_type'=>$bill_type,
                            'customer_id '=>$customer_id,
                            'customer_name'=>$customer->name,
                            'date'=>$date,
                            'sellinvoice_id'=>NULL,
                            'sellpurchase_id'=>NULL,
                            'rs'=>$rs,
                            'remark'=>$remark,
                            'created_at'=>date("Y-m-d h:i:s")];
            $detail=$this->db->insert('sell_payment',$data);
            $data['status']="success";
            $data['msg']="Customer's Payment Added" ;       
        }else{
            $data['status']="error";
            $data['msg']="Something is Worng";              
        }
        echo json_encode($data);
    }
    public function get_editfrm($id)
    {
        $this->General_model->auth_check();
        $data['page_title']="Sell Payment";
        $data['method']="edit";
        $data['frm_id']="Edit_frm";
        $data['payment']=$this->General_model->get_row('sell_payment','id',$id);
        $data['customer']=$this->General_model->get_data('customer','status','*','1');
        $this->load->view('admin/controller/header');
        $this->load->view('admin/controller/sidebar');
        $this->load->view('admin/data/salepayment',$data);
        $this->load->view('admin/controller/footer');
    }
    public function update()
    {
        $this->General_model->auth_check();
        $customer_id=$this->input->post("customer");
        $date=$this->input->post("date");
        $date=explode("/", $date);
        $date=[$date[2],$date[1],$date[0]];
        $date=implode("-", $date);
        $rs=$this->input->post("rs");
        $remark=$this->input->post("remark");
        $id_payment=$this->input->post("id_payment");
        $bill_type=$this->input->post("bill_type");
        if(isset($customer_id) && !empty($customer_id) && isset($date) && !empty($date) && isset($rs) && !empty($rs) && isset($bill_type) && !empty($bill_type) ){
                $customer=$this->General_model->get_row('customer','id_customer',$customer_id);;
                if(!isset($remark) && empty($remark)){
                    $remark=NULL;
                }
                $data=['bill_type'=>$bill_type,
                            'customer_id '=>$customer_id,
                            'customer_name'=>$customer->name,
                            'date'=>$date,
                            'rs'=>$rs,
                            'remark'=>$remark ];
            $this->General_model->update('sell_payment',$data,'id',$id_payment);            
            $data['status']="success";
            $data['msg']="Customer's Payment updated" ;       
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
            $this->General_model->delete('sell_payment','id',$id);           
            $data['status']="success";
            $data['msg']="Customer's Payment updated" ;       
        }else{
            $data['status']="error";
            $data['msg']="Something is Worng";              
        }
        echo json_encode($data);
    } 
    public function ledger()
    {
        $data['page_title']="Ledger";
        $data['method']="view";
        $data['action']=base_url('SalePayment/ledger');
        $data['customer']=$this->General_model->get_data('customer','status','*','1');
        $strt_date=$this->input->post("start");
        $end_date=$this->input->post("end");
        $data['display']=false;
        $customer_id=$this->input->post("customer");
        if(isset($customer_id) && !empty($customer_id) && isset($strt_date) && !empty($strt_date) && isset($end_date) && !empty($end_date)){
            $data['method']="edit";
            $data['btn_url']='print_ledger?customer='.$customer_id.'&start='.$strt_date.'&end='.$end_date.'';
            $strt_date=explode("/", $strt_date);
            $strt_date=[$strt_date[2],$strt_date[1],$strt_date[0]];
            $strt_date=implode("-",$strt_date);
            $end_date=explode("/", $end_date);
            $end_date=[$end_date[2],$end_date[1],$end_date[0]];
            $end_date=implode("-",$end_date);
            $data['strt_date']=date('d/m/Y', strtotime($strt_date));
            $data['end_date']=date('d/m/Y', strtotime($end_date));
            $data['customer_id']=$customer_id;
            $data['display']=true;
            $customer=$this->db->query("SELECT t1.*,t2.name as city_name FROM customer as t1 LEFT JOIN city as t2 ON t1.city_id=t2.id WHERE t1.id_customer='".$customer_id."'")->row(); 
            $data['acc_name']=ucwords($customer->name).", (".$customer->city_name.")";
            $data['debit']=$this->db->query("SELECT t1.*,t2.gst_type, t2.invoice_no FROM sell_payment as t1 LEFT JOIN sell_invoice as t2 ON t1.sellinvoice_id=t2.id_sell WHERE t1.bill_type='2' and  t1.date<='".$end_date."' AND t1.date >='".$strt_date."' AND t1.customer_id='".$customer_id."' ORDER BY t1.`date` ASC")->result();            
            $data['credit']=$this->db->query("SELECT t1.*,t2.gst_type, t2.invoice_no FROM sell_payment as t1 LEFT JOIN sell_purchase as t2 ON t1.sellpurchase_id=t2.id_sellpurchase WHERE t1.bill_type='1' and  t1.date<='".$end_date."' AND t1.date >='".$strt_date."' AND t1.customer_id='".$customer_id."' ORDER BY t1.`date` ASC")->result();
            $data['c_total']=$this->db->query("SELECT SUM(rs) as c_total FROM sell_payment as t1 LEFT JOIN sell_purchase as t2 ON t1.sellpurchase_id=t2.id_sellpurchase WHERE t1.bill_type='1' and  t1.date<='".$end_date."' AND t1.date >='".$strt_date."' AND t1.customer_id='".$customer_id."'")->row();
            $data['d_total']=$this->db->query("SELECT SUM(t1.rs) as d_total FROM sell_payment as t1 LEFT JOIN sell_invoice as t2 ON t1.sellinvoice_id=t2.id_sell WHERE t1.bill_type='2' and  t1.date<='".$end_date."' AND t1.date >='".$strt_date."' AND t1.customer_id='".$customer_id."'")->row();
        }
        $this->load->view('admin/controller/header');
        $this->load->view('admin/controller/sidebar');
        $this->load->view('admin/sales/ledger/ledger',$data);
        $this->load->view('admin/controller/footer');
    }
    public function print_ledger()
    {
        $data['page_title']="Ledger Print";
        $customer_id=$this->input->get("customer");
        $strt_date=$this->input->get("start");
        $end_date=$this->input->get("end");
        $data['display']=false;
        if(isset($customer_id) && !empty($customer_id)  && isset($strt_date) && !empty($strt_date) && isset($end_date) && !empty($end_date)){
            $strt_date=explode("/", $strt_date);
            $strt_date=[$strt_date[2],$strt_date[1],$strt_date[0]];
            $strt_date=implode("-",$strt_date);
            $end_date=explode("/", $end_date);
            $end_date=[$end_date[2],$end_date[1],$end_date[0]];
            $end_date=implode("-",$end_date);
            $data['strt_date']=date('d/m/Y', strtotime($strt_date));
            $data['end_date']=date('d/m/Y', strtotime($end_date));
            $data['customer_id']=$customer_id;
            $data['display']=true;
            $customer=$this->db->query("SELECT t1.*,t2.name as city_name FROM customer as t1 LEFT JOIN city as t2 ON t1.city_id=t2.id WHERE t1.id_customer='".$customer_id."'")->row(); 
            $data['acc_name']=ucwords($customer->name).", (".$customer->city_name.")";
            $data['debit']=$this->db->query("SELECT t1.*,t2.gst_type, t2.invoice_no FROM sell_payment as t1 LEFT JOIN sell_invoice as t2 ON t1.sellinvoice_id=t2.id_sell WHERE t1.bill_type='2' and  t1.date<='".$end_date."' AND t1.date >='".$strt_date."' AND t1.customer_id='".$customer_id."' ORDER BY t1.`date` ASC")->result();            
            $data['credit']=$this->db->query("SELECT t1.*,t2.gst_type, t2.invoice_no FROM sell_payment as t1 LEFT JOIN sell_purchase as t2 ON t1.sellpurchase_id=t2.id_sellpurchase WHERE t1.bill_type='1' and  t1.date<='".$end_date."' AND t1.date >='".$strt_date."' AND t1.customer_id='".$customer_id."' ORDER BY t1.`date` ASC")->result();
            $data['c_total']=$this->db->query("SELECT SUM(rs) as c_total FROM sell_payment as t1 LEFT JOIN sell_purchase as t2 ON t1.sellpurchase_id=t2.id_sellpurchase WHERE t1.bill_type='1' and  t1.date<='".$end_date."' AND t1.date >='".$strt_date."' AND t1.customer_id='".$customer_id."'")->row();
            $data['d_total']=$this->db->query("SELECT SUM(t1.rs) as d_total FROM sell_payment as t1 LEFT JOIN sell_invoice as t2 ON t1.sellinvoice_id=t2.id_sell WHERE t1.bill_type='2' and  t1.date<='".$end_date."' AND t1.date >='".$strt_date."' AND t1.customer_id='".$customer_id."'")->row();
        }
        $this->load->view('admin/controller/header');
        $this->load->view('admin/controller/sidebar');
        $this->load->view('admin/sales/ledger/print_ledger',$data);
        $this->load->view('admin/controller/footer');
    }
    public function final_report()
    {
       $customers=$this->db->query("SELECT t1.customer_id,t2.name FROM sell_payment as t1 LEFT JOIN customer as t2 ON t1.customer_id=t2.id_customer GROUP BY t1.customer_id")->result();
       $data['page_title']="Final Report";
       $data['display']=true;
       $i=1;
       foreach ($customers as  $customers) {
           $c_total=$this->db->query("SELECT SUM(rs) as c_total FROM `sell_payment` WHERE `bill_type`='1' and `customer_id`='".$customers->customer_id."'")->row();
           $d_total=$this->db->query("SELECT SUM(rs) as d_total FROM `sell_payment` WHERE `bill_type`='2' and `customer_id`='".$customers->customer_id."'")->row();
           $ctotal=((isset($c_total->c_total) && !empty($c_total->c_total))?($c_total->c_total):0);
           $dtotal=((isset($d_total->d_total) && !empty($d_total->d_total))?($d_total->d_total):0);
           $rs=(($ctotal >= $dtotal)?($ctotal-$dtotal):($dtotal-$ctotal));
           $rs_closing=(($ctotal >= $dtotal)?"Cr":"DB");
           $customer_data[]=['sr_no'=>$i,                    
                        'customer_id'=>$customers->customer_id,
                        'customer_name'=>$customers->name,
                        'rs'=>$rs,
                        'rs_closing'=>$rs_closing
                    ];
            $i++;
       }
       $data['customer_data']=$customer_data;
       $this->load->view('admin/controller/header');
       $this->load->view('admin/controller/sidebar');
       $this->load->view('admin/sales/ledger/final_ledger',$data);
       $this->load->view('admin/controller/footer');
    }
}