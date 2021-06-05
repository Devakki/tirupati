<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BankPayment extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Genral_datatable');
        $this->load->model('General_model');
        $this->load->database();
        $this->load->library('session');
        $this->General_model->auth_master();
    }
    public function index()
    {
        $data['page_title']="Bank's Payment";
        $data['method']="add";
        $data['frm_id']="Add_frm";
        $data['bank']=$this->General_model->get_data('bank','status','*','1');
        $this->load->view('admin/controller/header');
        $this->load->view('admin/controller/sidebar');
        $this->load->view('admin/sell/bankpayment',$data);
        $this->load->view('admin/controller/footer');
    }
    function getLists(){
        $table='bank_payment';
        $order_column_array=array('id','ac_type','name','date','rs','remark');
        $search_order= array('name','date','rs','remark');
        $order_by_array= array('id' => 'ASC');
        $data = $row = array();
        $Master_Data = $this->Genral_datatable->getRows($_POST,$table,$order_column_array,$search_order,$order_by_array);
        $i = $_POST['start'];
        foreach($Master_Data as $m_data){
            $i++;
            $remark=((isset($m_data->remark) && !empty($m_data->remark))?$m_data->remark:"N-A");
            $data[] =   [$i,
                        $m_data->name, 
                        date('d/m/Y',strtotime($m_data->date)), 
                        number_format($m_data->rs,2),
                        $remark,
                        '<a href="'.base_url('BankPayment/get_editfrm/').$m_data->id.'"><button type="button" class="btn btn-custom waves-effect waves-light"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                            <button type="button" class="btn btn-danger waves-effect waves-light" data-id="delete" data-value="'.$m_data->id.'"><i class="fa fa-trash" aria-hidden="true"></i></button>'
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
        $bank_id=$this->input->post("bank");
        $date=$this->input->post("date");
        $date=explode("/", $date);
        $date=[$date[2],$date[1],$date[0]];
        $date=implode("-", $date);
        $rs=$this->input->post("rs");
        $remark=$this->input->post("remark");
        $ac_type=$this->input->post("ac_type");
        if(isset($bank_id) && !empty($bank_id) && isset($date) && !empty($date) && isset($rs) && !empty($rs) && isset($ac_type) && !empty($ac_type) ){
                $bank=$this->General_model->get_row('bank','id',$bank_id);
                if(!isset($remark) && empty($remark)){
                    $remark=NULL;
                }
                $data=['ac_type'=>$ac_type,
                            'bank_id '=>$bank_id,
                            'name'=>$bank->name." - ".$bank->ac_number,
                            'date'=>$date,
                            'rs'=>$rs,
                            'remark'=>$remark,
                            'created_at'=>date("Y-m-d h:i:s")];
            $this->db->insert('bank_payment',$data);
            $data['status']="success";
            $data['msg']="Bank's Money Added" ;       
        }else{
            $data['status']="error";
            $data['msg']="Something is Worng";              
        }
        echo json_encode($data);
    }
    public function get_editfrm($id)
    {
        $this->General_model->auth_check();
        $data['page_title']="bank Payment";
        $data['method']="edit";
        $data['frm_id']="Edit_frm";
        $data['payment']=$this->General_model->get_row('bank_payment','id',$id);
        $data['bank']=$this->General_model->get_data('bank','status','*','1');
        $this->load->view('admin/controller/header');
        $this->load->view('admin/controller/sidebar');
        $this->load->view('admin/sell/bankpayment',$data);
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
        $data['action']=base_url('SellPayment/ledger');
        $data['customer']=$this->General_model->get_data('customer','status','*','1');
        $currentYear = date('Y');
        $data['year']=range(START_YR, $currentYear);
        $data['display']=false;
        $customer_id=$this->input->post("customer");
        $year=$this->input->post("year");
        if(isset($customer_id) && !empty($customer_id) && isset($year) && !empty($year)){
            $data['year_op']=$year;
            $data['customer_id']=$customer_id;
            $data['btn_url']='print_ledger?customer='.$customer_id.'&year='.$year;
            $yr=explode("-", $year);
            $fstdate=$yr[0]."-4-1";
            $snddate=$yr[1]."-3-31";
            $data['display']=true;
            $data['method']="edit";
            $customer=$this->db->query("SELECT t1.*,t2.name as city_name FROM customer as t1 LEFT JOIN city as t2 ON t1.city_id=t2.id WHERE t1.id_customer='".$customer_id."'")->row(); 
            $data['acc_name']=ucwords($customer->name).", (".$customer->city_name.")";
            $data['yr_frm']=date('d/m/Y', strtotime($fstdate))." TO ".date('d/m/Y', strtotime($snddate));
            $data['debit']=$this->db->query("SELECT t1.*,t2.gst_type, t2.invoice_no FROM sell_payment as t1 LEFT JOIN sell_invoice as t2 ON t1.sellinvoice_id=t2.id_sell WHERE t1.bill_type='2' and  t1.date<='".$snddate."' AND t1.date >='".$fstdate."' AND t1.customer_id='".$customer_id."' ORDER BY t1.`date` DESC")->result();
            $data['d_total']=$this->db->query("SELECT SUM(t1.rs) as d_total FROM sell_payment as t1 LEFT JOIN sell_invoice as t2 ON t1.sellinvoice_id=t2.id_sell WHERE t1.bill_type='2' and  t1.date<='".$snddate."' AND t1.date >='".$fstdate."' AND t1.customer_id='".$customer_id."'")->row();
            $data['credit']=$this->db->query("SELECT t1.*,t2.gst_type, t2.invoice_no FROM sell_payment as t1 LEFT JOIN sell_purchase as t2 ON t1.sellpurchase_id=t2.id_sellpurchase WHERE t1.bill_type='1' and  t1.date<='".$snddate."' AND t1.date >='".$fstdate."' AND t1.customer_id='".$customer_id."' ORDER BY t1.`date` DESC")->result();
            $data['c_total']=$this->db->query("SELECT SUM(rs) as c_total FROM sell_payment as t1 LEFT JOIN sell_purchase as t2 ON t1.sellpurchase_id=t2.id_sellpurchase WHERE t1.bill_type='1' and  t1.date<='".$snddate."' AND t1.date >='".$fstdate."' AND t1.customer_id='".$customer_id."'")->row();
        }
        $this->load->view('admin/controller/header');
        $this->load->view('admin/controller/sidebar');
        $this->load->view('admin/sell/ledger',$data);
        $this->load->view('admin/controller/footer');
    }
    public function print_ledger()
    {
        $data['page_title']="Ledger Print";
        $currentYear = date('Y');
        $customer_id=$this->input->get("customer");
        $year=$this->input->get("year");
        $data['display']=false;
        if(isset($customer_id) && !empty($customer_id) && isset($year) && !empty($year)){                      
            $yr=explode("-", $year);
            $fstdate=$yr[0]."-4-1";
            $snddate=$yr[1]."-3-31";
            $data['display']=true;
            $customer=$this->db->query("SELECT t1.*,t2.name as city_name FROM customer as t1 LEFT JOIN city as t2 ON t1.city_id=t2.id WHERE t1.id_customer='".$customer_id."'")->row(); 
            $data['acc_name']=ucwords($customer->name).", (".$customer->city_name.")";
            $data['yr_frm']=date('d/m/Y', strtotime($fstdate))." TO ".date('d/m/Y', strtotime($snddate));
            $data['debit']=$this->db->query("SELECT t1.*,t2.gst_type, t2.invoice_no FROM sell_payment as t1 LEFT JOIN sell_invoice as t2 ON t1.sellinvoice_id=t2.id_sell WHERE t1.bill_type='2' and  t1.date<='".$snddate."' AND t1.date >='".$fstdate."' AND t1.customer_id='".$customer_id."' ORDER BY t1.`date` DESC")->result();
            $data['d_total']=$this->db->query("SELECT SUM(t1.rs) as d_total FROM sell_payment as t1 LEFT JOIN sell_invoice as t2 ON t1.sellinvoice_id=t2.id_sell WHERE t1.bill_type='2' and  t1.date<='".$snddate."' AND t1.date >='".$fstdate."' AND t1.customer_id='".$customer_id."'")->row();
            $data['credit']=$this->db->query("SELECT t1.*,t2.gst_type, t2.invoice_no FROM sell_payment as t1 LEFT JOIN sell_purchase as t2 ON t1.sellpurchase_id=t2.id_sellpurchase WHERE t1.bill_type='1' and  t1.date<='".$snddate."' AND t1.date >='".$fstdate."' AND t1.customer_id='".$customer_id."' ORDER BY t1.`date` DESC")->result();
            $data['c_total']=$this->db->query("SELECT SUM(rs) as c_total FROM sell_payment as t1 LEFT JOIN sell_purchase as t2 ON t1.sellpurchase_id=t2.id_sellpurchase WHERE t1.bill_type='1' and  t1.date<='".$snddate."' AND t1.date >='".$fstdate."' AND t1.customer_id='".$customer_id."'")->row();
        }
        $this->load->view('admin/controller/header');
        $this->load->view('admin/controller/sidebar');
        $this->load->view('admin/sell/print_ledger',$data);
        $this->load->view('admin/controller/footer');
    }
}