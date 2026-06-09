
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
 function grhp(types) { // dept graph
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
    
// pieSeries.slices.template.events.on("hit", function(ev) {

//     if (!ev.target.dataItem) {
//         console.log("Walang laman");
//         return false;
//     }

//     let dept_id = ev.target.dataItem.dept_id;

//     console.log("Clicked Type:", dept_id);

//     selected = ev.target.dataItem.index;

//     chart.data = generateChartData();

//     _deptgraph(dept_id);

// }, this);

pieSeries.slices.template.events.on("hit", function(ev) {

    if (!ev.target.dataItem) {
        console.log("Walang laman");
        return false;
    }

    let dept_id = ev.target.dataItem.dataContext.dept_id;

    console.log("Clicked Dept ID:", dept_id);

    selected = ev.target.dataItem.index;
    chart.data = generateChartData();

    _deptgraph(dept_id);
    _loadDeptBreakdownTable(dept_id);

}, this);

var deptBreakTable;

/**
 *  loaddeptbreakdowntable.
 */
function _loadDeptBreakdownTable(dept_id) {

    if ($.fn.DataTable.isDataTable('#tbl_deptbreak')) {
        $('#tbl_deptbreak').DataTable().clear().destroy();
        $('#tbl_deptbreak').empty();
    }

    deptBreakTable = $("#tbl_deptbreak").DataTable({
        dom:
            "<'dt-top d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2'" +
                "<'dt-left d-flex align-items-center gap-2'l<f>>" +
                "<'dt-right d-flex align-items-center gap-2'B>" +
            ">" +
            "<'dt-table'rt>" +
            "<'dt-bottom d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2'ip>",

        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> <span class="d-none d-md-inline">Export</span>',
                attr: {
                    title: 'Export to Excel',
                    class: 'btn btn-success btn-sm rounded-pill px-3 shadow-sm'
                }
            }
        ],

        ajax: {
            url: "fetchdata/fetch_data.php",
            type: "POST",
            data: {
                mode: "dept_ticket_datatable",
                dept_id: dept_id
            },
            dataSrc: ""
        },

        pagingType: "simple_numbers",
        bDestroy: true,
        responsive: {
            details: {
                type: 'inline',
                target: 'tr'
            }
        },

        lengthChange: false,
        autoWidth: false,
        scrollX: false,
        ordering: true,
        pageLength: 10,

        language: {
            search: "",
            searchPlaceholder: "Search tickets…",
            zeroRecords: "No matching tickets found",
            info: "Showing _START_ to _END_ of _TOTAL_ tickets",
            infoEmpty: "No tickets to show"
        },

        order: [[2, "desc"]],

        columns: [
            {
                title: "Ticket No",
                data: "ticket_no",
                defaultContent: ""
            },
            {
                title: "Store Code",
                data: "str_code",
                defaultContent: ""
            },
            {
                title: "Date Created",
                data: "date_created",
                defaultContent: "",
                render: function(data, type, row) {
                    if (!data) return "";

                    if (type === 'sort' || type === 'type') {
                        let parts = data.split(" ");
                        let date = parts[0].split("/");
                        let time = parts[1] || "00:00";

                        return date[2] + "-" + date[0] + "-" + date[1] + " " + time;
                    }

                    return data;
                }
            },
            {
                title: "Concern",
                data: "concern",
                defaultContent: "",
                className: "dept-concern",
                render: function(data, type, row) {
                    if (type !== 'display') return data;
                    if (!data) return "";

                    const txt = String(data);
                    return txt.length > 120 ? txt.slice(0, 120) + "…" : txt;
                }
            },
            {
                title: "Via",
                data: "via",
                defaultContent: ""
            },
            {
                title: "Status",
                data: "status",
                defaultContent: "",
                render: function(data, type, row) {
                    if (type !== 'display') return data;

                    const s = (data || "").toUpperCase();
                    let cls = "badge bg-secondary text-white";

                    if (
                        s === "CLOSED" ||
                        s === "CLOSE" ||
                        s === "RESOLVED" ||
                        s === "SUBJECT FOR CLOSING"
                    ) {
                        cls = "badge bg-success text-white";
                    } else if (
                        s === "ON PROCESS" ||
                        s === "IN PROCESS" ||
                        s === "IN PROGRESS" ||
                        s.indexOf("ATTENDED") !== -1
                    ) {
                        cls = "badge bg-warning text-dark";
                    } else if (
                        s === "PENDING" ||
                        s.indexOf("PENDING") !== -1
                    ) {
                        cls = "badge bg-danger text-white";
                    } else if (
                        s === "NEW REPORT" ||
                        s === "NEW"
                    ) {
                        cls = "badge bg-purple text-white";
                    }

                    return `<span class="${cls} px-2 py-1">${data}</span>`;
                }
            },
            {
                title: "DTDF",
                data: "dtdf",
                defaultContent: "",
                responsivePriority: 10001
            },
            {
                title: "Category",
                data: "category",
                defaultContent: "",
                responsivePriority: 10002
            },
            {
                title: "Sub Category",
                data: "sub_category",
                defaultContent: "",
                responsivePriority: 10003
            },
            {
                title: "Date Closed",
                data: "date_closed",
                defaultContent: "",
                responsivePriority: 10004,
                render: function(data, type, row) {
                    if (type !== 'display') return data;

                    if (!data) return "";
                    if (data === "01/01/1970 01:00" || data === "01/01/1970 08:00") return "";

                    return data;
                }
            },
            {
                title: "Remarks",
                data: "remarks",
                defaultContent: "",
                responsivePriority: 10005,
                render: function(data, type, row) {
                    if (type !== 'display') return data;
                    if (!data) return "";

                    const txt = String(data);
                    return txt.length > 80 ? txt.slice(0, 80) + "…" : txt;
                }
            }
        ],

        rowCallback: function(row, data) {
            $(row).removeClass('status-row-pending status-row-closed status-row-process status-row-new');

            const s = (data.status || "").toUpperCase();

            if (s === "CLOSED" || s === "CLOSE" || s === "RESOLVED") {
                $(row).addClass('status-row-closed');
            } else if (s === "ON PROCESS" || s === "IN PROGRESS" || s.indexOf("ATTENDED") !== -1) {
                $(row).addClass('status-row-process');
            } else if (s.indexOf("PENDING") !== -1) {
                $(row).addClass('status-row-pending');
            } else if (s === "NEW REPORT" || s === "NEW") {
                $(row).addClass('status-row-new');
            }
        }
    });

    $('#dept_graph_modal').modal({
        show: true,
        backdrop: 'static'
    });

    $('#dept_graph_modal').on('shown.bs.modal', function () {
        if ($.fn.DataTable.isDataTable('#tbl_deptbreak')) {
            $('#tbl_deptbreak').DataTable().columns.adjust().responsive.recalc();
        }
    });
}
// end am4core.ready()

function _deptgraph(dept_id){
  $.ajax({
    url:"fetchdata/fetch_data.php",
    method:'POST',
    data:{mode:'str_grph',dept_id:dept_id},
    success:function(fdata){
      var objstorearea = JSON.parse(fdata);

      _plot_dept_graph(objstorearea);

      $('#dept_graph_modal').modal({
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
#dept_graph {
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
 *  plot dept graph.
 */
function _plot_dept_graph(strdata){

  am4core.ready(function() {

    // Themes begin
    am4core.useTheme(am4themes_animated);

    // Create chart instance
    var chart = am4core.create("dept_graph", am4charts.XYChart);
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
#chartdiv_category { 
/*   margin-top: 2px;
  margin-left: 18px;*/
  width: 100%;
  height:400px;
}

</style>

<!-- Chart code -->
<script>
const curdates = new Date();
const curyrs = curdates.getFullYear();

let overallChart = null;

// Initial load
_overallpie(curyrs, $('#dept_id').val());



/**
 *  overallpie.
 */
function _overallpie(curyrs, dept_id) {
    $.ajax({
        url: "fetchdata/fetch_data.php",
        method: "POST",
        data: {
            yr: curyrs,
            dept_id: dept_id,
            mode: "overallgrph"
        },
        success: function (data5) {
            try {
                var obj5 = JSON.parse(data5);
                _plotovpie(obj5);
            } catch (e) {
                console.log("JSON Parse Error:", e);
                console.log("Returned Data:", data5);
            }
        }
    });
}

let categoryChart = null;

/**
 *  plotovpie.
 */
function _plotovpie(grphdata) {
    am4core.ready(function () {
        am4core.useTheme(am4themes_animated);

        // Dispose old chart before creating new chart
        if (overallChart) {
            overallChart.dispose();
        }

        // Create chart
        var chart = am4core.create("chartdiv1", am4charts.PieChart);
        overallChart = chart;

        chart.innerRadius = am4core.percent(40);
        chart.fontFamily = "Segoe UI, Roboto, sans-serif";
        chart.background.fill = am4core.color("#f8faff");

        chart.data = grphdata;
if (grphdata && grphdata.length > 0) {
    let defaultStatus = grphdata[1].stat_name;
    let selectedYear = $("#yearpicker").val() || curyrs;
    let selectedDept = $("#dept_id").val();

    $("#selected_status_title").html(" - " + defaultStatus);

    _categorypie(selectedYear, selectedDept, defaultStatus);
}
        // Legend styling
        chart.legend = new am4charts.Legend();
        chart.legend.position = "bottom";
        chart.legend.valign = "bottom";
        chart.legend.labels.template.fill = am4core.color("#444");
        chart.legend.labels.template.fontSize = 12;
        chart.legend.labels.template.text = "[bold {color}]{name}[/]";

        // Series
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

pieSeries.slices.template.events.on("hit", function (ev) {
    let status = ev.target.dataItem.category;
    let yr = $("#yearpicker").val() || curyrs;
    let dept_id = $("#dept_id").val();

    $("#selected_status_title").html(" - " + status);

    _categorypie(yr, dept_id, status);
});


        // Glow effect
        let shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter());
        shadow.blur = 6;
        shadow.color = am4core.color("#999");
        shadow.opacity = 0.4;

        // Hover animation
        let hs = pieSeries.slices.template.states.create("hover");
        hs.properties.scale = 1.08;
        hs.properties.shiftRadius = 0.03;

        // Custom colors
        pieSeries.slices.template.adapter.add("fill", function (fill, target) {
            if (target.dataItem) {
                let category = String(target.dataItem.category).toUpperCase();

                switch (category) {
                    case "PENDING":
                        return am4core.color("#FF7A7A");

                    case "ON PROCESS":
                        return am4core.color("#F2A65A");

                    case "CLOSED":
                        return am4core.color("#578f63");

                    case "SUBJECT FOR CLOSING":
                        return am4core.color("#b667eb");

                    case "ASSIGNED":
                        return am4core.color("#9EC9F7");

                    default:
                        return am4core.color("#9EC9F7");
                }
            }

            return fill;
        });

        // Animation on load
        pieSeries.hiddenState.properties.opacity = 1;
        pieSeries.hiddenState.properties.endAngle = -90;
        pieSeries.hiddenState.properties.startAngle = -90;
        
    });
}

// all load

function _categorypie_all(yr, dept_id) {
    $.ajax({
        url: "fetchdata/fetch_data.php",
        method: "POST",
        dataType: "json",
        data: {
            yr: yr,
            dept_id: dept_id,
            mode: "category_all_grph"
        },
        success: function (data) {
            $("#selected_status_title").html(" - All Status");
            _plotcategorypie(data);
        },
        error: function (xhr) {
            console.log("Category All AJAX Error:");
            console.log(xhr.responseText);
        }
    });
} 

// end of code

var selectedCategoryStatus = "";

/**
 *  categorypie.
 */
function _categorypie(yr, dept_id, status) {
    selectedCategoryStatus = status;

    $.ajax({
        url: "fetchdata/fetch_data.php",
        method: "POST",
        data: {
            yr: yr,
            dept_id: dept_id,
            status: status,
            mode: "category_status_grph"
        },
        success: function (data) {
            try {
                let objcat = JSON.parse(data);
                console.log(objcat);
                _plotcategorypie(objcat);
            } catch (e) {
                console.log("JSON Parse Error:", e);
                console.log("Returned Data:", data);
            }
        }
    });
}

/**
 *  plotcategorypie.
 */
function _plotcategorypie(grphdata) {
    am4core.ready(function () {
        am4core.useTheme(am4themes_animated);

        if (categoryChart) {
            categoryChart.dispose();
        }

        let chart = am4core.create("chartdiv_category", am4charts.PieChart);
        categoryChart = chart;

        chart.innerRadius = am4core.percent(35);
        chart.fontFamily = "Segoe UI, Roboto, sans-serif";
        chart.background.fill = am4core.color("#f8faff");

        chart.data = grphdata;

        chart.legend = new am4charts.Legend();
        chart.legend.position = "bottom";
        chart.legend.valign = "bottom";
        chart.legend.labels.template.fill = am4core.color("#444");
        chart.legend.labels.template.fontSize = 12;

        let pieSeries = chart.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "points";
        pieSeries.dataFields.category = "cat_desc";

        pieSeries.labels.template.maxWidth = 150;
        pieSeries.labels.template.wrap = true;
        pieSeries.labels.template.fontSize = 12;
        pieSeries.labels.template.fill = am4core.color("#444");

        pieSeries.labels.template.text =
            "[bold]{category}[/]\n{value.value} Reports ({value.percent.formatNumber('.##')}%)";

        pieSeries.slices.template.tooltipText =
            "{category}: {value.value} Reports ({value.percent.formatNumber('.##')}%)";

pieSeries.slices.template.events.on("hit", function (ev) {
    let row = ev.target.dataItem.dataContext;

    let yr = $("#yearpicker").val() || curyrs;
    let dept_id = $("#dept_id").val();
    let status = selectedCategoryStatus;
    let cat_desc = row.cat_desc;

    $("#categoryTicketModalLabel").html(
        "Ticket Details - " + cat_desc + " / " + status
    );

    _category_ticket_dt(yr, dept_id, status, cat_desc);

    $("#category_ticket_modal").modal({
        show: true,
        backdrop: "static"
    });
});


        let shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter());
        shadow.blur = 6;
        shadow.color = am4core.color("#999");
        shadow.opacity = 0.4;

        let hs = pieSeries.slices.template.states.create("hover");
        hs.properties.scale = 1.08;
        hs.properties.shiftRadius = 0.03;

        pieSeries.slices.template.adapter.add("fill", function (fill, target) {
            if (target.dataItem && target.dataItem.dataContext.clr) {
                return am4core.color(target.dataItem.dataContext.clr);
            }

            return fill;
        });

        pieSeries.hiddenState.properties.opacity = 1;
        pieSeries.hiddenState.properties.endAngle = -90;
        pieSeries.hiddenState.properties.startAngle = -90;
    });
}


/**
 *  category ticket dt.
 */
function _category_ticket_dt(yr, dept_id, status, cat_desc) {
    $.ajax({
        url: "fetchdata/fetch_data.php",
        method: "POST",
        data: {
            yr: yr,
            dept_id: dept_id,
            status: status,
            cat_desc: cat_desc,
            mode: "category_ticket_dt"
        },
        success: function (data) {
            try {
                let obj = JSON.parse(data);
                console.log(obj);
                category_ticket_datatable(obj);
            } catch (e) {
                console.log("Category Ticket JSON Parse Error:", e);
                console.log("Returned Data:", data);
            }
        }
    });
}

/**
 * Format date time.
 */
function formatDateTime(value) {
    if (!value || value === "0000-00-00 00:00:00" || value === "null") {
        return "";
    }

    // Remove microseconds if meron
    let cleanValue = String(value).split(".")[0];

    // Convert MySQL datetime to JS-compatible format
    let date = new Date(cleanValue.replace(" ", "T"));

    if (isNaN(date.getTime())) {
        return value;
    }

    return date.toLocaleString("en-US", {
        year: "numeric",
        month: "short",
        day: "2-digit",
        hour: "numeric",
        minute: "2-digit",
        hour12: true
    });
}

/**
 * Category ticket datatable.
 */
function category_ticket_datatable(data) {
    if ($.fn.DataTable.isDataTable("#tbl_category_tickets")) {
        $("#tbl_category_tickets").DataTable().clear().destroy();
    }

    $("#tbl_category_tickets").DataTable({
        data: data,
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        order: [[2, "desc"]],
      columns: [
            { data: "ticket_no" },
            { data: "str_code" },
          {
    data: "date_created",
    render: function (data, type, row) {
        if (type !== "display") {
            return data;
        }

        return formatDateTime(data);
    }
},
            { data: "concern" },
            { data: "via" },

            {
                data: "status",
                className: "text-center",
                render: function (data, type, row) {
                    if (type !== "display") {
                        return data;
                    }

                    let statusRaw = data || "";
                    let status = String(statusRaw).trim().toUpperCase();

                    let badgeClass = "status-default";

                    if (status === "ASSIGNED") {
                        badgeClass = "status-assigned";
                    } else if (status === "ON PROCESS") {
                        badgeClass = "status-onprocess";
                    } else if (status === "PENDING") {
                        badgeClass = "status-pending";
                    } else if (status === "SUBJECT FOR CLOSING") {
                        badgeClass = "status-subject";
                    } else if (status === "CLOSED") {
                        badgeClass = "status-closed";
                    } else if (status === "TRANSFERRED") {
                        badgeClass = "status-transferred";
                    }

                    return '<span class="dt-status-badge ' + badgeClass + '">' + statusRaw + '</span>';
                }
            },

        
            {
                data: "dtdf",
                className: "text-center",
                render: function (data, type, row) {
                    let status = row.status ? String(row.status).trim().toUpperCase() : "";

                    if (status === "CLOSED") {
                        return 0;
                    }

                    return data;
                }
            },

            { data: "dept_desc" },
            { data: "category" },
            { data: "sub_category" },
       {
    data: "date_closed",
    render: function (data, type, row) {
        if (type !== "display") {
            return data;
        }

        return formatDateTime(data);
    }
},
            { data: "remarks" }
        ]
    });
}

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

/**
 *  areagraph.
 */
function _areagraph(curyrz) {
    $.ajax({
        url: "fetchdata/fetch_data.php",
        method: "POST",
        data: {
            yr: curyrz,
            dept_id: $('#dept_id').val(),
            mode: "area_grph"
        },
        success: function (data) {
            try {
                var objarea = JSON.parse(data);
                _plotareagrph(objarea);
            } catch (e) {
                console.log("JSON Parse Error:", e);
                console.log("Returned Data:", data);
            }
        }
    });
}
var areaChart = null;

/**
 *  plotareagrph.
 */
function _plotareagrph(grphdata) {
    am4core.ready(function () {
        am4core.useTheme(am4themes_animated);

        if (areaChart) {
            areaChart.dispose();
        }

        var chart = am4core.create("chart_area", am4charts.XYChart);
        areaChart = chart;

        chart.fontFamily = "Segoe UI, Roboto, sans-serif";
        chart.data = grphdata;

        chart.legend = new am4charts.Legend();
        chart.legend.position = "bottom";
        chart.legend.labels.template.fontSize = 12;

        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "area_desc";
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.minGridDistance = 30;

        categoryAxis.renderer.labels.template.adapter.add("dy", function (dy, target) {
            if (target.dataItem && target.dataItem.index & 2 == 2) {
                return dy + 25;
            }
            return dy;
        });

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.min = 0;
        valueAxis.title.text = "No. of Reports";
        valueAxis.title.fontWeight = "600";

        /**
         * Create series.
         */
        function createSeries(field, name, color, widthPercent, opacity) {
            let hasData = chart.data.some(function (row) {
                return Number(row[field]) > 0;
            });

            // If buong series ay zero, huwag na gumawa ng legend/bar
            if (!hasData) {
                return null;
            }

            var series = chart.series.push(new am4charts.ColumnSeries());

            series.dataFields.valueY = field;
            series.dataFields.categoryX = "area_desc";
            series.name = name;
            series.clustered = false;

            series.columns.template.width = am4core.percent(widthPercent);
            series.columns.template.fill = am4core.color(color);
            series.columns.template.stroke = am4core.color(color);
            series.columns.template.fillOpacity = opacity;
            series.columns.template.strokeOpacity = 1;

            // Tooltip
            series.columns.template.tooltipText =
                "[bold]{categoryX}[/]\n" + name + ": [bold]{valueY}[/] Reports";

            // Hide tooltip kapag zero yung specific bar
            series.columns.template.adapter.add("tooltipText", function (text, target) {
                if (target.dataItem && Number(target.dataItem.valueY) <= 0) {
                    return "";
                }
                return text;
            });

            // Hide zero-value columns
            series.columns.template.adapter.add("visible", function (visible, target) {
                if (target.dataItem && Number(target.dataItem.valueY) <= 0) {
                    return false;
                }
                return visible;
            });

            // Clean tooltip style
            series.tooltip.getFillFromObject = false;
            series.tooltip.background.fill = am4core.color("#ffffff");
            series.tooltip.background.stroke = am4core.color(color);
            series.tooltip.background.strokeWidth = 2;
            series.tooltip.label.fill = am4core.color("#000000");
            series.tooltip.label.fontSize = 13;
            series.tooltip.pointerOrientation = "vertical";

            series.columns.template.cursorOverStyle = am4core.MouseCursorStyle.pointer;

            series.columns.template.events.on("hit", function (ev) {
                let s_area = ev.target.dataItem.dataContext["area_desc"];
                let syr = ev.target.dataItem.dataContext["dc"];

                _storegraph(s_area, syr);
            });

            return series;
        }

        // Back to front layering
        createSeries("cntarea", "TOTAL", "#b8c2cc", 90, 0.25);
        createSeries("closed", "CLOSED", "#578f63", 75, 0.85);
        createSeries("pending", "PENDING", "#FF7A7A", 62, 0.85);
        createSeries("on_process", "ON PROCESS", "#F2A65A", 50, 0.9);
        createSeries("subject_for_closing", "SUBJECT FOR CLOSING", "#b667eb", 38, 0.9);
        createSeries("assigned", "ASSIGNED", "#9EC9F7", 26, 0.95);

        // IMPORTANT:
        // Do not add XYCursor here.
        // It causes multiple tooltips on layered/overlapping columns.
    });
}


/**
 *  storegraph.
 */
function _storegraph(s_area, syr) {
    $.ajax({
        url: "fetchdata/fetch_data.php",
        method: "POST",
        data: {
            area_desc: s_area,
            yr: syr,
            dept_id: $('#dept_id').val(),
            mode: 'str_grphnew'
        },
        success: function (fdata) {
            try {
                var objstorearea = JSON.parse(fdata);
                console.log(objstorearea);

                $("#store_ticket_section").hide();

                if ($.fn.DataTable.isDataTable("#tbl_store_tickets")) {
                    $("#tbl_store_tickets").DataTable().clear().destroy();
                }

                $('#store_graph_modal').modal({
                    show: true,
                    backdrop: 'static'
                });

                setTimeout(function () {
                    _plot_store_graph(objstorearea);
                }, 300);

            } catch (e) {
                console.log("JSON Parse Error:", e);
                console.log("Returned Data:", fdata);
            }
        }
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

<!-- Chart code -->
<script>


var storeChart = null;

/**
 *  plot store graph.
 */
function _plot_store_graph(strdata) {
    am4core.ready(function () {
        am4core.useTheme(am4themes_animated);

        if (storeChart) {
            storeChart.dispose();
        }

        var chart = am4core.create("store_graph", am4charts.XYChart);
        storeChart = chart;

        chart.fontFamily = "Segoe UI, Roboto, sans-serif";
        chart.data = strdata;

        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "str_code";
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.minGridDistance = 25;
        categoryAxis.renderer.labels.template.rotation = 0;
        categoryAxis.renderer.labels.template.fontSize = 12;

        categoryAxis.renderer.labels.template.adapter.add("dy", function (dy, target) {
            if (target.dataItem && target.dataItem.index & 2 == 2) {
                return dy + 18;
            }
            return dy;
        });

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.min = 0;
        valueAxis.title.text = "No. of Reports";
        valueAxis.title.fontWeight = "600";

        chart.legend = new am4charts.Legend();
        chart.legend.position = "bottom";
        chart.legend.labels.template.fontSize = 12;

        /**
         * Create series.
         */
        function createSeries(field, name, color, widthPercent, opacity) {
            let hasData = chart.data.some(function (row) {
                return Number(row[field]) > 0;
            });

            if (!hasData) {
                return null;
            }

            var series = chart.series.push(new am4charts.ColumnSeries());

            series.dataFields.valueY = field;
            series.dataFields.categoryX = "str_code";
            series.name = name;
            series.clustered = false;

            series.columns.template.width = am4core.percent(widthPercent);
            series.columns.template.fill = am4core.color(color);
            series.columns.template.stroke = am4core.color(color);
            series.columns.template.fillOpacity = opacity;
            series.columns.template.strokeOpacity = 1;

            series.columns.template.tooltipText =
                "[bold]{categoryX}[/]\n" + name + ": [bold]{valueY}[/] Reports";

            series.columns.template.adapter.add("tooltipText", function (text, target) {
                if (target.dataItem && Number(target.dataItem.valueY) <= 0) {
                    return "";
                }
                return text;
            });

            series.columns.template.adapter.add("visible", function (visible, target) {
                if (target.dataItem && Number(target.dataItem.valueY) <= 0) {
                    return false;
                }
                return visible;
            });

            series.tooltip.getFillFromObject = false;
            series.tooltip.background.fill = am4core.color("#ffffff");
            series.tooltip.background.stroke = am4core.color(color);
            series.tooltip.background.strokeWidth = 2;
            series.tooltip.label.fill = am4core.color("#000000");
            series.tooltip.label.fontSize = 13;
            series.tooltip.pointerOrientation = "vertical";

            series.columns.template.cursorOverStyle = am4core.MouseCursorStyle.pointer;

            series.columns.template.events.on("hit", function (ev) {
                let row = ev.target.dataItem.dataContext;

                let store = row.store;
                let str_code = row.str_code;
                let yr = row.dc || $("#yearpicker").val() || curyrs;
                let dept_id = $("#dept_id").val();

                $("#store_ticket_section").show();
                $("#storeTicketTableTitle").html("Store Ticket Details - " + str_code);

                _store_ticket_dt(yr, dept_id, store);
            });

            return series;
        }
// Layer order: biggest/back layer first, smaller/front layer after
createSeries("cnt_ttl", "TOTAL", "#b8c2cc", 90, 0.35);
createSeries("closed", "CLOSED", "#578f63", 75, 0.85);
createSeries("pending", "PENDING", "#FF7A7A", 62, 0.85);
createSeries("on_process", "ON PROCESS", "#F2A65A", 50, 0.9);
createSeries("subject_for_closing", "SUBJECT FOR CLOSING", "#b667eb", 38, 0.9);
createSeries("assigned", "ASSIGNED", "#9EC9F7", 26, 0.95);

        // No XYCursor to avoid overlapping tooltips.
    });
}

/**
 *  store ticket dt.
 */
function _store_ticket_dt(yr, dept_id, store) {
    $.ajax({
        url: "fetchdata/fetch_data.php",
        method: "POST",
        data: {
            yr: yr,
            dept_id: dept_id,
            store: store,
            mode: "store_ticket_dt"
        },
        success: function (data) {
            try {
                let obj = JSON.parse(data);
                console.log(obj);
                store_ticket_datatable(obj);
            } catch (e) {
                console.log("Store Ticket JSON Parse Error:", e);
                console.log("Returned Data:", data);
            }
        }
    });
}

/**
 * Format date time.
 */
function formatDateTime(value) {
    if (!value || value === "0000-00-00 00:00:00" || value === "null") {
        return "";
    }

    // Remove microseconds if meron
    let cleanValue = String(value).split(".")[0];

    // Convert MySQL datetime to JS-compatible format
    let date = new Date(cleanValue.replace(" ", "T"));

    if (isNaN(date.getTime())) {
        return value;
    }

    return date.toLocaleString("en-US", {
        year: "numeric",
        month: "short",
        day: "2-digit",
        hour: "numeric",
        minute: "2-digit",
        hour12: true
    });
}


/**
 * Store ticket datatable.
 */
function store_ticket_datatable(data) {
    if ($.fn.DataTable.isDataTable("#tbl_store_tickets")) {
        $("#tbl_store_tickets").DataTable().clear().destroy();
    }

    $("#tbl_store_tickets").DataTable({
        data: data,
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        order: [[2, "desc"]],
        columns: [
            { data: "ticket_no" },
            { data: "str_code" },
          {
    data: "date_created",
    render: function (data, type, row) {
        if (type !== "display") {
            return data;
        }

        return formatDateTime(data);
    }
},
            { data: "concern" },
            { data: "via" },

            {
                data: "status",
                className: "text-center",
                render: function (data, type, row) {
                    if (type !== "display") {
                        return data;
                    }

                    let statusRaw = data || "";
                    let status = String(statusRaw).trim().toUpperCase();

                    let badgeClass = "status-default";

                    if (status === "ASSIGNED") {
                        badgeClass = "status-assigned";
                    } else if (status === "ON PROCESS") {
                        badgeClass = "status-onprocess";
                    } else if (status === "PENDING") {
                        badgeClass = "status-pending";
                    } else if (status === "SUBJECT FOR CLOSING") {
                        badgeClass = "status-subject";
                    } else if (status === "CLOSED") {
                        badgeClass = "status-closed";
                    } else if (status === "TRANSFERRED") {
                        badgeClass = "status-transferred";
                    }

                    return '<span class="dt-status-badge ' + badgeClass + '">' + statusRaw + '</span>';
                }
            },

        
            {
                data: "dtdf",
                className: "text-center",
                render: function (data, type, row) {
                    let status = row.status ? String(row.status).trim().toUpperCase() : "";

                    if (status === "CLOSED") {
                        return 0;
                    }

                    return data;
                }
            },

            { data: "dept_desc" },
            { data: "category" },
            { data: "sub_category" },
       {
    data: "date_closed",
    render: function (data, type, row) {
        if (type !== "display") {
            return data;
        }

        return formatDateTime(data);
    }
},
            { data: "remarks" }
        ]
    });
}

</script>