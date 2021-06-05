    function validateForm() {
      var party=$('#party_id').val()
      if(party == "0"){
        swal("error",'Please select Party',"warning","#4fa7f3"); 
        return false;
      }else{
        return true;
      }
    }
    function childrentlb($obj) {
      var bag=parseInt($obj.find('.cBag').val());
      var weight=parseFloat($obj.find('.cWeight').val());
          if(!weight || !bag){
              $obj.find('.cTWeight').val(0);
          }else{
            var cTWeight=bag*weight;
            $obj.find('.cTWeight').val(Math.round(cTWeight));
          }
          var tr='';
          $('.mGross_W').each(function(){
            tr=$(this).parents('tr');
            mastertlbobj(tr); 
          });
          $('form').parsley().reset();
    }
    function mastertlbobj($obj) {
      var mTr_no=$obj.find('.mTr_no').val();
      var sum = 0;
      $('.cTr_no').each(function(){
        childtr=$(this).val();        
        if(childtr==mTr_no){
          sum += parseFloat($(this).parents('tr').find('.cTWeight').val());  // Or this.innerHTML, this.innerText
        }        
      });
      if(!sum){
        sum=0;
      }
      $obj.find('.mBag_W').val(sum);
        var mGross_W=parseFloat($obj.find('.mGross_W').val());
        var mGross_Weight=mGross_W-sum;
        $obj.find('.mNet_W').val(mGross_Weight);
        var mTouch=parseFloat($obj.find('.mTouch').val());
        var mWastage=parseFloat($obj.find('.mWastage').val());
        var T_G=mTouch+mWastage;
        $obj.find('.mT_G').val(T_G);
        var mT_G=parseFloat($obj.find('.mT_G').val());
        var mNet_W=parseFloat($obj.find('.mNet_W').val());
        var fine=(mT_G*mNet_W/100);
        $obj.find('.mFine').val(Math.round(fine));
        var mRate=parseFloat($obj.find('.mRate').val());
        var mNet_W=parseFloat($obj.find('.mNet_W').val());
        var labour=(mRate*mNet_W/1000);
        $obj.find('.mLabour').val(Math.round(labour));
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
      $('.tLabour').val(Math.round(TLabour));
}
    $(document).ready(function() {
      $('form').parsley();
      var xChildTr=$("#xChildTr").html();
      var xMsaterTr=$("#xMsaterTr").html();
      if(method=="add"){
          $("#xMsaterTr").find('.masterRmvBtn').removeClass('masterRmvBtn');
          $("#xChildTr").find('.chlildRmvBtn').removeClass('chlildRmvBtn');
      }else{
          $('#xMsaterTr').remove();
          $('#xChildTr').remove();
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
        $('body').on('change','.mTr_no', function(){
              var obj=$(this).parents('tr');
              mastertlbobj(obj)
        });
        $('body').on('keyup','.mGross_W', function(){
              var obj=$(this).parents('tr');
              mastertlbobj(obj)
        });
        $('body').on('keyup','.mTouch', function(){
              var obj=$(this).parents('tr');
              mastertlbobj(obj)
        });
        $('body').on('keyup','.mWastage', function(){
              var obj=$(this).parents('tr');
              mastertlbobj(obj)
        });
        $('body').on('keyup','.mRate', function(){
              var obj=$(this).parents('tr');
              mastertlbobj(obj)
        });
        /*child Table */
        $('body').on('click','.chlildAddBtn', function(){
             var a=$('#childtbl > tbody > tr:last').before("<tr>"+xChildTr+"</tr>");
             $("select").select2();
        });
        $('body').on('click','.chlildRmvBtn', function(){
             $(this).parents('tr').remove();
             $(".sItem_id").each(function() {
                var tr=$(this).parents('tr');
                mastertlbobj(tr);
              });
        });
        $('body').on('keyup','.cBag', function(){
             var obj=$(this).parents('tr');
             childrentlb(obj)
        });
        $('body').on('keyup','.cWeight', function(){
             var obj=$(this).parents('tr');
             childrentlb(obj)
        });
        $('body').on('click','[data-id=chlildDltBtn]', function(){
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
                       url: "../invoicebag_delete/"+id+"",
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
                       url: "../invoiceitem_delete/"+id+"",
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