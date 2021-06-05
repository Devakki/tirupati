<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title float-left"><?php echo $page_title; ?></h4>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('dashbord');?>"><?php echo COMPANY; ?></a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('RoughPayment/index');?>"><?php echo $page_title; ?></a></li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card-box">
                    <h4 class="header-title m-t-0 text-center m-b-30"><?php echo (($method=="edit")?"Update":"Add")?> Payment</h4>
                    <form role="form" id="<?php echo (($method=="edit")?"$frm_id":"$frm_id");?>">
                        <div class="form-group row">
                            <label for="bill_type" class="col-4 col-form-label">Bill Type</label>
                                <?php if($method=="edit") :?>
                                    <div class="col-7" style="margin-top: 4px;">                                
                                        <label class="radio-inline" style="padding-right:20px;">
                                            <input type="radio"  name="bill_type" value="1"
                                            <?php echo(($method=="edit")?(($payment->bill_type=="1")?"checked":""):"checked"); ?> >Credit
                                            <input type="radio" name="bill_type" value="2" 
                                            <?php echo(($method=="edit")?(($payment->bill_type=="2")?"checked":""):""); ?>>Debit
                                        </label>                                   
                                    </div>
                                <?php else: ?>                                    
                                    <div class="col-7" style="margin-top: 4px;">                                
                                        <label class="radio-inline" style="padding-right:20px;">
                                            <input type="radio"  name="bill_type" value="1" checked >Credit
                                            <input type="radio" name="bill_type" value="2">Debit
                                        </label>                                   
                                    </div>
                                <?php endif; ?>                                
                        </div>
                        <div class="form-group row">
                            <label for="party" class="col-4 col-form-label">Party<span class="text-danger">*</span></label>
                            <div class="col-7">
                            <select  name="party" data-style="btn-secondary btn-rounded" >
                                <option value="0">None</option>
                                <?php foreach ($party as $party) { ?>
                                  <option value="<?php echo $party->id_party; ?>" <?php echo (($method=="edit")?(($party->id_party==$payment->party_id)?"selected":""):"");  ?>> <?php echo $party->name; ?></option>   
                                <?php } ?> 
                            </select>
                                <?php echo (($method=="edit")?'<input type="hidden" name="id_payment" required  value="'.$payment->id.'">':'')?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="code" class="col-4 col-form-label">Date<span class="text-danger">*</span></label>
                            <div class="col-7">
                                <input type="text" class="form-control" <?php echo (($method=="edit")?((isset($readonly) && !empty($readonly))?"":"id='datepicker-autoclose'"):"id='datepicker-autoclose'");?>   name="date" placeholder="Date" value="<?php echo (($method=="edit")?(date('d/m/Y',strtotime($payment->date))):date('d/m/Y'));?>" <?php echo (($method=="edit")? ((isset($readonly) && !empty($readonly))?$readonly:""):"");?>  required >                                
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="code" class="col-4 col-form-label">Rs<span class="text-danger">*</span></label>
                            <div class="col-7">
                                <input type="text" name="rs" placeholder="Rs" autofocus="off" class="form-control" value="<?php echo (($method=="edit")?$payment->rs:"");?>"  <?php echo (($method=="edit")?$readonly:"");?> required >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="remark" class="col-4 col-form-label">Remark<span class="text-danger">*</span></label>
                            <div class="col-7">
                                <textarea class="form-control" name="remark" placeholder="Remark"><?php echo (($method=="edit")?$payment->remark:"");?></textarea>                                
                            </div>
                        </div>
                        <div class="form-group row m-t-40">
                            <div class="col-8 offset-4">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    <?php echo (($method=="edit")?"Update":"Register")?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div> 
            </div>
            <div class="col-md-8">
                <div class="card-box table-responsive">
                    <table id="datatable-buttons" class=" table table-striped table-bordered text-center" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Bill Type</th>
                            <th>Party</th>
                            <th>Date</th>
                            <th>Rs</th>
                            <th>Remark</th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div> 
</div> 
<script type="text/javascript">
    $(document).ready(function() {
        $('form').parsley();
        $('select').select2();
        var table = $('#datatable-buttons').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                   "url": "<?php echo base_url('RoughPayment/myFunction/'); ?>",
                   "type": "POST"
               },
            "order": [[0, "asc" ]],
            columns: [      
                        { "data": "sr_no" },
                        { "data": "bill_type"},
                        { "data": "party" },
                        { "data": "date" },
                        { "data": "rs" },
                        { "data": "remark" },
                        { "data": "button" },
                    ],
            buttons: ['print','copy', 'excel', 'colvis'],
            lengthChange: false,
            dom: 'Blfrtip'
        });
        table.buttons().container().appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
        $("#Add_frm").submit(function(event){
            event.preventDefault();
            var data=$(this).serialize();   
            $.ajax({
                url: "<?php echo base_url('RoughPayment/create')?>",
                type: "POST",
                data: data,              
                success: function(result){
                    var result  = JSON.parse(result);
                    if(result.status=="success"){
                        swal("success",result.msg,"success","#4fa7f3");
                        $('#Add_frm')[0].reset();
                        table.ajax.reload();
                    }else{
                        swal("error",result.msg,"warning","#4fa7f3");
                    }
                }
            });
        });
        $("#Edit_frm").submit(function(event){
            event.preventDefault();              
            var data=$(this).serialize();            
            $.ajax({
                url: "<?php echo base_url('RoughPayment/update')?>",
                type: "POST",
                data: data,              
                success: function(result){
                    var result  = JSON.parse(result);
                    if(result.status=="success"){
                        swal("success",result.msg,"success","#4fa7f3").then(function () { 
                        window.location="<?php echo base_url("RoughPayment");?>";            
                    });
                    }else{
                        swal("error",result.msg,"warning","#4fa7f3");
                    }
                }
            });
        });
        $('body').on('click','[data-id=delete]', function(){
              var obj=$(this).parents('tr');
              var id=$(this).data("value");
              swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'No, Cancel!',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger m-l-10',
                    buttonsStyling: false
                }).then(function () {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('RoughPayment/delete')?>/",
                        data:{
                              id:id
                        },
                        success: function(data){
                          var data  = JSON.parse(data);
                          if(data.status=="success"){
                             swal('Deleted!',data.msg,'success');
                             obj.remove();
                          }else{
                             swal("error",data.msg,"warning","#4fa7f3");  
                          }              
                        }
                     })
                }, function (dismiss) {
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        'Your imaginary file is safe :)',
                        'error'
                    )
                }
            }) 
        });
});
</script>