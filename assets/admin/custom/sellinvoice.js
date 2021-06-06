$(document).ready(function() {
    var i=2;
    $('form').parsley();
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

      $('body').on('keyup','.Advance', function(){
        calculate();
      });

      
      $('body').on('keyup','.Discount', function(){
        calculate();
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
      
      $('body').on('click','.AddBtn', function(){
        var tr=$(this).parents('tr');
        tr.before("<tr id='tr"+i+"'>"+xItemadd+"</tr>");
        
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
  if(!quality){
      swal("error","Please Enter Quantity","warning","#4fa7f3");
      return false;
    }else{
      var sPrice=$obj.find('.sPrice').val();
      var total=sPrice*quality;
      $obj.find('.stotal').val(total.toFixed(2));
    
      $obj.find('.sAmount').val(total.toFixed(2));
      calculate();
    }
}
  

function callByamount($obj) {
  var quality= $obj.find('.sQuality').val();
  
  if(!quality){
      swal("error","Please Enter Quality","warning","#4fa7f3");
      return false;
    }else{
       var amount=$obj.find('.sAmount').val();
      
        var total=amount;
        $obj.find('.stotal').val((total).toFixed(2))
        var stotal= $obj.find('.stotal').val();
        var sellprice=stotal/quality;
        $obj.find('.sPrice').val((sellprice).toFixed(2));
        calculate();
    }
  }

function calculate() {
  
  c_stotal=0;
  $('.stotal').each(function(){
      c_stotal += parseFloat($(this).val());        
  });
  $('.SubTotal').val(c_stotal.toFixed(2));
  c_gtotal=0;
  $('.sAmount').each(function(){
      c_gtotal += parseFloat($(this).val());        
  });
  var advance = $('.Advance').val() * 1;
  var discount = $('.Discount').val() * 1;
  var baki = c_stotal  - advance;
  c_gtotal = c_stotal  - discount;
  $('.Baki').val(baki.toFixed(2));
  $('.Gtotal').val(Math.round(c_gtotal));
  $('form').parsley().reset();
}