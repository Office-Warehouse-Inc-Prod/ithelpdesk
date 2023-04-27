 <?php

session_start();

if ($_SESSION['login']!='true'){
    header("Location: index.php");
    exit();
}

// ======== header =========
$datetime = new DateTime();
$timezone = new DateTimeZone('Asia/Manila');
$datetime->setTimezone($timezone);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width= , initial-scale=1.0">
  <title>I.T Helpdesk</title>
<link rel="icon" href="../images/owi.ico" type="image/icon type">
<link rel="stylesheet" href="../css/4bootstrap.min.css" />
<link rel="stylesheet" href="../vendor/sweetalert/dist/sweetalert2.min.css" />
<script src="../js/jquery-3.5.1.js"></script>
<script src="../js/moment.min.js"></script>

<link rel="stylesheet" href="../css/dashboard.css">
<link rel="stylesheet" type="text/css" href="../dist/fontawesome/css/fontawesome.min.css" />

      <!-- ============= -->
  <script src="https://kit.fontawesome.com/426b4bab4c.js" crossorigin="anonymous"></script> 

      <script src="../js/jquery.timeago.js"></script>
      <script src="../js/helpdesk.js"></script> 
      <script src="../js/coms.js"></script> 
      <script src="../js/amcharts/core.js"></script>
      <script src="../js/amcharts/charts.js"></script>
      <script src="../js/amcharts/material.js"></script>
      <script src="../js/amcharts/animated.js"></script>
<!--       <script src="../js/responsive.bootstrap.min.js"></script> -->
      <script src="../js/popper.min.js"></script>
      <script src="../js/4bootstrap.min.js"></script>
      <link rel="stylesheet" href="../plugins/DataTables-1.10.25/media/css/dataTables.bootstrap.min.css"/>
<link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="../assets/Date-Time-Picker-Bootstrap-4/src/sass/bootstrap-datetimepicker-build.css" />
<script src="../assets/Date-Time-Picker-Bootstrap-4/src/js/bootstrap-datetimepicker.js"></script>
<link rel="stylesheet" href="styles.css" />
<script src="../plugins/DataTables-1.10.25/media/js/jquery.dataTables.min.js"></script>
<script src="../js/ellipsis.js"></script>
  <style>

<style>
html {
  scroll-behavior: smooth;
}

table, td, th {
   border: 1px solid black;
  width: 180px;
  }
div.container {
    width: 95%;
}
.hidden{
  visibility:hidden;
        }
.input-group-text{
  word-spacing: 0.5em;
}
button {
margin-left :15px }

.modal-backdrop {
   background-color: black ;
}
</style>

 </head>
 <body>
  <br>
  <br><br><br><br>
<div class="page-wrapper">
  <!-- Button trigger modal -->
 <div class="row" id="setofcharts">
 <div class="card2 col-md-6">
  <h5 class="card-header">STATUS</h5>
  <div class="card-body">
          <div id="genpiegrph"></div>
  </div>
</div>
<div class="card2 col-md-6">
  <h5 class="card-header">PER CATEGORIES</h5>
  <div class="card-body">
          <div id="chartdiv"></div>
  </div>
</div>

  <div class="card2 col-md-12">
    <h5 class="card-header">I.T LOGS</h5>
    <div class="card-body"> 
      <div id="chartdiv8"></div>
    </div>
  </div>
    <div class="card2 col-md-12">
    <h5 class="card-header">I.T LOGS</h5>
    <div class="card-body"> 
          <div class="mb-2" style="margin-left: 20px;">
     <select class="form-control" name="areapicker" id="areapicker" required>
     <option value=""> &larr; Select area here &rarr;</option>
     <option value="AREA 1"> AREA 1</option>
     <option value="AREA 2"> AREA 2</option>
     <option value="AREA 3"> AREA 3 </option>
     <option value="AREA 4"> AREA 4 </option>
     <option value="AREA 5"> AREA 5 </option>
     <option value="AREA 6"> AREA 6 </option>
     <option value="AREA 7"> AREA 7 </option>
     <option value="AREA 8"> AREA 8 </option>
    </select>

    </div>
      <div id="chartdiv6"></div>
    </div>
  </div>
    </div>
</div>
</div>

</div>
  <br><br><br>
<div class="col-xl-12 col-md-12">
    <div class="container-fluid">

      <div class="row">
<div class="col-12 col-md-8">
    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#genModal">Generate Report <i class="far fa-list-alt"></i> </button>
    <button id="export_excel" class="btn btn-success pull-right">Export <i class="fas fa-file-excel"></i></button>
</div>

</div>
      

      <style type="text/css">
        th { font-size: 12px; }
        td { font-size: 11px; }
      </style>
   <div class="table-responsive">
    <br />

    <table id="report_data" class="display table-condensed table-responsive ">
     <thead class="bg-warning">
      <tr class="">
       <th>TICKET#</th>
       <th>STORE</th>
       <th>DATE CREATED</th>
       <th>CONCERN</th>
       <th>VIA</th>
       <th>STATUS</th>
       <!-- <th>ID</th> -->
       <th>I.T SUPPORT</th>
       <th>CATEGORY</th>
       <th>SUBCATEGORY</th>
       <th>DATE CLOSED</th>
       <th>DAYS COMPLETED</th>
       <th>WORK OUTPUT</th>
        <th>CHECKED BY</th>
       <!-- <th>UPDATE</th> -->
 <!--       <th>DELETE</th> -->

       <tbody>
    
</tbody>
</tr>
</thead>
</table>
</div>
</div>
</div>
 </body>

</div>

<!-- Modal -->
<div class="modal fade" id="genModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Generate Report</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">


                 <br />
    <div class="row">
      <div class="col-md-6">
            <label>DATE CREATED</label>
          <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
          <input type="date" name="start_date" id="start_date" class="form-control datetimepicker-input" data-target="#datetimepicker1" value=""  autocomplete="off" />
          </div>

      </div>
      <div class="col-md-6">
        
                 <label>END DATE</label>
  <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
          <input type="date" name="end_date" id="end_date" class="form-control datetimepicker-input" data-target="#datetimepicker2" value=""  autocomplete="off"/>
  </div>


      </div>
  
    

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
         <button type="button" id="search" style="float: right;" class=" btn btn-info"> <i class="medium material-icons">SEARCH</i></button>
      </div>
<div class="row"> </div>
 
      </div>
    </div>
  </div>
</div>
</html>

<div class="modal fade" id="piegraphModal" tabindex="-1" role="dialog" aria-labelledby="piegraphModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">SUB CATEGORY</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div id="chartdiv9"></div>
      </div>
    </div>
  </div>
</div>




<script type="text/javascript" language="javascript" >

moment.updateLocale(moment.locale(), { invalidDate: "" }); //sets null value


$(document).ready(function(){
 



   setTimeout(function(){
    $('body').addClass('loaded');
  }, 1000);
  
 fetch_data('no');

 function fetch_data(is_date_search, start_date='', end_date='')
 {
  let dataTable = $('#report_data').DataTable({
    dom: 'Bfrtip',
        buttons: [
          'excel', 'pdf', 'print'
        ],
   "processing" : true,
   "serverSide" : true,
   "order" : [],
   "lengthMenu": [[-1], ["All"]],
   "columnDefs": [
            {

                targets: [9,10],
                render: function ( data, type, row) {
                    if(type === 'display'){
                        if(data == '01/01/1970 01:00'){
                          data = ''
                        }
                       else if(data == '01/01/1970 08:00'){
                          data = ''
                        }
                        else if(data<0){
                          data = ''
                        }
                        else if(data == '0'){
                          data = 'Solve Immediately'
                        }
                }
                return data;
              }
            }
        ],
   "ajax" : {
    url:"gendates.php",
    type:"POST",
    data:{
     is_date_search:is_date_search, start_date:start_date, end_date:end_date
    }
   },
   rowCallback: function(row, data, index){
    if(data[5].toUpperCase() == 'OPEN'){
      $(row).find('td:eq(0)').css('color', 'red');
      $(row).find('td:eq(1)').css('color', 'red');
      $(row).find('td:eq(2)').css('color', 'red');
      $(row).find('td:eq(3)').css('color', 'red');
      $(row).find('td:eq(4)').css('color', 'red');
      $(row).find('td:eq(5)').css('color', 'red');
      $(row).find('td:eq(6)').css('color', 'red');
      $(row).find('td:eq(7)').css('color', 'red');
      $(row).find('td:eq(8)').css('color', 'red');
      $(row).find('td:eq(9)').css('color', 'red');
    }
    else if (data[5].toUpperCase() == 'OPEN WITH FIX ASSET'){
      $(row).find('td:eq(0)').css('color', 'red');
      $(row).find('td:eq(1)').css('color', 'red');
      $(row).find('td:eq(2)').css('color', 'red');
      $(row).find('td:eq(3)').css('color', 'red');
      $(row).find('td:eq(4)').css('color', 'red');
      $(row).find('td:eq(5)').css('color', 'red');
      $(row).find('td:eq(6)').css('color', 'red');
      $(row).find('td:eq(7)').css('color', 'red');
      $(row).find('td:eq(8)').css('color', 'red');
      $(row).find('td:eq(9)').css('color', 'red');
    }
        else if (data[5].toUpperCase() == 'CLOSED'){
      $(row).find('td:eq(0)').css('color', 'green');
      $(row).find('td:eq(1)').css('color', 'green');
      $(row).find('td:eq(2)').css('color', 'green');
      $(row).find('td:eq(3)').css('color', 'green');
      $(row).find('td:eq(4)').css('color', 'green');
      $(row).find('td:eq(5)').css('color', 'green');
      $(row).find('td:eq(6)').css('color', 'green');
      $(row).find('td:eq(7)').css('color', 'green');
      $(row).find('td:eq(8)').css('color', 'green');
      $(row).find('td:eq(9)').css('color', 'green');
      $(row).find('td:eq(10)').css('color', 'green');
      $(row).find('td:eq(11)').css('color', 'green');
      $(row).find('td:eq(12)').css('color', 'green');
      $(row).find('td:eq(13)').css('color', 'green');
    }

  }

  });
 }

 $('#search').click(function(){
  let start_date = $('#start_date').val();
  let end_date = $('#end_date').val();
  if(start_date != '' && end_date !='')
  {
   $('#report_data').DataTable().destroy();
   fetch_data('yes', start_date, end_date);
  }
  else
  {
   alert("Both Date is Required");
  }
 }); 
 
});
</script>


<script src="../table2excel/src/jquery.table2excel.js" type="text/javascript"></script>
<script>
    $("#export_excel").click(function(){

        $("#report_data").table2excel({

            // exclude CSS class
            exclude: ".noExl",
              name: "Pending Cases",
              filename: `IT REPORTS`,
              fileext: ".xlsx",
              exclude_img: true,
              exclude_links: true,
              exclude_inputs: true,
              preserveColors: true

    });
    });

</script>

<style>
#chartdiv {
   margin-top: 2px;
  margin-left: 12px;
  width: 100%;
  height: 250px;
}

</style>


<script>




  $(document).ready(function(){

$('#search').click(function(){

  let start_date = $('#start_date').val();
  let YEARS = $('#end_date').val();

  if(start_date !='' && YEARS !='')
  {
    let x = "start_date="+start_date+"&end_date="+YEARS;

      $.ajax({
    url:"piegrph.php",
    method:'POST',
    data:x,
    datatype:'JSON',
   
    success:function(data)
    {
      let obj = JSON.parse(data);
     // console.log(obj);
      grhp(obj);
    }
   });

  }
  else
  {
    alert('Please Complete')
  }
 });


  

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
//legend 
// chart.legend = new am4charts.Legend();
// chart.legend.scrollable = true;

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
pieSeries.labels.template.maxWidth = 130;
pieSeries.labels.template.wrap = true;
pieSeries.labels.template.paddingTop = 0;
pieSeries.labels.template.paddingBottom = 0;
pieSeries.labels.template.fontSize = 10;
pieSeries.labels.template.text = "{type}: {value.value}";
pieSeries.slices.template.tooltipText = "{type}: {value.value}";
pieSeries.slices.template.tooltipPosition = "pointer";

chart.exporting.menu = new am4core.ExportMenu();

function generateChartData() {
 let d = Array();
  var chartData = [];
  for (var i = 0; i < types.length; i++) {
    if (i == selected) {
      for (var x = 0; x < types[i].subs.length; x++) {
         // d= new Array('types'=>types[i].subs[x].type)
        chartData.push({
          type: types[i].subs[x].type,
          percent: types[i].subs[x].percent,
          color: types[i].color,
          pulled:true
        });

      }

      for (var y = 0; y < types[i].subs.length; y++) {
         // d= new Array('types'=>types[i].subs[x].type)
        d.push({
          type: types[i].subs[y].type,
          percent: types[i].subs[y].percent
        });

      }
      console.log(d);
newgrph(d)
   
      // chartData.push({
      //   type: types[i].type,
      //   percent: types[i].percent,
      //   color: types[i].color,
      //   id: i
      // });

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
am4core.options.autoDispose = true;


}); // end am4core.ready()

} // end am4core.ready()


 function newgrph(data){
// console.log(data)

am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance

var chart = am4core.create("chartdiv9", am4charts.PieChart);

// legend
// chart.legend = new am4charts.Legend();
// chart.legend.scrollable = true;
chart.innerRadius = am4core.percent(40);
// chart.legend.labels.template.text = "[bold {color}]{name}[/]";
// series1.legendSettings.value = "{points}";
// Add data
chart.data = data;




// Add and configure Series
var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "percent";
pieSeries.dataFields.category = "types";
pieSeries.slices.template.stroke = am4core.color("#FFF"); //outline
pieSeries.slices.template.strokeWidth = 2;
pieSeries.slices.template.strokeOpacity = 1;
pieSeries.slices.template.tooltipPosition = "pointer";
pieSeries.labels.template.maxWidth = 130;
pieSeries.labels.template.wrap = true;
pieSeries.labels.template.fontSize = 10;

// pieSeries.alignLabels = false;
pieSeries.labels.template.text = "{type}: {value}";
pieSeries.slices.template.tooltipText = "{type}:{value}";


// This creates initial animation
pieSeries.hiddenState.properties.opacity = 1;
pieSeries.hiddenState.properties.endAngle = -90;
pieSeries.hiddenState.properties.startAngle = -90;


am4core.options.autoDispose = true;

}); // end am4core.ready()


$('#piegraphModal').modal('show'); 
 
  // console.log(data)
}


</script>


<script type="text/javascript">
  $(document).ready(function(){

$('#search').click(function(){

  let start_date = $('#start_date').val();
  let YEARS = $('#end_date').val();

  if(start_date !='' && YEARS !='')
  {
    let x = "start_date="+start_date+"&end_date="+YEARS;

      $.ajax({
    url:"fetchdata/fetch_gentbar.php",
    method:'POST',
    data:x,
    datatype:'JSON',
   
    success:function(data)
    {
      let objtechres = JSON.parse(data);
      show_techgraph(objtechres);
    }
   });

  }
  else
  {
    alert('Please Complete')
  }
 });


  

  });

</script>

<!-- Styles -->
<style>
#chartdiv8 {
  width: 100%;
  height: 500px;
}

</style>

<!-- Chart code -->
<script>
  function show_techgraph(json_data){
    am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv8", am4charts.XYChart);

// Add percent sign to all numbers
// chart.numberFormatter.numberFormat = "#.#'%'";

// Add data
chart.data = json_data;

chart.colors.list = [
  am4core.color("#0077F7"),
  am4core.color("#27A243")
];

// Create axes
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "it_name";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 30;

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
// valueAxis.title.text = "GDP growth rate";
// valueAxis.title.fontWeight = 800;

// Create series
var series = chart.series.push(new am4charts.ColumnSeries());
series.dataFields.valueY = "total";
series.dataFields.categoryX = "it_name";
series.clustered = false;
series.tooltipText = "TOTAL REPORTS: [bold]{valueY}";

var series2 = chart.series.push(new am4charts.ColumnSeries());
series2.dataFields.valueY = "completed";
series2.dataFields.categoryX = "it_name";
series2.clustered = false;
series2.columns.template.width = am4core.percent(50);
series2.tooltipText = "COMPLETED REPORTS: [bold]{valueY}";

chart.cursor = new am4charts.XYCursor();
chart.cursor.lineX.disabled = true;
chart.cursor.lineY.disabled = true;

var bullet = series.bullets.push(new am4charts.LabelBullet());
bullet.label.text = "{completed} / {total} ";
bullet.label.verticalCenter = "bottom";
bullet.label.dy = -10;
bullet.label.fontSize = 15;

}); // end am4core.ready()
  }

</script>




<style>
#chartdiv6 {
  width: 100%;
  height: 350px;
}
</style>


<script type="text/javascript">

$('#search').click(function(){

  let start_date = $('#start_date').val();
  let YEARS = $('#end_date').val();

  if(start_date !='' && YEARS !='')
  {
    let x = "start_date="+start_date+"&end_date="+YEARS;

      $.ajax({
    url:"fetchdata/fetch_genareax.php",
    method:'POST',
    data:x,
    datatype:'JSON',
   
    success:function(data)
    {
           let objarres = JSON.parse(data);
     console.log(objarres);
           show_areagraph(objarres);
     
    }
   });

  }
  else
  {
    alert('Please Complete')
  }
 });


function show_areagraph (json_data) {
  am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv6", am4charts.XYChart);

// Add data
chart.data = json_data; //palitan


// Create axes

var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "str_name";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 30;

categoryAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {
  if (target.dataItem && target.dataItem.index & 2 == 2) {
    return dy + 25;
  }
  return dy;
});

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

// Create series
var series = chart.series.push(new am4charts.ColumnSeries());
series.dataFields.valueY = "points";
series.dataFields.categoryX = "str_name";
series.name = "points";
series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
series.columns.template.fillOpacity = .8;
series.columns.template.events.on("hit", function(ev) {
 alert("MODAL ", ev.target);
}, this);

var columnTemplate = series.columns.template;
columnTemplate.strokeWidth = 2;
columnTemplate.strokeOpacity = 1;


var bullet = series.bullets.push(new am4charts.LabelBullet());
bullet.label.text = "{points}";
bullet.label.verticalCenter = "bottom";
bullet.label.dy = -10;
bullet.label.fontSize = 15;



});
}
 // end am4core.ready()
</script>




<!--Modal:-->
<script type="text/javascript">
  setTimeout(function() {
    $('#genModal').modal({
            backdrop: 'static',
            keyboard: false
    });
}, 1000);

</script>
<script type="text/javascript">
  $('button#search').on("click", function(event) {
    // submit form via ajax, then

    event.preventDefault();
    $('#genModal').modal( 'hide' );
});
</script>

