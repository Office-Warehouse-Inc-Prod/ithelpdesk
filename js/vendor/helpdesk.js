function pager(pg) {
  //var pgurl=;
  $.ajax({
    url: pg + ".php",
    // cache: false,
    method: "POST",
    success: function (data) {
      $("#container").html(data);
    },
    error: function () {
      alert("Error Occured");
    },
  });
}


function admin_hideshowforms() {
  let datetime = null,
    date = null;

  let update = function () {
    date = moment(new Date());
    datetime.html(date.format("dddd, MMMM Do YYYY, h:mm:ss a"));
  };
  datetime = $("#datetime");
  update();
  setInterval(update, 1000);

  let crndt = new Date();
  let rd = moment(crndt).format("MM/DD/YYYY HH:mm:ss");
  $("#date_closed").attr("value", rd);

  $("#status").change(function () {
    if ($(this).val() == "CLOSED") {
      $("#date_closed").show();
      $("#date_closed").val(rd);
      $("#close_by").show();
      $("#ico_cal").show();
      $("#cl_desc").show();
      $(".hide_cl").show();
      document.getElementById("dateclabel").className = "";
      document.getElementById("clby_label").className = "";
    } else if ($(this).val() == "PARTIALLY CLOSED") {
      $("#date_closed").show();
      $("#close_by").show();
      $("#ico_cal").show();
      document.getElementById("dateclabel").className = "";
      document.getElementById("clby_label").className = "";
    } else {
      $("#date_closed").hide();
      $("#date_closed").val("");
      $("#close_by").hide();
      $("#ico_cal").hide();
      $("#cl_desc").hide();
      $(".hide_cl").hide();
      $("#date_closed").removeAttr("required");
      $("#date_closed").removeAttr("data-error");
      document.getElementById("dateclabel").className = "hidden";
      document.getElementById("clby_label").className = "hidden";
    }
  });
  $("#status").trigger("change");
}

function wfithsforms() {
  $("#status").change(function () {
    if ($(this).val() == "CLOSED") {
      $("#ModalDate_close").show();
      $("#ModalCls_desc").show();
      document.getElementById("hidefrst").className = "input-group-text";
      document.getElementById("hidefrst2").className = "input-group-text";
    } else {
      $("#close_by").hide();
      $("#ModalCls_desc").hide();
      $("#ModalDate_close").hide();
      $("#ModalDate_close").removeAttr("required");
      $("#ModalDate_close").removeAttr("data-error");
      document.getElementById("hidefrst").className = "hidden";
      document.getElementById("hidefrst2").className = "hidden";
    }
  });
  $("#Modalstatus").trigger("change");
}

// function slct_itsup() {
//   $("#itsup").on("change", function () {
//     var itval = this.value;
//     // alert(subdesc);
//     $("#it_num").val(itval);
//   });
// }


function slct_isp() {
  $("#isp").on("change", function () {
    var ispval = this.value;
    // alert(subdesc);
    $("#isp_num").val(ispval);
  });
}


function gtsub_id() {
  $("#sub").on("change", function () {
    var subdesc = this.value;
    // alert(subdesc);
    $("#sub_num").val(subdesc);
  });
}

function slct_sub() {
  var $sub = $("#sub");
  if (!$sub.length) {
    return;
  }

  var lblIsp = document.getElementById("lbl_isp");
  var lblRefNo = document.getElementById("lbl_refNo");
  var lblDtRefNo = document.getElementById("lbl_DtRefNo");

  $sub.change(function () {
    var value = $(this).val();
    var needsIsp = ["75", "76", "77", "78", "79", "80", "81"].includes(value);

    if (needsIsp) {
      $(".hide_isp").show();
      $("#isp").attr("required", "true");
      if (lblIsp) lblIsp.className = "";
      if (lblRefNo) lblRefNo.className = "";
      if (lblDtRefNo) lblDtRefNo.className = "";
    } else {
      $(".hide_isp").hide();
      $("#date_closed").removeAttr("required");
      $("#date_closed").removeAttr("data-error");
      if (lblIsp) lblIsp.className = "hidden";
      if (lblRefNo) lblRefNo.className = "hidden";
      if (lblDtRefNo) lblDtRefNo.className = "hidden";
    }
  });

  $sub.trigger("change");
}

function unilayout_netshowmodalform(){
  var lblIsp = document.getElementById("lbl_isp");
  var lblRefNo = document.getElementById("lbl_refNo");
  var lblDtRefNo = document.getElementById("lbl_DtRefNo");
  var subNum = $("#sub_num");

  if (!subNum.length) {
    return;
  }

  var needsIsp = ["75", "76", "77", "78", "79", "80", "81"].includes(subNum.val());

  if (needsIsp) {
      $(".hide_isp").show();
      $("#isp").attr("required", "true");
      if (lblIsp) lblIsp.className = "";
      if (lblRefNo) lblRefNo.className = "";
      if (lblDtRefNo) lblDtRefNo.className = "";
  }
  else{
     $(".hide_isp").hide();
      $("#date_closed").removeAttr("required");
      $("#date_closed").removeAttr("data-error");
      if (lblIsp) lblIsp.className = "hidden";
      if (lblRefNo) lblRefNo.className = "hidden";
      if (lblDtRefNo) lblDtRefNo.className = "hidden";
  }
}

function lrinout() {
  setTimeout(function () {
    $("body").addClass("loaded");
    // $('h1').css('color','#222222');
  }, 1500);
}

function crd_btm() {
  $("a[href='#bottom']").click(function () {
    $("html, body").animate({ scrollTop: $(".second").offset().top }, "slow");
    return false;
  });
}

function admin_dttable() {
  moment.updateLocale(moment.locale(), { invalidDate: "" }); //sets null value
  // var d = new Date();
  // var d = '2'
  var dataTable = $("#report_data")
    .removeAttr("width")
    .DataTable({
      dom: 'l<"toolbar">frtip',
      fixedHeader: true,
      responsive: true,
      select: true,
      order: [0],
      search: {},
      columnDefs: [
        {
          targets: [0, 10, 11],
          orderable: false,
          sortable: true,
        },
      ],
      columnDefs: [
        {
          targets: [9, 10, 20],
          width: "2%",
          render: function (data, type, row) {
            if (type === "display") {
              if (data == "01/01/1970 08:00") {
                data = "UNRESOLVED";
              } else if (data == "01/01/1970 01:00") {
                data = "UNRESOLVED";
              } else if (data < 0) {
                data = "";
              } else if (data == 0) {
                data = "Solve Immediately";
              }
            }
            return data;
          },
        },
      ],

      rowCallback: function (row, data, index) {
        if (data[5].toUpperCase() == "OPEN") {
          $(row).find("td:eq(0)").css("color", "red");
          $(row).find("td:eq(1)").css("color", "red");
          $(row).find("td:eq(2)").css("color", "red");
          $(row).find("td:eq(3)").css("color", "red");
          $(row).find("td:eq(4)").css("color", "red");
          $(row).find("td:eq(5)").css("color", "red");
          $(row).find("td:eq(6)").css("color", "red");
          $(row).find("td:eq(7)").css("color", "red");
          $(row).find("td:eq(8)").css("color", "red");
          $(row).find("td:eq(9)").css("color", "red");
        } else if (data[5].toUpperCase() == "OPEN WITH FIX ASSET") {
          $(row).find("td:eq(0)").css("color", "red");
          $(row).find("td:eq(1)").css("color", "red");
          $(row).find("td:eq(2)").css("color", "red");
          $(row).find("td:eq(3)").css("color", "red");
          $(row).find("td:eq(4)").css("color", "red");
          $(row).find("td:eq(5)").css("color", "red");
          $(row).find("td:eq(6)").css("color", "red");
          $(row).find("td:eq(7)").css("color", "red");
          $(row).find("td:eq(8)").css("color", "red");
          $(row).find("td:eq(9)").css("color", "red");
        } else if (data[5].toUpperCase() == "CLOSED") {
          $(row).find("td:eq(0)").css("color", "green");
          $(row).find("td:eq(1)").css("color", "green");
          $(row).find("td:eq(2)").css("color", "green");
          $(row).find("td:eq(3)").css("color", "green");
          $(row).find("td:eq(4)").css("color", "green");
          $(row).find("td:eq(5)").css("color", "green");
          $(row).find("td:eq(6)").css("color", "green");
          $(row).find("td:eq(7)").css("color", "green");
          $(row).find("td:eq(8)").css("color", "green");
          $(row).find("td:eq(9)").css("color", "green");
          $(row).find("td:eq(10)").css("color", "green");
          $(row).find("td:eq(11)").css("color", "green");
          $(row).find("td:eq(12)").css("color", "green");
          $(row).find("td:eq(13)").css("color", "green");
        }
      },
    }); // close bracket main
}




