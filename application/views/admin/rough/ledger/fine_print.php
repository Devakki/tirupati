<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <style type="text/css">
                  .table td, .table th{
                    border-top: none;
                  }
                  .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
                        padding: 7px 10px;
                  }
                  .responsive {
                    width: 100%;
                    max-width: 400px;
                    height: auto;
                  }
                </style>
                <div class="page-title-box">
                    <h4 class="page-title float-left"><?php echo $page_title; ?></h4>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="#"><?php echo COMPANY; ?></a></li>
                        <li class="breadcrumb-item"><a href="#"><?php echo $page_title; ?></a></li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <div class="text-center">
                        <img class="responsive" src="<?php echo base_url('assets/admin/images/sliver.png'); ?>" >
                    </div>
                    <div class="text-center m-b-30">
                        <p class="m-0"><?php echo ADDRESS1;?></p>
                        <p class="m-0"><?php echo ADDRESS2;?></p>
                    </div>
                    <?php if($display) :?>
                    <div class="text-left m-b-30">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="text-left font-weight-bold">
                                  Account Statement For  &nbsp;&nbsp;
                                </div>
                                <div class="text-right">
                                  <?php echo $acc_name; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="text-left font-weight-bold">
                                  From :  &nbsp;&nbsp;
                                </div>
                                <div class="text-right">
                                  <?php echo $strt_date.' To '.$end_date ; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
                    <?php if($display) :?>
                    <div class="row">
                        <div class="col-md-6 table-responsive-sm">
                            <style type="text/css">
                                @media print {
                                   .table thead th {
                                       border: 1px solid #0c0c0c !important;
                                       font-size: 15px;
                                     }
                                    .table-bordered td, .table-bordered th {
                                        border: 1px solid #0c0c0c !important;
                                        font-size: 15px;
                                    }
                                }
                               .table thead th {
                                   border: 1px solid #0c0c0c !important;
                                 }
                                .table-bordered td, .table-bordered th {
                                    border: 1px solid #0c0c0c !important;
                                }
                            </style>
                            <table class="table w-100 table-bordered" style="border:1px solid black">
                                <thead>
                                    <tr>
                                        <th scope="col" colspan="6" class="text-center">Credit Particulars</th>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="text-center">DATE</th>
                                        <th scope="col" class="text-center">NET WEIGHT</th>
                                        <th scope="col" class="text-center">T + W</th>
                                        <th scope="col" class="text-center">FINE</th>
                                        <th scope="col" class="text-center">REMARK</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php   if(isset($credit) && !empty($credit)):
                                             foreach ($credit as $credit): ?>
                                    <tr>
                                        <?php $credit_fine[]=$credit->fine; ?>
                                        <td class="text-center"><?php echo ((isset($credit->invoice_no) && !empty($credit->invoice_no))?$credit->invoice_no."<br>":"").(date('d/m/Y', strtotime($credit->date))); ?></td>
                                        <td class="text-center"><?php echo $credit->n_weight; ?></td>
                                        <td class="text-center"><?php echo number_format($credit->touch_wastage,2); ?></td>
                                        <td scope="row" class="text-center"><?php echo $credit->fine; ?></td>
                                        <td class="text-center"><?php echo strtoupper($credit->item_name).((isset($credit->parent_remark) && !empty($credit->parent_remark))?"<br>".strtoupper($credit->parent_remark):''); ?></td>
                                    </tr>
                                    <?php   endforeach; ?>                                    
                                    <tr>                                        
                                        <td colspan="3"></td>
                                        <th scope="row"  class="border-top border-dark text-right p-t-0 p-b-0 text-center"><?php echo number_format((array_sum($credit_fine)),0) ?> </th>
                                        <td></td>
                                    </tr>
                                    <?php endif; ?>                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 table-responsive-sm">
                            <table class="table table-bordered" width="100%">
                                <thead>
                                    <tr>
                                        <th scope="col" colspan="6" class="text-center">Debit Particulars</th>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="text-center">DATE</th>
                                        <th scope="col" class="text-center">NET WEIGHT</th>
                                        <th scope="col" class="text-center">T + W</th>
                                        <th scope="col" class="text-center">FINE</th>
                                        <th scope="col" class="text-center">REMARK</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($debit) && !empty($debit)):
                                            foreach ($debit as $debit): ?>
                                    <tr>
                                        <?php $debit_fine[]=$debit->fine; ?>
                                        <td class="text-center"><?php echo $debit->invoice_no."<br/>".date('d/m/Y', strtotime($debit->date)); ?></td>
                                        <td class="text-center"><?php echo $debit->n_weight;  ?></td>
                                        <td class="text-center"><?php echo number_format($debit->touch_wastage,2);  ?></td>
                                        <td class="text-center"><?php echo $debit->fine; ?></td>
                                        <td class="text-center"><?php echo strtoupper($debit->item_name).((isset($debit->parent_remark) && !empty($debit->parent_remark))?"<br>".strtoupper($debit->parent_remark):''); ?></td>
                                        
                                    </tr>
                                    <?php   endforeach; ?> 
                                    <tr>
                                        <td colspan="3"></td>
                                        <th scope="row"  class="border-top border-dark text-right p-t-0 p-b-0 text-center"><?php echo number_format((array_sum($debit_fine)),0)?> </th>
                                        <td></td>
                                    </tr>
                                    <?php endif;?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 row">
                            <?php if( $c_total->c_total >= $d_total->d_total) :?>
                            <div class="col-md-6">
                                <table class="table">
                                  <thead>
                                  </thead>
                                  <tbody>
                                    <tr>
                                        <th class="text-right w-50"><?php echo number_format($c_total->c_total,0);?></th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right w-50"><?php echo "-".number_format($d_total->d_total,0);?></th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right w-50" style="border-top: 2px solid black; "><?php echo "F.  ".number_format(($c_total->c_total-$d_total->d_total),0);?></th>
                                        <td>Cr Closing Balance</td>
                                    </tr>
                                  </tbody>
                                </table>
                            </div>
                            <?php else:?>
                            <div class="offset-md-6 col-md-6">
                                <table class="table">
                                  <thead>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <th class="text-right w-50"><?php echo number_format($d_total->d_total,0);?></th>
                                      <td></td>
                                    </tr>
                                    <tr>
                                      <th class="text-right w-50"><?php echo "-".number_format($c_total->c_total,0);?></th>
                                      <td></td>
                                    </tr>
                                    <tr>
                                      <th class="text-right w-50" style="border-top: 2px solid black; "><?php echo  "F.  ".number_format(($d_total->d_total-$c_total->c_total),0);?></th>
                                      <td>DB Closing Balance</td>
                                    </tr>
                                  </tbody>
                                </table>
                            </div>
                            <?php endif;?>
                        </div>
                    </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div> 
</div> 
<script type="text/javascript">
    $(document).ready(function() {
        window.print();
});
</script>