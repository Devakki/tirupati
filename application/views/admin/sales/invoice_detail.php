    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left"><?php echo $page_title; ?></h4>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('Dashbord');?>"><?php echo COMPANY; ?></a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url('SellInvoice/index');?>"><?php echo $page_title; ?></a></li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12" style="">
                    <div class="card-box table-responsive" style="">
                        <a href="<?php echo base_url("SellInvoice/get_addfrm")?>"><button type="button" class="btn btn-primary waves-effect waves-light" >Add Invoice</button></a>
                        <hr />
                        <table id="datatable-buttons" class="table table-striped table-bordered text-center" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>M/s</th>
                                <th>Date</th>
                                <th>Invoice No </th>
                                <th>Amount</th>
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
        var msg='<?php echo((isset($_SESSION['Msg']) && !empty($_SESSION['Msg']))?$_SESSION['Msg']:"");?>';
        if(msg !== null && msg !== ''){
          swal("sucess",msg,"success","#4fa7f3").then(function () { 
             <?php unset($_SESSION['Msg']);?> 
        });
        }
        $('form').parsley();
        var table = $('#datatable-buttons').DataTable({
          processing: true,
          serverSide: true,
          order: [],
          dom: 'Blfrtip',                      
          ajax: {
                 "url": "<?php echo base_url('SellInvoice/getLists/'); ?>",
                 "type": "POST",
             }, 
          columnDefs: [{ "targets": [0], "orderable": false}],
          lengthChange: false,
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
    } );
</script>