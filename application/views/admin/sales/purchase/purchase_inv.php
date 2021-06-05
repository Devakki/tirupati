<div class="content">
      <div class="container-fluid">
          <div class="row">
              <div class="col-12">
                  <div class="page-title-box">
                      <h4 class="page-title float-left"><?php echo $page_title;?></h4>
                      <ol class="breadcrumb float-right">
                          <li class="breadcrumb-item"><a href="<?php echo base_url('Dashbord'); ?>"><?php echo COMPANY; ?></a></li>
                          <li class="breadcrumb-item"><a href="#"><?php echo $page_title;?></a></li>
                      </ol>
                      <div class="clearfix"></div>
                  </div>
              </div>
          </div>
          <?php 
          $city_code=((isset($party->city_code) && !empty($party->city_code))?" - ".$party->city_code : "");
          $state_code=((isset($party->state_code) && !empty($party->state_code))?" - ".$party->state_code :"");
          ?>
          <div class="row">
             <div class="col-md-12 border-dark">
                 <div class="card-box">
                    <div class="row">
                        <div class="col-md-12 table-responsive" style="margin-bottom: 0px;">
                          <style type="text/css">
                              @media print {
                                 .table thead th {
                                      border: 1px solid #0c0c0c !important;
                                  }
                                  .table-bordered td, .table-bordered th {
                                      border: 1px solid #0c0c0c !important;
                                  }
                                  .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
                                    padding: 6px 10px;
                                  }
                                }
                                  .table thead th {
                                    border: 1px solid #0c0c0c !important;
                                  }
                                  .table-bordered td, .table-bordered th {
                                    border: 1px solid #0c0c0c !important;
                                  }
                                  .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
                                    padding: 6px 10px;
                                  }
                                  h4,h5{
                                    margin: 0px;
                                  }
                          </style>
                          <style type="text/css">
                          </style>
                          <table class="table table-bordered ">
                               <thead>
                                  <tr>
                                      <th colspan="3" class="text-center" >
                                       <H2>ADWAIT FIBRE</H2>
                                     
                                      </th>
                                  </tr>
                               </thead>
                               <tbody>
                                  <tr>
                                      <td colspan="3" class="text-right"><h5>PURCHASE</h5></td>
                                  </tr>
                                  <tr>
                                      <td><h5>Customer</h5></td>
                                      <td><h5><?php echo $customer->name; ?></h5></td>
                                      <td><h5>Bill No : <?php echo $sell_purchase->invoice_no ?></h5></td>
                                  </tr>
                                  <tr>
                                      <td><h5>Address</h5></td>
                                      <td><h5><?php echo $customer->address.", ".$customer->city_name.$city_code.", ".$customer->state_name.$state_code; ?></h5></td>
                                      <td rowspan="2"><h5>Date : <?php echo date('d/m/Y', strtotime($sell_purchase->date)); ?> </h5></td>
                                  </tr>
                                  <tr>
                                      <td><h5>Mo.</h5></td>
                                      <td><h5><?php echo $customer->mobile; ?></h5></td>
                                  </tr>
                               </tbody>
                          </table>
                        </div>
                        <div class="col-md-12 table-responsive" style="margin-top: 0px;">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th colspan="7"><h4>ITEMS</h4></th>                                       
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                          <th>PRODUCT</th>
                                          <th>QUANTITY</th>
                                          <th>PRICE</th>
                                          <th>SUB TOTAL</th>
                                          <th><?php echo (($sell_purchase->gst_type==1)?"S+C GST":"IGST"); ?></th>
                                          <th>G TOTAl</th>
                                    </tr>                                  
                                </thead>
                                <tbody>
                                <?php $i=1; foreach ($sell_product as $sell_product): ?>                                      
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                          <td><?php echo $sell_product->product_name; ?></td>
                                          <td><?php echo $sell_product->quantity; ?></td>
                                          <td><?php echo $sell_product->price; ?></td>
                                          <td><?php echo $sell_product->total; ?></td>
                                          <?php $gst_amount=(($sell_purchase->gst_type==1)?$sell_product->sgst+$sell_product->cgst:$sell_product->igst);?>
                                          <td><?php echo $gst_amount; ?></td>
                                          <td><?php echo $sell_product->amount; ?></td>
                                    </tr>
                                <?php $i++; endforeach; ?>
                                    <tr>
                                        <td style="height: 60px;"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>                                          
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" rowspan="3" class="text-center"></td>
                                        <td><h5>SUB TOTAL</h5></td>
                                        <td><h5><?php echo number_format($sell_purchase->subtotal,2); ?></h5></td>  
                                    </tr>
                                    <tr>                                        
                                        <td><h5>GST</h5></td>
                                        <td><h5><?php echo number_format($sell_purchase->all_gst,2); ?></h5></td>  
                                    </tr>
                                    <tr>                                        
                                        <td><h5>G TOTAL</h5></td>
                                        <td><h5><?php echo number_format($sell_purchase->grandtotal,2); ?></h5></td>  
                                    </tr>
                                                                        
                                </tbody>
                            </table>
                        </div>
                    </div>
                 </div>
             </div>               
          </div>
      </div> 
  </div> 
             


