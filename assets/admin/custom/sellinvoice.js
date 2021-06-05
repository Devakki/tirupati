$(document).ready(function() {
    var i=2;
    $('form').parsley();
    var gst=$('.trgst').html();
    var igst='<input type="number" name="igst[]" class="form-control sIgst "  step="any"  placeholder="IGST" readonly />';
    var xItemadd=$('#xItemadd').html();
    var last_tr=$('#lsttr').html();
      if(method=="edit"){
        $("#xItemadd").remove();
         calculate();
      }
      $('body').on('click','.bDelete', function(){
        $(this).parents('tr').remove();
        calculate();
      });
      $('body').on('keyup','.sAmount', function(){
        var obj=$(this).parents('tr');
        callByamount(obj);
      });
      
      $('body').on('keyup','.sPrice', function(){
        var obj=$(this).parents('tr');
        callByprice(obj);
      });
      
      $('body').on('click','[data-id=DltBtn]', function(){
        var obj=$(this).parents('tr');
        var id=$(this).data("value");
            swal({
                   title: 'Are you sure?',
                   text: "You won't be able to revert this!",
                   type: 'warning',
                   showCancelButton: true,
                   confirmButtonText: 'Yes, Delete',
                   cancelButtonText: 'No, Cancel!',
                   confirmButtonClass: 'btn btn-success',
                   cancelButtonClass: 'btn btn-danger m-l-10',
                   buttonsStyling: false
               }).then(function () {
                   $.ajax({
                       type: "POST",
                       url: "../sellitem_delete/"+id+"",
                       success: function(data){
                         var data  = JSON.parse(data);
                         if(data.status=="success"){
                            swal('Deleted!',data.msg,'success');                            
                            obj.remove();
                            calculate();
                         }else{
                            swal("error",data.msg,"warning","#4fa7f3");  
                         }              
                       }
                    })
               }, function (dismiss) {
                   if (dismiss === 'cancel') {
                       swal(
                           'Cancelled',
                           'Your imaginary file is safe :)',
                           'error'
                       )
                   }
               })
      });
      $('body').on('change','#gst_type', function(){
        if($(this).val()==1){
          $('.trtext').addClass("text-center");
          $("#myTable > tbody").empty();
          $("#myTable > tbody").html("<tr id='tr1'>"+xItemadd+"</tr><tr>"+last_tr+"</tr>");
          $('.trgst').html(gst);
        }else{
          $('.trtext').removeClass("text-center");
          $("#myTable > tbody").empty();
          $("#myTable > tbody").html("<tr id='tr1'>"+xItemadd+"</tr><tr>"+last_tr+"</tr>");
          $('.trgst').html(igst);
        }
        $("tbody").find('tr:eq(0)').find('.bDelete').removeClass('bDelete');
        $("select").select2();
      });
      $('body').on('click','.AddBtn', function(){
        var g_type=$('#gst_type').val();
        var tr=$(this).parents('tr');
        tr.before("<tr id='tr"+i+"'>"+xItemadd+"</tr>");
        if(g_type==2){ 
          $('#tr'+i+'').find('.trgst').html(igst);          
        }
        i++;
        $("select").select2();
      });
      $("select").select2();
});

function validateForm() {
  var customer=$('#customer_id').val()
  if(customer == "0"){
    swal("error",'Please select Customer',"warning","#4fa7f3"); 
    return false;
  }else{
    return true;
  }
}
function callByprice($obj) {
  var quality= $obj.find('.sQuality').val();
  var g_type=$('#gst_type').val();
  var Touch=$('.Touch').val();
  if(!g_type){
    swal("error","Please Select Gst Type","warning","#4fa7f3");
    return false;
  }
  if(!quality){
      swal("error","Please Enter Quantity","warning","#4fa7f3");
      return false;
    }else{
      var fine=(parseFloat(quality)*parseFloat(Touch))/100;
      $obj.find('.sFine').val(fine.toFixed(2));
      var sPrice=$obj.find('.sPrice').val();
      var total=sPrice*quality;
      $obj.find('.stotal').val(total.toFixed(2));
      if(g_type==1){
        var cgst=(total*cgst_percentage)/100;
        var sgst=(total*sgst_percentage)/100;
        $obj.find('.sSgst').val(sgst.toFixed(2));
        $obj.find('.sCgst').val(sgst.toFixed(2));
        var gst=cgst+sgst;
      }else{
        var igst=(total*igst_percentage)/100;
        $obj.find('.sIgst').val((igst).toFixed(2));
        var gst=igst;
      }
      var amount=total+gst;
      $obj.find('.sAmount').val(amount.toFixed(2));
      calculate();
    }
}
  

function callByamount($obj) {
  var quality= $obj.find('.sQuality').val();
  var g_type=$('#gst_type').val();
  var Touch=$('.Touch').val();
  if(!g_type){
    swal("error","Please Select Gst Type","warning","#4fa7f3");
    return false;
  }
  if(!quality){
      swal("error","Please Enter Quality","warning","#4fa7f3");
      return false;
    }else{
      var fine=(parseFloat(quality)*parseFloat(Touch))/100;
      $obj.find('.sFine').val(fine.toFixed(2));
      var amount=$obj.find('.sAmount').val();
      var gst_percentage=100+cgst_percentage+sgst_percentage;
      var i_percentage=100+igst_percentage;
      if(g_type==1){
        var gst_price=amount-(amount*100/gst_percentage);
        $obj.find('.sSgst').val((gst_price/2).toFixed(2));
        $obj.find('.sCgst').val((gst_price/2).toFixed(2));
      }else{
        var gst_price=amount-(amount*100/i_percentage);
        $obj.find('.sIgst').val((gst_price).toFixed(2));
      }
        var total=amount-(gst_price.toFixed(2));
        $obj.find('.stotal').val((total).toFixed(2))
        var stotal= $obj.find('.stotal').val();
        var sellprice=stotal/quality;
        $obj.find('.sPrice').val((sellprice).toFixed(2));
        calculate();
    }
  }

function calculate() {
  var g_type=$('#gst_type').val();
  if(g_type==1){
      var c_sgst = 0;
      $('.sSgst').each(function(){
          c_sgst += parseFloat($(this).val());        
      });
      var c_cgst = 0;
      $('.sCgst').each(function(){
          c_cgst += parseFloat($(this).val());        
      });
      var c_gst=c_sgst+c_cgst;
  }else{
      var c_igst = 0;
      $('.sIgst').each(function(){
          c_igst += parseFloat($(this).val());        
      });
      var c_gst=c_igst;
  }
  $('.Gst').val(c_gst.toFixed(2));
  c_stotal=0;
  $('.stotal').each(function(){
      c_stotal += parseFloat($(this).val());        
  });
  $('.SubTotal').val(c_stotal.toFixed(2));
  c_gtotal=0;
  $('.sAmount').each(function(){
      c_gtotal += parseFloat($(this).val());        
  });
  $('.Gtotal').val(Math.round(c_gtotal));
  $('form').parsley().reset();
}