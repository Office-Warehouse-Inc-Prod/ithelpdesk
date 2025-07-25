<!-- Styles -->
<!-- <style>
#chartdiv1 {
  width: 100%;
  height: 300px;
}

</style> -->

<!-- <script>

 const curdatea = new Date();
 const curyra = curdatea.getFullYear();

_dbline(curyra);

function _dbline(curyra){
  $.ajax({
    url:"fetchdata/fetch_data.php",
    method:'POST',
     data:{yr:curyra,mode:'dblinegrph'},
    success:function(data1)
    {

      var obja = JSON.parse(data1);
      // console.log(obja)
       _plotdbline(obja)
      
     // grhp(obj);
    }
   });
}

function _plotdbline(grphdata){

am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

var chart = am4core.create("chartdiv1", am4charts.XYChart);

chart.data = grphdata

// Create axes
var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
dateAxis.renderer.minGridDistance = 60;

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

// Create series
var series = chart.series.push(new am4charts.LineSeries());
series.dataFields.valueY = "value";
series.dataFields.dateX = "date";
series.tooltipText = "{value}"


series.tooltip.pointerOrientation = "vertical";
series.strokeWidth = 1.5;
series.fillOpacity = 0.3;
chart.cursor = new am4charts.XYCursor();
chart.cursor.snapToSeries = series;
chart.cursor.xAxis = dateAxis;

//chart.scrollbarY = new am4core.Scrollbar();
chart.scrollbarX = new am4core.Scrollbar();
chart.exporting.menu = new am4core.ExportMenu();

am4core.options.autoDispose = true;


}); // end am4core.ready()

}


</script> -->

<!-- Styles -->
<style>
#chartdiv2 {
    margin-top: 12px;
  margin-left: 12px;
  width: 100%;
  height: 300px; 
}

</style>


<script>
  // const curdate2 = new Date();
  // const curyr2 = g=curdate2.getFullYear();

  // _catpie(curyr2);
 function _catpie(curyr2){
   var selected;
var types = $.ajax({
    url:"fetchdata/fetch_data.php",
    method:'POST',
    data:{yr:curyr2,mode:'dashpie'},
    datatype:'JSON',
   
    success:function(data)
    {
      var obj = JSON.parse(data);
      // console.log(obj);
      grhp(obj);
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
pieSeries.labels.template.text = "{category} | {value.value} OPEN Tickets";
pieSeries.slices.template.tooltipText = "{category} | {value.value} OPEN Tickets";
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
// newgrph(d)
   
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


//  function newgrph(data){
// // console.log(data)

// am4core.ready(function() {

// // Themes begin
// am4core.useTheme(am4themes_animated);
// // Themes end

// // Create chart instance

// var chart = am4core.create("chartdiv9", am4charts.PieChart);

// // legend
// // chart.legend = new am4charts.Legend();
// // chart.legend.scrollable = true;
// chart.innerRadius = am4core.percent(40);
// // chart.legend.labels.template.text = "[bold {color}]{name}[/]";
// // series1.legendSettings.value = "{points}";
// // Add data
// chart.data = data;




// // Add and configure Series
// var pieSeries = chart.series.push(new am4charts.PieSeries());
// pieSeries.dataFields.value = "percent";
// pieSeries.dataFields.category = "types";
// pieSeries.slices.template.stroke = am4core.color("#FFF"); //outline
// pieSeries.slices.template.strokeWidth = 2;
// pieSeries.slices.template.strokeOpacity = 1;
// pieSeries.slices.template.tooltipPosition = "pointer";
// pieSeries.labels.template.maxWidth = 130;
// pieSeries.labels.template.wrap = true;
// pieSeries.labels.template.fontSize = 10;

// // pieSeries.alignLabels = false;
// // pieSeries.labels.template.text = "{type}: {value}";
// // pieSeries.slices.template.tooltipText = "{type}:{value}";
// pieSeries.labels.template.text = "{type}: {value.value} | {value.percent.formatNumber('.##')}%";
// pieSeries.slices.template.tooltipText = "{type}: {value.value} | {value.percent.formatNumber('.##')}%";


// // This creates initial animation
// pieSeries.hiddenState.properties.opacity = 1;
// pieSeries.hiddenState.properties.endAngle = -90;
// pieSeries.hiddenState.properties.startAngle = -90;


// am4core.options.autoDispose = true;

// }); // end am4core.ready()



// $('#piegraphModal').modal({"show": true, "backdrop": 'static'});

 
// }


</script>

<!-- Styles -->
<style>
#chartdiv5 {
/*   margin-top: 2px;
  margin-left: 18px;*/
  width: 100%;
  height: 300px;
}

</style>

<!-- Chart code -->
<script>
//  const curdates = new Date();
//   const curyrs = g=curdates.getFullYear();

  // _overallpie(curyrs);
  function _overallpie(curyrs){

 $.ajax({
    url:"fetchdata/fetch_data.php",
    method:'POST',
     data:{yr:curyrs,mode:'overallgrph'},

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
pieSeries.labels.template.text = "{category} | {value.value} OPEN Tickets";
pieSeries.slices.template.tooltipText = "{category} | {value.value} OPEN Tickets";
// pieSeries.slices.template.shiftRadius = 0;
// pieSeries.slices.template.states.hover.properties.shiftRadius = 0;


// This creates initial animation
pieSeries.hiddenState.properties.opacity = 1;
pieSeries.hiddenState.properties.endAngle = -90;
pieSeries.hiddenState.properties.startAngle = -90;

// pieSeries.colors.list = [
//   am4core.color("#27A243"),
//   am4core.color("#D53343"),
//   am4core.color("#F7BB07"),
//   am4core.color("#169DB2"),
// ];

am4core.options.autoDispose = true;

}); // end am4core.ready()


 }

</script>


<!-- Styles -->
<!-- <style>
#chartdiv9 {
   margin-top: 2px;
  margin-left: 12px;
  width: 100%;
  height: 350px;
}

</style> -->


<!-- modal pie -->



<style>
#chartdiv6 {
  width: 100%;
  height: 350px;
}

</style>


<!-- Chart code -->
<script>
function show_graph (json_data) {
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


am4core.options.autoDispose = true;

});
}
 // end am4core.ready()
</script>







<!-- Styles -->
<!-- <style>
#chartdiv8 {
  width: 100%;
  height: 300px;
}

</style> -->

<!-- Chart code -->
<!-- <script>
  const curdate = new Date();
  const curyr = g=curdate.getFullYear();

  _techgraph(curyr);
  function _techgraph(curyr){

 $.ajax({
    url:"fetchdata/fetch_data.php",
    method:'POST',
     data:{yr:curyr,mode:'techbargrph'},

    success:function(data1)
    {

      var obj1 = JSON.parse(data1);
       _plotgraph(obj1)
      
     // grhp(obj);
    }
   });

  }
  function _plotgraph(grphdata){

am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv8", am4charts.XYChart);

// Add percent sign to all numbers
// chart.numberFormatter.numberFormat = "#.#'%'";

// Add data
chart.data = grphdata

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
valueAxis.min = 0;
// valueAxis.max = 500;
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
series2.columns.template.events.on("hit", function(ev) {
              
              let itVal = ev.target.dataItem.dataContext["itsup"] ;
              let flItName = ev.target.dataItem.dataContext["it_name"];
              // let syr = ev.target.dataItem.dataContext["year"];

 alert(itVal); 
tech_bar_result(itVal,flItName);


}, this);

chart.cursor = new am4charts.XYCursor();
chart.cursor.lineX.disabled = true;
chart.cursor.lineY.disabled = true;

var bullet = series.bullets.push(new am4charts.LabelBullet());
bullet.label.text = "{completed} / {total} ";
bullet.label.verticalCenter = "bottom";
bullet.label.dy = -10;
bullet.label.fontSize = 15;
bullet.label.truncate = false;

}); // end am4core.ready()

  }

function tech_bar_result(itVal){
                          $.ajax({
                  url:"fetchdata/fetch_data.php",
                  method:'POST',
                   data:{itsup: itVal , mode:'str_grph'},
                  success:function(fdata)
                  {
                    $('#store_graph_modal').modal({"show": true, "backdrop": 'static'});
                  }
                 });
    

            }

</script> -->






<!-- Styles -->
<style>
#chart_area {
  width: 100%;
  height: 350px;
}

</style>





<!-- Chart code -->
<script>
  // const curdatez = new Date();
  // const curyrz = g=curdatez.getFullYear();

// _areagraph(curyrz);

  function _areagraph(curyrz){

 $.ajax({
    url:"fetchdata/fetch_data.php",
    method:'POST',
     data:{yr:curyrz,mode:'area_grph'},

    success:function(data)
    {

      var objarea = JSON.parse(data);
      // console.log(objarea)
       _plotareagrph(objarea);
      
    }
   });

  }

function _plotareagrph(grphdata){

am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chart_area", am4charts.XYChart);

// Add data
chart.data = grphdata
// Create axes

var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "area_desc";
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
// valueAxis.max = 300 ;

// Create series
var series = chart.series.push(new am4charts.ColumnSeries());
series.dataFields.valueY = "cntarea";
series.dataFields.categoryX = "area_desc";
series.name = "fyr";
series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/] Tickets";
series.columns.template.fillOpacity = .8;
series.columns.template.events.on("hit", function(ev) {
               
              let s_area = ev.target.dataItem.dataContext["area_desc"] ;
              let syr = ev.target.dataItem.dataContext["fyr"];

 // alert(syr); 

 _storegraph(s_area,syr);


}, this);

var columnTemplate = series.columns.template;
columnTemplate.strokeWidth = 2;
columnTemplate.strokeOpacity = 1;

}); // end am4core.ready()

function _storegraph(s_area,syr){
                          $.ajax({
                  url:"fetchdata/fetch_data.php",
                  method:'POST',
                   data:{area_desc:s_area,yr:syr,mode:'str_grph'},

                  success:function(fdata)
                  {
                    var objstorearea = JSON.parse(fdata);
                    // _plot_store_graph(objstorearea);
                    // $('#store_graph_modal').modal({"show": true, "backdrop": 'static'});
                  }
                 });
}



}

</script>

<!-- Styles -->
<!-- <style>
#store_graph {
  width: 100%;
  height: 500px;
}

</style> -->

<!-- Chart code -->
<!-- <script>

function _plot_store_graph(strdata){

am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("store_graph", am4charts.XYChart);

// Add data
chart.data = strdata

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





</script> -->

<style>
#chart_polled {
  width: 100%;
  height: 350px;
}

</style>


<script>
  // const curdatez = new Date();
  let fromPolled = $('#frompolDate').val();
  let toPolled = $('#topolDate').val();

  // _polledraph();

  function _polledraph(fromPolled,toPolled){

 $.ajax({
    url:"fetchdata/fetch_data.php",
    method:'POST',
     data:{toPolled:toPolled,fromPolled:fromPolled,mode:'polled_store'},

    success:function(data)
    {

      var objarea = JSON.parse(data);
      // console.log(objarea);
       _polledstore(objarea);
      
    }
   });

  }

function _polledstore(grphdata){

am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chart_polled", am4charts.XYChart);

// Add data
chart.data = grphdata
// Create axes

var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "str_code";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 30;


var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
// valueAxis.min = 0;
// valueAxis.max = 300 ;

// Create series
var series = chart.series.push(new am4charts.ColumnSeries());
series.dataFields.valueY = "cntstore";
series.dataFields.categoryX = "str_code";
series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/] NOT POLLED";
series.columns.template.fillOpacity = .8;

var columnTemplate = series.columns.template;
columnTemplate.strokeWidth = 2;
columnTemplate.strokeOpacity = 1;

}); // end am4core.ready()




}

</script>
