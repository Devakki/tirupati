<div class="left side-menu">
    <div class="slimscroll-menu" id="remove-scroll">
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">
                <li>
                    <a href="<?php echo base_url('Dashbord')?>"><i class="fi-air-play"></i><span class="badge badge-success pull-right"></span> <span> Dashbord </span></a>
                </li>
                <li>
                    <a href="javascript: void(0);"><i class="fa fa-cog"></i><span> Setting </span> <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="<?php echo base_url('City')?>">City</a></li>
                        <li><a href="<?php echo base_url('State')?>">State</a></li>
                        <?php if($_SESSION['auth_role_id'] =="2"): ?>
                        <li><a href="<?php echo base_url('Transpoter')?>">Transpoter</a></li>
                        <?php endif;?>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo base_url('Account')?>"> <i class="fa fa-users " aria-hidden="true"></i><span> Account </span> <span class="menu-arrow"></span></a>
                </li>
                <li>
                    <a  href="<?php echo base_url('Product')?>"> <i class="fa fa-product-hunt" aria-hidden="true"></i><span> Product </span> <span class="menu-arrow"></span></a>
                    
                </li>
                <li>
                    <a href="javascript: void(0);"><i class="mdi mdi-script"></i><span> Rough Invoice</span> <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="<?php echo base_url('SellInvoice')?>">Sale</a></li>
                        <li><a href="<?php echo base_url('RoughPurchase')?>">Purchase </a></li>   
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);"><i class="fa fa-rupee"></i><span>Payment </span> <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="<?php echo base_url('RoughPayment')?>">Payment </a></li>
                    </ul>
                </li> 
                <li>
                    <a href="javascript: void(0);"><i class="mdi mdi-printer"></i><span>Ledger </span> <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="<?php echo base_url('RoughPayment/fine_ledger')?>">Fine Ledger</a></li>
                        <li><a href="<?php echo base_url('RoughPayment/rs_ledger')?>">Rs Ledger</a></li>
                        <li><a href="<?php echo base_url('RoughPayment/final_report')?>">Final Report</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);"><i class="fa fa-trash"></i><span>Menage</span> <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="<?php echo base_url('MenageAccount')?>">Account</a></li>
                    </ul>
                </li>    -->                         
                
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div class="content-page">
  