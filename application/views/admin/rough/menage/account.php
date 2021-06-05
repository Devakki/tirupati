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
            <div class="col-md-12">
                <div class="card-box">
                    <h4 class="header-title m-t-0 text-center m-b-30">Menage Account</h4>
                    <form id="menagefrm" method="post" action="<?php echo base_url('MenageAccount/clear');?>">
                        <div class="row">
                            <div class="form-group row col-md-6">
                                <label for="party" class="col-3 col-form-label">Party<span class="text-danger">*</span></label>
                                <div class="col-9">
                                    <select class="selectpicker" data-live-search="true" id="party"  name="party" data-style="btn-secondary btn-rounded">
                                        <option value="0">None</option>
                                        <?php foreach ($party as $party) { ?>
                                          <option value="<?php echo $party->id_party; ?>" <?php echo (($method=="edit")?(($party->id_party==$party_id)?"selected":""):"");  ?>> <?php echo $party->name; ?></option>  
                                        <?php } ?> 
                                    </select>                                    
                                </div>
                            </div>
                            <div class="form-group row col-md-6">
                                <label for="Date" class="col-3 col-form-label">Date<span class="text-danger">*</span></label>
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
                                <button type="submit" class="btn btn-danger waves-effect waves-light"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </form>
                </div> 
            </div>
        </div>
    </div> 
</div> 
<script type="text/javascript">
$(document).ready(function() {
    $('form').parsley();
    $("#menagefrm").submit(function(e){
        e.preventDefault();
        let party=$('#party').val();
        console.log(party);
        if(party=="0"){
            swal("error","PLZ Select Party","warning","#4fa7f3");
            return false;
        }
        data=$(this).serialize();
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
              data:data,
              url: "<?php echo base_url('MenageAccount/clear'); ?>",
              success: function(data){
                var data  = JSON.parse(data);
                if(data.status=="success"){
                    swal("success",data.msg,"success","#4fa7f3");
                }else{
                    swal("error",data.msg,"warning","#4fa7f3");
                }              
              }
           })
        });
    });
    $("select").select2();
    $('#date-range').datepicker({
        toggleActive: true,
        format: "dd/mm/yyyy"
    });
});
</script>