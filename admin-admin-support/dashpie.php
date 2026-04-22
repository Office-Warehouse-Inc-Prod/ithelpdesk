<?php  

session_start();

if ($_SESSION['login']!='true'){
    header("Location: index.php");
    exit;
}
// ======== db for dropdown =========
include '../condb.php';
// ======== branchdb extension =========
$conn=new dbconfig();

 ?>





<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}

</style>

<!-- Resources -->
  <!-- <script src="js/jquery.min.js"></script> -->


  <style type="text/css">
     .piebox
   {
    width: 1500px;
    padding:20px;
    background-color:#E8E8E8;
    border:5px solid #2B2928;
    border-radius:5px;
    margin-top:25px;
   }
</style>

<!-- Chart code -->
  <script src="../js/jquery-3.5.1.js"></script>


<script src="../js/helpdesk.js"></script> 

<script src="../js/amcharts/core.js"></script>
<script src="../js/amcharts/charts.js"></script>
<script src="../js/amcharts/material.js"></script>
<script src="../js/amcharts/animated.js"></script>




<script>

var selected;
var types = $.ajax({
    url:"fetch_dashpie.php",
    method:'POST',
    // data:x,
    datatype:'JSON',
   
    success:function(data)
    {
      var obj = JSON.parse(data);
     console.log(obj);
      grhp(obj);
    }
   });


 function grhp(types){
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_material);
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.PieChart);

// Set data
var selected;


// Add data
chart.data = generateChartData();

// Add and configure Series
var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "percent";
pieSeries.dataFields.category = "type";
pieSeries.slices.template.propertyFields.fill = "color";
pieSeries.slices.template.propertyFields.isActive = "pulled";
pieSeries.slices.template.strokeWidth = 0;

function generateChartData() {
  var chartData = [];
  for (var i = 0; i < types.length; i++) {
    if (i == selected) {
      for (var x = 0; x < types[i].subs.length; x++) {
        chartData.push({
          type: types[i].subs[x].type,
          percent: types[i].subs[x].percent,
          color: types[i].color,
          pulled: true
        });
      }
    } else {
      chartData.push({
        type: types[i].type,
        percent: types[i].percent,
        color: types[i].color,
        id: i
      });
    }
  }
  return chartData;
}

pieSeries.slices.template.events.on("hit", function(event) {
  if (event.target.dataItem.dataContext.id != undefined) {
    selected = event.target.dataItem.dataContext.id;
  } else {
    selected = undefined;
  }
  chart.data = generateChartData();
});

}); // end am4core.ready()

} // end am4core.ready()
</script>

<!-- HTML -->



<div class="container piebox">

<div class="container">
  <div class="row">
        <div class="col-md-12">
  <div id="chartdiv"></div>
        </div>
