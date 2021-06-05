function validateForm() {
  var party=$('#party_id').val()
  if(party == "0"){
    swal("error",'Please select Party',"warning","#4fa7f3"); 
    return false;
  }else{
    return true;
  }
}
function mastertlbobj($obj) {
    var mTr_no=$obj.find('.mTr_no').val();
    var mTouch=parseFloat($obj.find('.mTouch').val());
    var mWastage=parseFloat($obj.find('.mWastage').val());
    var T_G=mTouch+mWastage;
    $obj.find('.mT_G').val(T_G);
    var mT_G=parseFloat($obj.find('.mT_G').val());
    var mNet_W=parseFloat($obj.find('.mNet_W').val());
    var fine=(mT_G*mNet_W/100);
    $obj.find('.mFine').val(Math.round(fine));
    var mNet_W=parseFloat($obj.find('.mNet_W').val());
    calculate();
    $('form').parsley().reset();
}
function calculate(){
      var TFine = 0;
      $('.mFine').each(function(){        
          TFine += parseFloat($(this).val());                 
      });
      $('.tFine').val(Math.round(TFine));
      var TLabour = 0;
      $('.mLabour').each(function(){        
          TLabour += parseFloat($(this).val());                 
      });
      if(!TLabour){
        TLabour=0;
      }
      $('.tLabour').val(Math.round(TLabour));
}
    $(document).ready(function() {
      $('form').parsley();      
      var xChildTr=$("#xChildTr").html();
      var xMsaterTr=$("#xMsaterTr").html();
      if(method=="add"){
          $("#xMsaterTr").find('.masterRmvBtn').removeClass('masterRmvBtn');
      }else{
        $('#xMsaterTr').remove();
      }
        $('body').on('click','.masterdAddBtn', function(){
             var a=$('#mastertbl > tbody > tr:last').before("<tr>"+xMsaterTr+"</tr>");
             $("select").select2();
        });
        $('body').on('click','.masterRmvBtn', function(){
              var obj=$(this).parents('tr').remove();
              $(".sItem_id").each(function() {
                var tr=$(this).parents('tr');
                mastertlbobj(tr);
              });
        });
        $('body').on('keyup','.mNet_W', function(){
              var obj=$(this).parents('tr');
              mastertlbobj(obj)
        });
        $('body').on('keyup','.mTouch', function(){
              var obj=$(this).parents('tr');
              mastertlbobj(obj)
        });
        $('body').on('keyup','.mLabour', function(){
              var obj=$(this).parents('tr');
              mastertlbobj(obj)
        });
        $('body').on('keyup','.mWastage', function(){
              var obj=$(this).parents('tr');
              mastertlbobj(obj)
        });
        $('body').on('click','[data-id=masterDltBtn]', function(){
          console.log("hello");
            var id=$(this).data("value"); 
            var obj=$(this).parents('tr');
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
                       url: "../purchseitem_delete/"+id+"",
                       success: function(data){
                         var data  = JSON.parse(data);
                         if(data.status=="success"){
                            swal('Deleted!',data.msg,'success');
                            obj.remove();
                         }else{
                            swal("error",data.msg,"warning","#4fa7f3");  
                         }              
                       }
                    })
               }, function (dismiss) {
                   // dismiss can be 'cancel', 'overlay',
                   // 'close', and 'timer'
                   if (dismiss === 'cancel') {
                       swal(
                           'Cancelled',
                           'Your imaginary file is safe :)',
                           'error'
                       )
                   }
               })
        });
        $("select").select2();
    });