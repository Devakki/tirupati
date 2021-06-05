<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class  RoughInvoice extends CI_Controller {
	function __construct() {
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
		$data['page_title']="Rough Invoice";
		$this->load->view('admin/controller/header');
		$this->load->view('admin/controller/sidebar');
		$this->load->view('admin/rough/invoice_detail',$data);
		$this->load->view('admin/controller/footer');
	}
	public function get_addfrm()
	{
		$this->General_model->auth_check();
		$data["party"]=$this->General_model->get_all_where('party','status','1');
		$data["item"]=$this->General_model->get_all_where('item','status','1');
		$data["method"]="add";
		$data['invoice']=$this->db->query("SELECT invoice_no FROM rough_invoice ORDER BY invoice_no DESC LIMIT 1")->row(); 
		if(empty($data['invoice'])){
			$data['invoice']= array('no_invoice' => '1');
		}else{
			$no_invoice=(($data['invoice']->invoice_no)+1);
			$data['invoice']= array('no_invoice' =>$no_invoice);
		}
		$this->load->view('admin/controller/header');
		$this->load->view('admin/controller/sidebar');
		$this->load->view('admin/partial/rough_invoice',$data);
		$this->load->view('admin/controller/footer');
	}
	public function invoice_create(){
		$this->General_model->auth_check();
		$bill_type=$this->input->post("bill_type");
		$gst_type=$this->input->post("gst_type");
		$invoice_no=$this->input->post("invoice_no");
		$party=$this->input->post("party_id");	
		$date=$this->input->post("date");
		$date=explode("/", $date);
		$date=[$date[2],$date[1],$date[0]];
		$date=implode("-", $date);
		$t_fine=$this->input->post("t_fine");
		$t_labour=$this->input->post("t_labour");
		$remark=$this->input->post("remark");
		if(isset($bill_type) && !empty($bill_type) && isset($invoice_no) && !empty($invoice_no) && isset($party) && !empty($party) && isset($date) && !empty($date)) {		
			$i=0;
			$j=0;
			$query=$this->db->query("SELECT t1.*,t2.name as city_name ,t3.name as state_name,t3.country FROM party as t1 LEFT JOIN city as t2 ON t1.city_id = t2.id LEFT JOIN state as t3 ON t1.state_id = t3.id WHERE t1.`id_party`='".$party."'")->row();
			$rough_invoice=['bill_type'=>$bill_type,
							'party_id'=>$party,
							'invoice_no'=>$invoice_no,
							'buyer_name'=>$query->name,
							'address'=>$query->address,
							'city'=>$query->city_name,
							'state'=>$query->state_name,
							'country'=>$query->country,
							'date'=>$date,
							'mobile'=>$query->mobile,							
							't_fine'=>$t_fine,
							't_labour'=>$t_labour,
							'remark'=>$remark,
							'status'=>'1',
							'created_at'=>date("Y-m-d h:i:s")
						];
			$this->db->insert("rough_invoice",$rough_invoice);
			$lastid= $this->db->insert_id();
			foreach ($this->input->post("ctr_no") as $pr) {
				$ctr=$this->input->post("ctr_no")[$i];
				$cbag=$this->input->post("cbag")[$i];
				$cweight=$this->input->post("cweight")[$i];
				$ctweight=$this->input->post("ctweight")[$i];		
				if(!empty($ctr) && !empty($ctr) && !empty($cbag)&& !empty($cbag)&& !empty($cweight)&& !empty($cweight) && !empty($ctweight) && !empty($ctweight)) {						
						$bag=['roughinvoice_id'=>$lastid,
								'tr_no'=>$ctr,
								'bag'=>$cbag,
								'weight'=>$cweight,
								'total'=>$ctweight,												
								'created_at'=>date("Y-m-d h:i:s")];
						$this->db->insert('rough_bag',$bag);						
					} 		
					$i++;
			}
			foreach ($this->input->post("item_id") as $pr) {
				$item_id=$this->input->post("item_id")[$j];
				$mtr=$this->input->post("mtr_no")[$j];
				$g_weight=$this->input->post("mg_weight")[$j];
				$b_weight=$this->input->post("mb_weight")[$j];
				$n_weight=$this->input->post("mn_weight")[$j];		
				$mtouch=$this->input->post("mtouch")[$j];
				$wastage=$this->input->post("mwastage")[$j];
				$t_w=$this->input->post("mtouch_wastage")[$j];
				$fine=$this->input->post("mfine")[$j];
				$rate=$this->input->post("mrate")[$j];
				$labour=$this->input->post("mlabour")[$j];
				if(!empty($item_id) && !empty($item_id) && !empty($mtr)&& !empty($mtr) && !empty($lastid) && !empty($lastid)) {						
						$item=['roughinvoice_id'=>$lastid,
								'item_id'=>$item_id,
								'tr_no'=>$mtr,
								'g_weight'=>$g_weight,
								'b_weight'=>$b_weight,
								'n_weight'=>$n_weight,
								'touch'=>$mtouch,
								'wastage'=>$wastage,
								'touch_wastage'=>$t_w,	
								'fine'=>$fine,
								'rate'=>$rate,
								'labour'=>$labour,													
								'created_at'=>date("Y-m-d h:i:s")];
						$this->db->insert('rough_item',$item);						
				}		
				$j++;
			}
				$rough_payment=['party_id'=>$party,
									'bill_type'=>'2',
									'roughpur_id'=>NULL,
									'roughinvoice_id'=>$lastid,
									'party_name'=>$query->name,
									'date'=>$date,
									'rs'=>$t_labour,
									'remark'=>NULL,
									'status'=>'0',
									'created_at'=>date("Y-m-d h:i:s")];
				$this->db->insert("rough_payment",$rough_payment);
				$this->session->set_userdata('Msg','Invoice Generated');
			}else{
				$this->session->set_userdata('Msg','Something Is Missing');			
		}					
			redirect('RoughInvoice');
	}
	public function getLists(){
			$this->General_model->auth_check();
			$table='rough_invoice';
			$order_column_array=array('id_rough', 'bill_type','invoice_no','buyer_name','address','date','mobile','t_fine','t_labour');
			$search_order= array('bill_type','invoice_no','buyer_name','address','mobile','date','t_labour','t_fine');
			$order_by_array= array('id_rough' => 'ASC');
	        $data = $row = array();
	        $Master_Data = $this->Genral_datatable->getRows($_POST,$table,$order_column_array,$search_order,$order_by_array);
	        $i = $_POST['start'];
	        foreach($Master_Data as $m_data){
	            $i++;
	            $data[] = 	[$i,
	    					date('d/m/Y',Strtotime($m_data->date)),
	    					$m_data->invoice_no,
	    					$m_data->buyer_name,
	    					$m_data->t_fine,
	    					$m_data->t_labour,
	    					'<a href="'.base_url('RoughInvoice/get_editfrm/').$m_data->id_rough.'"><button type="button" class="btn btn-custom waves-effect waves-light"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
	    					<button type="button" class="btn btn-danger waves-effect waves-light" data-id="delete" data-value="'.$m_data->id_rough.'"><i class="fa fa-trash" aria-hidden="true"></i></button>
	    					<a href="'.base_url('RoughInvoice/invoice/').$m_data->id_rough.'"><button type="button" class="btn btn-primary waves-effect waves-light"><i class="fa fa-eye"></i></button></a>
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
    public function get_editfrm($id)
    {
    	$this->General_model->auth_check();
    	$data['method']="edit";  
    	$data["party"]=$this->General_model->get_all_where('party','status','1');
    	$data["item"]=$this->General_model->get_all_where('item','status','1');	  
    	$data['rough_invoice']=$this->General_model->get_row('rough_invoice','id_rough',$id);
    	$data['rough_bag']=$this->General_model->get_data('rough_bag','roughinvoice_id','*',$id);
    	$data['rough_item']=$this->General_model->get_data('rough_item','roughinvoice_id','*',$id);
    	$this->load->view('admin/controller/header');
    	$this->load->view('admin/controller/sidebar');    	
    	$this->load->view('admin/partial/rough_invoice',$data);
    	$this->load->view('admin/controller/footer');
    }
	public function invoice_update()
	{
    	$this->General_model->auth_check();
    	$id_rough=$this->input->post("id_rough");	    	
    	$bill_type=$this->input->post("bill_type");
		$invoice_no=$this->input->post("invoice_no");
		$party=$this->input->post("party_id");	
		$date=$this->input->post("date");
		$date=explode("/", $date);
		$date=[$date[2],$date[1],$date[0]];
		$date=implode("-", $date);
		$t_fine=$this->input->post("t_fine");
		$t_labour=$this->input->post("t_labour");
		$remark=$this->input->post("remark");
		if(isset($bill_type) && !empty($bill_type) && isset($invoice_no) && !empty($invoice_no) && isset($party) && !empty($party) && isset($date) && !empty($date)) {
	    			$i=0;
					$j=0;
					$query=$this->db->query("SELECT t1.*,t2.name as city_name ,t3.name as state_name,t3.country FROM party as t1 LEFT JOIN city as t2 ON t1.city_id = t2.id LEFT JOIN state as t3 ON t1.state_id = t3.id WHERE t1.`id_party`='".$party."'")->row();		
	    			$rough_invoice=['bill_type'=>$bill_type,
	    							'party_id'=>$party,
	    							'invoice_no'=>$invoice_no,
	    							'buyer_name'=>$query->name,
	    							'address'=>$query->address,
	    							'city'=>$query->city_name,
	    							'state'=>$query->state_name,
	    							'country'=>$query->country,
	    							'date'=>$date,
	    							'mobile'=>$query->mobile,							
	    							't_fine'=>$t_fine,
	    							't_labour'=>$t_labour,
	    							'remark'=>$remark
	    						];
	    			$this->General_model->update('rough_invoice',$rough_invoice,'id_rough',$id_rough);   			
	    			foreach ($this->input->post("ctr_no") as $pr) {
	    				$ctr=$this->input->post("ctr_no")[$i];
	    				$cbag=$this->input->post("cbag")[$i];
	    				$cweight=$this->input->post("cweight")[$i];
	    				$ctweight=$this->input->post("ctweight")[$i];
	    				$id_bag=$this->input->post("id_bag")[$i];		
	    				if(isset($id_bag) && !empty($id_bag) && isset($ctr) && !empty($ctr) && isset($cbag)&& !empty($cbag)&& isset($cweight)&& isset($cweight) && isset($ctweight) && !empty($ctweight)) {						
	    						$bag=['roughinvoice_id'=>$id_rough,
	    								'tr_no'=>$ctr,
	    								'bag'=>$cbag,
	    								'weight'=>$cweight,
	    								'total'=>$ctweight,												
	    								];
	    						$this->General_model->update('rough_bag',$bag,'id',$id_bag); 					
	    					}elseif (isset($ctr) && !empty($ctr) && isset($cbag)&& !empty($cbag)&& isset($cweight)&& isset($cweight) && isset($ctweight) && !empty($ctweight)) {
	    							$bag=['roughinvoice_id'=>$id_rough,
	    									'tr_no'=>$ctr,
	    									'bag'=>$cbag,
	    									'weight'=>$cweight,
	    									'total'=>$ctweight,												
	    									'created_at'=>date("Y-m-d h:i:s")];
	    							$this->db->insert('rough_bag',$bag);
	    					}else{
	    					} 		
	    					$i++;
	    			}
	    			foreach ($this->input->post("item_id") as $pr) {
	    				$item_id=$this->input->post("item_id")[$j];
	    				$roughitem_id=$this->input->post("roughitem_id")[$j];
	    				$mtr=$this->input->post("mtr_no")[$j];
	    				$g_weight=$this->input->post("mg_weight")[$j];
	    				$b_weight=$this->input->post("mb_weight")[$j];
	    				$n_weight=$this->input->post("mn_weight")[$j];		
	    				$mtouch=$this->input->post("mtouch")[$j];
	    				$wastage=$this->input->post("mwastage")[$j];
	    				$t_w=$this->input->post("mtouch_wastage")[$j];
	    				$fine=$this->input->post("mfine")[$j];
	    				$rate=$this->input->post("mrate")[$j];
	    				$labour=$this->input->post("mlabour")[$j];
	    				if(isset($roughitem_id) && !empty($roughitem_id) && isset($item_id) && !empty($item_id) && isset($mtr)&& !empty($mtr)) {
	    					$item=['roughinvoice_id'=>$id_rough,
	    							'item_id'=>$item_id,
	    							'tr_no'=>$mtr,
	    							'g_weight'=>$g_weight,
	    							'b_weight'=>$b_weight,
	    							'n_weight'=>$n_weight,
	    							'touch'=>$mtouch,
	    							'wastage'=>$wastage,
	    							'touch_wastage'=>$t_w,	
	    							'fine'=>$fine,
	    							'rate'=>$rate,
	    							'labour'=>$labour,													
	    							];
	    					$this->General_model->update('rough_item',$item,'id',$roughitem_id);				
	    				}elseif (isset($item_id) && !empty($item_id) && isset($mtr)&& !empty($mtr)) {
	    					$item=['roughinvoice_id'=>$id_rough,
	    							'item_id'=>$item_id,
	    							'tr_no'=>$mtr,
	    							'g_weight'=>$g_weight,
	    							'b_weight'=>$b_weight,
	    							'n_weight'=>$n_weight,
	    							'touch'=>$mtouch,
	    							'wastage'=>$wastage,
	    							'touch_wastage'=>$t_w,	
	    							'fine'=>$fine,
	    							'rate'=>$rate,
	    							'labour'=>$labour,													
	    							'created_at'=>date("Y-m-d h:i:s")];
	    					$this->db->insert('rough_item',$item);
	    				}else{
	    				}		
	    				$j++;
	    			}
	    			$rough_payment=['party_id'=>$party,
	    								'bill_type'=>'2',
	    								'party_name'=>$query->name,
	    								'date'=>$date,
	    								'status'=>'0',
	    								'rs'=>$t_labour
	    								 ];
	    			$this->General_model->update('rough_payment',$rough_payment,'roughinvoice_id',$id_rough);
	    			$this->session->set_userdata('Msg','RoughInvoice Updated');
	    	}else{
	    		$this->session->set_userdata('Msg','Something Worng');
	    	}
	    	redirect('RoughInvoice');
	    }
	    public function invoice_delete($id)
	    {
	    	$this->General_model->auth_check();
	    	if(isset($id) && !empty($id)){
	    		$delete_invoice=$this->General_model->delete('rough_invoice','id_rough',$id);
	    		$delete_product=$this->General_model->delete('rough_bag','roughinvoice_id',$id); 
	    		$delete_product=$this->General_model->delete('rough_item','roughinvoice_id',$id); 
	    		$this->General_model->delete('rough_payment','roughinvoice_id',$id);  		
	    		$data['status']="success";
	    		$data['msg']="Rough Invoice Deleted";
	    	}else{
	    		$data['status']="error";
	    		$data['msg']="Something is Worng";				
	    	}
	    	echo json_encode($data);
	    }
	    public function invoice($id)
	    {
	    	require_once(APPPATH.'third_party/fpdf/fpdf.php');
	    	$pdf = new FPDF();
	    	$pdf->AddPage();
	    	$r_invoice=$this->General_model->get_row('rough_invoice','id_rough',$id);
	    	$r_bag=$this->General_model->get_data('rough_bag','roughinvoice_id','*',$id);
	    	$r_item=$this->db->query("SELECT t1.*,t2.name as item_name FROM rough_item as t1 LEFT JOIN item as t2 ON t1.item_id=t2.id_item WHERE t1.roughinvoice_id='".$id."'")->result();
	    	$party_id=$r_invoice->party_id;
	    	$party=$this->db->query("SELECT t1.*,t2.name as city_name FROM party as t1 LEFT JOIN city as t2 ON t1.city_id=t2.id WHERE t1.id_party='".$party_id."'")->row();
	    	$image=base_url('assets/admin/images/r_invoice.png');
	    	$pdf->Image($image,0,0,210,0,'PNG');
	    	$pdf->SetFont('Arial','',10);
	    	$pdf->SetXY(47,39);
	    	$pdf->Cell(82,5,$party->name,0,1,'L');
	    	$pdf->SetXY(170,39);
	    	$pdf->Cell(25,5,$r_invoice->invoice_no,0,1,'L');
	    	$pdf->SetXY(47,43.5);
	    	$pdf->Cell(82,5,$party->city_name,0,1,'L');    	
	    	$pdf->SetXY(170,44);
	    	$pdf->Cell(33,5,date('d/m/Y',strtotime($r_invoice->date)),0,1,'L');
	    	$i=0;
	    	$y=0;
	    	foreach ($r_item as $r_item) {
	    		if($i<2){
	    			$pdf->SetXY(12,57+$y);
	    			$pdf->MultiCell(33,5,$r_item->item_name,0,'C');
	    			$pdf->SetXY(47,57+$y);
	    			$pdf->Cell(24,5,$r_item->g_weight,0,1,'C');
	    			$pdf->SetXY(71,57+$y);
	    			$pdf->Cell(19,5,$r_item->b_weight,0,1,'C');
	    			$n_weight[]=$r_item->n_weight;
	    			$pdf->SetXY(90,57+$y);
	    			$pdf->Cell(22,5,$r_item->n_weight,0,1,'C');
	    			$touch_wst[]=$r_item->touch+$r_item->wastage;
	    			$pdf->SetXY(112,57+$y);
	    			$pdf->Cell(17,5,number_format($r_item->touch,2),0,1,'C');
	    			$pdf->SetXY(129,57+$y);
	    			$pdf->Cell(17,5,number_format($r_item->wastage,2),0,1,'C');
	    			$fine[]=$r_item->fine;
	    			$pdf->SetXY(146,57+$y);
	    			$pdf->Cell(22,5,$r_item->fine,0,1,'C');
	    			$pdf->SetXY(169,57+$y);
	    			$pdf->Cell(14,5,$r_item->rate,0,1,'C');
	    			$labour[]=$r_item->labour;
	    			$pdf->SetXY(184,57+$y);
	    			$pdf->Cell(19,5,$r_item->labour,0,1,'C');
	    			$y=10+$y;
	    		}else{
	    		}
	    		$i++;
	    	}
	    	$pdf->SetXY(90,80);
	    	$pdf->Cell(22,5,array_sum($n_weight),0,1,'C');
	    	$pdf->SetXY(112,80);
	    	$pdf->Cell(34,5,number_format((array_sum($touch_wst)),2),0,1,'C');
	    	$pdf->SetXY(146,80);
	    	$pdf->Cell(22,5,array_sum($fine),0,1,'C');
	    	$pdf->SetXY(169,80);
	    	$pdf->Cell(35,5,"Rs. ".number_format((array_sum($labour)),1),0,1,'C');
	    	$j=0;
	    	$z=0;
	    	foreach ($r_bag as $r_bag) {
	    		if($j<4){
	    			$pdf->SetXY(12,94+$z);
	    			$pdf->Cell(33,5,$r_bag->bag,0,1,'C');
	    			$pdf->SetXY(47,94+$z);
	    			$pdf->Cell(24,5,$r_bag->weight,0,1,'C');
	    			$pdf->SetXY(71,94+$z);
	    			$pdf->Cell(19,5,$r_bag->total,0,1,'C');
	    			$z=5+$z;
	    		}else{
	    		}
	    		$j++;
	    	}
	    	$pdf->SetXY(47,178.5);
	    	$pdf->Cell(82,5,$party->name,0,1,'L');
	    	$pdf->SetXY(170,178.5);
	    	$pdf->Cell(25,5,$r_invoice->invoice_no,0,1,'L');
	    	$pdf->SetXY(47,183.5);
	    	$pdf->Cell(82,5,$party->city_name,0,1,'L');    	
	    	$pdf->SetXY(170,183.5);
	    	$pdf->Cell(33,5,date('d/m/Y',strtotime($r_invoice->date)),0,1,'L');
	    	$a=0;
	    	$b=0;
	    	$r_item=$this->db->query("SELECT t1.*,t2.name as item_name FROM rough_item as t1 LEFT JOIN item as t2 ON t1.item_id=t2.id_item WHERE t1.roughinvoice_id='".$id."'")->result();
	    	foreach ($r_item as $r_item) {
	    		if($a<2){
	    			$pdf->SetXY(12,198+$b);
	    			$pdf->MultiCell(33,5,$r_item->item_name,0,'C');
	    			$pdf->SetXY(47,198+$b);
	    			$pdf->Cell(24,5,$r_item->g_weight,0,1,'C');
	    			$pdf->SetXY(71,198+$b);
	    			$pdf->Cell(19,5,$r_item->b_weight,0,1,'C');
	    			$n_weight1[]=$r_item->n_weight;
	    			$pdf->SetXY(90,198+$b);
	    			$pdf->Cell(22,5,$r_item->n_weight,0,1,'C');
	    			$touch_wst1[]=$r_item->touch+$r_item->wastage;
	    			$pdf->SetXY(112,198+$b);
	    			$pdf->Cell(17,5,number_format($r_item->touch,2),0,1,'C');
	    			$pdf->SetXY(129,198+$b);
	    			$pdf->Cell(17,5,number_format($r_item->wastage,2),0,1,'C');
	    			$fine1[]=$r_item->fine;
	    			$pdf->SetXY(146,198+$b);
	    			$pdf->Cell(22,5,$r_item->fine,0,1,'C');
	    			$pdf->SetXY(169,198+$b);
	    			$pdf->Cell(14,5,$r_item->rate,0,1,'C');
	    			$labour1[]=$r_item->labour;
	    			$pdf->SetXY(184,198+$b);
	    			$pdf->Cell(19,5,$r_item->labour,0,1,'C');
	    			$b=10+$b;
	    		}else{
	    		}
	    		$a++;
	    	}
	    	$pdf->SetXY(90,219.5);
	    	$pdf->Cell(22,5,array_sum($n_weight1),0,1,'C');
	    	$pdf->SetXY(112,219.5);
	    	$pdf->Cell(34,5,number_format((array_sum($touch_wst1)),2),0,1,'C');
	    	$pdf->SetXY(146,219.5);
	    	$pdf->Cell(22,5,array_sum($fine1),0,1,'C');
	    	$pdf->SetXY(169,219.5);
	    	$pdf->Cell(35,5,"Rs. ".number_format((array_sum($labour1)),1),0,1,'C');
	    	$e=0;
	    	$f=0;
	    	$r_bag=$this->General_model->get_data('rough_bag','roughinvoice_id','*',$id);
	    	foreach ($r_bag as $r_bag) {
	    		if($e<4){
	    			$pdf->SetXY(12,235+$f);
	    			$pdf->Cell(33,5,$r_bag->bag,0,1,'C');
	    			$pdf->SetXY(47,235+$f);
	    			$pdf->Cell(24,5,$r_bag->weight,0,1,'C');
	    			$pdf->SetXY(71,235+$f);
	    			$pdf->Cell(19,5,$r_bag->total,0,1,'C');
	    			$f=5+$f;
	    		}else{
	    		}
	    		$e++;
	    	}
	    	$pdf->Output();
	    }
	    public function invoicebag_delete($id)
	    {
	    	$this->General_model->auth_check();
	    	if(isset($id) && !empty($id)){
	    		$detail=$this->General_model->delete('rough_bag','id',$id);
	    		$data['status']="success";
	    		$data['msg']="Item Sucessful Deleted";
	    	}else{
	    		$data['status']="error";
	    		$data['msg']="Something is Worng";				
	    	}
	    	echo json_encode($data);
	    }
	    public function invoiceitem_delete($id)
	    {
	    	$this->General_model->auth_check();
	    	if(isset($id) && !empty($id)){
	    		$detail=$this->General_model->delete('rough_item','id',$id);
	    		$data['status']="success";
	    		$data['msg']="Item Sucessful Deleted";
	    	}else{
	    		$data['status']="error";
	    		$data['msg']="Something is Worng";				
	    	}
	    	echo json_encode($data);
	    }
}