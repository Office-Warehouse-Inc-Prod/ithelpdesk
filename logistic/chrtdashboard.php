<!-- Styles -->
<style>
#chartdiv1 {
  width: 100%;
  height: 300px;
}

</style>

<script>

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

chart.colors.list = [
  am4core.color("#0077F7")
];

//chart.scrollbarY = new am4core.Scrollbar();
chart.scrollbarX = new am4core.Scrollbar();
chart.exporting.menu = new am4core.ExportMenu();

am4core.options.autoDispose = true;


}); // end am4core.ready()

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
  if (target.dataItem && (target.dataItem.category == 'FOR PICKUP')) {
    return am4core.color("#6C757D");
  }
  if (target.dataItem && (target.dataItem.category == 'DELIVERED TO SUPPLIER')) {
    return am4core.color("#17A2B8");
  }
  if (target.dataItem && (target.dataItem.category == 'FOR DELIVERY TO STORE')) {
    return am4core.color("#F7BB07");
  }
  if (target.dataItem && (target.dataItem.category == 'CLOSED')) {
    return am4core.color("#27A243");
  }
  else {
    return fill;
  }
});

am4core.options.autoDispose = true;

}); // end am4core.ready()


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
  am4core.color("#6C757D"),
  am4core.color("#17A2B8"),
  am4core.color("#FFC107"),

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
              let t_pickup = ev.target.dataItem.dataContext["t_pickup"];
              let t_deliver_supplier = ev.target.dataItem.dataContext["t_deliver_supplier"];
              let t_for_delivery = ev.target.dataItem.dataContext["t_for_delivery"];
              let resasgnsupcnt = ev.target.dataItem.dataContext["resassgncnt"];
              let count_slares = ev.target.dataItem.dataContext["res_sla"];
              let yrsx1 = ev.target.dataItem.dataContext["years"];
              
// alert(itVal);
 // console.log(yrsx1); 
 // console.log(itVal); 
itsupdata(itVal,ItFullname,cmp_role,img_name,totrep,cmplted,opencase,openwfx,t_pickup,t_deliver_supplier,t_for_delivery,yrsx1);
resgncnt(resasgnsupcnt.cnt_resassgn);
rpt_sla(count_slares.tclosdif);
rpt_cntsla(count_slares.tdccl)
}, this);

var series2 = chart.series.push(new am4charts.ColumnSeries());
series2.dataFields.valueY = "completed";
series2.dataFields.categoryX = "it_name";
series2.clustered = false;
// series2.columns.template.width = am4core.percent(50);
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
              let t_pickup = ev.target.dataItem.dataContext["t_pickup"];
              let t_deliver_supplier = ev.target.dataItem.dataContext["t_deliver_supplier"];
              let t_for_delivery = ev.target.dataItem.dataContext["t_for_delivery"];
              let resasgnsupcnt = ev.target.dataItem.dataContext["resassgncnt"];
              let count_slares = ev.target.dataItem.dataContext["res_sla"];
              let yrsx1 = ev.target.dataItem.dataContext["years"];
              
// alert(itVal);
 // console.log(yrsx1); 
 // console.log(itVal); 
itsupdata(itVal,ItFullname,cmp_role,img_name,totrep,cmplted,opencase,openwfx,t_pickup,t_deliver_supplier,t_for_delivery,yrsx1);
resgncnt(resasgnsupcnt.cnt_resassgn);
rpt_sla(count_slares.tclosdif);
rpt_cntsla(count_slares.tdccl)
}, this);

var series3 = chart.series.push(new am4charts.ColumnSeries());
series3.dataFields.valueY = "opncase";
series3.dataFields.categoryX = "it_name";
series3.clustered = false;
series3.columns.template.width = am4core.percent(70);
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
              let t_pickup = ev.target.dataItem.dataContext["t_pickup"];
              let t_deliver_supplier = ev.target.dataItem.dataContext["t_deliver_supplier"];
              let t_for_delivery = ev.target.dataItem.dataContext["t_for_delivery"];
              let resasgnsupcnt = ev.target.dataItem.dataContext["resassgncnt"];
              let count_slares = ev.target.dataItem.dataContext["res_sla"];
              let yrsx1 = ev.target.dataItem.dataContext["years"];
              
// alert(itVal);
 // console.log(yrsx1); 
 // console.log(itVal); 
itsupdata(itVal,ItFullname,cmp_role,img_name,totrep,cmplted,opencase,openwfx,t_pickup,t_deliver_supplier,t_for_delivery,yrsx1);
resgncnt(resasgnsupcnt.cnt_resassgn);
rpt_sla(count_slares.tclosdif);
rpt_cntsla(count_slares.tdccl)
}, this);

var series4 = chart.series.push(new am4charts.ColumnSeries());
series4.dataFields.valueY = "t_pickup";
series4.dataFields.categoryX = "it_name";
series4.clustered = false;
series4.columns.template.width = am4core.percent(60);
series4.tooltipText = "FOR PICKUP: [bold]{valueY}";
series4.columns.template.events.on("hit", function(ev) {
              
              let itVal= ev.target.dataItem.dataContext["itsup"] ;
              let flItName = ev.target.dataItem.dataContext["it_name"];
              let ItFullname = ev.target.dataItem.dataContext["it_desc"];
              let cmp_role = ev.target.dataItem.dataContext["cmp_role"];
              let img_name = ev.target.dataItem.dataContext["img_name"];
              let totrep = ev.target.dataItem.dataContext["total"];
              let cmplted = ev.target.dataItem.dataContext["completed"];
              let opencase = ev.target.dataItem.dataContext["opncase"];
              let openwfx = ev.target.dataItem.dataContext["opnwfxast"];
              let t_pickup = ev.target.dataItem.dataContext["t_pickup"];
              let t_deliver_supplier = ev.target.dataItem.dataContext["t_deliver_supplier"];
              let t_for_delivery = ev.target.dataItem.dataContext["t_for_delivery"];
              let resasgnsupcnt = ev.target.dataItem.dataContext["resassgncnt"];
              let count_slares = ev.target.dataItem.dataContext["res_sla"];
              let yrsx1 = ev.target.dataItem.dataContext["years"];
              
// alert(itVal);
 // console.log(yrsx1); 
 // console.log(itVal); 
itsupdata(itVal,ItFullname,cmp_role,img_name,totrep,cmplted,opencase,openwfx,t_pickup,t_deliver_supplier,t_for_delivery,yrsx1);
resgncnt(resasgnsupcnt.cnt_resassgn);
rpt_sla(count_slares.tclosdif);
rpt_cntsla(count_slares.tdccl)
}, this);

var series5 = chart.series.push(new am4charts.ColumnSeries());
series5.dataFields.valueY = "t_deliver_supplier";
series5.dataFields.categoryX = "it_name";
series5.clustered = false;
series5.columns.template.width = am4core.percent(50);
series5.tooltipText = "DELIVERED TO SUPPLIER: [bold]{valueY}";
series5.columns.template.events.on("hit", function(ev) {
              
              let itVal= ev.target.dataItem.dataContext["itsup"] ;
              let flItName = ev.target.dataItem.dataContext["it_name"];
              let ItFullname = ev.target.dataItem.dataContext["it_desc"];
              let cmp_role = ev.target.dataItem.dataContext["cmp_role"];
              let img_name = ev.target.dataItem.dataContext["img_name"];
              let totrep = ev.target.dataItem.dataContext["total"];
              let cmplted = ev.target.dataItem.dataContext["completed"];
              let opencase = ev.target.dataItem.dataContext["opncase"];
              let openwfx = ev.target.dataItem.dataContext["opnwfxast"];
              let t_pickup = ev.target.dataItem.dataContext["t_pickup"];
              let t_deliver_supplier = ev.target.dataItem.dataContext["t_deliver_supplier"];
              let t_for_delivery = ev.target.dataItem.dataContext["t_for_delivery"];
              let resasgnsupcnt = ev.target.dataItem.dataContext["resassgncnt"];
              let count_slares = ev.target.dataItem.dataContext["res_sla"];
              let yrsx1 = ev.target.dataItem.dataContext["years"];
              
// alert(itVal);
 // console.log(yrsx1); 
 // console.log(itVal); 
itsupdata(itVal,ItFullname,cmp_role,img_name,totrep,cmplted,opencase,openwfx,t_pickup,t_deliver_supplier,t_for_delivery,yrsx1);
resgncnt(resasgnsupcnt.cnt_resassgn);
rpt_sla(count_slares.tclosdif);
rpt_cntsla(count_slares.tdccl)
}, this);

var series6 = chart.series.push(new am4charts.ColumnSeries());
series6.dataFields.valueY = "t_for_delivery";
series6.dataFields.categoryX = "it_name";
series6.clustered = false;
series6.columns.template.width = am4core.percent(40);
series6.tooltipText = "FOR DELIVERY TO STORE: [bold]{valueY}";
series6.columns.template.events.on("hit", function(ev) {
              
              let itVal= ev.target.dataItem.dataContext["itsup"] ;
              let flItName = ev.target.dataItem.dataContext["it_name"];
              let ItFullname = ev.target.dataItem.dataContext["it_desc"];
              let cmp_role = ev.target.dataItem.dataContext["cmp_role"];
              let img_name = ev.target.dataItem.dataContext["img_name"];
              let totrep = ev.target.dataItem.dataContext["total"];
              let cmplted = ev.target.dataItem.dataContext["completed"];
              let opencase = ev.target.dataItem.dataContext["opncase"];
              let openwfx = ev.target.dataItem.dataContext["opnwfxast"];
              let t_pickup = ev.target.dataItem.dataContext["t_pickup"];
              let t_deliver_supplier = ev.target.dataItem.dataContext["t_deliver_supplier"];
              let t_for_delivery = ev.target.dataItem.dataContext["t_for_delivery"];
              let resasgnsupcnt = ev.target.dataItem.dataContext["resassgncnt"];
              let count_slares = ev.target.dataItem.dataContext["res_sla"];
              let yrsx1 = ev.target.dataItem.dataContext["years"];
              
// alert(itVal);
 // console.log(yrsx1); 
 // console.log(itVal); 
itsupdata(itVal,ItFullname,cmp_role,img_name,totrep,cmplted,opencase,openwfx,t_pickup,t_deliver_supplier,t_for_delivery,yrsx1);
resgncnt(resasgnsupcnt.cnt_resassgn);
rpt_sla(count_slares.tclosdif);
rpt_cntsla(count_slares.tdccl)
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
 
function itsupdata(itVal,ItFullname,cmp_role,img_name,totrep,cmplted,opencase,openwfx,t_pickup,t_deliver_supplier,t_for_delivery,yrsx1){
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
            data = ''
          }
         else if(data == '01/01/1970 08:00'){
            data = ''
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
else if (data['status'] == 'FOR PICKUP'){
$(row).find('td:eq(0)').css('color', 'gray');
$(row).find('td:eq(1)').css('color', 'gray');
$(row).find('td:eq(2)').css('color', 'gray');
$(row).find('td:eq(3)').css('color', 'gray');
$(row).find('td:eq(4)').css('color', 'gray');
$(row).find('td:eq(5)').css('color', 'gray');
$(row).find('td:eq(6)').css('color', 'gray');
$(row).find('td:eq(7)').css('color', 'gray');
$(row).find('td:eq(8)').css('color', 'gray');
$(row).find('td:eq(9)').css('color', 'gray');
$(row).find('td:eq(10)').css('color', 'gray');
$(row).find('td:eq(11)').css('color', 'gray');
$(row).find('td:eq(11)').html(' ');
$(row).find('td:eq(12)').css('color', 'gray');
}
else if (data['status'] == 'DELIVERED TO SUPPLIER'){
$(row).find('td:eq(0)').css('color', 'teal');
$(row).find('td:eq(1)').css('color', 'teal');
$(row).find('td:eq(2)').css('color', 'teal');
$(row).find('td:eq(3)').css('color', 'teal');
$(row).find('td:eq(4)').css('color', 'teal');
$(row).find('td:eq(5)').css('color', 'teal');
$(row).find('td:eq(6)').css('color', 'teal');
$(row).find('td:eq(7)').css('color', 'teal');
$(row).find('td:eq(8)').css('color', 'teal');
$(row).find('td:eq(9)').css('color', 'teal');
$(row).find('td:eq(10)').css('color', 'teal');
$(row).find('td:eq(11)').css('color', 'teal');
$(row).find('td:eq(11)').html(' ');
$(row).find('td:eq(12)').css('color', 'teal');
}
else if (data['status'] == 'FOR DELIVERY TO STORE'){
$(row).find('td:eq(0)').css('color', 'orange');
$(row).find('td:eq(1)').css('color', 'orange');
$(row).find('td:eq(2)').css('color', 'orange');
$(row).find('td:eq(3)').css('color', 'orange');
$(row).find('td:eq(4)').css('color', 'orange');
$(row).find('td:eq(5)').css('color', 'orange');
$(row).find('td:eq(6)').css('color', 'orange');
$(row).find('td:eq(7)').css('color', 'orange');
$(row).find('td:eq(8)').css('color', 'orange');
$(row).find('td:eq(9)').css('color', 'orange');
$(row).find('td:eq(10)').css('color', 'orange');
$(row).find('td:eq(11)').css('color', 'orange');
$(row).find('td:eq(11)').html(' ');
$(row).find('td:eq(12)').css('color', 'orange');
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


