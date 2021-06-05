<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class  SellInvoice extends CI_Controller {
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
		$data['page_title']="Sale Invoice";
		$this->load->view('admin/controller/header');
		$this->load->view('admin/controller/sidebar');
		$this->load->view('admin/sales/invoice_detail',$data);
		$this->load->view('admin/controller/footer');
	}
	public function get_addfrm()
	{
		$this->General_model->auth_check();
		$data["customer"]=$this->General_model->get_all_where('customer','status','1');
		$data["product"]=$this->General_model->get_all_where('product','status','1');
		$data["method"]="add";
		$data['page_title']="Sale Invoice";
		$data["settings"]=$this->General_model->get_data('settings','s_index','*','1');
		$data['invoice']=$this->db->query("SELECT invoice_no FROM sell_invoice where cur_year=2021 ORDER BY invoice_no  DESC LIMIT 1")->row();
		$data["transpoter"]=$this->General_model->get_data('transpoter','status','*','1'); 
		$data['action']=base_url('SellInvoice/create');
		$date=date("Y-m-d", strtotime("-7days"));
		
		if(empty($data['invoice'])){
			$data['invoice']= array('no_invoice' => '1');
		}else{
			$no_invoice=(($data['invoice']->invoice_no)+1);
			$data['invoice']= array('no_invoice' =>$no_invoice);
		}
		$this->load->view('admin/controller/header');
		$this->load->view('admin/controller/sidebar');
		$this->load->view('admin/partial/sell_invoice',$data);
		$this->load->view('admin/controller/footer');
	}
	public function create()
	{
		$this->General_model->auth_check();
		$bill_type=$this->input->post("bill_type");
		$gst_type=$this->input->post("gst_type");
		$invoice_no=$this->input->post("invoice_no");
		$customer=$this->input->post("customer_id");
		$transpoter_id=$this->input->post("transpoter_id");
		$date=$this->input->post("date");
		$date=explode("/", $date);
		$date=[$date[2],$date[1],$date[0]];
		$date=implode("-", $date);
		$s_total=$this->input->post("sub_total");
		$all_gst=$this->input->post("all_gst");
		$g_total=$this->input->post("grand_total");		
		if(isset($bill_type) && !empty($bill_type) &&  isset($invoice_no) && !empty($invoice_no) && isset($customer) && !empty($customer) &&  isset($date) && !empty($date) && isset($s_total) && !empty($s_total) &&  isset($g_total) && !empty($g_total) ) {		
			$i=0;
			$query=$this->db->query("SELECT t1.*,t2.name as city_name ,t3.name as state_name,t3.country FROM customer as t1 LEFT JOIN city as t2 ON t1.city_id = t2.id LEFT JOIN state as t3 ON t1.state_id = t3.id WHERE t1.`id_customer`='".$customer."'")->row();
			if(isset($transpoter_id) && !empty($transpoter_id) && $transpoter_id !=0){
				$transpoter=$this->General_model->get_row('transpoter','id',$transpoter_id);
				$transpoter_id=$transpoter_id;
				$transpoter_name=$transpoter->name;
			}else{
				$transpoter_id=NULL;
				$transpoter_name=NULL;
			}
			$sell_invoice=['bill_type'=>$bill_type,
							'gst_type'=>$gst_type,
							'customer_id'=>$customer,
							'invoice_no'=>$invoice_no,
							'transpoter_id'=>$transpoter_id,
							'transpoter_name'=>$transpoter_name,
							'buyer_name'=>$query->name,
							'address'=>$query->address,
							'city'=>$query->city_name,
							'state'=>$query->state_name,
							'country'=>$query->country,
							'date'=>$date,
							'mobile'=>$query->mobile,							
							'subtotal'=>$s_total,
							'all_gst'=>$all_gst,
                            'cur_year'=>2021,
							'grandtotal'=>$g_total,
							'status'=>'1',
							'created_at'=>date("Y-m-d h:i:s")
						];
			$this->db->insert('sell_invoice',$sell_invoice);
			$lastid= $this->db->insert_id();			
			foreach ($this->input->post("product_id") as $pr) {
				$product_id=$this->input->post("product_id")[$i];
				$quality=$this->input->post("quality")[$i];
				$price=$this->input->post("item_price")[$i];
				$total=$this->input->post("total")[$i];
				$fine=$this->input->post("fine")[$i];
				if($gst_type=="1"):		
					$cgst=$this->input->post("cgst")[$i];
					$sgst=$this->input->post("sgst")[$i];
				else:
					$igst=$this->input->post("igst")[$i];
				endif;
				$amount=$this->input->post("amount")[$i];
				if(!empty($product_id) && !empty($quality) && !empty($price)&& !empty($total)&& !empty($amount) && !empty($lastid)) {
					$setting=$this->General_model->get_data('settings','s_index','*','1');
					if($gst_type=="1"):	
						$sell_product=['sellinvoice_id'=>$lastid,
											'product_id'=>$product_id,
											'quality'=>$quality,
											'price'=>$price,
											'date'=>$date,
											'sfine'=>$fine,
											'total'=>$total,
											'sgst_p'=>$setting[0]->s_value,
											'cgst_p'=>$setting[1]->s_value,
											'cgst'=>$cgst,
											'sgst'=>$sgst,
											'amount'=>$amount,
											'status'=>1,					
											'created_at'=>date("Y-m-d h:i:s")];
					else:
						$sell_product=['sellinvoice_id'=>$lastid,
											'product_id'=>$product_id,
											'quality'=>$quality,
											'date'=>$date,
											'sfine'=>$fine,
											'price'=>$price,
											'total'=>$total,
											'igst_p'=>$setting[2]->s_value,
											'igst'=>$igst,
											'amount'=>$amount,
											'status'=>1,					
											'created_at'=>date("Y-m-d h:i:s")];
					endif;				
						$this->db->insert('sell_product',$sell_product);		
					} 		
					$i++;
				}
				$sell_payment = ['bill_type'=>'2',
									'date'=>$date,
									'rs'=>$g_total,
									'sellinvoice_id'=>$lastid,
									'customer_id'=>$customer,
									'customer_name'=>$query->name,
									'sellpurchase_id'=>NULL,
									'remark'=>NULL,
									'status'=>'0',
									'created_at'=>date("Y-m-d h:i:s") ];
				$this->db->insert('sell_payment',$sell_payment);
				$this->session->set_userdata('Msg','Invoice Created');
			}else{
				$this->session->set_userdata('Msg','warning');			
			}					
			redirect('SellInvoice');
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
	            $data[] = 	[$i,
	    					ucwords($m_data->buyer_name),
	    					date('d/m/Y',Strtotime($m_data->date)),
	    					$m_data->invoice_no,
	    					$m_data->grandtotal,
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
	public function get_editfrm($id){
		$this->General_model->auth_check();
		$data['method']="edit"; 
		$data['page_title']="Sale Invoice"; 
		$data["customer"]=$this->General_model->get_all_where('customer','status','1');
		$data["product"]=$this->General_model->get_all_where('product','status','1');	    	    	
		$data['sell_invoice']=$this->General_model->get_row('sell_invoice','id_sell',$id);
		$data["settings"]=$this->General_model->get_data('settings','s_index','*','1');
		$data["sell_product"]=$this->General_model->get_data('sell_product','sellinvoice_id','*',$id);
		$data["transpoter"]=$this->General_model->get_data('transpoter','status','*','1');
		$date=date("Y-m-d", strtotime("-7days"));
		$pfine=$this->db->query("SELECT SUM(`fine`) as tfine FROM `sellpurchase_product` WHERE `date`<= '".$date."'")->row();
		$sfine=$this->db->query("SELECT SUM(`sfine`) as sfine FROM `sell_product` WHERE `sellinvoice_id` != '".$id."'")->row();
		if(isset($pfine->tfine) && !empty($pfine->tfine) && $pfine->tfine >=$sfine->sfine){
			$data['tfine']=($pfine->tfine-$sfine->sfine);
		}else{
			$data['tfine']=0;
		}
		$data['action']=base_url('SellInvoice/update');
		$this->load->view('admin/controller/header');
		$this->load->view('admin/controller/sidebar');
		$this->load->view('admin/partial/sell_invoice',$data);
		$this->load->view('admin/controller/footer');
	}
    public function update(){
		$this->General_model->auth_check();
		$bill_type=$this->input->post("bill_type");
		$gst_type=$this->input->post("gst_type");
		$transpoter_id=$this->input->post("transpoter_id");	
		$invoice_no=$this->input->post("invoice_no");
		$customer=$this->input->post("customer_id");	
		$date=$this->input->post("date");
		$date=explode("/", $date);
		$date=[$date[2],$date[1],$date[0]];
		$date=implode("-", $date);
		$pand_fine=$this->input->post("pand_fine");
		$touch=$this->input->post("touch");
		$s_total=$this->input->post("sub_total");
		$all_gst=$this->input->post("all_gst");
		$g_total=$this->input->post("grand_total");
		$id_sell=$this->input->post("id_sell");	
		if(isset($bill_type) && !empty($bill_type) &&  isset($invoice_no) && !empty($invoice_no) && isset($customer) && !empty($customer) &&  isset($date) && !empty($date) && isset($s_total) && !empty($s_total) &&  isset($g_total) && !empty($g_total) &&  isset($id_sell) && !empty($id_sell) && isset($pand_fine) && !empty($pand_fine) &&  isset($touch) && !empty($touch)) {		
	    			$i=0;
	    			$query=$this->db->query("SELECT t1.*,t2.name as city_name ,t3.name as state_name,t3.country FROM customer as t1 LEFT JOIN city as t2 ON t1.city_id = t2.id LEFT JOIN state as t3 ON t1.state_id = t3.id WHERE t1.`id_customer`='".$customer."'")->row();
	    			if(isset($transpoter_id) && !empty($transpoter_id)){
	    				$transpoter=$this->General_model->get_row('transpoter','id',$transpoter_id);
	    				$transpoter_id=$transpoter_id;
	    				$transpoter_name=$transpoter->name;
	    			}else{
	    				$transpoter_id=NULL;
	    				$transpoter_name=NULL;
	    			}			
	    			$sell_invoice=['bill_type'=>$bill_type,
							'gst_type'=>$gst_type,
							'customer_id'=>$customer,
							'pand_find'=>$pand_fine,
							'touch'=>$touch,
							'transpoter_id'=>$transpoter_id,
							'transpoter_name'=>$transpoter_name,
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
	    			$this->General_model->update('sell_invoice',$sell_invoice,'id_sell',$id_sell);
	    			foreach ($this->input->post("product_id") as $pr) {
							$product_id=$this->input->post("product_id")[$i];
							$quality=$this->input->post("quality")[$i];
							$price=$this->input->post("item_price")[$i];
							$total=$this->input->post("total")[$i];
							$fine=$this->input->post("fine")[$i];
							$id_sellproduct=$this->input->post("sellproduct")[$i];
							$amount=$this->input->post("amount")[$i];
							$setting=$this->General_model->get_data('settings','s_index','*','1');
							if(isset($id_sellproduct) && !empty($id_sellproduct) && !empty($product_id) && !empty($quality) && !empty($price)&& !empty($total)&& !empty($amount)) {
								if($gst_type=="1"):
									$cgst=$this->input->post("cgst")[$i];
									$sgst=$this->input->post("sgst")[$i];	
									$sell_product=['sellinvoice_id'=>$id_sell,
														'product_id'=>$product_id,
														'quality'=>$quality,
														'date'=>$date,
														'sfine'=>$fine,
														'price'=>$price,
														'total'=>$total,
														'sgst_p'=>$setting[0]->s_value,
														'cgst_p'=>$setting[1]->s_value,
														'cgst'=>$cgst,
														'sgst'=>$sgst,
														'amount'=>$amount ];
								else:
									$igst=$this->input->post("igst")[$i];
									$sell_product=['sellinvoice_id'=>$id_sell,
														'product_id'=>$product_id,
														'date'=>$date,
														'sfine'=>$fine,
														'quality'=>$quality,
														'price'=>$price,
														'total'=>$total,
														'igst_p'=>$setting[2]->s_value,
														'igst'=>$igst,
														'amount'=>$amount ];
								endif;				
								$this->General_model->update('sell_product',$sell_product,'id_sellproduct',$id_sellproduct);	
								}elseif (!empty($product_id) && !empty($quality) && !empty($price)&& !empty($total)&& !empty($amount)) {
									if($gst_type=="1"):
											$cgst=$this->input->post("cgst")[$i];
											$sgst=$this->input->post("sgst")[$i];	
											$sell_product=['sellinvoice_id'=>$id_sell,
																'product_id'=>$product_id,
																'quality'=>$quality,
																'date'=>$date,
																'sfine'=>$fine,
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
											$igst=$this->input->post("igst")[$i];
											$sell_product=['sellinvoice_id'=>$id_sell,
																'product_id'=>$product_id,
																'date'=>$date,
																'sfine'=>$fine,
																'quality'=>$quality,
																'price'=>$price,
																'total'=>$total,
																'igst_p'=>$setting[2]->s_value,
																'igst'=>$igst,
																'amount'=>$amount,
																'status'=>1,					
																'created_at'=>date("Y-m-d h:i:s")];
										endif;				
										$this->db->insert('sell_product',$sell_product);
								}else{
								} 		
							$i++;
						}
					$sell_payment = ['bill_type'=>'2',
										'date'=>$date,
										'rs'=>$g_total,
										'customer_id'=>$customer,
										'customer_name'=>$query->name,
										'status'=>'0'
										];
					$this->General_model->update('sell_payment',$sell_payment,'sellinvoice_id',$id_sell);
	    			$this->session->set_userdata('Msg','Invoice Updated');
	    	}else{
	    		$this->session->set_userdata('Msg','Something Worng');
	    	}
	    	redirect('SellInvoice');
	    }
	    public function delete($id)
	    {
	    	$this->General_model->auth_check();
	    	if(isset($id) && !empty($id)){
	    		$this->General_model->delete('sell_invoice','id_sell',$id);
	    		$this->General_model->delete('sell_product','sellinvoice_id',$id);
	    		$this->General_model->delete('sell_payment','sellinvoice_id',$id);  		
	    		$data['status']="success";
	    		$data['msg']="Invoice Deleted";
	    	}else{
	    		$data['status']="error";
	    		$data['msg']="Something is Worng";				
	    	}
	    	echo json_encode($data);
	    }
	    public function invoice($id)
	    {
	    	$this->General_model->auth_check();
			require_once APPPATH.'third_party/fpdf/fpdf.php';
	    	$pdf = new FPDF();
	    	$pdf->AddPage();
	    	setlocale(LC_MONETARY, 'en_IN');
	    	$sell_invoice=$this->General_model->get_row('sell_invoice','id_sell',$id);
	    	$sell_product=$this->db->query("SELECT t1.*,t2.name as product_name,t2.hsn_code  FROM sell_product as t1 LEFT JOIN product as t2 ON t1.product_id = t2.id_product WHERE t1.sellinvoice_id='".$id."'")->result(); 
	    	$customer=$this->db->query("SELECT t1.*,t2.name as city_name,t2.code as city_code,t3.name as state_name,t3.code as state_code,t3.country FROM customer as t1 LEFT JOIN city as t2 ON t1.city_id= t2.id LEFT JOIN state as t3 ON t1.state_id= t3.id WHERE t1.id_customer='".$sell_invoice->customer_id."'")->row();
	    	$pdf->SetFont('Arial','B',12);
	    	$image=base_url('assets/admin/images/adwait.png');
			$pdf->Image($image,5,5,200,0,'PNG');
			$pdf->SetXY(12,52.5);
			$pdf->Cell(40,5,ucfirst($sell_invoice->bill_type)." Memo",0,1,'L');
			$pdf->SetXY(178,52.5);
			$pdf->Cell(20,5,"Original",0,1,'L');
			$pdf->SetFont('Arial','',10);
			$pdf->SetXY(23,62);
			$city_code=((isset($customer->city_code) && !empty($customer->city_code))?" - ".$customer->city_code:"");
			$pdf->MultiCell(95,5, strtoupper($customer->name)."\n". strtoupper($customer->address).", ".strtoupper($customer->city_name).$city_code."\n"."Mo :  ".$customer->mobile,0,'L');
			/*invoice No*/
			$pdf->SetXY(157,60.1);
			$pdf->Cell(20,5,$sell_invoice->invoice_no,0,1,'L');
			$pdf->SetXY(157,65.3);
			$pdf->Cell(20,5,date('d/m/Y',strtotime($sell_invoice->date)),0,1,'L');
			if(isset($sell_invoice->transpoter_id) && !empty($sell_invoice->transpoter_id)){
				$transpoter=$this->General_model->get_row('transpoter','id', $sell_invoice->transpoter_id);
				$transpoter_name=$transpoter->name;
				$lr_no=$transpoter->lr_no;
			}else{
				$transpoter_name="N-A";
				$lr_no="N-A";
			}
			$pdf->SetXY(157,75);
			$pdf->MultiCell(43,6,$transpoter_name,0,'L');
			$pdf->SetXY(157,87);
			$pdf->Cell(43,6,$lr_no,0,1,'L');
			$pdf->SetXY(40,85);
			$pdf->Cell(70,5,$customer->gst_no,0,1,'L');
			$state_code=((isset($customer->state_code) && !empty($customer->state_code))?" - ".$customer->state_code:"");
			$pdf->SetXY(40,90);
			$pdf->Cell(70,5,strtoupper($customer->state_name.$state_code),0,1,'L');
			$pdf->SetXY(40,90);
			$pdf->Cell(70,5,strtoupper($customer->state_name.$state_code),0,1,'L');
			$pdf->SetFont('Arial','B',10);
			$pdf->SetXY(39,207);
			$pdf->Cell(70,5,strtoupper(GST_NO),0,1,'L');
			$pdf->SetXY(162,207);
			$pdf->Cell(37,5,money_format('%!i',$sell_invoice->subtotal),0,1,'R');
			$pdf->SetXY(170,216.2);
			$pdf->Cell(29,5,money_format('%!i',$sell_invoice->subtotal),0,1,'R');
			if($sell_invoice->gst_type==2){
				$pdf->SetXY(138.5,227);
				$pdf->Cell(20,5,"IGST   3.0%",0,1,'L');
				$pdf->SetXY(170,227);
				$pdf->Cell(29,5, money_format('%!i',$sell_invoice->all_gst),0,1,'R');
			}else{
				$pdf->SetXY(138.8,223);
				$pdf->Cell(20,5,"SGST  9%",0,1,'L');
				$pdf->SetXY(170,223);
				$pdf->Cell(29,5, money_format('%!i',($sell_invoice->all_gst/2)),0,1,'R');
				$pdf->SetXY(138.8,231);
				$pdf->Cell(20,5,"CGST  9%",0,1,'L');
				$pdf->SetXY(170,231);
				$pdf->Cell(29,5, money_format('%!i',($sell_invoice->all_gst/2)),0,1,'R');
			}
			$pdf->SetXY(170,237.5);
			$pdf->Cell(29,5, money_format('%!i',($sell_invoice->grandtotal-($sell_invoice->subtotal+$sell_invoice->all_gst))),0,1,'R');
			$pdf->SetXY(170,248);
			$pdf->Cell(29,5, money_format('%!i',$sell_invoice->grandtotal),0,1,'R');
			$pdf->SetFont('Arial','B',8);
			$pdf->SetXY(161,97.5);
			if($sell_invoice->gst_type==1){
				$pdf->Cell(14,5,"S+C GST",0,1,'L');
			}else{
				$pdf->Cell(14,5,"IGST",0,1,'C');
			}
			/*product*/
			$pdf->SetFont('Arial','',9);
			$i=1;
			$j=0;		
			foreach ($sell_product as $sell_product) {
				if($i<5){
					$pdf->SetXY(10,105+$j);
					$pdf->Cell(12,5,$i,0,1,'C');
					$pdf->SetXY(24,105+$j);
					$pdf->MultiCell(79.5,5,$sell_product->product_name,0,'L');
					$pdf->SetXY(106,105+$j);
					$pdf->MultiCell(13,5,$sell_product->hsn_code,0,'C');
					$pdf->SetXY(121,105+$j);
					$pdf->Cell(15,5,$sell_product->quality,0,1,'C');
					$pdf->SetXY(138,105+$j);
					$pdf->Cell(21,5,number_format($sell_product->price,2),0,1,'C');
					if($sell_invoice->gst_type==1){
						$pdf->SetXY(162,105+$j);
						$pdf->Cell(12,5,$sell_product->sgst_p." + ".$sell_product->cgst_p,0,1,'C');
					}else{
						$pdf->SetXY(162,105+$j);
						$pdf->Cell(12,5,$sell_product->igst_p,0,1,'C');
					}
					$pdf->SetXY(176,105+$j);
					$pdf->Cell(23,5,money_format('%!i',$sell_product->total),0,1,'C');
					$j=$j+15;
				}else{
				}
				$i++;
			}
			/*end product*/
			$gst_price=$this->General_model->getIndianCurrency($sell_invoice->all_gst);
			$g_total=$this->General_model->getIndianCurrency($sell_invoice->grandtotal);
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(38,230);
			$pdf->MultiCell(95,5,$gst_price,0,'L');
			$pdf->SetXY(38,239.5);
			$pdf->MultiCell(95,6,$g_total,0,'L');

			/*2 Nd print */
	    	$pdf->AddPage();
	    	setlocale(LC_MONETARY, 'en_IN');
	    	$sell_invoice=$this->General_model->get_row('sell_invoice','id_sell',$id);
	    	$sell_product=$this->db->query("SELECT t1.*,t2.name as product_name,t2.hsn_code  FROM sell_product as t1 LEFT JOIN product as t2 ON t1.product_id = t2.id_product WHERE t1.sellinvoice_id='".$id."'")->result(); 
	    	$customer=$this->db->query("SELECT t1.*,t2.name as city_name,t2.code as city_code,t3.name as state_name,t3.code as state_code,t3.country FROM customer as t1 LEFT JOIN city as t2 ON t1.city_id= t2.id LEFT JOIN state as t3 ON t1.state_id= t3.id WHERE t1.id_customer='".$sell_invoice->customer_id."'")->row();
	    	$pdf->SetFont('Arial','B',12);
	    	$image=base_url('assets/admin/images/adwait.png');
			$pdf->Image($image,5,5,200,0,'PNG');
			$pdf->SetXY(12,52.5);
			$pdf->Cell(40,5,ucfirst($sell_invoice->bill_type)." Memo",0,1,'L');
			$pdf->SetXY(178,52.5);
			$pdf->Cell(20,5,"Duplicate",0,1,'L');
			$pdf->SetFont('Arial','',10);
			$pdf->SetXY(23,62);
			$city_code=((isset($customer->city_code) && !empty($customer->city_code))?" - ".$customer->city_code:"");
			$pdf->MultiCell(95,5, strtoupper($customer->name)."\n". strtoupper($customer->address).", ".strtoupper($customer->city_name).$city_code."\n"."Mo :  ".$customer->mobile,0,'L');
			/*invoice No*/
			$pdf->SetXY(157,60.1);
			$pdf->Cell(20,5,$sell_invoice->invoice_no,0,1,'L');
			$pdf->SetXY(157,65.3);
			$pdf->Cell(20,5,date('d/m/Y',strtotime($sell_invoice->date)),0,1,'L');
			if(isset($sell_invoice->transpoter_id) && !empty($sell_invoice->transpoter_id)){
				$transpoter=$this->General_model->get_row('transpoter','id', $sell_invoice->transpoter_id);
				$transpoter_name=$transpoter->name;
				$lr_no=$transpoter->lr_no;
			}else{
				$transpoter_name="N-A";
				$lr_no="N-A";
			}
			$pdf->SetXY(157,75);
			$pdf->MultiCell(43,6,$transpoter_name,0,'L');
			$pdf->SetXY(157,87);
			$pdf->Cell(43,6,$lr_no,0,1,'L');
			$pdf->SetXY(40,85);
			$pdf->Cell(70,5,$customer->gst_no,0,1,'L');
			$state_code=((isset($customer->state_code) && !empty($customer->state_code))?" - ".$customer->state_code:"");
			$pdf->SetXY(40,90);
			$pdf->Cell(70,5,strtoupper($customer->state_name.$state_code),0,1,'L');
			$pdf->SetXY(40,90);
			$pdf->Cell(70,5,strtoupper($customer->state_name.$state_code),0,1,'L');
			$pdf->SetFont('Arial','B',10);
			$pdf->SetXY(39,207);
			$pdf->Cell(70,5,strtoupper(GST_NO),0,1,'L');
			$pdf->SetXY(162,207);
			$pdf->Cell(37,5,money_format('%!i',$sell_invoice->subtotal),0,1,'R');
			$pdf->SetXY(170,216.2);
			$pdf->Cell(29,5,money_format('%!i',$sell_invoice->subtotal),0,1,'R');
			if($sell_invoice->gst_type==2){
				$pdf->SetXY(138.5,227);
				$pdf->Cell(20,5,"IGST   3.0%",0,1,'L');
				$pdf->SetXY(170,227);
				$pdf->Cell(29,5, money_format('%!i',$sell_invoice->all_gst),0,1,'R');
			}else{
				$pdf->SetXY(138.8,223);
				$pdf->Cell(20,5,"SGST  9%",0,1,'L');
				$pdf->SetXY(170,223);
				$pdf->Cell(29,5, money_format('%!i',($sell_invoice->all_gst/2)),0,1,'R');
				$pdf->SetXY(138.8,231);
				$pdf->Cell(20,5,"CGST  9%",0,1,'L');
				$pdf->SetXY(170,231);
				$pdf->Cell(29,5, money_format('%!i',($sell_invoice->all_gst/2)),0,1,'R');
			}
			$pdf->SetXY(170,237.5);
			$pdf->Cell(29,5, money_format('%!i',($sell_invoice->grandtotal-($sell_invoice->subtotal+$sell_invoice->all_gst))),0,1,'R');
			$pdf->SetXY(170,248);
			$pdf->Cell(29,5, money_format('%!i',$sell_invoice->grandtotal),0,1,'R');
			$pdf->SetFont('Arial','B',8);
			$pdf->SetXY(161,97.5);
			if($sell_invoice->gst_type==1){
				$pdf->Cell(14,5,"S+C GST",0,1,'L');
			}else{
				$pdf->Cell(14,5,"IGST",0,1,'C');
			}
			/*product*/
			$pdf->SetFont('Arial','',9);
			$i=1;
			$j=0;		
			foreach ($sell_product as $sell_product) {
				if($i<5){
					$pdf->SetXY(10,105+$j);
					$pdf->Cell(12,5,$i,0,1,'C');
					$pdf->SetXY(24,105+$j);
					$pdf->MultiCell(79.5,5,$sell_product->product_name,0,'L');
					$pdf->SetXY(106,105+$j);
					$pdf->MultiCell(13,5,$sell_product->hsn_code,0,'C');
					$pdf->SetXY(121,105+$j);
					$pdf->Cell(15,5,$sell_product->quality,0,1,'C');
					$pdf->SetXY(138,105+$j);
					$pdf->Cell(21,5,number_format($sell_product->price,2),0,1,'C');
					if($sell_invoice->gst_type==1){
						$pdf->SetXY(162,105+$j);
						$pdf->Cell(12,5,$sell_product->sgst_p." + ".$sell_product->cgst_p,0,1,'C');
					}else{
						$pdf->SetXY(162,105+$j);
						$pdf->Cell(12,5,$sell_product->igst_p,0,1,'C');
					}
					$pdf->SetXY(176,105+$j);
					$pdf->Cell(23,5,money_format('%!i',$sell_product->total),0,1,'C');
					$j=$j+15;
				}else{
				}
				$i++;
			}
			/*end product*/
			$gst_price=$this->General_model->getIndianCurrency($sell_invoice->all_gst);
			$g_total=$this->General_model->getIndianCurrency($sell_invoice->grandtotal);
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(38,230);
			$pdf->MultiCell(95,5,$gst_price,0,'L');
			$pdf->SetXY(38,239.5);
			$pdf->MultiCell(95,6,$g_total,0,'L');

			/*3rd time*/
	    	$pdf->AddPage();
	    	setlocale(LC_MONETARY, 'en_IN');
	    	$sell_invoice=$this->General_model->get_row('sell_invoice','id_sell',$id);
	    	$sell_product=$this->db->query("SELECT t1.*,t2.name as product_name,t2.hsn_code  FROM sell_product as t1 LEFT JOIN product as t2 ON t1.product_id = t2.id_product WHERE t1.sellinvoice_id='".$id."'")->result(); 
	    	$customer=$this->db->query("SELECT t1.*,t2.name as city_name,t2.code as city_code,t3.name as state_name,t3.code as state_code,t3.country FROM customer as t1 LEFT JOIN city as t2 ON t1.city_id= t2.id LEFT JOIN state as t3 ON t1.state_id= t3.id WHERE t1.id_customer='".$sell_invoice->customer_id."'")->row();
	    	$pdf->SetFont('Arial','B',12);
	    	$image=base_url('assets/admin/images/adwait.png');
			$pdf->Image($image,5,5,200,0,'PNG');
			$pdf->SetXY(12,52.5);
			$pdf->Cell(40,5,ucfirst($sell_invoice->bill_type)." Memo",0,1,'L');
			$pdf->SetXY(178,52.5);
			$pdf->Cell(20,5,"Triplicate",0,1,'L');
			$pdf->SetFont('Arial','',10);
			$pdf->SetXY(23,62);
			$city_code=((isset($customer->city_code) && !empty($customer->city_code))?" - ".$customer->city_code:"");
			$pdf->MultiCell(95,5, strtoupper($customer->name)."\n". strtoupper($customer->address).", ".strtoupper($customer->city_name).$city_code."\n"."Mo :  ".$customer->mobile,0,'L');
			/*invoice No*/
			$pdf->SetXY(157,60.1);
			$pdf->Cell(20,5,$sell_invoice->invoice_no,0,1,'L');
			$pdf->SetXY(157,65.3);
			$pdf->Cell(20,5,date('d/m/Y',strtotime($sell_invoice->date)),0,1,'L');
			if(isset($sell_invoice->transpoter_id) && !empty($sell_invoice->transpoter_id)){
				$transpoter=$this->General_model->get_row('transpoter','id', $sell_invoice->transpoter_id);
				$transpoter_name=$transpoter->name;
				$lr_no=$transpoter->lr_no;
			}else{
				$transpoter_name="N-A";
				$lr_no="N-A";
			}
			$pdf->SetXY(157,75);
			$pdf->MultiCell(43,6,$transpoter_name,0,'L');
			$pdf->SetXY(157,87);
			$pdf->Cell(43,6,$lr_no,0,1,'L');
			$pdf->SetXY(40,85);
			$pdf->Cell(70,5,$customer->gst_no,0,1,'L');
			$state_code=((isset($customer->state_code) && !empty($customer->state_code))?" - ".$customer->state_code:"");
			$pdf->SetXY(40,90);
			$pdf->Cell(70,5,strtoupper($customer->state_name.$state_code),0,1,'L');
			$pdf->SetXY(40,90);
			$pdf->Cell(70,5,strtoupper($customer->state_name.$state_code),0,1,'L');
			$pdf->SetFont('Arial','B',10);
			$pdf->SetXY(39,207);
			$pdf->Cell(70,5,strtoupper(GST_NO),0,1,'L');
			$pdf->SetXY(162,207);
			$pdf->Cell(37,5,money_format('%!i',$sell_invoice->subtotal),0,1,'R');
			$pdf->SetXY(170,216.2);
			$pdf->Cell(29,5,money_format('%!i',$sell_invoice->subtotal),0,1,'R');
			if($sell_invoice->gst_type==2){
				$pdf->SetXY(138.5,227);
				$pdf->Cell(20,5,"IGST   3.0%",0,1,'L');
				$pdf->SetXY(170,227);
				$pdf->Cell(29,5, money_format('%!i',$sell_invoice->all_gst),0,1,'R');
			}else{
				$pdf->SetXY(138.8,223);
				$pdf->Cell(20,5,"SGST  9%",0,1,'L');
				$pdf->SetXY(170,223);
				$pdf->Cell(29,5, money_format('%!i',($sell_invoice->all_gst/2)),0,1,'R');
				$pdf->SetXY(138.8,231);
				$pdf->Cell(20,5,"CGST  9%",0,1,'L');
				$pdf->SetXY(170,231);
				$pdf->Cell(29,5, money_format('%!i',($sell_invoice->all_gst/2)),0,1,'R');
			}
			$pdf->SetXY(170,237.5);
			$pdf->Cell(29,5, money_format('%!i',($sell_invoice->grandtotal-($sell_invoice->subtotal+$sell_invoice->all_gst))),0,1,'R');
			$pdf->SetXY(170,248);
			$pdf->Cell(29,5, money_format('%!i',$sell_invoice->grandtotal),0,1,'R');
			$pdf->SetFont('Arial','B',8);
			$pdf->SetXY(161,97.5);
			if($sell_invoice->gst_type==1){
				$pdf->Cell(14,5,"S+C GST",0,1,'L');
			}else{
				$pdf->Cell(14,5,"IGST",0,1,'C');
			}
			/*product*/
			$pdf->SetFont('Arial','',9);
			$i=1;
			$j=0;		
			foreach ($sell_product as $sell_product) {
				if($i<5){
					$pdf->SetXY(10,105+$j);
					$pdf->Cell(12,5,$i,0,1,'C');
					$pdf->SetXY(24,105+$j);
					$pdf->MultiCell(79.5,5,$sell_product->product_name,0,'L');
					$pdf->SetXY(106,105+$j);
					$pdf->MultiCell(13,5,$sell_product->hsn_code,0,'C');
					$pdf->SetXY(121,105+$j);
					$pdf->Cell(15,5,$sell_product->quality,0,1,'C');
					$pdf->SetXY(138,105+$j);
					$pdf->Cell(21,5,number_format($sell_product->price,2),0,1,'C');
					if($sell_invoice->gst_type==1){
						$pdf->SetXY(162,105+$j);
						$pdf->Cell(12,5,$sell_product->sgst_p." + ".$sell_product->cgst_p,0,1,'C');
					}else{
						$pdf->SetXY(162,105+$j);
						$pdf->Cell(12,5,$sell_product->igst_p,0,1,'C');
					}
					$pdf->SetXY(176,105+$j);
					$pdf->Cell(23,5,money_format('%!i',$sell_product->total),0,1,'C');
					$j=$j+15;
				}else{
				}
				$i++;
			}
			/*end product*/
			$gst_price=$this->General_model->getIndianCurrency($sell_invoice->all_gst);
			$g_total=$this->General_model->getIndianCurrency($sell_invoice->grandtotal);
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(38,230);
			$pdf->MultiCell(95,5,$gst_price,0,'L');
			$pdf->SetXY(38,239.5);
			$pdf->MultiCell(95,6,$g_total,0,'L');
	    	$pdf->Output();
	    }
	    public function sellitem_delete($id)
	    {
	    	$this->General_model->auth_check();
	    	if(isset($id) && !empty($id)){
	    		$detail=$this->General_model->delete('sell_product','id_sellproduct',$id);
	    		$data['status']="success";
	    		$data['msg']="Product Deleted";
	    	}else{
	    		$data['status']="error";
	    		$data['msg']="Something is Worng";				
	    	}
	    	echo json_encode($data);
	    }
}