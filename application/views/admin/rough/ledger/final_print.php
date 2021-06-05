<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <style type="text/css">
                  .table td, .table th{
                    border-top: none;
                  }
                  .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
                        padding: 7px 10px;
                  }
                  .responsive {
                    width: 100%;
                    max-width: 400px;
                    height: auto;
                  }
                </style>
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
        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <div class="text-center m-b-20">
                        <img class="responsive" src="<?php echo base_url('assets/admin/images/sliver.png'); ?>" >
                    </div> 
                    <?php if($display) :?>
                    <div class="row">
                        <div class="col-md-12 table-responsive-sm">
                            <style type="text/css">
                                @media print {
                                   .table thead th {
                                       border: 1px solid #0c0c0c !important;
                                       font-size: 20px;
                                     }
                                    .table-bordered td, .table-bordered th {
                                        border: 1px solid #0c0c0c !important;
                                        font-size: 20px;
                                    }
                                }
                               .table thead th {
                                   border: 1px solid #0c0c0c !important;
                                 }
                                .table-bordered td, .table-bordered th {
                                    border: 1px solid #0c0c0c !important;
                                }
                            </style>
                            <table class="table w-100 table-bordered" style="border:1px solid black">
                                <thead>
                                    <tr>
                                        <th scope="col" colspan="6" class="text-center"><h3>FINAL REPORT</h3></th>
                                    </tr>
                                    <tr>
                                        <th scope="col">SR No.</th>
                                        <th scope="col">PARTY</th>
                                        <th scope="col">FINE</th>
                                        <th scope="col">RS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($party_data as $key=>$party): ?>
                                        <tr>
                                            <td><?php echo $party['sr_no']; ?></td>
                                            <td><?php echo strtoupper($party['party_name']); ?></td>
                                            <td><?php echo "F. ".$party['fine'] ."  ".$party['fine_closing'];  ?></td>
                                            <td><?php echo "₹ ".$party['rs']."  ".$party['rs_closing']; ?></td>
                                        </tr>
                                    <?php endforeach;?>                                    
                                </tbody>
                            </table>
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