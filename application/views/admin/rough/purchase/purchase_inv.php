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
                                          <img src="<?php echo base_url('assets/admin/images/sliver.png')?>" height="100" >
                                          <!-- <h5><?php echo ADDRESS1; ?></h5> -->
                                        </th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr>
                                        <td colspan="3" class="text-right"><h5>PURCHASE</h5></td>
                                    </tr>
                                    <tr>
                                        <td><h5>Party</h5></td>
                                        <td><h5><?php echo $party->name; ?></h5></td>
                                        <td><h5>Bill No : <?php echo $p_invoice->invoice_no; ?></h5></td>
                                    </tr>
                                    <tr>
                                        <td><h5>Address</h5></td>
                                        <td><h5><?php echo $party->address.", ".$party->city_name.$city_code.", ".$party->state_name.$state_code; ?></h5></td>
                                        <td rowspan="2"><h5>Date : <?php echo date('d/m/Y', strtotime($p_invoice->date)); ?> </h5></td>
                                    </tr>
                                    <tr>
                                        <td><h5>Mo.</h5></td>
                                        <td><h5><?php echo $party->mobile; ?></h5></td>
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
                                          <th>ITEM</th>
                                          <th>NET WT.</th>
                                          <th>TOUNCH</th>
                                          <th>WASTAGE</th>
                                          <th>FINE</th>
                                          <th>LABOUR</th>
                                      </tr>                                  
                                  </thead>
                                  <tbody>
                                  <?php $i=1; foreach ($r_item as $r_item): ?>                                      
                                      <tr>
                                          <td><?php echo $i; ?></td>
                                          <td><?php  echo $r_item->item_name; ?></td>
                                          <td><?php $n_weight[]=$r_item->n_weight; echo $r_item->n_weight; ?></td>
                                          <td><?php echo $r_item->touch; ?></td>
                                          <td><?php echo $r_item->wastage; ?></td>
                                          <td><?php $fine[]=$r_item->fine; echo $r_item->fine; ?></td>
                                          <td><?php $labour[]=$r_item->labour; echo $r_item->labour; ?></td>
                                      </tr>
                                  <?php $i++; endforeach; ?>
                                      <tr>
                                          <td style="height: 50px;"></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>                                          
                                          <td></td>
                                      </tr>
                                      <tr>
                                          <td colspan="2" class="text-center">TOTAL</td>
                                          <td><?php echo array_sum($n_weight); ?></td>  
                                          <td colspan="2"></td>                                         
                                          <td><?php echo array_sum($fine); ?></td>
                                          <td><?php echo array_sum($labour); ?></td>  
                                      </tr>
                                      <tr>
                                          <td colspan="5" class="text-right"><br><br>Authorised Sign.</td>
                                          <td colspan="2" class="text-center"></td>
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
               