

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



 function grhp(types) {
  am4core.ready(function () {
    am4core.useTheme(am4themes_animated); // Animated theme looks more modern

    var chart = am4core.create("chartdiv2", am4charts.PieChart);
    chart.innerRadius = am4core.percent(40); // Donut look
    chart.fontFamily = "Segoe UI, Roboto, sans-serif";
    chart.fontSize = 12;

    var selected;

    // Pastel color palette
    const pastelColors = [
      am4core.color("#AEC6CF"),
      am4core.color("#FFB347"),
      am4core.color("#FF6961"),
      am4core.color("#77DD77"),
      am4core.color("#CBAACB"),
      am4core.color("#FDFD96"),
      am4core.color("#B39EB5"),
      am4core.color("#FFD1DC"),
    ];

    chart.data = generateChartData();

    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "percent";
    pieSeries.dataFields.category = "type";
    pieSeries.labels.template.maxWidth = 140;
    pieSeries.labels.template.wrap = true;
    pieSeries.labels.template.fontSize = 12;
    pieSeries.labels.template.text = "[bold]{category}[/]\n{value.value} OPEN";
    pieSeries.slices.template.tooltipText = "{category} | {value.value} OPEN Tickets";

    // ✅ Add soft shadow for futuristic feel
    let shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter());
    shadow.blur = 8;
    shadow.color = am4core.color("#000000");
    shadow.opacity = 0.25;

    // ✅ Hover animation
    let hs = pieSeries.slices.template.states.create("hover");
    hs.properties.scale = 1.08;
    hs.properties.shiftRadius = 0.03;

    chart.exporting.menu = new am4core.ExportMenu();

    function generateChartData() {
      let chartData = [];
      for (let i = 0; i < types.length; i++) {
        let color = pastelColors[i % pastelColors.length];

        if (i == selected) {
          for (let x = 0; x < types[i].subs.length; x++) {
            chartData.push({
              type: types[i].subs[x].type,
              percent: types[i].subs[x].percent,
              color: pastelColors[x % pastelColors.length],
              pulled: true,
            });
          }
        } else {
          chartData.push({
            type: types[i].type,
            percent: types[i].percent,
            color: color,
            id: i,
          });
        }
      }
      return chartData;
    }

    pieSeries.slices.template.propertyFields.fill = "color";

    pieSeries.slices.template.events.on("hit", function (event) {
      selected =
        event.target.dataItem.dataContext.id !== undefined
          ? event.target.dataItem.dataContext.id
          : undefined;
      chart.data = generateChartData();
    });

    am4core.options.autoDispose = true;
  });
}






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
