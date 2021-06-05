<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title float-left"><?php echo $page_title; ?></h4>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Dashbord');?>"><?php echo COMPANY; ?></a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('MasterAdmin/index');?>"><?php echo $page_title; ?></a></li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card-box">
                    <h4 class="header-title m-t-0 text-center m-b-30"><?php echo (($method=="edit")?"Update":"Add")?> User</h4>
                    <form role="form" id="<?php echo (($method=="edit")?"$frm_id":"$frm_id");?>">
                        <div class="form-group row">
                            <label for="username" class="col-4 col-form-label">User Name<span class="text-danger">*</span></label>
                            <div class="col-7">
                                <input type="text" name="username" required class="form-control" placeholder="User Name" value="<?php echo (($method=="edit")?$user->username:"")?>">
                                <?php echo (($method=="edit")?'<input type="hidden" name="id" required  value="'.$user->id_master.'">':'')?>
                            </div>
                        </div>
                        <?php if($method=="add"){?>
                        <div class="form-group row">
                            <label for="hori-pass1" class="col-4 col-form-label">Password<span class="text-danger">*</span></label>
                            <div class="col-7">
                                <input id="hori-pass1" type="password" name="password" placeholder="Password" required class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hori-pass2" class="col-4 col-form-label">Confirm Password
                                <span class="text-danger">*</span></label>
                            <div class="col-7">
                                <input data-parsley-equalto="#hori-pass1" type="password" required
                                       placeholder="Password" class="form-control" id="hori-pass2" data-parsley-required-message="Enter password" data-parsley-equalto-message="Password and conform Password are not same">
                            </div>
                        </div>
                        <?php } ?>
                        <div class="form-group row">
                            <label for="mobile" class="col-4 col-form-label">Mobile No.<span class="text-danger">*</span></label>
                            <div class="col-7">
                                <input type="number" placeholder="Mobile No" required data-parsley-length="[10,10]"  class="form-control" name="mobile" data-parsley-length-message="Enter valid mo no" value="<?php echo (($method=="edit")?"$user->phone":"")?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="role" class="col-4 col-form-label">Role.<span class="text-danger">*</span></label>
                            <div class="col-7">
                                <select class="selectpicker" data-style="btn-custom" name="role_id">
                                    <?php foreach ($role as $role): ?>
                                    <option value="<?php echo $role->role_id; ?>"  <?php echo (($method=="edit")?(($role->role_id==$user->role_id)?"selected":""):"") ?>><?php echo $role->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
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
            <div class="col-md-6">
                <div class="card-box table-responsive">
                    <table id="datatable-buttons" class=" table table-striped table-bordered text-center" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>USER NAME</th>
                            <th>MOBILE NUMBER</th>
                            <th>ROLE</th>
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
                   "url": "<?php echo base_url('MasterAdmin/myFunction/'); ?>",
                   "type": "POST"
               },
            "order": [[0, "asc" ]],
            columns: [      
                        { "data": "sr_no" },
                        { "data": "username"},
                        { "data": "phone" },
                        { "data": "role" },
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
                url: "<?php echo base_url('MasterAdmin/create')?>",
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
                url: "<?php echo base_url('MasterAdmin/update')?>",
                type: "POST",
                data: data,              
                success: function(result){
                    var result  = JSON.parse(result);
                    if(result.status=="success"){
                        swal("success",result.msg,"success","#4fa7f3").then(function () { 
                         window.location="<?php echo base_url("MasterAdmin");?>";            
                    });
                    }else{
                        swal("error",result.msg,"warning","#4fa7f3");
                    }
                }
            });
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
                    url: "<?php echo base_url('MasterAdmin/delete/');?>"+id+"",
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
    });
</script>