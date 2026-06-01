<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}

</style>

<!-- Resources -->
<script src="core.js"></script>
<script src="charts.js"></script>
<script src="animated.js"></script>
<script src="jquery.min.js" type="text/javascript"></script>
<script src="jquery-1.12.4.js" type="text/javascript"></script>




  






<!-- Chart code -->
<script>

     

am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.PieChart);

// Set data
var selected;
// var types = [{
//   type: "Fossil Energy",
//   percent: 70,
//   color: chart.colors.getIndex(0),
//   subs: [{
//     type: "Oil",
//     percent: 15
//   }, {
//     type: "Coal",
//     percent: 35
//   }, {
//     type: "Nuclear",
//     percent: 20
//   }]
// }, {
//   type: "Green Energy",
//   percent: 30,
//   color: chart.colors.getIndex(1),
//   subs: [{
//     type: "Hydro",
//     percent: 15
//   }, {
//     type: "Wind",
//     percent: 10
//   }, {
//     type: "Other",
//     percent: 5
//   }]
// }];

// Add data
function generateChartData() {

var chartData;
  //var chartData = [];
var types=[];

  $.ajax({
type:"GET",
     //data:{cid:selected},
      url:"data.php",

     contentType:"application/json; charset=utf-8",
      dataType:"json", 
    
      success:function(data){

var  typez =[];

         for (var i = 0; i < data.length; i++) {
          typez.push({type:data[i].type,percent:data[i].percent});
      // chartData.push({
      //   type: data[i].type,
      //   percent: data[i].percent,
        
      //   id:  data[i].id
      // });
 
//alert(chartData);

      }
console.log( typez);
chartData=typez;
        chart.data = typez;

    // console.log( types);
//chartData=type;
//alert(a);
      
  
    }
    });
 

  // for (var i = 0; i < types.length; i++) {
  //   if (i == selected) {
  //     for (var x = 0; x < types[i].subs.length; x++) {
  //       chartData.push({
  //         type: types[i].subs[x].type,
  //         percent: types[i].subs[x].percent,
  //         color: types[i].color,
  //         pulled: true
  //       });
  //     }
  //   } else {
  //     chartData.push({
  //       type: types[i].type,
  //       percent: types[i].percent,
  //       color: types[i].color,
  //       id: i
  //     });
  //   }
  // }
  // return chartData;
}

//chart.data = generateChartData();

// Add and configure Series
var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "percent";
pieSeries.dataFields.category = "type";
pieSeries.slices.template.propertyFields.fill = "color";
pieSeries.slices.template.propertyFields.isActive = "pulled";
pieSeries.slices.template.strokeWidth = 0;

pieSeries.slices.template.events.on("hit", function(event) {
  if (event.target.dataItem.dataContext.id != undefined) {
    selected = event.target.dataItem.dataContext.id;
  } else {
    selected = undefined;
  }
  chart.data = generateChartData();
});

}); // end am4core.ready()
</script>

<!-- HTML -->
<div id="chartdiv"></div>