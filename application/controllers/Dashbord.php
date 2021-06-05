<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class  Dashbord extends CI_Controller {
	function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');
        $this->load->model('General_model');
        $this->load->model('Genral_datatable');
    }
	public function index()
	{
		$this->General_model->auth_check();
		$data['page_title']="Dashbord";
	
			$data['sticky_note']=$this->db->query("SELECT * FROM sticky_notes order by id ASC")->result();
		
		$this->load->view('admin/controller/header');
		$this->load->view('admin/controller/sidebar');
		$this->load->view('admin/index',$data);
		$this->load->view('admin/controller/footer');
	}
	public function getLists(){
			$this->General_model->auth_check();
			$table='sell_invoice';
			$order_column_array=array('id_sell', 'bill_type','gst_type','invoice_no','buyer_name','address','city','state','country','date','mobile','subtotal','all_gst','grandtotal');
			$search_order= array('bill_type','invoice_no','address','buyer_name','city','date','mobile','subtotal','all_gst','grandtotal');
			$order_by_array= array('id_sell' => 'ASC');
	        $data = $row = array();
	        $Master_Data = $this->Genral_datatable->getRows($_POST,$table,$order_column_array,$search_order,$order_by_array);
	        $i = $_POST['start'];
	        foreach($Master_Data as $m_data){
	            $i++;
	            $data[] = ["#".$m_data->invoice_no,
	    					ucwords($m_data->buyer_name),
	            			ucfirst($m_data->bill_type),		        				
	    					date('d/m/Y',Strtotime($m_data->date)),
	    					"₹".number_format($m_data->grandtotal,2),
	    					'<a href="'.base_url('SellInvoice/get_editfrm/').$m_data->id_sell.'"><button type="button" class="btn btn-custom waves-effect waves-light"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
	    					<button type="button" class="btn btn-danger waves-effect waves-light" data-id="delete" data-value="'.$m_data->id_sell.'"><i class="fa fa-trash" aria-hidden="true"></i></button>
	    					<a href="'.base_url('SellInvoice/invoice/').$m_data->id_sell.'"><button type="button" class="btn btn-primary waves-effect waves-light"><i class="fa fa-eye"></i></button></a>
	    					'];
	        }
	        $output = array(
	            "draw" => $_POST['draw'],
	            "recordsTotal" => $this->Genral_datatable->countAll($table),
	            "recordsFiltered" => $this->Genral_datatable->countFiltered($_POST,$table,$order_column_array,$search_order,$order_by_array),
	            "data" => $data,
	        );
	        echo json_encode($output);
	}
}