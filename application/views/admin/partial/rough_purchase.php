<div class="content">
  <div class="container-fluid">
      <div class="row">
          <div class="col-12">
              <div class="page-title-box">
                  <h4 class="page-title float-left">Purchase</h4>
                  <ol class="breadcrumb float-right">
                      <li class="breadcrumb-item"><a href="<?php echo base_url('dashbord');?>"><?php echo COMPANY; ?></a></li>
                      <li class="breadcrumb-item"><a href="#"><?php echo $page_title; ?></a></li>
                  </ol>
                  <div class="clearfix"></div>
              </div>
          </div>
      </div>
      <div class="row">
          <div class="col-lg-12">
              <div class="card-box ">
                  <h4 class="header-title m-t-0"><?php echo (($method=="edit")?"Update Purchase Invoice":"Add Purchase Invoice" )?></h4><br>
                  <form action="<?php echo $action; ?>" method="post" >
                      <div class="row">
                          <div class="col-md-6">   
                              <div class="form-group row">
                                  <label for="party_id" class="col-3 col-form-label">Party</label>
                                      <div class="col-9">
                                          <select data-live-search="true" data-style="btn-custom" name="party_id" id="party_id" data-parsley-required-message="You Must Select 1 Party" required >
                                            <option value="0">None</option>
                                            <?php
                                            foreach ($party as  $party) {
                                              echo '<option value="'.$party->id_party .'"'.
                                              (($method=="edit")?(($party->id_party ==$rough_purchase->party_id)?"selected":""):"")
                                              .' >'.$party->name.'</option>';
                                            }
                                            ?>
                                          </select>
                                      </div>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group row">
                                 <label for="invoice_no" class="col-3 col-form-label">Bill No</label>
                                     <div class="col-9">
                                        <input type="text" class="form-control"  title="Bill No" name="invoice_no" placeholder="Bill No." value="<?php echo (($method=="edit")?$rough_purchase->invoice_no:"")?>">
                                     </div>
                              </div>
                              <div class="form-group row">
                                  <label for="date" class="col-3 col-form-label">Date</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="datepicker-autoclose" title="Date" name="date" placeholder="Date" value="<?php echo (($method=="edit")?(date('d/m/Y',strtotime($rough_purchase->date))):date('d/m/Y'));?>">
                                       <?php echo (($method=="edit")?'<input type="hidden" name="id_rough" required value="'.$rough_purchase->id_rough .'">':"");?>
                                    </div>
                              </div>
                          </div>
                      </div>
                      <div class="row">                        
                          <div class="col-md-12">
                              <div style="overflow-x:auto;">
                                 <h4 class="header-title m-t-0">Item Detail</h4>
                                    <table class="table" id="mastertbl" style="min-width: 1080px;">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>NET WEIGHT</th>
                                                <th>TOUNCH</th>
                                                <th>WASTAGE</th>
                                                <th>FINE</th>
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
                                                  <input type="number" name="mn_weight[]" class="form-control mNet_W"  placeholder="NET WEIGHT" required  value="<?php echo $rough_item->n_weight; ?>" step="any" />
                                                  <input type="hidden" name="roughitem_id[]" class="form-control" required  value="<?php echo $rough_item->id; ?>" step="any" />
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
                                              <input type="number" name="mlabour[]" class="form-control mLabour"  placeholder="LABOUR" value="<?php echo $rough_item->labour; ?>" step="any"  />
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
                                              <input type="number" name="mlabour[]" class="form-control mLabour"  placeholder="LABOUR" step="any"/>
                                              </td>
                                              <td>
                                                  <button type="button" class="btn btn-icon waves-effect waves-light masterRmvBtn  btn-danger btn-sm"><i class="fa fa-minus"></i></button>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td colspan="6"></td>
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
                                      <textarea  class="form-control"  name="remark" placeholder="REMARK"  ><?php echo (($method=="edit")?$rough_purchase->remark:"")?></textarea>
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
                          <button type="submit" onclick="return validateForm()" class="btn btn-purple waves-effect waves-light justify-content-center"><?php echo (($method=="edit")?"Update":"Add")?></button>                            
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
<script src="<?php echo base_url('assets/admin/custom/roughpurchase.js');?>"></script>