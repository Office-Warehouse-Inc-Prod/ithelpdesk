

 function pager(pg){
    //var pgurl=;
 $.ajax({
    url:pg+".php",
     cache: false,
     async: false,
    method:'POST',
    success:function(data)
    {
      // alert(data); 
     $("#container").html(data);
    },
    error:function(){
        alert("Error Occured");
       },

  });


  }





     function populate_branch(str){

      $.ajax({
      type:"POST",
      url:"display_store.php",
      contentType:"application/json; charset=utf-8",
      dataType:"json", 
    
      success:function(data){
        if (str != "edit"){

  $('#userModal #selectstore').empty();
    $('#userModal #selectstore').append("<option value =''>Select Branch</option>");

        }



  $.each(data,function(i,item){

    $('#userModal #selectstore').append('<option value ='+ data[i].id+'>' + data[i].desc + '</option>');
  });
      },
      complete:function(){
        
      }
    });

} 


  
     function populate_tech(str){

      $.ajax({
      type:"POST",
      url:"display_tech.php",
      contentType:"application/json; charset=utf-8",
      dataType:"json", 
    
      success:function(data){
        if (str != "edit"){

  $('#userModal #select_tech').empty();
    $('#userModal #select_tech').append("<option value =''>Select Technical Support</option>");

        }



  $.each(data,function(i,item){

    $('#userModal #select_tech').append('<option value ='+ data[i].id+'>' + data[i].desc + '</option>');
  });
      },
      complete:function(){
        
      }
    });

} 

  

function populatedrop(str){
      $.ajax({
      type:"POST",
      url:"display_category.php",
      contentType:"application/json; charset=utf-8",
      dataType:"json", 
    
      success:function(data){
        if (str != "edit"){

  $('#userModal #selectcategory').empty();
  $('#userModal #selectcategory').append("<option value =''>Select Category</option>");

        }


  $.each(data,function(i,item){
  $('#userModal #selectcategory').empty();
    $('#userModal #selectcategory').append('<option value ='+ data[i].id+'>' + data[i].desc + '</option>');
  });
      },
      complete:function(){
        
      }
    });

} 

function populatedropsub(str){

  $('#userModal #select_subcategory').empty();
  // $('#userModal #select_subcategory').append('<option></option>');
  var Cid =str;
  //var populate  = 'load';
      $.ajax({
     type:"GET",
     data:{Cid:Cid},
      url:"display_subcat.php",

      contentType:"application/json; charset=utf-8",
      dataType:"json", 
    
      success:function(data){
  $('#userModal #select_subcategory').empty();
  $('#userModal #select_subcategory').append("<option>Select SubCategory</option>");
  

  $.each(data,function(i,item){
    $('#userModal #select_subcategory').append('<option value ='+ data[i].id+'>' + data[i].desc + '</option>');
  });
      },
      complete:function(){
        
      }
    });

} 


function select_cl(str){

      $.ajax({
      type:"POST",
      url:"cl_drpdwn.php",
      contentType:"application/json; charset=utf-8",
      dataType:"json", 
    
      success:function(data){
        if (str != "edit"){

  $('#userModal #select_cl').empty();
    $('#userModal #select_cl').append("<option value =''>CLOSE BY:</option>");

        }



  $.each(data,function(i,item){

    $('#userModal #select_cl').append('<option value ='+ data[i].id+'>' + data[i].desc + '</option>');
  });
      },
      complete:function(){
        
      }
    });

} 


    function hideshowforms(){

      $("#status").change(function() {
          if ($(this).val() == "CLOSED") {
    $('#date_closed').show();
    $('#select_cl').show();
    $('#ico_cal').show();
    document.getElementById("dateclabel").className = '';
    document.getElementById("clby_label").className = '';
    document.getElementById("ico_cal").className = '';
  }
      else if ($(this).val() == "PARTIALLY CLOSED") {
    $('#date_closed').show();
    $('#select_cl').show();
    $('#ico_cal').show();
    document.getElementById("dateclabel").className = '';
    document.getElementById("clby_label").className = '';
    document.getElementById("ico_cal").className = '';
  
  }

   else {
    $('#date_closed').hide();
    $('#select_cl').hide();
    $('#ico_cal').hide();
    $('#date_closed').removeAttr('required');
    $('#date_closed').removeAttr('data-error');
    // document.getElementById("dateclabel").className = 'hidden';
    // document.getElementById("clby_label").className = 'hidden';
  }



});
$("#status").trigger("change");
      
    }



  function wfithsforms(){

      $("#Modalstatus").change(function() {
          if ($(this).val() == "CLOSED") {
    $('#date_closed').show();
    $('#select_cl').show();
    $('#ico_cal').show();
    document.getElementById("dateclabel").className = '';
    document.getElementById("clby_label").className = '';
    document.getElementById("ico_cal").className = '';
  }
      else if ($(this).val() == "PARTIALLY CLOSED") {
    $('#date_closed').show();
    $('#select_cl').show();
    $('#ico_cal').show();
    document.getElementById("dateclabel").className = '';
    document.getElementById("clby_label").className = '';
    document.getElementById("ico_cal").className = '';
  
  }
   else {
    $('#date_closed').hide();
    $('#select_cl').hide();
    $('#ico_cal').hide();
    $('#date_closed').removeAttr('required');
    $('#date_closed').removeAttr('data-error');
    // document.getElementById("dateclabel").className = 'hidden';
    // document.getElementById("clby_label").className = 'hidden';
  }



});
$("#Modalstatus").trigger("change");
      
    }






   function Netshowforms(){

      $("#sub").change(function() {
          if ($(this).val() == "15") {
    $('#isp').show();
    $('#netRefNo').show();
    // $('#ico_cal').show();
    document.getElementById("label_isp").className = '';
    document.getElementById("label_Ref").className = '';
    // document.getElementById("ico_cal").className = '';
  }

   else {
    $('#isp').hide();
    // $('#netRefNo').hide();
    // $('#ico_cal').hide();
    $('#isp').removeAttr('required');
    $('#isp').removeAttr('data-error');
    document.getElementById("isp").className = 'hidden';
    document.getElementById("netRefNo").className = 'hidden';
    document.getElementById("label_isp").className = 'hidden';
    document.getElementById("label_Ref").className = 'hidden';
    // document.getElementById("clby_label").className = 'hidden';
  }



});
$("#sub").trigger("change");
      
    }






//     $(function() {
//     var def="AREA 1"; 
//     $("#areapicker").val(def);
// });