

<!-- Styles -->
<style>
#chartdiv2 {
    margin-top: 12px;
  margin-left: 12px;
  width: 100%;
  height: 400px; 

  
}

.ampopup {
  overflow: visible;
  position: absolute;
  top: 0;
  left: 0;
  z-index: 2000
}

.ampopup-curtain {
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
  z-index: 2001;
  background: #fff;
  opacity: 0.5
}

.ampopup-title {
  font-weight: bold;
  font-size: 120%
}

.ampopup-content {
  padding: 1em 2em;
  background: rgb(255, 255, 255);
  background-color: rgba(255, 255, 255, 0.8);
  display: inline-block;
  position: absolute;
  max-width: 90%;
  max-height: 90%;
  overflow: auto;
  z-index: 2002
}

.ampopup-close {
  display: block;
  position: absolute;
  top: 0.3em;
  right: 0.3em;
  background-color: rgb(100, 100, 100);
  background: rgba(100, 100, 100, 0.1) url(data:image/svg+xml;charset=utf-8;base64,PHN2ZyBoZWlnaHQ9IjUxMiIgdmVyc2lvbj0iMSIgdmlld0JveD0iMCAwIDUxMiA1MTIiIHdpZHRoPSI1MTIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTQ0NS4yIDEwOS4ybC00Mi40LTQyLjRMMjU2IDIxMy42IDEwOS4yIDY2LjhsLTQyLjQgNDIuNEwyMTMuNiAyNTYgNjYuOCA0MDIuOGw0Mi40IDQyLjRMMjU2IDI5OC40bDE0Ni44IDE0Ni44IDQyLjQtNDIuNEwyOTguNCAyNTYiLz48L3N2Zz4=) no-repeat center;
  background-size: 80%;
  width: 1.2em;
  height: 1.2em;
  cursor: pointer
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
    pieSeries.slices.template.propertyFields.fill = "color";
    pieSeries.slices.template.propertyFields.isActive = "pulled";
    pieSeries.slices.template.strokeWidth = 0;
    

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
    
    


     //  Custom pastel colors based on category
    pieSeries.slices.template.adapter.add("fill", function (fill, target) {
      
      
      if (target.dataItem) {
       
        
        switch (target.dataItem.category) {
           case "IT":
            return am4core.color("#E1D0B3"); 
           case "ADMIN":
            return am4core.color("#B7BDF7"); 
           case "MARKETING":
            return am4core.color("#F9B2D7"); 
           case "MERCHANDISING":
            return am4core.color("#6594B1"); 
           case "PURCHASING":
            return am4core.color("#AAB99A"); 
           case "VISUAL":
            return am4core.color("#FF7444"); 
           case "HUMAN RESOURCES":
            return am4core.color("#A98B76"); 
           case "INVENTORY CONTROL GROUP":
            return am4core.color("#853953"); 
          case "ACCOUNTS PAYABLE":
            return am4core.color("#612D53"); 
          case "SALES ACCOUNTING":
            return am4core.color("#DA4848");
          case "TREASURY":
            return am4core.color("#DA4848");  

          case "ACCOUNTS RECIEVABLE":
            return am4core.color("#DA4848");  

        
        
          case "PENDING":
            return am4core.color("#FF7A7A"); 
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
     

      
    });


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

    //  Click event to drill down
    pieSeries.slices.template.events.on("hit", function (event) {
      

      selected =
        event.target.dataItem.dataContext.id !== undefined
          ? event.target.dataItem.dataContext.id
          : undefined;
      chart.data = generateChartData();
      
      
       pieSeries.ticks.template.events.on("ready", hideSmall);
pieSeries.ticks.template.events.on("visibilitychanged", hideSmall);
pieSeries.labels.template.events.on("ready", hideSmall);
pieSeries.labels.template.events.on("visibilitychanged", hideSmall);


       pieSeries.ticks.template.events.on("ready", showSmall);
pieSeries.ticks.template.events.on("visibilitychanged", showSmall);
pieSeries.labels.template.events.on("ready", showSmall);
pieSeries.labels.template.events.on("visibilitychanged", showSmall);

 pieSeries.slices.template.adapter.add("fill", function (fill, target) {


// Set innerRadius to make it a donut chart
chart.innerRadius = am4core.percent(50);

// Add label inside the donut
let label = pieSeries.createChild(am4core.Label);
label.text = (selected !== undefined) ? types[selected].type : "Department";
label.horizontalCenter = "middle";
label.verticalCenter = "middle";
label.fontSize = 20;

      
      if (target.dataItem) {
       
        
        switch (target.dataItem.category) {
           case "IT":
            return am4core.color("#D3DAD9"); 
           case "ADMIN":
            return am4core.color("#D3DAD9"); 
           case "MARKETING":
            return am4core.color("#D3DAD9"); 
           case "MERCHANDISING":
            return am4core.color("#D3DAD9"); 
           case "PURCHASING":
            return am4core.color("#D3DAD9"); 
           case "VISUAL":
            return am4core.color("#D3DAD9"); 
           case "HUMAN RESOURCES":
            return am4core.color("#D3DAD9"); 
           case "INVENTORY CONTROL GROUP":
            return am4core.color("#D3DAD9"); 
          case "ACCOUNTS PAYABLE":
            return am4core.color("#D3DAD9"); 
          case "SALES ACCOUNTING":
            return am4core.color("#D3DAD9");
          case "TREASURY":
            return am4core.color("#D3DAD9");  

          case "ACCOUNTS RECIEVABLE":
            return am4core.color("#D3DAD9");  

        
        
          case "PENDING":
            return am4core.color("#FF7A7A"); 
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
      }else{
      
      chart.data = generateChartData();
      chart.dataSource.url = "/data/chrtdashboard.php";
      }
      
    });


function hideSmall(ev) {
  if (ev.target.dataItem && (ev.target.dataItem.category === "IT" 
  || ev.target.dataItem.category === "ADMIN" 
  || ev.target.dataItem.category === "MARKETING"
  || ev.target.dataItem.category === "MERCHANDISING"
  || ev.target.dataItem.category === "PURCHASING"
  || ev.target.dataItem.category === "VISUAL"
  || ev.target.dataItem.category === "HUMAN RESOURCES"
  || ev.target.dataItem.category === "INVENTORY CONTROL GROUP"
  || ev.target.dataItem.category === "ACCOUNTS PAYABLE"
  || ev.target.dataItem.category === "SALES ACCOUNTING"
  || ev.target.dataItem.category === "TREASURY"
  || ev.target.dataItem.category === "ACCOUNTS RECIEVABLE"

  )) {
     ev.target.hide();
     
  }
  else {  
    ev.target.show();
    series.slices.template.events.on("over", function(){
  console.log("hover");
  
    
});


// override tooltipText so tooltipHTML is actually used
series.slices.template.tooltipHTML = "something...";
series.slices.template.adapter.add("tooltipHTML", function(tooltipHTML) {
  

  console.log("adapter");
  return tooltipHTML;
});


// Set innerRadius to make it a donut chart
chart.innerRadius = am4core.percent(50);


  }
}

});



    am4core.options.autoDispose = true;
    
    
    

    
  });

  
 
   
}






</script>

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

    //  Animation on load
    pieSeries.hiddenState.properties.opacity = 1;
    pieSeries.hiddenState.properties.endAngle = -90;
    pieSeries.hiddenState.properties.startAngle = -90;

    am4core.options.autoDispose = true;
  });
}


</script>

