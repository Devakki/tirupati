<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?php echo TITLE; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="DEVLOPED BY - LARK INFOWAY.  SPECIALIZES IN WEB DEVELOPMENT, MOBILE APP DEVELOPMENT, INTERNET MARKETING, ERP SOLUTIONS, CRM SOLUTIONS, CUSTOM DEVELOPMENT, AND PORTAL DEVELOPMENT." name="description" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="icon" type="image/png" href="<?php echo base_url('assets/admin/images/logo_sm.png');?>">
        <!-- App css -->
        <link href="<?php echo base_url('assets/admin/');?>css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/admin/');?>css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/admin/');?>css/style.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/admin/');?>plugins/jquery-toastr/jquery.toast.min.css" rel="stylesheet" />
        <link href="<?php echo base_url('assets/admin/');?>css/style.css" rel="stylesheet" type="text/css" />
         <script src="<?php echo base_url('assets/admin/');?>js/jquery.min.js"></script>
         <script src="<?php echo base_url('assets/admin/');?>plugins/jquery-toastr/jquery.toast.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/admin/');?>pages/jquery.toastr.js" type="text/javascript"></script>
         <script src="<?php echo base_url('assets/admin/');?>/js/jquery.core.js"></script>
        <script src="<?php echo base_url('assets/admin/');?>/js/popper.min.js"></script><!-- Popper for Bootstrap -->
        <script src="<?php echo base_url('assets/admin/');?>/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url('assets/admin/');?>/js/metisMenu.min.js"></script>
        <script src="<?php echo base_url('assets/admin/');?>/js/waves.js"></script>
        <script src="<?php echo base_url('assets/admin/');?>/js/jquery.slimscroll.js"></script>
        <style type="text/css">
            .text-v{
                color: #343a40;
            }
            .bg-accpunt-pages {
                background: #6D6D6D;
                background: -webkit-linear-gradient(to left, #6D6D6D, #6D6D6D);
                background: linear-gradient(to left, #6D6D6D, #6D6D6D);
                padding-bottom: 0;
                min-height: 100px !important;
            }
        </style>
    </head>
    <body class="bg-accpunt-pages">
        <!-- HOME -->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="wrapper-page">
                            <div class="account-pages">
                                <div class="account-box">
                                    <div class="account-logo-box">
                                        <div class="text-center">
                                            <a href="#" class="text-center">
                                                 <span class="text-v"><?php echo COMPANY ;?></span> 
                                            </a>
                                        </div>
                                    </div>
                                    <div class="account-content">
                                        <form role="form" class="form-horizontal" action="<?php echo base_url('admin/logincheck')?>" method="post">
                                            <div class="form-group m-b-20 row">
                                                <div class="col-12">
                                                    <label for="emailaddress">User Name</label>
                                                    <input class="form-control" type="text" parsley-trigger="change"  name="uname"  required="" autocomplete="off" placeholder="User Name">
                                                </div>
                                            </div>
                                            <div class="form-group row m-b-20">
                                                <div class="col-12">
                                                    <label for="password">Password</label>
                                                    <input class="form-control" type="password" name="password" required="" id="password" autocomplete="off" placeholder="Password">
                                                </div>
                                            </div>
                                            <div class="form-group row text-center m-t-10">
                                                <div class="col-12">
                                                    <button class="btn btn-md btn-block btn-dark waves-effect waves-light" type="submit">Sign In</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end card-box-->
                        </div>
                        <!-- end wrapper -->
                    </div>
                </div>
            </div>
          </section>
          <!-- END HOME -->
          <script type="text/javascript" src="<?php echo base_url('assets/admin');?>/plugins/parsleyjs/parsley.min.js"></script>
          <script type="text/javascript">
            $(document).ready(function(){
                 $('form').parsley();
                 <?php if(isset($errMsg) && !empty($errMsg)){ ?>
                    var msg="<?php echo $errMsg; ?>";
                 <?php  }else {?>
                    var msg = null;
                  <?php }?>
                if(msg !== null){
                $.toast({
                    heading: 'wrong turn',
                    text: '<?php if(isset($errMsg)){echo $errMsg;}?>',
                    position: 'top-right',
                    loaderBg: '#bf441d',
                    icon: 'error',
                    hideAfter: 3000,
                    stack: 1
                });
                }
            });
          </script>
    </body>
</html>