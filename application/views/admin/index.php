  <link href="<?php echo base_url('assets/admin/plugins/summernote/summernote-bs4.css');?>" rel="stylesheet" />
  <style type="text/css">
    th.dt-center, td.dt-center { text-align: center; }
  </style>
  <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
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
            <?php if(isset($_SESSION['note_msg']) && !empty($_SESSION['note_msg'])): ?>
            <div class="alert <?php echo $_SESSION['class']; ?> alert-dismissible fade show" role="alert">
              <strong><?php echo $_SESSION['status']; ?></strong><?php echo " ".$_SESSION['note_msg']; ?> 
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php unset($_SESSION['note_msg']); unset($_SESSION['status']); unset($_SESSION['class']); endif;?>
            <?php if($_SESSION['auth_role_id'] =="2"): ?>
            <div class="row">
              
               <div class="col-xl-3 col-sm-6">
                   <div class="card-box row">
                       <div class="col-6 text-center m-b-10"><a class="" href="<?php echo base_url('SellInvoice/get_addfrm');?>"><button type="button" class="btn btn-danger waves-effect waves-light">Add Invoice</button></a></div>
                       <br>
                       <div class="col-6 text-center m-b-10"><a class="" href="<?php echo base_url('SellPurchase/get_addfrm');?>"><button type="button" class="btn btn-dark waves-effect waves-light">Add Purchase</button></a></div>
                       <div class="col-6 text-center m-b-10"><a class="" href="<?php echo base_url('SalePayment/ledger');?>"><button type="button" class="btn btn-danger waves-effect waves-light">Ledger</button></a></div>
                       <br>
                       <div class="col-6 text-center m-b-10"><a class="" href="<?php echo base_url('SalePayment');?>"><button type="button" class="btn btn-dark waves-effect waves-light">Add Payment</button></a></div>
                   </div>
               </div>               
           </div>
           <div class="row">
               <div class="col-12" style="">
                   <div class="card-box table-responsive">
                       <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                           <thead>
                           <tr>
                              <th>#</th>
                               <th>M/s</th>
                               <th>Date</th>
                               <th>Invoice No</th>
                               <th>Amount</th>
                               <th></th>                                        
                           </tr>
                           </thead>
                       </table>
                   </div>
               </div>
           </div>
           <?php endif;?>
           <?php if($_SESSION['auth_role_id'] =="1"): ?>
            <div class="row">
               <div class="col-xl-3 col-sm-6">
                   <div class="card-box widget-box-two widget-two-custom">
                       <div class="text-center"><a class="" href="<?php echo base_url('RoughInvoice/get_addfrm');?>"><button type="button" class="btn btn-danger waves-effect waves-light">Add Invoice</button></a></div>
                      <br>
                      <div class="text-center"><a class="" href="<?php echo base_url('RoughPurchase/get_addfrm');?>"><button type="button" class="btn btn-dark waves-effect waves-light">Add Purchase</button></a></div>
                   </div>
               </div>
               <div class="col-xl-3 col-sm-6">
                   <div class="card-box widget-box-two widget-two-custom">
                       <div class="text-center"><a class="" href="<?php echo base_url('RoughPayment');?>"><button type="button" class="btn btn-danger waves-effect waves-light">Add Payment</button></a></div>
                   <br>
                   <div class="text-center"><button type="button" class="btn btn-dark waves-effect waves-light" data-toggle="modal" data-target="#con-close-modal">Add Sticky</button></div>
                   </div>
               </div>
               <div class="col-xl-3 col-sm-6">
                   <div class="card-box widget-box-two widget-two-custom">
                       <div class="text-center"><a class="" href="<?php echo base_url('RoughPayment/fine_ledger');?>"><button type="button" class="btn btn-danger waves-effect waves-light">Fine Ledger</button></a></div>
                       <br>
                       <div class="text-center"><a class="" href="<?php echo base_url('RoughPayment/rs_ledger');?>"><button type="button" class="btn btn-dark waves-effect waves-light">Rs Ledger</button></a></div>
                   </div>
               </div>
           </div>
           <div class="row">
            <?php $i=1;  foreach ($sticky_note as $sticky_note) { ?>
              <div class="col-xl-3 col-sm-6">
                  <div class="card-box">
                      <div class="row">
                      <div class="col-md-12 text-right">
                        <button class="btn btn-custom btn-xs"  data-id="edit-sticky" data-value="<?php echo $sticky_note->id;?>"  data-toggle="modal" data-target="#con-close-modal2" ><i class="mdi mdi-pencil"></i></button>
                        <button class="btn btn-danger btn-xs" data-id="remove-sticky" data-value="<?php echo $sticky_note->id;?>"><i class="fa fa-remove"></i></button>
                      </div>
                      <div class="col-md-12 text-center text-danger">
                          <h5 class="bg-dark text-white" style="padding: 10px 0px;"><?php echo ((isset($sticky_note->name) && !empty($sticky_note->name))?$sticky_note->name:"Sticky Note ".$i); ?></h5>
                      </div>
                      <div class="col-md-12">
                        <?php echo $sticky_note->content;?>
                      </div>
                      </div>
                  </div>
              </div>
            <?php $i++; } ?>
           </div>
           <div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                 <div class="modal-dialog">
                   <form action="<?php echo base_url('StickyNote/create')?>" method="POST">
                     <div class="modal-content">
                         <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                             <h4 class="modal-title text-center">ADD STICKY NOTES</h4>
                         </div>
                         <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" placeholder="Party Name" name="party" class="form-control" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                               <div class="col-md-12">
                                   <div class="form-group">
                                       <textarea class="form-control summernote" name="content" required></textarea>
                                   </div>
                               </div>
                            </div>
                         </div>
                         <div class="modal-footer">
                             <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                             <button type="submit" class="btn btn-dark waves-effect waves-light">ADD</button>
                         </div>
                     </div>
                   </form>
               </div>
           </div>
           <div id="con-close-modal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                 <div class="modal-dialog">
                   <form action="<?php echo base_url('StickyNote/update')?>" method="POST">
                     <div class="modal-content">
                         <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                             <h4 class="modal-title text-center">UPDATE STICKY NOTES</h4>
                         </div>
                         <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" placeholder="Party Name" name="party" class="form-control updparty" required />
                                        <input type="hidden"  name="id" class="form-control updid" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                               <div class="col-md-12">
                                   <div class="form-group">
                                       <textarea class="form-control updpartycontent" name="content" required></textarea>
                                   </div>
                               </div>
                            </div>
                         </div>
                         <div class="modal-footer">
                             <button type="submit" class="btn btn-dark waves-effect waves-light">Update</button>
                         </div>
                     </div>
                   </form>
               </div>
           </div>
           <?php endif;?>
        </div> 
    </div> 
<script type="text/javascript" src="<?php echo base_url('assets/admin/plugins/counterup/jquery.counterup.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/admin/plugins/waypoints/jquery.waypoints.min.js');?>"></script>
<script src="<?php echo base_url('assets/admin/plugins/summernote/summernote-bs4.min.js')?>"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('form').parsley();
  <?php if($_SESSION['auth_role_id'] =="2"): ?>
      var table = $('#datatable-buttons').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        dom: 'Blfrtip',                      
        ajax: {
               "url": "<?php echo base_url('SellInvoice/getLists/'); ?>",
               "type": "POST",
           }, 
        columnDefs: [{ "targets": [0], "orderable": false ,"className": "dt-center", "targets": "_all" }],
        buttons: ['print','copy', 'excel', 'colvis'],
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
                    url: "<?php echo base_url('SellInvoice/delete/');?>"+id+"",
                    success: function(data){
                      var data  = JSON.parse(data);
                      if(data.status=="success"){
                          swal("success",data.message,"success","#4fa7f3");
                         table.ajax.reload();
                      }else{
                          swal("Eroor",data.message,"warning","#4fa7f3");
                      }
                    }
                });
            })
        });
  <?php endif;?>
  <?php if($_SESSION['auth_role_id'] =="1"): ?>
    $('.summernote').summernote({
           height: 250, 
           toolbar: [
              ['style', ['style']],
              ['style', ['bold', 'italic', 'underline', 'clear']],
              ['fontname', ['fontname']],
              ['font', ['strikethrough', 'superscript', 'subscript']],
              ['fontsize', ['fontsize']],
              ['color', ['color']],
              ['para', ['ul', 'ol', 'paragraph']],
              ['view', ['fullscreen', 'help']]
            ] 
       });
     $('.inline-editor').summernote({
         airMode: true
     });
     $('body').on('click', '[data-id=remove-sticky]', function () { 
        let id=$(this).data("value");
        let obj=$(this).parents('.card-box');
        swal({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Yes, delete it!',
              cancelButtonText: 'No, cancel!',
              confirmButtonClass: 'btn btn-success',
              cancelButtonClass: 'btn btn-danger m-l-10',
              buttonsStyling: false
          }).then(function () {
              $.ajax({
                  type: "POST",
                  url: "<?php echo base_url('StickyNote/delete/');?>"+id+"",
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
              // dismiss can be 'cancel', 'overlay',
              // 'close', and 'timer'
              if (dismiss === 'cancel') {
                  swal(
                      'Cancelled',
                      'Your imaginary file is safe :)',
                      'error'
                  )
              }
          })
     });
     $('body').on('click', '[data-id=edit-sticky]', function () { 
        let id=$(this).data("value");
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('StickyNote/edit/');?>"+id+"",
            success: function(data){
              var data  = JSON.parse(data);
              if(data.status=="success"){
                $('.updparty').val(data.row.name);
                $('.updid').val(data.row.id);
                $('.updpartycontent').val(data.row.content);
                $('.updpartycontent').summernote('destroy');
                $('.updpartycontent').summernote({
                    height: 250, 
                    toolbar: [
                       ['style', ['style']],
                       ['style', ['bold', 'italic', 'underline', 'clear']],
                       ['fontname', ['fontname']],
                       ['font', ['strikethrough', 'superscript', 'subscript']],
                       ['fontsize', ['fontsize']],
                       ['color', ['color']],
                       ['para', ['ul', 'ol', 'paragraph']],
                       ['view', ['fullscreen', 'help']]
                     ] 
                 });
              }             
            }
         })
     });
  <?php endif;?>
  });
</script>
               