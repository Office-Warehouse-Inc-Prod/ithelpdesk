
function populate_tech(str){
    // $('#userModal #selectcategory').append('<option selected value ='+ str+'>' + str + '</option>');


   $('#userModal #select_tech').empty();
  //$('#userModal #selectcategory').append('<option></option>');
  //var populate  = 'load';
      $.ajax({
      type:"POST",
      url:"display_tech.php",
      contentType:"application/json; charset=utf-8",
      dataType:"json", 
    
      success:function(data){
  $('#userModal #select_tech').empty();
  $('#userModal #select_tech').append("<option disabled selected hidden>Select Technical Support</option>");

  $.each(data,function(i,item){
    $('#userModal #select_tech').append('<option value ='+ data[i].id+'>' + data[i].desc + '</option>');
  });
      },
      complete:function(){
        
      }
    });

} 



function populatedrop(str){
    // $('#userModal #selectcategory').append('<option selected value ='+ str+'>' + str + '</option>');


   $('#userModal #selectcategory').empty();
  //$('#userModal #selectcategory').append('<option></option>');
  //var populate  = 'load';
      $.ajax({
      type:"POST",
      url:"display_category.php",
      contentType:"application/json; charset=utf-8",
      dataType:"json", 
    
      success:function(data){
  $('#userModal #selectcategory').empty();
  $('#userModal #selectcategory').append("<option disabled selected hidden>Select Category</option>");

  $.each(data,function(i,item){
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
</script>

