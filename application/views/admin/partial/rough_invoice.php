<div class="content">
  <div class="container-fluid">
      <div class="row">
          <div class="col-12">
              <div class="page-title-box">
                  <h4 class="page-title float-left">Invoice</h4>
                  <ol class="breadcrumb float-right">
                      <li class="breadcrumb-item"><a href="<?php echo base_url('dashbord');?>"><?php echo COMPANY; ?></a></li>
                      <li class="breadcrumb-item"><a href="#"><?php echo (($method=="edit")?"Update ":"Add " )?>Rough Invoice</a></li>
                  </ol>
                  <div class="clearfix"></div>
              </div>
          </div>
      </div>
      <div class="row">
          <div class="col-lg-12">
              <div class="card-box ">
                  <h4 class="header-title m-t-0"><?php echo (($method=="edit")?"Update Invoice":"Add Invoice" )?></h4><br>
                  <form action="<?php echo (($method=="edit")?base_url('RoughInvoice/invoice_update'):base_url('RoughInvoice/invoice_create')); ?>" method="post" >
                      <div class="row">
                          <div class="col-md-6">   
                              <div class="form-group row">
                                  <label for="bill_type" class="col-3 col-form-label">Bill Type</label>
                                    <div class="col-9" style="margin-top: 4px;">                                
                                        <label class="radio-inline" style="padding-right:20px;">                                        
                                        <input type="radio" name="bill_type" value="debit" 
                                          <?php echo(($method=="edit")?(($rough_invoice->bill_type=="debit")?"checked":""):"checked"); ?>>Debit
                                        </label>                                   
                                    </div>
                              </div>
                              <div class="form-group row">
                                  <label for="party_id" class="col-3 col-form-label">Party</label>
                                      <div class="col-9">
                                          <select name="party_id" id="party_id" required >
                                            <option value="0">None</option>
                                            <?php
                                            foreach ($party as  $party) {
                                              echo '<option value="'.$party->id_party .'"'.
                                              (($method=="edit")?(($party->id_party ==$rough_invoice->party_id)?"selected":""):"")
                                              .' >'.$party->name.'</option>';
                                            }
                                            ?>
                                          </select>
                                      </div>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group row">
                                 <label for="invoice_no" class="col-3 col-form-label">Invoice No</label>
                                     <div class="col-9">
                                        <input type="text" class="form-control"  title="Invoice No" name="invoice_no" placeholder="Invoice No." value="<?php echo (($method=="edit")?$rough_invoice->invoice_no:$invoice['no_invoice'] )?>" required >
                                     </div>
                              </div>
                              <div class="form-group row">
                                  <label for="date" class="col-3 col-form-label">Date</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="datepicker-autoclose" title="Date" name="date" placeholder="Date" value="<?php echo (($method=="edit")?(date('d/m/Y',strtotime($rough_invoice->date))):date('d/m/Y'));?>">
                                       <?php echo (($method=="edit")?'<input type="hidden" name="id_rough" required value="'.$rough_invoice->id_rough .'">':"");?>
                                    </div>
                              </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 row">
                          <div class="col-md-6 offset-md-3" style="overflow-x:auto;">
                            <table class="table" id="childtbl" style="min-width: 600px;">
                                <thead>
                                    <tr>
                                        <th>Tr</th>
                                        <th>NO OF BAG</th>
                                        <th>WEIGHT / BAG</th> 
                                        <th>TOTAL WEIGHT</th>                                                 
                                        <th></th>                               
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php
                                      if($method=="edit"):
                                      $i=1;
                                      foreach ($rough_bag as $rough_bag):
                                  ?>
                                    <tr>
                                      <td width="15%">
                                          <select name="ctr_no[]" class="cTr_no" data-live-search="true"  data-style="btn-custom">
                                            <?php
                                              for ($x = 1; $x <= 5; $x++) {
                                                  echo '<option value="'.$x.'" '.(($x==$rough_bag->tr_no)?"selected":"").' >'.$x.'</option>';
                                              }
                                            ?>
                                          </select>
                                      </td>
                                      <td>
                                          <input type="number" name="cbag[]"  step="any"  class="form-control cBag" name="" required  placeholder="NO OF BAG" value="<?php echo $rough_bag->bag; ?>" />
                                          <input type="hidden" name="id_bag[]" class="form-control"   value="<?php echo $rough_bag->id; ?>" />
                                      </td>
                                      <td>
                                          <input type="number" name="cweight[]" step="any" class="form-control cWeight"  placeholder="WEIGHT / BAG" value="<?php echo $rough_bag->weight; ?>"  />
                                      </td>
                                      <td>
                                          <input type="number"  name="ctweight[]" step="any" class="form-control cTWeight " placeholder="TOTAL WEIGHT" value="<?php echo $rough_bag->total; ?>"   readonly />
                                      </td>
                                      <td>
                                        <?php if($i !='1'):?>
                                              <button type="button" data-id="chlildDltBtn" data-value="<?php echo $rough_bag->id; ?>" class="btn btn-icon waves-effect waves-light btn-warning btn-sm"><i class="mdi mdi-delete"></i></button>
                                        <?php endif;?>
                                      </td>
                                    </tr>
                                    <?php 
                                          $i++;
                                          endforeach;
                                          endif; 
                                    ?>
                                    <tr id="xChildTr">
                                      <td width="15%">
                                          <select name="ctr_no[]" class="cTr_no" data-live-search="true"  data-style="btn-custom">
                                            <?php
                                              for ($x = 1; $x <= 5; $x++) {
                                                  echo '<option value="'.$x.'">'.$x.'</option>';
                                              }
                                            ?>
                                          </select>
                                      </td>
                                      <td>
                                          <input type="number" name="cbag[]"  step="any"  class="form-control cBag" name=""   placeholder="NO OF BAG" />
                                      </td>
                                      <td>
                                          <input type="number" name="cweight[]" step="any" class="form-control cWeight"  placeholder="WEIGHT / BAG" value=""  />
                                      </td>
                                      <td>
                                          <input type="number"  name="ctweight[]" step="any" class="form-control cTWeight " placeholder="TOTAL WEIGHT" value=""   readonly />
                                      </td>
                                      <td>
                                          <button type="button" class="btn btn-icon waves-effect waves-light chlildRmvBtn btn-danger btn-sm"><i class="fa fa-minus"></i></button>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="4"></td>
                                      <td><button type="button" class="btn btn-icon waves-effect waves-light btn-secondary chlildAddBtn btn-sm " style="margin-left: 2px;"> <i class="fa fa-plus"></i> </button></td>
                                    </tr>
                                </tbody>
                            </table>
                          </div>
                        </div>
                          <div class="col-lg-12">
                              <div style="overflow-x:auto;">
                                 <h4 class="header-title m-t-0">Item Detail</h4>
                                    <table class="table" id="mastertbl" style="min-width: 1080px;">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Tr</th>
                                                <th>GROSS WEIGHT</th>
                                                <th>BAG WEIGHT</th>
                                                <th>NET WEIGHT</th>
                                                <th>TOUNCH</th>
                                                <th>WASTAGE</th>
                                                <th>FINE</th>
                                                <th>RATE</th>
                                                <th>LABOUR</th>
                                                <th></th>                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                          <?php if($method=="edit"):
                                            $j=1; 
                                            foreach ($rough_item as $rough_item) :
                                          ?>
                                          <tr>
                                              <td width="15%">
                                                  <select name="item_id[]" class="sItem_id"  data-live-search="true"   data-style="btn-custom" required>
                                                      <?php
                                                      $items=$this->General_model->get_all_where('item','status','1');
                                                      foreach ($items as  $items) {
                                                        echo '<option value="'.$items->id_item.'"'.(($items->id_item==$rough_item->item_id)?"selected":"").'>'.$items->name.'</option>';
                                                      }
                                                      ?>
                                                  </select>
                                              </td>
                                              <td>
                                                <select name="mtr_no[]" class="mTr_no" data-live-search="true" name="" data-style="btn-custom">
                                                    <?php
                                                      for ($x = 1; $x <= 5; $x++) {
                                                          echo '<option value="'.$x.'"'.(($x==$rough_item->tr_no)?"selected":"").'>'.$x.'</option>';
                                                      }
                                                    ?>
                                                </select>
                                              </td>
                                              <td>
                                                  <input type="number" name="mg_weight[]" class="form-control mGross_W" required  placeholder="GROSS WEIGHT" value="<?php echo $rough_item->g_weight; ?>" />
                                                  <input type="hidden" name="roughitem_id[]" class="form-control" required  value="<?php echo $rough_item->id; ?>" step="any" />
                                              </td>
                                              <td>
                                                  <input type="number" name="mb_weight[]" class="form-control mBag_W" placeholder="BAG WEIGHT"  required readonly  value="<?php echo $rough_item->b_weight; ?>" step="any" />
                                              </td>
                                              <td>
                                                  <input type="number" name="mn_weight[]" class="form-control mNet_W"  placeholder="NET WEIGHT" required  value="<?php echo $rough_item->n_weight; ?>" step="any" />
                                              </td>
                                              <td>
                                                  <input type="number" name="mtouch[]" class="form-control mTouch"  placeholder="TOUNCH"  value="<?php echo $rough_item->touch; ?>" step="any" />
                                              </td>
                                              <td>
                                                  <input type="number" name="mwastage[]" class="form-control mWastage"  placeholder="WASTAGE"  value="<?php echo $rough_item->wastage; ?>" step="any" />
                                                  <input type="hidden" name="mtouch_wastage[]" class="form-control mT_G"   readonly placeholder="T + W"  value="<?php echo $rough_item->touch_wastage; ?>" step="any" />
                                              </td>
                                              <td>
                                              <input type="number" name="mfine[]" class="form-control mFine"  readonly placeholder="FINE"  value="<?php echo $rough_item->fine; ?>" step="any" />
                                              </td>
                                              <td>
                                              <input type="number" name="mrate[]" class="form-control mRate"  placeholder="RATE"  value="<?php echo $rough_item->rate; ?>" step="any" />
                                              </td>
                                              <td>
                                              <input type="number" name="mlabour[]" class="form-control mLabour"  placeholder="LABOUR" readonly   value="<?php echo $rough_item->labour; ?>" step="any"  />
                                              </td>
                                              <td>
                                                <?php if($j!=1):?>
                                                  <button type="button" data-id="masterDltBtn" data-value="<?php echo $rough_item->id; ?>" class="btn btn-icon waves-effect waves-light btn-warning btn-sm"><i class="mdi mdi-delete"></i></button>
                                                <?php endif; ?>
                                              </td>
                                          </tr>
                                          <?php $j++; endforeach; endif; ?>
                                            <tr id="xMsaterTr">
                                              <td width="15%">
                                                  <select name="item_id[]" class="sItem_id"  data-live-search="true"   data-style="btn-custom" required>
                                                      <?php
                                                      foreach ($item as  $item) {
                                                        echo '<option value="'.$item->id_item.'">'.$item->name.'</option>';
                                                      }
                                                      ?>
                                                  </select>
                                              </td>
                                              <td>
                                                <select name="mtr_no[]" class="mTr_no" data-live-search="true" name="" data-style="btn-custom">
                                                    <?php
                                                      for ($x = 1; $x <= 5; $x++) {
                                                          echo '<option value="'.$x.'">'.$x.'</option>';
                                                      }
                                                    ?>
                                                </select>
                                              </td>
                                              <td width="15%">
                                                  <input type="number" name="mg_weight[]" class="form-control mGross_W" required  placeholder="GROSS WEIGHT" step="any" />
                                              </td>
                                              <td>
                                                  <input type="number" name="mb_weight[]" class="form-control mBag_W" placeholder="BAG WEIGHT" value="" required readonly step="any" />
                                              </td>
                                              <td>
                                                  <input type="number" name="mn_weight[]" class="form-control mNet_W"  placeholder="NET WEIGHT" required step="any"/>
                                              </td>
                                              <td>
                                                  <input type="number" name="mtouch[]" class="form-control mTouch"  placeholder="TOUNCH" step="any" />
                                              </td>
                                              <td>
                                                  <input type="number" name="mwastage[]" class="form-control mWastage"  placeholder="WASTAGE" step="any"/>
                                                  <input type="hidden" name="mtouch_wastage[]" class="form-control mT_G"   readonly placeholder="T + W" step="any"/>
                                              </td>
                                              <td>
                                              <input type="number" name="mfine[]" class="form-control mFine"  readonly placeholder="FINE" step="any"/>
                                              </td>
                                              <td>
                                              <input type="number" name="mrate[]" class="form-control mRate"  placeholder="RATE" step="any"/>
                                              </td>
                                              <td>
                                              <input type="number" name="mlabour[]" class="form-control mLabour"  placeholder="LABOUR" readonly step="any"/>
                                              </td>
                                              <td>
                                                  <button type="button" class="btn btn-icon waves-effect waves-light masterRmvBtn  btn-danger btn-sm"><i class="fa fa-minus"></i></button>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td colspan="10"></td>
                                              <td><button type="button" class="btn btn-icon waves-effect waves-light btn-secondary btn-sm masterdAddBtn" style="margin-left: 2px;"> <i class=" fa fa-plus"></i> </button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                              </div>
                          </div>
                          <div class="col-md-4 m-b-30">
                            <div class="form-group row">
                               <label for="remark" class="col-3 col-form-label">Remark</label>
                                   <div class="col-9">
                                      <textarea  class="form-control"  name="remark" placeholder="REMARK"  ><?php echo (($method=="edit")?$rough_invoice->remark:"")?></textarea>
                                   </div>
                            </div>
                          </div>
                          <div class="col-md-4 offset-md-4 m-b-30">
                              <div class="form-group row">
                                 <label for="t_fine" class="col-3 col-form-label">Total Fine</label>
                                     <div class="col-9">
                                        <input type="number" class="form-control tFine"  name="t_fine" placeholder="Total Fine" value="" required readonly step="any"/>
                                     </div>
                              </div>
                              <div class="form-group row">
                                 <label for="t_labour" class="col-3 col-form-label">Total Labour</label>
                                     <div class="col-9">
                                        <input type="number" class="form-control tLabour"  name="t_labour" placeholder="Total Labour" value="" required readonly step="any"/ >
                                     </div>
                              </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 d-flex justify-content-center">
                          <button type="submit" onclick="return validateForm()"  class="btn btn-purple waves-effect waves-light justify-content-center"><?php echo (($method=="edit")?"Update":"Add")?></button>                            
                        </div>                        
                      </div>
                  </form>
              </div> 
          </div>
      </div>
    </div> <!-- container -->
</div> <!-- content -->
<script type="text/javascript">
  <?php if($method=="edit"): ?>
    var method= "edit";
  <?php else: ?>
    var method="add";
  <?php endif; ?>
</script>
<script src="<?php echo base_url('assets/admin/custom/roughinvoice.js');?>"></script>