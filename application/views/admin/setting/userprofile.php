<style type="text/css">
  .thumb-lg {
      height: 200px;
      width: 200px;
  }
</style>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title float-left"><?php echo $page_title; ?></h4>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Dashbord'); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#"><?php echo $page_title; ?></a></li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
           <!-- end col -->
            <div class="col-md-6">
               <div class="card-box">
                   <h4 class="m-t-0 m-b-30 header-title text-center">Profile</h4>
                   <form class="form-horizontal" role="form" method="post" action="<?php echo (($method=="edit")?base_url('UserProfile/update'):base_url('UserProfile/create'))?>" enctype="multipart/form-data">
                       <div class="form-group row">
                           <label for="fullname" class="col-3 col-form-label">Full Name</label>
                           <div class="col-9">
                               <input type="text" name="name" class="form-control"  placeholder="Full Name" value="<?php echo (($method=="edit")?$profile->name:"") ?>" required>
                               <input type="hidden" name="id" value="<?php echo (($method=="edit")?$profile->id:"") ?>">
                           </div>
                       </div>
                       <div class="form-group row">
                           <label for="image" class="col-3 col-form-label">Image</label>
                           <div class="col-9">
                                <div class="custom-file">
                                   <input type="file" class="custom-file-input" id="customFile" name="image" <?php echo (($method=="add")?"required":""); ?> >
                                   <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                           </div>
                       </div>
                       <div class="form-group m-b-0 row">
                           <div class="offset-3 col-9">
                               <button type="submit" class="btn btn-info waves-effect waves-light"><?php echo (($method=="edit")?"Update":"Add")?></button>
                           </div>
                       </div>
                   </form>
               </div>
            </div>
            <div class="col-md-6">
                <?php if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){?>
                <div class="alert <?php echo $_SESSION['class']; ?> alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <strong></strong> <?php echo $_SESSION['msg']; ?>
                </div>
                <?php 
                unset($_SESSION['class']);
                unset($_SESSION['msg']);
                } ?>
               <div class="card-box text-center">
                   <h4 class="m-t-0 m-b-30 header-title text-center">User Profile</h4>                   
                   <div class="m-b-30">
                       <img src="<?php echo (($method=="edit")?(base_url('assets/uploads/profiles/').$profile->image):base_url('assets/admin/images/users/avatar-5.jpg')); ?>" class="rounded-circle img-thumbnail thumb-lg" alt="thumbnail">
                   </div>
                   <h3 class="text-muted m-b-0">Name : <?php echo (($method=="edit")?ucwords($profile->name):""); ?></h3>
               </div>
            </div>
       </div>
    </div> <!-- container -->
</div> <!-- content -->
<script type="text/javascript">
    $(document).ready(function() {
        $('form').parsley();
        $(".custom-file-input").on("change", function() {
          var fileName = $(this).val().split("\\").pop();
          $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    });
</script>
            