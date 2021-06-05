<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title float-left"><?php echo $page_title; ?></h4>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Dashbord');?>"><?php echo COMPANY; ?></a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('RoughPayment/index');?>"><?php echo $page_title; ?></a></li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <h4 class="header-title m-t-0 text-center m-b-30">Customer's Ledger</h4>
                    <form role="form" method="post" action="<?php echo $action ;?>">
                        <div class="row">
                            <div class="form-group row col-md-6">
                                <label for="customer" class="col-3 col-form-label">Customer<span class="text-danger">*</span></label>
                                <div class="col-9">
                                <select name="customer">
                                    <?php foreach ($customer as $customer) { ?>
                                      <option value="<?php echo $customer->id_customer; ?>" <?php echo (($method=="edit")?(($customer->id_customer==$customer_id)?"selected":""):"");  ?>> <?php echo $customer->name; ?></option>   
                                    <?php } ?> 
                                </select>
                                </div>
                            </div>
                            <div class="form-group row col-md-6">
                                <label for="code" class="col-3 col-form-label">Date<span class="text-danger">*</span></label>
                                <div class="col-9">
                                    <?php $month= date('d/m/Y',strtotime('-1 month'));?>
                                    <div class="input-daterange input-group" id="date-range">
                                        <input type="text" class="form-control" name="start" autocomplete="off" value="<?php echo (($method=="edit")?$strt_date:$month) ; ?>" />
                                        <input type="text" class="form-control" name="end" autocomplete="off"  value="<?php echo (($method=="edit")?$end_date:date('d/m/Y')) ; ?>" />
                                    </div>                            
                                </div>
                            </div>
                        </div>
                        <div class="form-group row m-t-20">
                            <div class="col-12 text-center">
                                <button type="submit" target="_blank" class="btn btn-primary waves-effect waves-light">
                                    Check
                                </button>
                            </div>
                        </div>
                    </form>
                </div> 
            </div>
        </div>
        <?php if($display): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
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
                    <div class="row">
                        <div class="col-md-6" >
                            <style type="text/css">
                                .table td, .table th{
                                    border-top: none;
                                }
                                .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
                                        padding: 7px 10px;
                                }
                            </style>
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
                        <div class="col-md-6">
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
                            <?php if($c_total->c_total  >=  $d_total->d_total ) :?>
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
                        <div class="col-md-12 text-center">
                            <a href="<?php echo $btn_url; ?>" target="_blank" class="btn btn-danger btn-bordered waves-effect  waves-light"><i class="mdi mdi-printer"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div> 
</div> 
<script type="text/javascript">
    $(document).ready(function() {
        $('form').parsley();
        $("select").select2();
        $('#date-range').datepicker({
            toggleActive: true,
            format: "dd/mm/yyyy"
        });
});
</script>