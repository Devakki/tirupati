<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title float-left"><?php echo $page_title; ?></h4>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Dashbord');?>"><?php echo COMPANY; ?></a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Product/index');?>"><?php echo $page_title; ?></a></li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card-box">
                    <center><h4 class="header-title m-t-0"><?php echo (($method=="edit")?"Update Product":"Add Product" )?></h4></center><br>
                    <form id="<?php echo (($method=="edit")?"$frm_id":"$frm_id");?>" class="form-horizontal" role="form" >
                          <div class="form-group row">
                              <label for="name" class="col-3 col-form-label">Product Name<span class="text-danger">*</span></label>
                              <div class="col-9">
                                  <input placeholder="Product Name" type="text" name="name" title="Product Name" required="" data-parsley-required-message="Product Name is Required" class="form-control" autocomplete="off" value="<?php echo (($method=="edit")?$result->name:"");  ?>">
                              </div>
                          </div>
                          
                          <div class="form-group row">
                              <label for="hsn_code" class="col-3 col-form-label">HSN Code<span class="text-danger">*</span></label>
                              <div class="col-9">
                                  <input placeholder="HSN Code" type="text" name="hsn_code"  data-parsley-maxlength="8" data-parsley-maxlength-message="Place Enter Valid HSN Code" class="form-control" autocomplete="off" value="<?php echo (($method=="edit")?$result->hsn_code:''); ?>">
                              </div>
                          </div>
                        <?php if($method=="edit"): ?> 
                        <div class="form-group row">
                            <label for="status" class="col-3 col-form-label">Status</label>
                            <div class="col-9">
                                <select class="selectpicker"  title="item Status" name="status"  data-style="btn-custom">
                                    <option value="1" <?php echo (($method=="edit")?(($result->status=="1")?"selected":""):"");  ?>>Active</option>                
                                    <option value="0" <?php echo (($method=="edit")?(($result->status=="0")?"selected":""):"");  ?>>InActive</option>
                                </select>
                                <?php echo (($method=="edit")?'<input type="hidden" name="id" value="'.$result->id_product.'">':""); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="form-group text-center m-t-20 m-b-20 ">
                          <button class="btn btn-primary waves-effect waves-light" type="submit">
                              <?php echo (($method=="edit")?"Update":"Add") ?>
                          </button>
                      </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-box table-responsive">
                    <table id="datatable-buttons" class="table table-striped table-bordered text-center" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Item Name</th> 
                            <th>HSN Code</th>
                            <th>Stutus</th>
                            <th>Action</th>
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
      $("select").select2(); 
        var table = $('#datatable-buttons').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            dom: 'Blfrtip',
            ajax: {
                   "url": "<?php echo base_url('Product/getLists/'); ?>",
                   "type": "POST",
               },
            columnDefs: [{ "targets": [0],"orderable": false  }],
            buttons: ['print','copy', 'excel', 'colvis'],
            lengthChange: false,
        });
       $('#datatable-buttons').on('click', '[data-id=delete]', function () {                        
            var id= $(this).data("value");
            swal({
                    title: 'Are you sure delete?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4fa7f3',
                    cancelButtonColor: '#d57171',
                    confirmButtonText: 'Yes, delete it!'
                }).then(function () {
                $.ajax({
                  type: "POST",
                  url: "<?php echo base_url('Product/delete/');?>"+id+"",
                  success: function(data){
                    var data  = JSON.parse(data);
                    if(data.status=="success"){
                        swal("success",data.message,"success","#4fa7f3");
                        table.ajax.reload();
                    }else{
                        swal("error",data.message,"warning","#4fa7f3");
                    }
                  }
                });
            })
        });
        $("#Add_frm").submit(function(event){
           event.preventDefault();
           var data=$(this).serialize();            
           $.ajax({
               url: "<?php echo base_url('Product/create')?>",
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
               url: "<?php echo base_url('Product/update')?>",
               type: "POST",
               data: data,              
               success: function(result){
                   var result  = JSON.parse(result);
                   if(result.status=="success"){
                       swal("success",result.msg,"success","#4fa7f3").then(function () { 
                        window.location="<?php echo base_url("Product");?>";            
                   });
                   }else{
                       swal("error",result.msg,"warning","#4fa7f3");
                   }
               }
           });
       });
    });
</script>