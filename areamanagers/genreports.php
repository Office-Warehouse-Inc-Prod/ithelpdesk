<?php

include_once 'admin.php';
include '../condb.php';
include 'sub_graph_modal.php';
$conn=new dbconfig();

 ?>
<link rel="stylesheet" href="../plugins/DataTables-1.10.25/media/css/dataTables.bootstrap.min.css"/>
<link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="../assets/Date-Time-Picker-Bootstrap-4/src/sass/bootstrap-datetimepicker-build.css" />
<script src="../assets/Date-Time-Picker-Bootstrap-4/src/js/bootstrap-datetimepicker.js"></script>
<link rel="stylesheet" href="styles.css" />
<script src="../plugins/DataTables-1.10.25/media/js/jquery.dataTables.min.js"></script>
<script src="../js/ellipsis.js"></script>
<script src="../table2excel/src/jquery.table2excel.js" type="text/javascript"></script>

<div class="container-fluid mt-3 buttons">
    <span class="mb-2" id="genrep_title"></span>
	<div class="row">

<div class="card card2 col-xs-12 col-md-6">
<h5 class="card-header text-white">Overall Status</h5>
<div class="card-body">
<div id="chartdiv5"></div>
</div>
</div>

<div class="card card2 col-xs-12 col-md-6">
<h5 class="card-header text-white">CATEGORIES</h5>
<div class="card-body">
<div id="chartdiv2" name="chartdiv2"></div>
</div>
</div>

<div class="card card2 col-12 col-md-12">
<h5 class="card-header text-white">Number of Report Escalated Per Store.</h5>
<div class="card-body">
<div id="store_graph1" name="store_graph1"></div>
</div>     
</div>

<div class="col-md-2 mt-2 mb-3 ">
    <button id="export_excel" class="btn btn-success pull-right">Export <i class="fas fa-file-excel"></i></button>
</div>
<div class="col-md-2 mt-2 mb-3 ">
    <button type="button" class="btn btn-primary pull-right mr-2" data-toggle="modal" data-target="#genModal">Generate Again <i class="far fa-list-alt"></i> </button>
</div>

			<div class="col-md-12">
				<table class="table table-dark table-responsive table-condensed" id="genrep_table"></table>
			</div>
	</div>

</div>

<div class="modal fade bd-example-modal-lg" id="genModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Generate Report</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

<div class="alert alert-danger div_alrt" role="alert">
  <span id="div_alrtmsg"></span>
</div>

                 <br />
    <div class="row">
      <div class="col-md-6">
         <label for="start_date">DATE CREATED</label>
         <div class="col-md-12">
           <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
          <input type="datetime-local" name="start_date" id="start_date" class="form-control datetimepicker-input" data-target="#datetimepicker1" value=""  autocomplete="off"  />
          </div>
         </div>


      </div>
      <div class="col-md-6">
        
          <label for="end_date">END DATE</label>
          <div class="col-md-12">
          <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                  <input type="datetime-local" name="end_date" id="end_date" class="form-control datetimepicker-input" data-target="#datetimepicker2" value=""  autocomplete="off" />
          </div>            
          </div>

      </div>
      <div class="col-md-12 mt-2 mb-2">
      	<label for="slct_area">SELECT AREA</label>
      	<div class="col-md-6">
      		<select class="form-control form-control-sm" name="slct_area" id="slct_area">
					<option value="1,2,3,4,5,6,7,8">ALL</option>  
				     <?php
		              $query="select * from tbl_area WHERE area_num NOT IN (201,202)";
		              $run=$conn->prepare($query);
		              $run->execute();
		              $rs=$run->get_result();
		              while ($res=$rs->fetch_assoc()) {
		                $areanum = $res['area_num'];
		                $areadesc = $res['area_desc'];
		              ?>

		              <option value="<?php echo $areanum;?>"><?= $areadesc; ?></option>
		              <?php }?>
		              ?>   
		  </select> 
      	</div>

      </div>
  
    

      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
         <button type="button" id="search" style="float: right;" class=" btn btn-info"><i class="fas fa-search"></i>SEARCH</button>
      </div>
<div class="row"> </div>
 
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

$(document).ready(function() {
  autostrt_modal();
$(".buttons").hide();
$(".div_alrt").hide();
 let start_date = "";
 let end_date = "";
 let slct_area = "";
 let tb_title = "";
 let apnd_date = "";


$("#search").click(function(event) {

 start_date = $('#start_date').val();
 end_date = $('#end_date').val();
 slct_area = $('#slct_area').val();
 let sdate = new Date(start_date);
 let edate = new Date(end_date);
 let st_date = ((sdate.getMonth() > 8) ? (sdate.getMonth() + 1) : ('0' + (sdate.getMonth() + 1))) + '/' + ((sdate.getDate() > 9) ? sdate.getDate() : ('0' + sdate.getDate())) + '/' + sdate.getFullYear();
 let en_date = ((edate.getMonth() > 8) ? (edate.getMonth() + 1) : ('0' + (edate.getMonth() + 1))) + '/' + ((edate.getDate() > 9) ? edate.getDate() : ('0' + edate.getDate())) + '/' + edate.getFullYear();

 tb_title = "GENERATED REPORT OF"+" "+"AREA"+" "+slct_area+" "+"FROM"+" "+st_date+" "+"to"+" "+en_date;
 apnd_date = st_date+" "+"TO"+" "+en_date;

   if (end_date < start_date ){
      $(".div_alrt").show();
      $('#div_alrtmsg').html('<i class="fa fa-exclamation-circle fa-lg" aria-hidden="true"></i> End date should not be greater than date created. </div>')
      hidealrtdiv();
      return false;
    }
    else{
      $.post('fetchdata/fetch_data.php',{mode:'admin_get_reports' , 'slct_area':slct_area , 'start_date': start_date , 'end_date': end_date},function(data){
        if (!data) {
              $('#div_alrt').html("NO RECORD FOUND")
          return;
        } else {
                getrepdata(data);
                _overallpie();
                _catpie();
                genrep_store();
                $('#genModal').modal( 'hide' );
                $(".buttons").show();
                $("#genrep_title").html(tb_title);

        }

                
      },'json');

    }

});

var table
function getrepdata(t){
const dataset=t.adminrptdata;
table =  $("#genrep_table").DataTable({

"dom":
'<"pull-left"lf><"pull-right">tip',
// stateSave: true,
"pagingType": "full_numbers",
"bDestroy": true,
"responsive": true, "lengthChange": false, "autoWidth": false,
"bInfo": false,
"bFilter": false,
"paging": false,
"select": true,
"pageLength":10,
"data": dataset,
// "order": [[ 0, "Asc" ]],

"columns": [

{title:"TICKET", data:'ticket_no',"defaultContent": ""},
{title:"STORE", data:'str_code',"defaultContent": ""},
{title:"AREA", data:'area_desc',"defaultContent": ""},
{title:"DATE CREATED", data:'date_created',"defaultContent": ""},
{title:"SUBJECT", data:'subject',"defaultContent": ""},
{title:"VIA", data:'via',"defaultContent": ""},
{title:"STATUS", data:'status',"defaultContent": ""},
{title:"Assigned Support", data:'it_desc',"defaultContent": ""},
{title:"Category", data:'cat_desc',"defaultContent": ""},
{title:"Sub Category", data:'sub_cat',"defaultContent": ""},
{title:"Date CLOSED", data:'date_closed',"defaultContent": ""},
{title:"Date Completion", data:'date_completion',"defaultContent": ""},
{title:"WORK OUTPUT", data:'remarks',"defaultContent": ""},
],
"columnDefs": [
{ 

  targets: [10,11],
  "width": "2%",
  render: function ( data, type, row) {
      if(type === 'display'){
          if(data == '1 Days Unresolved'){
            data = '1 Day Unresolved'
          }
         else if(data == '01/01/1970 01:00'){
            data = 'ATTENDED'
          }
         else if(data == '01/01/1970 08:00'){
            data = 'ATTENDED'
          }
          else if(data<0){
            data =   ''
          }
          else if(data == 0){
            data = 'Solve Immediately'
          }
          else if(data == '0 Days Unresolved'){
            data = ''
          }
  }
  return data;
}
}
],
rowCallback: function(row, data, index){
if(data['status'] == 'OPEN'){
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
$(row).find('td:eq(10)').css('color', 'red');
$(row).find('td:eq(11)').css('color', 'white');
$(row).find('td:eq(12)').css('color', 'red');
}
else if (data['status'] == 'OPEN WITH FIX ASSET'){
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
$(row).find('td:eq(10)').css('color', 'red');
$(row).find('td:eq(11)').css('color', 'red');
$(row).find('td:eq(12)').css('color', 'red');
}
else if (data['status'] == 'CLOSED'){
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

},

});

} // end of data table
    function autostrt_modal(){
      setTimeout(function() {
      $('#genModal').modal({
      backdrop: 'static',
      keyboard: false
      });
      }, 1000);
    }

    function hidealrtdiv(){
              setTimeout(function () {
  
            // Closing the alert
            $('.alert').hide();
        }, 5000);
    }



    $("#export_excel").click(function(){

        $("#genrep_table").table2excel({

            // exclude CSS class
            exclude: ".noExl",
              name: "Generated Report",
              filename: tb_title,
              fileext: ".xlsx",
              exclude_img: true,
              exclude_links: true,
              exclude_inputs: true,
              preserveColors: true

    });
  });

  function _overallpie(){

 $.ajax({
    url:"fetchdata/fetch_data.php",
    method:'POST',
     data:{mode:'genrep_overallpie','slct_area':slct_area , 'start_date': start_date , 'end_date': end_date},

    success:function(data5)
    {

      var obj5 = JSON.parse(data5);
      // console.log(obj5)
       _plotovpie(obj5)
      
    }
   });

}

function _plotovpie(grphdata){

am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv5", am4charts.PieChart);

// legend
chart.legend = new am4charts.Legend();
chart.legend.position = "bottom";
chart.legend.valign = "bottom";
chart.innerRadius = am4core.percent(40);
chart.legend.labels.template.text = "[bold {color}]{name}[/]";
// chart.legend.labels.template.text =
// series1.legendSettings.value = "{points}";
// Add data
chart.data = grphdata

// Add and configure Series
var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "points";
pieSeries.dataFields.category = "stat_name";
pieSeries.slices.template.stroke = am4core.color("#FFF"); //outline
pieSeries.slices.template.strokeWidth = 2;
pieSeries.slices.template.strokeOpacity = 1;
pieSeries.slices.template.tooltipPosition = "pointer";
pieSeries.labels.template.maxWidth = 130;
pieSeries.labels.template.wrap = true;
pieSeries.labels.template.fontSize = 12;
pieSeries.labels.template.text = "{type} {value.value} Reports | {value.percent.formatNumber('.##')}%";
pieSeries.slices.template.tooltipText = "{type} {value.value} Reports | {value.percent.formatNumber('.##')}%";


// This creates initial animation
pieSeries.hiddenState.properties.opacity = 1;
pieSeries.hiddenState.properties.endAngle = -90;
pieSeries.hiddenState.properties.startAngle = -90;

pieSeries.colors.list = [
  am4core.color("#27A243"),
  am4core.color("#D53343"),
  am4core.color("#F7BB07"),
  am4core.color("#169DB2"),
];

am4core.options.autoDispose = true;

}); // end am4core.ready()

 }

function _catpie(){
var selected;
var types = $.ajax({
    url:"fetchdata/fetch_data.php",
    method:'POST',
    data:{mode:'genrep_catpie','slct_area':slct_area , 'start_date': start_date , 'end_date': end_date},
    datatype:'JSON',
   
    success:function(data)
    {
      var objcat = JSON.parse(data);
      // console.log(objcat);
      grhp(objcat);
    }
   });
 } 



 function grhp(types){
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_material);
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv2", am4charts.PieChart);

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
pieSeries.integersOnly = true;
pieSeries.labels.template.text = "{type}: {value.value} | {value.percent.formatNumber('.##')}%";
pieSeries.slices.template.tooltipText = "{type}: {value.value} | {value.percent.formatNumber('.##')}%";
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
// pieSeries.labels.template.text = "{type}: {value}";
// pieSeries.slices.template.tooltipText = "{type}:{value}";
pieSeries.labels.template.text = "{type}: {value.value} | {value.percent.formatNumber('.##')}%";
pieSeries.slices.template.tooltipText = "{type}: {value.value} | {value.percent.formatNumber('.##')}%";


// This creates initial animation
pieSeries.hiddenState.properties.opacity = 1;
pieSeries.hiddenState.properties.endAngle = -90;
pieSeries.hiddenState.properties.startAngle = -90;


am4core.options.autoDispose = true;

}); // end am4core.ready()



$('#piegraphModal').modal({"show": true, "backdrop": 'static'});

 
}

function genrep_store(){
                          $.ajax({
                  url:"fetchdata/fetch_data.php",
                  method:'POST',
                   data:{mode:'genrep_strgrph','slct_area':slct_area , 'start_date': start_date , 'end_date': end_date},
                  success:function(fdata)
                  {
                    var objstore = JSON.parse(fdata);
                    // console.log(objstore)
                    plot_genrepstore(objstore);
                  }
                 });
}


function plot_genrepstore(strres){

am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("store_graph1", am4charts.XYChart);

// Add data
chart.data = strres

// Create axes

var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "str_code";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 30;

categoryAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {
  if (target.dataItem && target.dataItem.index & 2 == 2) {
    return dy + 25;
  }
  return dy;
});

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
// valueAxis.min = 0;
// valueAxis.max = 300;

// Create series
var series = chart.series.push(new am4charts.ColumnSeries());
series.dataFields.valueY = "cnt_ttl";
series.dataFields.categoryX = "str_code";
series.name = "cnt_ttl";
series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
series.columns.template.fillOpacity = .8;

var columnTemplate = series.columns.template;
columnTemplate.strokeWidth = 2;
columnTemplate.strokeOpacity = 1;

}); // end am4core.ready()



}

});

</script>


<!-- Styles -->
<style>
#chartdiv5 {
/*   margin-top: 2px;
  margin-left: 18px;*/
  width: 100%;
  height: 300px;
}

#chartdiv2 {
  margin-top: 12px;
  margin-left: 12px;
  width: 100%;
  height: 300px; 
}

#chartdiv9 {
   margin-top: 2px;
  margin-left: 12px;
  width: 100%;
  height: 350px;
}

#store_graph1 {
   margin-top: 2px;
  margin-left: 12px;
  width: 100%;
  height: 350px;
}


</style>





