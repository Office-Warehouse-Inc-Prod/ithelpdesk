<!-- Styles -->
<style>
#chartdiv1 {
  width: 100%;
  height: 300px;
}

</style>

<script>
var darkModeEnabled = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

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

function _plotdbline(grphdata) {
  am4core.ready(function () {
    am4core.useTheme(am4themes_animated);

    var chart = am4core.create("chartdiv1", am4charts.XYChart);
    chart.data = grphdata;

    // ✅ Light background with subtle gradient
    chart.background.fill = am4core.color("#f8faff");
    chart.background.fillOpacity = 1;

    // ✅ X Axis
    var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
    dateAxis.renderer.minGridDistance = 60;
    dateAxis.renderer.grid.template.stroke = am4core.color("#e0e6f5");
    dateAxis.renderer.grid.template.strokeOpacity = 0.6;
    dateAxis.renderer.labels.template.fill = am4core.color("#4a4a4a");

    // ✅ Y Axis
    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.renderer.grid.template.stroke = am4core.color("#e0e6f5");
    valueAxis.renderer.grid.template.strokeOpacity = 0.6;
    valueAxis.renderer.labels.template.fill = am4core.color("#4a4a4a");

    // ✅ Line series
    var series = chart.series.push(new am4charts.LineSeries());
    series.dataFields.valueY = "value";
    series.dataFields.dateX = "date";
    series.strokeWidth = 3;
    series.tensionX = 0.8; // Smooth curves
    series.tooltipText = "{value}";
    series.fillOpacity = 0.15;

    // ✅ Gradient stroke (pastel neon)
    series.stroke = new am4core.LinearGradient();
    series.stroke.addColor(am4core.color("#6a5acd")); // pastel purple
    series.stroke.addColor(am4core.color("#00bcd4")); // pastel cyan

    // ✅ Glow effect (lighter glow)
    let shadow = series.filters.push(new am4core.DropShadowFilter());
    shadow.color = am4core.color("#7bcfff");
    shadow.blur = 10;
    shadow.opacity = 0.6;

    // ✅ Add bullets
    var bullet = series.bullets.push(new am4charts.CircleBullet());
    bullet.circle.radius = 5;
    bullet.circle.fill = am4core.color("#ffffff");
    bullet.circle.strokeWidth = 2;
    bullet.circle.stroke = am4core.color("#00bcd4");

    bullet.states.create("hover").properties.scale = 1.4;

    // ✅ Cursor and scrollbar
    chart.cursor = new am4charts.XYCursor();
    chart.cursor.snapToSeries = series;
    chart.cursor.xAxis = dateAxis;

    chart.scrollbarX = new am4core.Scrollbar();
    chart.scrollbarX.background.fill = am4core.color("#e6ebf5");
    chart.scrollbarX.thumb.background.fill = am4core.color("#6a5acd");
    chart.scrollbarX.thumb.background.fillOpacity = 0.8;

    chart.exporting.menu = new am4core.ExportMenu();
    am4core.options.autoDispose = true;
  });
}



</script>

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
  const curdate2 = new Date();
  const curyr2 = g=curdate2.getFullYear();

  _catpie(curyr2);
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
    am4core.useTheme(am4themes_animated);

    // ✅ Create chart
    var chart = am4core.create("chartdiv2", am4charts.PieChart);
    chart.innerRadius = am4core.percent(35); // Donut style for modern look
    chart.fontFamily = "Segoe UI, Roboto, sans-serif";
    chart.background.fill = am4core.color("#f8faff");

    var selected;
    chart.data = generateChartData();

    // ✅ Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "percent";
    pieSeries.dataFields.category = "type";
    pieSeries.slices.template.propertyFields.fill = "color";
    pieSeries.slices.template.propertyFields.isActive = "pulled";
    pieSeries.slices.template.strokeWidth = 0;

    // ✅ Label styling
    pieSeries.labels.template.maxWidth = 130;
    pieSeries.labels.template.wrap = true;
    pieSeries.labels.template.fontSize = 12;
    pieSeries.labels.template.fill = am4core.color("#444");
    pieSeries.labels.template.text = "[bold]{type}[/]\n{value.value} ({value.percent.formatNumber('.##')}%)";

    // ✅ Tooltip styling
    pieSeries.slices.template.tooltipText =
      "{type}: {value.value} | {value.percent.formatNumber('.##')}%";

    // ✅ Glow effect
    let shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter());
    shadow.blur = 6;
    shadow.color = am4core.color("#999");
    shadow.opacity = 0.4;

    // ✅ Hover animation
    let hs = pieSeries.slices.template.states.create("hover");
    hs.properties.scale = 1.08;
    hs.properties.shiftRadius = 0.03;

    chart.exporting.menu = new am4core.ExportMenu();

    function generateChartData() {
      let chartData = [];
      for (var i = 0; i < types.length; i++) {
        if (i == selected) {
          for (var x = 0; x < types[i].subs.length; x++) {
            chartData.push({
              type: types[i].subs[x].type,
              percent: types[i].subs[x].percent,
              color: types[i].color,
              pulled: true,
            });
          }
        } else {
          chartData.push({
            type: types[i].type,
            percent: types[i].percent,
            color: types[i].color,
            id: i,
          });
        }
      }
      return chartData;
    }

    // ✅ Click event to drill down
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
pieSeries.dataFields.category = "type";
// pieSeries.dataFields.subs = "subs";
pieSeries.slices.template.stroke = am4core.color("#FFF"); //outline
pieSeries.slices.template.strokeWidth = 2;
pieSeries.slices.template.strokeOpacity = 1;
pieSeries.slices.template.tooltipPosition = "pointer";
pieSeries.labels.template.maxWidth = 130;
pieSeries.labels.template.wrap = true;
pieSeries.labels.template.fontSize = 10;

pieSeries.slices.template.events.on("hit", function(ev){
  // let srchvalx = ev.target.dataItem.category;
  let srchsubsx = ev.target.dataItem.category;
  // alert(srchsubsx);
  // var table = $("#table_cat").DataTable();
  // alert(srchval);
  tablecat.search(srchsubsx).draw()
  $('#piegraphModal, body').animate({
        scrollTop: $("#table_cat").offset().top
    }, 1000);
    
});



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


function getcategories(){
  $.post('fetchdata/fetch_data.php',{mode:'dtbcat'},function(data){
    console.log(data);
    datatable_categories(data)
  },'json');
}


getcategories();

var tablecat
function datatable_categories(t){
const dataset=t.rptcat;


tablecat = $("#table_cat").DataTable({

"dom":
'<"pull-left"lf><"pull-right">tip',

"info": true,
"pagingType": "full_numbers",
"bDestroy": true,
"responsive": true, "lengthChange": false, "autoWidth": false,
"language": {
"search": "_INPUT_",
"searchPlaceholder": "Search..."
},
order: [[0, 'desc']],
"pageLength":10,
"data": dataset,

"columns": [
{title:"TICKET", data:"ticket","defaultContent": "",},
{title:"BRANCH", data:"store","defaultContent": "",},
{title:"CATEGORY", data:"category","defaultContent": "",},
{title:"SUBCATEGORY", data:"subcat","defaultContent": "",},

],


});

}

 
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
 const curdates = new Date();
  const curyrs = g=curdates.getFullYear();

  _overallpie(curyrs);
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
 function _plotovpie(grphdata) {
  am4core.ready(function () {
    am4core.useTheme(am4themes_animated);

    // ✅ Create chart
    var chart = am4core.create("chartdiv5", am4charts.PieChart);
    chart.innerRadius = am4core.percent(40);
    chart.fontFamily = "Segoe UI, Roboto, sans-serif";
    chart.background.fill = am4core.color("#f8faff");

    // ✅ Legend styling
    chart.legend = new am4charts.Legend();
    chart.legend.position = "bottom";
    chart.legend.valign = "bottom";
    chart.legend.labels.template.fill = am4core.color("#444");
    chart.legend.labels.template.fontSize = 12;
    chart.legend.labels.template.text = "[bold {color}]{name}[/]";

    chart.data = grphdata;

    // ✅ Series
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

    // ✅ Glow effect
    let shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter());
    shadow.blur = 6;
    shadow.color = am4core.color("#999");
    shadow.opacity = 0.4;

    // ✅ Hover animation
    let hs = pieSeries.slices.template.states.create("hover");
    hs.properties.scale = 1.08;
    hs.properties.shiftRadius = 0.03;

    // ✅ Custom pastel colors based on category
    pieSeries.slices.template.adapter.add("fill", function (fill, target) {
      if (target.dataItem) {
        switch (target.dataItem.category) {
          case "OPEN":
            return am4core.color("#FF7A7A"); // soft red
          case "ATTENDED WITH FIX ASSET":
            return am4core.color("#FFD966"); // soft yellow
          case "CLOSED":
            return am4core.color("#7DD77D"); // soft green
          case "SUBJECT FOR CLOSING":
            return am4core.color("#CBA6E3"); // soft purple
          default:
            return am4core.color("#9EC9F7"); // fallback pastel blue
        }
      }
      return fill;
    });

    // ✅ Animation on load
    pieSeries.hiddenState.properties.opacity = 1;
    pieSeries.hiddenState.properties.endAngle = -90;
    pieSeries.hiddenState.properties.startAngle = -90;

    am4core.options.autoDispose = true;
  });
}


</script>


<!-- Styles -->
<style>
#chartdiv9 {
   margin-top: 2px;
  margin-left: 12px;
  width: 100%;
  height: 350px;
}

</style>


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
<style>
#chartdiv8 {
  width: 100%;
  height: 300px;
}

</style>

<!-- Chart code -->
<script>
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
  am4core.color("#27A243"),
  am4core.color("#DC3545"),
  am4core.color("#FFC107")

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
series.columns.template.events.on("hit", function(ev) {
              
              let itVal= ev.target.dataItem.dataContext["itsup"] ;
              let flItName = ev.target.dataItem.dataContext["it_name"];
              let ItFullname = ev.target.dataItem.dataContext["it_desc"];
              let cmp_role = ev.target.dataItem.dataContext["cmp_role"];
              let img_name = ev.target.dataItem.dataContext["img_name"];
              let totrep = ev.target.dataItem.dataContext["total"];
              let cmplted = ev.target.dataItem.dataContext["completed"];
              let opencase = ev.target.dataItem.dataContext["opncase"];
              let openwfx = ev.target.dataItem.dataContext["opnwfxast"];
              let resasgnsupcnt = ev.target.dataItem.dataContext["resassgncnt"];
              let count_slares = ev.target.dataItem.dataContext["res_sla"];
              let yrsx1 = ev.target.dataItem.dataContext["years"];
              
// alert(itVal);
 // console.log(yrsx1); 
 // console.log(itVal); 
itsupdata(itVal,ItFullname,cmp_role,img_name,totrep,cmplted,opencase,openwfx,yrsx1);
resgncnt(resasgnsupcnt.cnt_resassgn);
rpt_sla(count_slares.tclosdif);
rpt_cntsla(count_slares.tdccl)
}, this);

var series2 = chart.series.push(new am4charts.ColumnSeries());
series2.dataFields.valueY = "completed";
series2.dataFields.categoryX = "it_name";
series2.clustered = false;
series2.columns.template.width = am4core.percent(50);
series2.tooltipText = "COMPLETED REPORTS: [bold]{valueY}";
series2.columns.template.events.on("hit", function(ev) {
              
              let itVal= ev.target.dataItem.dataContext["itsup"] ;
              let flItName = ev.target.dataItem.dataContext["it_name"];
              let ItFullname = ev.target.dataItem.dataContext["it_desc"];
              let cmp_role = ev.target.dataItem.dataContext["cmp_role"];
              let img_name = ev.target.dataItem.dataContext["img_name"];
              let totrep = ev.target.dataItem.dataContext["total"];
              let cmplted = ev.target.dataItem.dataContext["completed"];
              let opencase = ev.target.dataItem.dataContext["opncase"];
              let openwfx = ev.target.dataItem.dataContext["opnwfxast"];
              let resasgnsupcnt = ev.target.dataItem.dataContext["resassgncnt"];
              let count_slares = ev.target.dataItem.dataContext["res_sla"];
              let yrsx1 = ev.target.dataItem.dataContext["years"];
              
// alert(itVal);
 // console.log(yrsx1); 
 // console.log(itVal); 
itsupdata(itVal,ItFullname,cmp_role,img_name,totrep,cmplted,opencase,openwfx,yrsx1);
resgncnt(resasgnsupcnt.cnt_resassgn);
rpt_sla(count_slares.tclosdif);
rpt_cntsla(count_slares.tdccl)
}, this);

var series3 = chart.series.push(new am4charts.ColumnSeries());
series3.dataFields.valueY = "opncase";
series3.dataFields.categoryX = "it_name";
series3.clustered = false;
series3.columns.template.width = am4core.percent(50);
series3.tooltipText = "OPEN REPORTS: [bold]{valueY}";
series3.columns.template.events.on("hit", function(ev) {
              
              let itVal= ev.target.dataItem.dataContext["itsup"] ;
              let flItName = ev.target.dataItem.dataContext["it_name"];
              let ItFullname = ev.target.dataItem.dataContext["it_desc"];
              let cmp_role = ev.target.dataItem.dataContext["cmp_role"];
              let img_name = ev.target.dataItem.dataContext["img_name"];
              let totrep = ev.target.dataItem.dataContext["total"];
              let cmplted = ev.target.dataItem.dataContext["completed"];
              let opencase = ev.target.dataItem.dataContext["opncase"];
              let openwfx = ev.target.dataItem.dataContext["opnwfxast"];
              let resasgnsupcnt = ev.target.dataItem.dataContext["resassgncnt"];
              let count_slares = ev.target.dataItem.dataContext["res_sla"];
              let yrsx1 = ev.target.dataItem.dataContext["years"];
              
// alert(itVal);
 // console.log(yrsx1); 
 // console.log(itVal); 
itsupdata(itVal,ItFullname,cmp_role,img_name,totrep,cmplted,opencase,openwfx,yrsx1);
resgncnt(resasgnsupcnt.cnt_resassgn);
rpt_sla(count_slares.tclosdif);
rpt_cntsla(count_slares.tdccl)
}, this);

var series4 = chart.series.push(new am4charts.ColumnSeries());
series4.dataFields.valueY = "opnwfxast";
series4.dataFields.categoryX = "it_name";
series4.clustered = false;
series4.columns.template.width = am4core.percent(50);
series4.tooltipText = "WITH FIX ASSET: [bold]{valueY}";

chart.cursor = new am4charts.XYCursor();
chart.cursor.lineX.disabled = true;
chart.cursor.lineY.disabled = true;

var bullet = series.bullets.push(new am4charts.LabelBullet());
bullet.label.text = "{completed} / {total} ";
bullet.label.verticalCenter = "bottom";
bullet.label.dy = -10;
bullet.label.fontSize = 15;
bullet.label.truncate = false;

chart.exporting.menu = new am4core.ExportMenu();

}); // end am4core.ready()

  }

function resgncnt(resasgnsupcnt){
  $.post('fetchdata/fetch_data.php', {resasgnsupcnt:resasgnsupcnt,mode: 'count_reassigned'}, function(data) {
    $('#itm_resasncnt').html(resasgnsupcnt); 
  });
}

function rpt_sla(count_slares){
  $.post('fetchdata/fetch_data.php', {count_slares:count_slares,mode: 'count_sla'}, function(data) {
       $('#itm_sla').html(count_slares); 
       $('#itm_sla').append('%');


  });
}

function rpt_cntsla(count_slares){
  $.post('fetchdata/fetch_data.php', {count_slares:count_slares,mode: 'count_sla'}, function(data) {
     $('#itm_cntsla').html(count_slares); 

  });
}
 
function itsupdata(itVal,ItFullname,cmp_role,img_name,totrep,cmplted,opencase,openwfx,yrsx1){
$.post('fetchdata/fetch_data.php',{itVal:itVal,ItFullname:ItFullname,cmp_role:cmp_role, img_name:img_name,totrep:totrep,cmplted:cmplted,opencase:opencase,openwfx:openwfx,yrsx1:yrsx1,mode:'dtbl_itsup'},function(data){
$('#tech_bar_modal').modal({"show": true, "backdrop": 'static'});
itsup_datatables(data);
$('#ITName').html(ItFullname); 
$('#cmprole').html(cmp_role); 
$('#tech_img').attr('src', '../images/users/'+img_name);
$('#itm_total').html(totrep); 
$('#itm_open').html(opencase); 
$('#itm_wfa').html(openwfx); 
$('#itm_closed').html(cmplted); 
},'json');
}

var table
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



} // end of data table

</script>






<!-- Styles -->
<style>
#chart_area {
  width: 100%;
  height: 350px;
}

</style>


<!-- Chart code -->
<script>
  const curdatez = new Date();
  const curyrz = g=curdatez.getFullYear();

_areagraph(curyrz);

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
chart.colors.list = [
  am4core.color("#0077F7")
];

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
series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
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

var bullet = series.bullets.push(new am4charts.LabelBullet());
bullet.label.text = "{cntarea} Reports";
bullet.label.verticalCenter = "bottom";
bullet.label.dy = -10;
bullet.label.fontSize = 15;
bullet.label.truncate = false;

}); // end am4core.ready()

function _storegraph(s_area,syr){
                          $.ajax({
                  url:"fetchdata/fetch_data.php",
                  method:'POST',
                   data:{area_desc:s_area,yr:syr,mode:'str_grph'},

                  success:function(fdata)
                  {
                    var objstorearea = JSON.parse(fdata);
                    _plot_store_graph(objstorearea);
                    $('#store_graph_modal').modal({"show": true, "backdrop": 'static'});
                  }
                 });
}



}

</script>

<!-- Styles -->
<style>
#store_graph {
  width: 100%;
  height: 500px;
}

</style>

<!-- Chart code -->
<script>

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


</script>



<!-- Styles -->
<!-- <style>
#chartdivnet {
    margin-top: 12px;
  margin-left: 12px;
  width: 100%;
  height: 300px; 
}
</style> -->

<!-- <script>
  const curdate3 = new Date();
  const curyr3 = g=curdate3.getFullYear();

  _netpie(curyr3);
 function _netpie(curyr3){
   var selected;
var types = $.ajax({
    url:"fetchdata/fetch_data.php",
    method:'POST',
    data:{yr:curyr3,mode:'netpie'},
    datatype:'JSON',
   
    success:function(data)
    {
      var obj = JSON.parse(data);
      // console.log(obj);
      netgrhp(obj);
    }
   });
 } 



 function netgrhp(types){
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_material);
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdivnet", am4charts.PieChart);

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
pieSeries.dataFields.subs = "subs";
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
pieSeries.dataFields.category = "type";
// pieSeries.dataFields.subs = "subs";
pieSeries.slices.template.stroke = am4core.color("#FFF"); //outline
pieSeries.slices.template.strokeWidth = 2;
pieSeries.slices.template.strokeOpacity = 1;
pieSeries.slices.template.tooltipPosition = "pointer";
pieSeries.labels.template.maxWidth = 130;
pieSeries.labels.template.wrap = true;
pieSeries.labels.template.fontSize = 10;

pieSeries.slices.template.events.on("hit", function(ev){
  // let srchvalx = ev.target.dataItem.category;
  let srchsubsx = ev.target.dataItem.category;
  // alert(srchsubsx);
  // var table = $("#table_cat").DataTable();
  // alert(srchval);
  tablecat.search(srchsubsx).draw()
  $('#piegraphModal, body').animate({
        scrollTop: $("#table_cat").offset().top
    }, 1000);
    
});



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


function getcategories(){
  $.post('fetchdata/fetch_data.php',{mode:'dtbcat'},function(data){
    console.log(data);
    datatable_categories(data)
  },'json');
}


getcategories();

var tablecat
function datatable_categories(t){
const dataset=t.rptcat;


tablecat = $("#table_cat").DataTable({

"dom":
'<"pull-left"lf><"pull-right">tip',

"info": true,
"pagingType": "full_numbers",
"bDestroy": true,
"responsive": true, "lengthChange": false, "autoWidth": false,
"language": {
"search": "_INPUT_",
"searchPlaceholder": "Search..."
},
order: [[0, 'desc']],
"pageLength":10,
"data": dataset,

"columns": [
{title:"TICKET", data:"ticket","defaultContent": "",},
{title:"BRANCH", data:"store","defaultContent": "",},
{title:"CATEGORY", data:"category","defaultContent": "",},
{title:"SUBCATEGORY", data:"subcat","defaultContent": "",},

],


});

}

 
}


</script> -->


<!-- Styles -->
<!-- <style>
#chartdivnet2 {
/*   margin-top: 2px;
  margin-left: 18px;*/
  width: 100%;
  height: 300px;
}

</style> -->

<!-- Chart code -->
<!-- <script>
 const curdates2 = new Date();
  const curyrs2 = g=curdates2.getFullYear();

  _overallnet(curyrs2);
  function _overallnet(curyrs2){

 $.ajax({
    url:"fetchdata/fetch_data.php",
    method:'POST',
     data:{yr:curyrs,mode:'overallnet'},

    success:function(data5)
    {

      var obj5 = JSON.parse(data5);
      // console.log(obj5)
       _plotovnet(obj5)
      
    }
   });

}
 function _plotovnet(grphdata){

am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdivnet2", am4charts.PieChart);

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
pieSeries.labels.template.text =  "{type}  {value.value} {category} Reports | {value.percent.formatNumber('.##')}%";
pieSeries.slices.template.tooltipText = "{type} {value.value} {category} Reports | {value.percent.formatNumber('.##')}%";


// This creates initial animation
pieSeries.hiddenState.properties.opacity = 1;
pieSeries.hiddenState.properties.endAngle = -90;
pieSeries.hiddenState.properties.startAngle = -90;

pieSeries.slices.template.adapter.add("fill", function(fill, target) {
  if (target.dataItem && (target.dataItem.category == 'OPEN')) {
    return am4core.color("#D53343");
  }
  if (target.dataItem && (target.dataItem.category == 'ATTENDED WITH FIX ASSET')) {
    return am4core.color("#F7BB07");
  }
  if (target.dataItem && (target.dataItem.category == 'CLOSED')) {
    return am4core.color("#27A243");
  }
  if (target.dataItem && (target.dataItem.category == 'SUBJECT FOR CLOSING')) {
    return am4core.color("#890188");
  }
  else {
    return fill;
  }
});

am4core.options.autoDispose = true;

}); // end am4core.ready()


 }

</script> -->

<!-- Styles -->
<!-- <style>
#net_area {
  width: 100%;
  height: 350px;
}

</style> -->


<!-- Chart code -->
<!-- <script>
  const curdatez1 = new Date();
  const curyrz1 = g=curdatez1.getFullYear();

_areagraph(curyrz1);

  function _areagraph(curyrz1){

 $.ajax({
    url:"fetchdata/fetch_data.php",
    method:'POST',
     data:{yr:curyrz1,mode:'areanet_grph'},

    success:function(data)
    {

      var objarea = JSON.parse(data);
      // console.log(objarea)
       _plotnetareagrph(objarea);
      
    }
   });

  }

function _plotnetareagrph(grphdata){

am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("net_area", am4charts.XYChart);

// Add data
chart.data = grphdata
// Create axes
chart.colors.list = [
  am4core.color("#0077F7")
];

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
series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
series.columns.template.fillOpacity = .8;
series.columns.template.events.on("hit", function(ev) {
               
              let s_area = ev.target.dataItem.dataContext["area_desc"] ;
              let syr = ev.target.dataItem.dataContext["fyr"];

 // alert(syr); 

 _storenetgraph(s_area,syr);


}, this);

var columnTemplate = series.columns.template;
columnTemplate.strokeWidth = 2;
columnTemplate.strokeOpacity = 1;

var bullet = series.bullets.push(new am4charts.LabelBullet());
bullet.label.text = "{cntarea} Reports";
bullet.label.verticalCenter = "bottom";
bullet.label.dy = -10;
bullet.label.fontSize = 15;
bullet.label.truncate = false;

}); // end am4core.ready()

function _storenetgraph(s_area,syr){
                          $.ajax({
                  url:"fetchdata/fetch_data.php",
                  method:'POST',
                   data:{area_desc:s_area,yr:syr,mode:'strnet_grph'},

                  success:function(fdata)
                  {
                    var objstorearea = JSON.parse(fdata);
                    _plot_netstore_graph(objstorearea);
                    $('#storenet_graph_modal').modal({"show": true, "backdrop": 'static'});
                  }
                 });
}



}

</script> -->

<!-- Styles -->
<!-- <style>
#storenet_graph {
  width: 100%;
  height: 500px;
}

</style> -->

<!-- Chart code -->
<!-- <script>

function _plot_netstore_graph(strdata){

am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("storenet_graph", am4charts.XYChart);

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