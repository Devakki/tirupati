<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class  SellPurchase extends CI_Controller {
	function __construct() {
        parent::__construct();        
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');
        $this->load->model('General_model');
        $this->load->model('Genral_datatable');
        $this->load->database();
        $this->General_model->auth_master();
    }
	public function index()
	{
		$this->General_model->auth_check();
		$data['page_title']="Purchase";
		$this->load->view('admin/controller/header');
		$this->load->view('admin/controller/sidebar');
		$this->load->view('admin/sales/purchase/purchase_detail',$data);
		$this->load->view('admin/controller/footer');
	}
	public function get_addfrm()
	{
		$this->General_model->auth_check();
		$data['page_title']="Purchase";
		$data["customer"]=$this->General_model->get_all_where('customer','status','1');
		$data["product"]=$this->General_model->get_all_where('product','status','1');
		$data["method"]="add";
		$data["settings"]=$this->General_model->get_data('settings','s_index','*','1');
		$data['action']=base_url('SellPurchase/create');
		$this->load->view('admin/controller/header');
		$this->load->view('admin/controller/sidebar');
		$this->load->view('admin/partial/sell_purchase',$data);
		$this->load->view('admin/controller/footer');
	}
	public function create()
	{
		$this->General_model->auth_check();
		$bill_type=$this->input->post("bill_type");
		$gst_type=$this->input->post("gst_type");
		$invoice_no=$this->input->post("invoice_no");
		$customer=$this->input->post("customer_id");	
		$date=$this->input->post("date");
		$date=explode("/", $date);
		$date=[$date[2],$date[1],$date[0]];
		$date=implode("-", $date);
		$touch=$this->input->post("touch");
		$s_total=$this->input->post("sub_total");
		$all_gst=$this->input->post("all_gst");
		$g_total=$this->input->post("grand_total");		
		if(isset($bill_type) && !empty($bill_type) &&  isset($invoice_no) && !empty($invoice_no) && isset($customer) && !empty($customer) &&  isset($date) && !empty($date) && isset($s_total) && !empty($s_total) &&  isset($g_total) && !empty($g_total) ) {		
			$i=0;
			$query=$this->db->query("SELECT t1.*,t2.name as city_name ,t3.name as state_name,t3.country FROM customer as t1 LEFT JOIN city as t2 ON t1.city_id = t2.id LEFT JOIN state as t3 ON t1.state_id = t3.id WHERE t1.`id_customer`='".$customer."'")->row();
			$sell_purchase=['bill_type'=>$bill_type,
							'gst_type'=>$gst_type,
							'customer_id'=>$customer,
							'touch'=>$touch,
							'invoice_no'=>$invoice_no,
							'buyer_name'=>$query->name,
							'gst_no'=>$query->gst_no,
							'address'=>$query->address,
							'city'=>$query->city_name,
							'state'=>$query->state_name,
							'country'=>$query->country,
							'date'=>$date,
							'mobile'=>$query->mobile,							
							'subtotal'=>$s_total,
							'all_gst'=>$all_gst,
							'grandtotal'=>$g_total,
							'status'=>'1',
							'created_at'=>date("Y-m-d h:i:s")
						];
			$this->db->insert('sell_purchase',$sell_purchase);
			$lastid= $this->db->insert_id();			
			foreach ($this->input->post("product_id") as $pr) {
				$product_id=$this->input->post("product_id")[$i];
				$quantity=$this->input->post("quantity")[$i];
				$price=$this->input->post("item_price")[$i];
				$total=$this->input->post("total")[$i];
				if($gst_type=="1"):		
					$cgst=$this->input->post("cgst")[$i];
					$sgst=$this->input->post("sgst")[$i];
				else:
					$igst=$this->input->post("igst")[$i];
				endif;
				$amount=$this->input->post("amount")[$i];
				if(!empty($product_id) && !empty($quantity) && !empty($price)&& !empty($total)&& !empty($amount) && !empty($lastid)) {
					$setting=$this->General_model->get_data('settings','s_index','*','1');
					if($gst_type=="1"):	
						$sell_product=['SellPurchase_id'=>$lastid,
											'product_id'=>$product_id,
											'quantity'=>$quantity,
											'date'=>$date,
											'price'=>$price,
											'total'=>$total,
											'sgst_p'=>$setting[0]->s_value,
											'cgst_p'=>$setting[1]->s_value,
											'cgst'=>$cgst,
											'sgst'=>$sgst,
											'amount'=>$amount,
											'status'=>1,					
											'created_at'=>date("Y-m-d h:i:s")];
					else:
						$sell_product=['SellPurchase_id'=>$lastid,
											'product_id'=>$product_id,
											'quantity'=>$quantity,
											'date'=>$date,
											'price'=>$price,
											'total'=>$total,
											'igst_p'=>$setting[2]->s_value,
											'igst'=>$igst,
											'amount'=>$amount,
											'status'=>1,					
											'created_at'=>date("Y-m-d h:i:s")];
					endif;				
						$this->db->insert('sellpurchase_product',$sell_product);		
					} 		
					$i++;
				}
				$sell_payment = ['bill_type'=>'1',
									'date'=>$date,
									'rs'=>$g_total,
									'sellinvoice_id'=>NULL,
									'customer_id'=>$customer,
									'customer_name'=>$query->name,
									'sellpurchase_id'=>$lastid,
									'remark'=>NULL,
									'status'=>'0',
									'created_at'=>date("Y-m-d h:i:s") ];
				$this->db->insert('sell_payment',$sell_payment);
				$this->session->set_userdata('Msg','Sell Purchase Added');
			}else{
				$this->session->set_userdata('Msg','warning');			
			}					
			redirect('SellPurchase');
	}
	public function getLists(){
			$this->General_model->auth_check();
			$table='sell_purchase';
			$order_column_array=array('id_sellpurchase', 'bill1_type','gst_type','invoice_no','buyer_name','gst_no','address','city','state','country','date','mobile','subtotal','all_gst','grandtotal');
			$search_order= array('bill_type','invoice_no','address','buyer_name','city','date','mobile','subtotal','all_gst','grandtotal');
			$order_by_array= array('id_sellpurchase' => 'ASC');
            $data = $row = array();
            
	        $Master_Data = $this->Genral_datatable->getRows($_POST,$table,$order_column_array,$search_order,$order_by_array);
	        $i = $_POST['start'];
	        foreach($Master_Data as $m_data){
	            $i++;
	            $data[] = 	[$i,
                            ucwords($m_data->buyer_name),
                            $m_data->gst_no,
	    					date('d/m/Y',Strtotime($m_data->date)),
                            $m_data->invoice_no,
                            $m_data->subtotal,
                            $m_data->all_gst,
	    					$m_data->grandtotal,
	    					'<a href="'.base_url('SellPurchase/get_editfrm/').$m_data->id_sellpurchase.'"><button type="button" class="btn btn-custom waves-effect waves-light"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
	    					<button type="button" class="btn btn-danger waves-effect waves-light" data-id="delete" data-value="'.$m_data->id_sellpurchase.'"><i class="fa fa-trash" aria-hidden="true"></i></button>
	    					<a href="'.base_url('SellPurchase/invoice_view/').$m_data->id_sellpurchase.'"><button type="button" class="btn btn-primary waves-effect waves-light"><i class="fa fa-eye"></i></button></a>
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
	public function get_editfrm($id){
		$this->General_model->auth_check();
		$data['method']="edit";  
		$data["customer"]=$this->General_model->get_all_where('customer','status','1');
		$data["product"]=$this->General_model->get_all_where('product','status','1');	    	    	
		$data['sell_invoice']=$this->General_model->get_row('sell_purchase','id_sellpurchase',$id);
		$data["settings"]=$this->General_model->get_data('settings','s_index','*','1');
		$data["sell_product"]=$this->General_model->get_data('sellpurchase_product','sellpurchase_id','*',$id);
		$data['action']=base_url('SellPurchase/update');
		$data['page_title']="Purchase";
		$this->load->view('admin/controller/header');
		$this->load->view('admin/controller/sidebar');
		$this->load->view('admin/partial/sell_purchase',$data);
		$this->load->view('admin/controller/footer');
	}
    public function update(){
		$this->General_model->auth_check();
		$bill_type=$this->input->post("bill_type");
		$gst_type=$this->input->post("gst_type");
		$invoice_no=$this->input->post("invoice_no");
		$customer=$this->input->post("customer_id");	
		$date=$this->input->post("date");
		$date=explode("/", $date);
		$date=[$date[2],$date[1],$date[0]];
		$date=implode("-", $date);
		$s_total=$this->input->post("sub_total");
		$all_gst=$this->input->post("all_gst");
		$g_total=$this->input->post("grand_total");
		$touch=$this->input->post("touch");
		$id_purchase=$this->input->post("id_sell");	
		if(isset($bill_type) && !empty($bill_type) &&  isset($invoice_no) && !empty($invoice_no) && isset($customer) && !empty($customer) &&  isset($date) && !empty($date) && isset($s_total) && !empty($s_total) &&  isset($g_total) && !empty($g_total) &&  isset($id_purchase) && !empty($id_purchase)  &&  isset($touch) && !empty($touch) ) {		
	    			$i=0;
	    			$query=$this->db->query("SELECT t1.*,t2.name as city_name ,t3.name as state_name,t3.country FROM customer as t1 LEFT JOIN city as t2 ON t1.city_id = t2.id LEFT JOIN state as t3 ON t1.state_id = t3.id WHERE t1.`id_customer`='".$customer."'")->row();			
	    			$sell_purchase=['bill_type'=>$bill_type,
							'gst_type'=>$gst_type,
							'customer_id'=>$customer,
							'touch'=>$touch,
							'invoice_no'=>$invoice_no,
							'buyer_name'=>$query->name,
							'address'=>$query->address,
							'city'=>$query->city_name,
							'state'=>$query->state_name,
							'country'=>$query->country,
							'date'=>$date,
							'mobile'=>$query->mobile,							
							'subtotal'=>$s_total,
							'all_gst'=>$all_gst,
							'grandtotal'=>$g_total
						];
	    			$this->General_model->update('sell_purchase',$sell_purchase,'id_sellpurchase',$id_purchase);
	    			foreach ($this->input->post("product_id") as $pr) {
							$product_id=$this->input->post("product_id")[$i];
							$quantity=$this->input->post("quantity")[$i];
							$price=$this->input->post("item_price")[$i];
							$total=$this->input->post("total")[$i];
							$id_sellproduct=$this->input->post("sellproduct")[$i];
							$fine=$this->input->post("fine")[$i];
							$amount=$this->input->post("amount")[$i];
							$setting=$this->General_model->get_data('settings','s_index','*','1');
							if($gst_type=="1"):		
								$cgst=$this->input->post("cgst")[$i];
								$sgst=$this->input->post("sgst")[$i];
								$sell_product=['sellpurchase_id'=>$id_purchase,
													'product_id'=>$product_id,
													'quantity'=>$quantity,
													'price'=>$price,
													'total'=>$total,
													'fine'=>$fine,
													'date'=>$date,
													'sgst_p'=>$setting[0]->s_value,
													'cgst_p'=>$setting[1]->s_value,
													'cgst'=>$cgst,
													'sgst'=>$sgst,
													'amount'=>$amount,
													'status'=>1,					
													'created_at'=>date("Y-m-d h:i:s")];
							else:
								$igst=$this->input->post("igst")[$i];
								$sell_product=['sellpurchase_id'=>$id_purchase,
													'product_id'=>$product_id,
													'quantity'=>$quantity,
													'price'=>$price,
													'fine'=>$fine,
													'date'=>$date,
													'total'=>$total,
													'igst_p'=>$setting[2]->s_value,
													'igst'=>$igst,
													'amount'=>$amount,
													'status'=>1,					
													'created_at'=>date("Y-m-d h:i:s")];
							endif;
							if(isset($id_sellproduct) && !empty($id_sellproduct) && !empty($quantity) && !empty($price)) {
									if($gst_type=="1"):		
										$cgst=$this->input->post("cgst")[$i];
										$sgst=$this->input->post("sgst")[$i];
										$sell_product=['sellpurchase_id'=>$id_purchase,
															'product_id'=>$product_id,
															'quantity'=>$quantity,
															'price'=>$price,
															'total'=>$total,
															'fine'=>$fine,
															'date'=>$date,
															'sgst_p'=>$setting[0]->s_value,
															'cgst_p'=>$setting[1]->s_value,
															'cgst'=>$cgst,
															'sgst'=>$sgst,
															'amount'=>$amount ];
									else:
										$igst=$this->input->post("igst")[$i];
										$sell_product=['sellpurchase_id'=>$id_purchase,
															'product_id'=>$product_id,
															'quantity'=>$quantity,
															'price'=>$price,
															'fine'=>$fine,
															'date'=>$date,
															'total'=>$total,
															'igst_p'=>$setting[2]->s_value,
															'igst'=>$igst,
															'amount'=>$amount];
									endif;			
									$this->General_model->update('sellpurchase_product',$sell_product,'id_sellproduct',$id_sellproduct);	
								}elseif(!empty($quantity) && !empty($price)) {
										if($gst_type=="1"):		
											$cgst=$this->input->post("cgst")[$i];
											$sgst=$this->input->post("sgst")[$i];
											$sell_product=['sellpurchase_id'=>$id_purchase,
																'product_id'=>$product_id,
																'quantity'=>$quantity,
																'price'=>$price,
																'total'=>$total,
																'fine'=>$fine,
																'date'=>$date,
																'sgst_p'=>$setting[0]->s_value,
																'cgst_p'=>$setting[1]->s_value,
																'cgst'=>$cgst,
																'sgst'=>$sgst,
																'amount'=>$amount,
																'status'=>1,					
																'created_at'=>date("Y-m-d h:i:s")];
										else:
											$igst=$this->input->post("igst")[$i];
											$sell_product=['sellpurchase_id'=>$id_purchase,
																'product_id'=>$product_id,
																'quantity'=>$quantity,
																'price'=>$price,
																'fine'=>$fine,
																'date'=>$date,
																'total'=>$total,
																'igst_p'=>$setting[2]->s_value,
																'igst'=>$igst,
																'amount'=>$amount,
																'status'=>1,					
																'created_at'=>date("Y-m-d h:i:s")];
										endif;				
										$this->db->insert('sellpurchase_product',$sell_product);
								}else{
								} 		
							$i++;
						}
					$sell_payment = ['bill_type'=>'1',
										'date'=>$date,
										'rs'=>$g_total,
										'customer_id'=>$customer,
										'customer_name'=>$query->name,
										'status'=>'0'
										 ];
					$this->General_model->update('sell_payment',$sell_payment,'sellpurchase_id',$id_purchase);
	    			$this->session->set_userdata('Msg','Sell Purchase Updated');
	    	}else{
	    		$this->session->set_userdata('Msg','Something Worng');
	    	}
	    	redirect('SellPurchase');
	    }
	    public function delete($id)
	    {
	    	$this->General_model->auth_check();
	    	if(isset($id) && !empty($id)){
	    		$this->General_model->delete('sell_purchase','id_sellpurchase',$id);
	    		$this->General_model->delete('sellpurchase_product','sellpurchase_id',$id);  
	    		$this->General_model->delete('sell_payment','sellpurchase_id',$id); 		
	    		$data['status']="success";
	    		$data['msg']="Purchase Detail Deleted";
	    	}else{
	    		$data['status']="error";
	    		$data['msg']="Something is Worng";				
	    	}
	    	echo json_encode($data);
        }
        public function get_Gst($id)
	    {
	    	$this->General_model->auth_check();
	    	if(isset($id) && !empty($id)){
				$product = $this->General_model->get_row('product','id_product',$id);	
				$CGST = $product->CGST;
				$SGST = $product->SGST;
				$IGST = $product->IGST;
				echo $CGST.'###'.$SGST.'###'.$IGST;
			}
	    }
	    public function invoice_view($id)
	    {
	    	$this->General_model->auth_check();
	    	$data['page_title']="Purchase Invoice";
	    	$data['sell_purchase']=$this->General_model->get_row('sell_purchase','id_sellpurchase',$id);
	    	$data['sell_product']=$this->db->query("SELECT t1.*,t2.name as product_name FROM sellpurchase_product as t1 LEFT JOIN product as t2 ON t1.product_id=t2.id_product WHERE t1.sellpurchase_id='".$id."'")->result();
	    	$data['customer']=$this->db->query("SELECT t1.*,t2.name as city_name,t2.code as city_code,t3.name as state_name,t3.code as state_code,t3.country FROM customer as t1 LEFT JOIN city as t2 ON t1.city_id= t2.id LEFT JOIN state as t3 ON t1.state_id= t3.id WHERE t1.id_customer='".$data['sell_purchase']->customer_id."'")->row(); 
	    	$this->load->view('admin/controller/header');
			$this->load->view('admin/controller/sidebar');
			$this->load->view('admin/sales/purchase/purchase_inv',$data);
			$this->load->view('admin/controller/footer');
	    }
	    public function sellitem_delete($id)
	    {
	    	$this->General_model->auth_check();
	    	if(isset($id) && !empty($id)){
	    		$detail=$this->General_model->delete('sellpurchase_product','id_sellproduct',$id);
	    		$data['status']="success";
	    		$data['msg']="Product Deleted";
	    	}else{
	    		$data['status']="error";
	    		$data['msg']="Something is Worng";				
	    	}
	    	echo json_encode($data);
	    }
}