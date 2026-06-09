
<style>
#chartdiv2 {
    margin-top: 12px;
  margin-left: 12px;
  width: 100%;
  height: 400px; 
}

</style>
<script>
  const curdate2 = new Date();
  const curyr2 = g=curdate2.getFullYear();

  _catpie(curyr2);
 /**
  *  catpie.
  */
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



 /**
  * Grhp.
  */
 function grhp(types) {
  am4core.ready(function () {
    am4core.useTheme(am4themes_animated);

    //  Create chart
    var chart = am4core.create("chartdiv2", am4charts.PieChart);
    chart.innerRadius = am4core.percent(35); // Donut style for modern look
    chart.fontFamily = "Segoe UI, Roboto, sans-serif";
    chart.background.fill = am4core.color("#f8faff");

    var selected;
    chart.data = generateChartData();

    //  Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "percent";
    pieSeries.dataFields.category = "type";
    pieSeries.dataFields.dept_id = "dept_id";
    pieSeries.slices.template.propertyFields.fill = "color";
    pieSeries.slices.template.strokeWidth = 0;

     //  Legend styling
    chart.legend = new am4charts.Legend();
    chart.legend.position = "bottom";
    chart.legend.valign = "bottom";
    chart.legend.labels.template.fill = am4core.color("#444");
    chart.legend.labels.template.fontSize = 10;
    chart.legend.labels.template.text = "[bold {color}]{name}[/]";

    //  Label styling
    pieSeries.labels.template.maxWidth = 130;
    pieSeries.labels.template.wrap = true;
    pieSeries.labels.template.fontSize = 12;
    pieSeries.labels.template.fill = am4core.color("#444");
    pieSeries.labels.template.text = "[bold]{type}[/]\n{value.value} ({value.percent.formatNumber('.##')}%)";
    
    //  Tooltip styling
    pieSeries.slices.template.tooltipText =
      "{type}: {value.value} | {value.percent.formatNumber('.##')}%";

    //  Glow effect
    let shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter());
    shadow.blur = 6;
    shadow.color = am4core.color("#4c4b4b");
    shadow.opacity = 0.4;

     //hover
    let hs = pieSeries.slices.template.states.create("hover");
    hs.properties.scale = 1.08;
    hs.properties.shiftRadius = 0.03;
    
pieSeries.slices.template.events.on("hit", function(ev) {

    if (!ev.target.dataItem) {
        console.log("Walang laman");
        return false;
    }

    let dept_id = ev.target.dataItem.dept_id;

    console.log("Clicked Type:", dept_id);

    selected = ev.target.dataItem.index;

    chart.data = generateChartData();

    _storegraph(dept_id);

}, this);


// end am4core.ready()

function _storegraph(dept_id){
  $.ajax({
    url:"fetchdata/fetch_data.php",
    method:'POST',
    data:{mode:'str_grph',dept_id:dept_id},
    success:function(fdata){
      var objstorearea = JSON.parse(fdata);

      _plot_store_graph(objstorearea);

      $('#store_graph_modal').modal({
        show: true,
        backdrop: 'static'
      });
    }
  });
}


    /**
     * Generate chart data.
     */
    function generateChartData() {
      let chartData = [];
      for (var i = 0; i < types.length; i++) {
        if (i == selected) {
          for (var x = 0; x < types[i].subs.length; x++) {
            chartData.push({
              type: types[i].subs[x].type,
              percent: types[i].subs[x].percent,
              color: types[i].color,
              dept_id: types[i].dept_id,
              pulled: true,
            });
          }
        } else {
          chartData.push({
            type: types[i].type,
            percent: types[i].percent,
            color: types[i].color,

      dept_id: types[i].dept_id,
            id: i,
          });
        }
      }
      return chartData;
    }

    //  Click event to drill down
        pieSeries.hiddenState.properties.opacity = 1;
    pieSeries.hiddenState.properties.endAngle = -90;
    pieSeries.hiddenState.properties.startAngle = -90;

    am4core.options.autoDispose = true;
  });

}
</script>


<!-- Styles -->
<style>
#store_graph {
  width: 100%;
  height: 500px;
}

</style>

<script>

  /**
   *  storegraph bycat.
   */
  function _storegraph_bycat(cat_id) {
    $.ajax({
        url: "fetchdata/fetch_data.php", 
        method: 'POST',
        data: { 
            cat_id: cat_id, 
            mode: 'table_by_category' 
        },
        success: function(response) {
            try {
                var obj = JSON.parse(response);
                itsup_datatables({ itsuptbldata: obj });
            } catch (e) {
                console.error("Error parsing JSON: ", e);
            }
        }
    });
}

/**
 *  plot store graph.
 */
function _plot_store_graph(strdata){

  am4core.ready(function() {

    // Themes begin
    am4core.useTheme(am4themes_animated);

    // Create chart instance
    var chart = am4core.create("store_graph", am4charts.XYChart);
    chart.scrollbarX = new am4core.Scrollbar();

    // Add data
    chart.data = strdata;

    // Create axes
    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "cat_desc";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.minGridDistance = 30;
    categoryAxis.renderer.labels.template.wrap = true;
    categoryAxis.renderer.labels.template.fontSize = 12;
    categoryAxis.renderer.minHeight = 90;

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.renderer.minWidth = 30;

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.sequencedInterpolation = true;
    series.dataFields.valueY = "ctn";
    series.dataFields.categoryX = "cat_desc";
    series.tooltipText = "[{categoryX}: bold]{valueY}";
    series.tooltip.pointerOrientation = "vertical";

   series.columns.template.events.on("hit", function(ev) {
    // Get the category description (or cat_id) from the clicked bar
    let category = ev.target.dataItem.dataContext.cat_desc;
    let cat_id = ev.target.dataItem.dataContext.cat_id;
    
    console.log("Clicked category: " + category);
    
    // Call function to fetch table data based on this category
    _storegraph_bycat(cat_id); 
}, this);

    var series4 = chart.series.push(new am4charts.ColumnSeries());
series4.dataFields.valueY = "status";
series4.dataFields.categoryX = "ticket_no";
series4.clustered = false;
series4.columns.template.width = am4core.percent(50);

chart.cursor = new am4charts.XYCursor();
chart.cursor.lineX.disabled = true;
chart.cursor.lineY.disabled = true;

var bullet = series.bullets.push(new am4charts.LabelBullet());
bullet.label.verticalCenter = "bottom";
bullet.label.dy = -10;
bullet.label.fontSize = 15;
bullet.label.truncate = false;

chart.exporting.menu = new am4core.ExportMenu();

    var valueLabel = series.bullets.push(new am4charts.LabelBullet());
    valueLabel.label.text = "{valueY}"; 
    valueLabel.label.fontSize = 14;
    valueLabel.label.verticalCenter = "bottom";
    valueLabel.label.dy = -10;
    valueLabel.label.truncate = false;
    valueLabel.label.hideOversized = false;

    var table
/**
 * Itsup datatables.
 */
function itsup_datatables(t){
const dataset=t.itsuptbldata;
table =  $("#dtbl_itsup").DataTable({

"dom":
'<"pull-left"lf><"pull-right">tip',
// stateSave: true,
"pagingType": "full_numbers",
"bDestroy": true,
"responsive": true, "lengthChange": false, "autoWidth": false,
language: {
search: "_INPUT_",
searchPlaceholder: "Search..."
},
pageLength:10,
data: dataset,
"order": [[ 5, "Desc" ]],

columns: [

{title:"TicketNo", data:"ticket_no","defaultContent": ""},
{title:"  Store", data:"str_code","defaultContent": ""},
{title:"Date Created", data:"date_created","defaultContent": ""},
{title:"Subject", data:"subject","defaultContent": ""},
// {title:"Concern", data:"concern","defaultContent": ""},
{title:"Via", data:"via","defaultContent": ""},
{title:"STATUS", data:"status","defaultContent": ""},
{title:"Assigned Support", data:"it_desc","defaultContent": ""},
{title:"CATEGORY", data:"category","defaultContent": ""},
{title:"SUBCATEGORY", data:"sub_category","defaultContent": ""},
{title:"DATE CLOSED", data:"date_closed","defaultContent": ""},
{title:"DAYS COMPLETION", data:"tdc","defaultContent": ""},
{title:"WORKOUTPUT", data:"remarks","defaultContent": ""}


],
"columnDefs": [
{ 

  targets: [9,10],
  "width": "2%",
  render: function ( data, type, row) {
      if(type === 'display'){
          if(data == '1 Days Unresolved'){
            data = '1 Day Unresolved'
          }
         else if(data == '01/01/1970 01:00'){
            data = 'ATTENDED WITH FIX ASSET'
          }
         else if(data == '01/01/1970 08:00'){
            data = 'ATTENDED WITH FIX ASSET'
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
$(row).find('td:eq(11)').css('color', 'red');
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
else if (data['status'] == 'SUBJECT FOR CLOSING'){
$(row).find('td:eq(0)').css('color', '#890188');
$(row).find('td:eq(1)').css('color', '#890188');
$(row).find('td:eq(2)').css('color', '#890188');
$(row).find('td:eq(3)').css('color', '#890188');
$(row).find('td:eq(4)').css('color', '#890188');
$(row).find('td:eq(5)').css('color', '#890188');
$(row).find('td:eq(6)').css('color', '#890188');
$(row).find('td:eq(7)').css('color', '#890188');
$(row).find('td:eq(8)').css('color', '#890188');
$(row).find('td:eq(9)').css('color', '#890188');
$(row).find('td:eq(10)').css('color', '#890188');
$(row).find('td:eq(11)').css('color', '#890188');
$(row).find('td:eq(12)').css('color', '#890188');
$(row).find('td:eq(13)').css('color', '#890188');
}
},

});



} 
    
    
  }); // end am4core.ready()
}




</script>
</style>



<!-- Styles -->
<style>
#chartdiv1 {
/*   margin-top: 2px;
  margin-left: 18px;*/
  width: 100%;
  height:400px;
}

</style>

<!-- Chart code -->
<script>
 const curdates = new Date();
  const curyrs = g=curdates.getFullYear();

  _overallpie(curyrs);
  /**
   *  overallpie.
   */
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
 /**
  *  plotovpie.
  */
 function _plotovpie(grphdata) {
  am4core.ready(function () {
    am4core.useTheme(am4themes_animated);

    //  Create chart
    var chart = am4core.create("chartdiv1", am4charts.PieChart);
    chart.innerRadius = am4core.percent(40);
    chart.fontFamily = "Segoe UI, Roboto, sans-serif";
    chart.background.fill = am4core.color("#f8faff");

    //  Legend styling
    chart.legend = new am4charts.Legend();
    chart.legend.position = "bottom";
    chart.legend.valign = "bottom";
    chart.legend.labels.template.fill = am4core.color("#444");
    chart.legend.labels.template.fontSize = 12;
    chart.legend.labels.template.text = "[bold {color}]{name}[/]";

    chart.data = grphdata;

    

    //  Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "points";
    pieSeries.dataFields.category = "stat_name";
    pieSeries.labels.template.maxWidth = 140;
    pieSeries.labels.template.wrap = true;
    pieSeries.labels.template.fontSize = 12;
    pieSeries.labels.template.fill = am4core.color("#444");
    pieSeries.labels.template.text =
      "[bold]{category}[/]\n{value.value} Reports ({value.percent.formatNumber('.##')}%)";
    pieSeries.slices.template.tooltipText =
      "{category}: {value.value} Reports ({value.percent.formatNumber('.##')}%)";

    //  Glow effect
    let shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter());
    shadow.blur = 6;
    shadow.color = am4core.color("#999");
    shadow.opacity = 0.4;

    //  Hover animation
    let hs = pieSeries.slices.template.states.create("hover");
    hs.properties.scale = 1.08;
    hs.properties.shiftRadius = 0.03;

    //  Custom pastel colors based on category
    pieSeries.slices.template.adapter.add("fill", function (fill, target) {
      if (target.dataItem) {
        switch (target.dataItem.category) {
          case "PENDING":
            return am4core.color("#FF7A7A"); // soft red
          case "ON PROCESS":
            return am4core.color("#F2A65A"); // soft yellow
          case "CLOSED":
            return am4core.color("#578f63"); // soft green
             case "closed":
            return am4core.color("#578f63"); // soft green
          case "SUBJECT FOR CLOSING":
            return am4core.color("#b667eb"); // soft purple
          default:
            return am4core.color("#9EC9F7"); // fallback pastel blue
        }
      }
      return fill;
    });

    //  Animation   on load
    pieSeries.hiddenState.properties.opacity = 1;
    pieSeries.hiddenState.properties.endAngle = -90;
    pieSeries.hiddenState.properties.startAngle = -90;

    am4core.options.autoDispose = true;
  });
}


</script>
