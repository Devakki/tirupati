<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title float-left"><?php echo $page_title; ?></h4>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Dashbord');?>"><?php echo COMPANY; ?></a></li>
                        <li class="breadcrumb-item"><a href="#"><?php echo $page_title; ?></a></li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <style type="text/css">
                      .table td, .table th{
                        border-top: none;
                      }
                      .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
                            padding: 7px 10px;
                      }
                      .responsive {
                        width: 100%;
                        max-width: 500px;
                        height: auto;
                      }
                      @media print {
                         .table thead th {
                             border: 1px solid #0c0c0c !important;
                             font-size: 20px;
                           }
                          .table-bordered td, .table-bordered th {
                              border: 1px solid #0c0c0c !important;
                              font-size: 20px;
                          }
                          .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
                            padding: 7px 10px;
                            font-size: 18px;
                           }
                      }
                    </style>
                    <div class="text-center">
                       <h2>ADWAIT FIBRE</h2>
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
                            <table class="table w-100" >
                                <thead>
                                    <tr>
                                        <th scope="col" colspan="2" class="text-center">Credit Particulars</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php   if(isset($credit) && !empty($credit)):
                                             foreach ($credit as $credit): ?>
                                    <tr>
                                        <?php $credit_rs[]=$credit->rs; ?>
                                        <td scope="row" class="text-center"><?php echo number_format($credit->rs,2); ?></td>
                                        <td class="text-center">
                                            <?php echo date('d/m/Y', strtotime($credit->date)); ?>
                                            <?php echo ((isset($credit->gst_type) && !empty($credit->gst_type))?(($credit->gst_type==1)?"<br /> Purchase A/c. ( S+C(GST) )":"<br /> Purchase A/c. (IGST)"):""); ?>
                                            <?php echo (((isset($credit->gst_type) && !empty($credit->gst_type)))?"<br />"."Bill No. ".$credit->invoice_no:""); ?>
                                            <?php echo (((isset($credit->remark) && !empty($credit->remark)))?"<br />".ucwords($credit->remark):"") ?>
                                        </td>
                                    </tr>
                                    <?php   endforeach; ?>                                    
                                    <tr>
                                        <?php $credit_rs=array_sum($credit_rs);  ?>
                                        <th scope="row"  class="border-top border-dark text-right p-t-0 p-b-0 text-center"><?php echo number_format($credit_rs,2)?> </th>
                                        <td></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 table-responsive-sm">
                            <table class="table" width="100%">
                                <thead>
                                    <tr>
                                        <th scope="col" colspan="2" class="text-center">Debit Particulars</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($debit) && !empty($debit)):
                                            foreach ($debit as $debit): ?>
                                    <tr>
                                        <?php $debit_rs[]= $debit->rs;?>
                                        <td class="text-center"><?php echo number_format($debit->rs,2); ?></td>
                                        <td class="text-center">
                                            <?php echo date('d/m/Y', strtotime($debit->date))." Payment"; ?>
                                            <?php echo ((isset($debit->gst_type) && !empty($debit->gst_type))?(($debit->gst_type==1)?"<br /> Sales A/c. ( S+C(GST) )":"<br /> Sales A/c. (IGST)"):""); ?>
                                            <?php echo (((isset($debit->gst_type) && !empty($debit->gst_type)))?"<br />"."Invoice No. ".$debit->invoice_no:""); ?>
                                            <?php echo (((isset($debit->remark) && !empty($debit->remark)))?"<br />".ucwords($debit->remark):"") ?>
                                        </td>
                                    </tr>
                                    <?php   endforeach; ?>
                                    <tr>
                                        <th scope="row"  class="border-top border-dark text-right p-t-0 p-b-0 text-center"><?php echo number_format(array_sum($debit_rs),2);?> </th>
                                        <td></td>
                                    </tr>
                                    <?php   endif;?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 row">
                            <?php if($c_total->c_total  >=  $d_total->d_total) :?>
                            <div class="col-md-6">
                                <table class="table">
                                  <thead>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <th class="text-right w-50"><?php echo number_format($c_total->c_total,2);?></th>
                                      <td></td>
                                    </tr>
                                    <tr>
                                      <th class="text-right w-50"><?php echo "-".number_format($d_total->d_total,2);?></th>
                                      <td></td>
                                    </tr>
                                    <tr>
                                      <th class="text-right w-50" style="border-top: 2px solid black; "><?php echo number_format(($c_total->c_total-$d_total->d_total),2);?></th>
                                      <td>CR Closing Balance</td>
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
                                        <th class="text-right w-50"><?php echo number_format($d_total->d_total,2);?></th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right w-50"><?php echo number_format($c_total->c_total,2);?></th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right w-50" style="border-top: 2px solid black; "><?php echo number_format(($d_total->d_total-$c_total->c_total),2);?></th>
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