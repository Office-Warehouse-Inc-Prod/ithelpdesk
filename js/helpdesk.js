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
    } else if ($(this).val() == "SUBJECT FOR CLOSING") {
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
    } else if ($(this).val() == "FOR PICKUP") {
      $("#tripticket1lbl").show();
      $("#tripticket1").show();
    } else if ($(this).val() == "FOR DELIVERY TO STORE") {
      $("#tripticket2lbl").show();
      $("#tripticket2").show();
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
  $("#sub").change(function () {
    if (
      $(this).val() == "75" ||
      $(this).val() == "76" ||
      $(this).val() == "77" ||
      $(this).val() == "78" ||
      $(this).val() == "79" ||
      $(this).val() == "80" ||
      $(this).val() == "81"
    ) {
      $(".hide_isp").show();
      $("#isp").attr("required", "true");
      document.getElementById("lbl_isp").className = "";
      document.getElementById("lbl_refNo").className = "";
      document.getElementById("lbl_DtRefNo").className = "";
      // document.getElementById("ico_cal3").className = '';
      // alert('data');
    } else {
      // alert('not 59');
      $(".hide_isp").hide();
      $("#date_closed").removeAttr("required");
      $("#date_closed").removeAttr("data-error");
      document.getElementById("lbl_isp").className = "hidden";
      document.getElementById("lbl_refNo").className = "hidden";
      document.getElementById("lbl_DtRefNo").className = "hidden";
      // document.getElementById("ico_cal3").className = 'hidden';
    }
  });
  $("#sub").trigger("change");
}

function unilayout_netshowmodalform() {
  if (
    $("#sub_num").val() == "75" ||
    $("#sub_num").val() == "76" ||
    $("#sub_num").val() == "77" ||
    $("#sub_num").val() == "78" ||
    $("#sub_num").val() == "79" ||
    $("#sub_num").val() == "80" ||
    $("#sub_num").val() == "81"
  ) {
    $(".hide_isp").show();
    $("#isp").attr("required", "true");
    document.getElementById("lbl_isp").className = "";
    document.getElementById("lbl_refNo").className = "";
    document.getElementById("lbl_DtRefNo").className = "";
  } else {
    $(".hide_isp").hide();
    $("#date_closed").removeAttr("required");
    $("#date_closed").removeAttr("data-error");
    document.getElementById("lbl_isp").className = "hidden";
    document.getElementById("lbl_refNo").className = "hidden";
    document.getElementById("lbl_DtRefNo").className = "hidden";
  }
}

function lrinout() {
  setTimeout(function () {
    $("body").addClass("loaded");
    // $('h1').css('color','#222222');
  }, 1500);
}

// function crd_btm() {
//   $("a[href='#bottom']").click(function () {
//     $("html, body").animate({ scrollTop: $(".second").offset().top }, "slow");
//     return false;
//   });
// }

// function admin_dttable() {
//   moment.updateLocale(moment.locale(), { invalidDate: "" }); //sets null value
//   // var d = new Date();
//   // var d = '2'
//   var dataTable = $("#report_data")
//     .removeAttr("width")
//     .DataTable({
//       dom: 'l<"toolbar">frtip',
//       fixedHeader: true,
//       responsive: true,
//       select: true,
//       order: [0],
//       search: {},
//       columnDefs: [
//         {
//           targets: [0, 10, 11],
//           orderable: false,
//           sortable: true,
//         },
//       ],
//       columnDefs: [
//         {
//           targets: [9, 10, 20],
//           width: "2%",
//           render: function (data, type, row) {
//             if (type === "display") {
//               if (data == "01/01/1970 08:00") {
//                 data = "UNRESOLVED";
//               } else if (data == "01/01/1970 01:00") {
//                 data = "UNRESOLVED";
//               } else if (data < 0) {
//                 data = "";
//               } else if (data == 0) {
//                 data = "Solve Immediately";
//               }
//             }
//             return data;
//           },
//         },
//       ],

//       rowCallback: function (row, data, index) {
//         if (data[5].toUpperCase() == "OPEN") {
//           $(row).find("td:eq(0)").css("color", "red");
//           $(row).find("td:eq(1)").css("color", "red");
//           $(row).find("td:eq(2)").css("color", "red");
//           $(row).find("td:eq(3)").css("color", "red");
//           $(row).find("td:eq(4)").css("color", "red");
//           $(row).find("td:eq(5)").css("color", "red");
//           $(row).find("td:eq(6)").css("color", "red");
//           $(row).find("td:eq(7)").css("color", "red");
//           $(row).find("td:eq(8)").css("color", "red");
//           $(row).find("td:eq(9)").css("color", "red");
//         } else if (data[5].toUpperCase() == "OPEN WITH FIX ASSET") {
//           $(row).find("td:eq(0)").css("color", "red");
//           $(row).find("td:eq(1)").css("color", "red");
//           $(row).find("td:eq(2)").css("color", "red");
//           $(row).find("td:eq(3)").css("color", "red");
//           $(row).find("td:eq(4)").css("color", "red");
//           $(row).find("td:eq(5)").css("color", "red");
//           $(row).find("td:eq(6)").css("color", "red");
//           $(row).find("td:eq(7)").css("color", "red");
//           $(row).find("td:eq(8)").css("color", "red");
//           $(row).find("td:eq(9)").css("color", "red");
//         } else if (data[5].toUpperCase() == "CLOSED") {
//           $(row).find("td:eq(0)").css("color", "green");
//           $(row).find("td:eq(1)").css("color", "green");
//           $(row).find("td:eq(2)").css("color", "green");
//           $(row).find("td:eq(3)").css("color", "green");
//           $(row).find("td:eq(4)").css("color", "green");
//           $(row).find("td:eq(5)").css("color", "green");
//           $(row).find("td:eq(6)").css("color", "green");
//           $(row).find("td:eq(7)").css("color", "green");
//           $(row).find("td:eq(8)").css("color", "green");
//           $(row).find("td:eq(9)").css("color", "green");
//           $(row).find("td:eq(10)").css("color", "green");
//           $(row).find("td:eq(11)").css("color", "green");
//           $(row).find("td:eq(12)").css("color", "green");
//           $(row).find("td:eq(13)").css("color", "green");
//         }
//       },
//     }); // close bracket main
// }

// function _insert_data() {
//   $(document).on("submit", "#report_form", function (e) {
//     // alert("1");
//     e.preventDefault();
//     var TicketNumber = $("#ticket_no").val();
//     var Store = $("#store").val();
//     var DateCreated = $("#date_created").val();
//     var Concern = $("#concern").val();
//     var Status = $("#status").val();
//     var Via = $("#via").val();
//     var ItSupport = $("#itsup").val();
//     var cat_id = $("#cat").val();
//     var sub_id = $("#sub").val();
//     var DateClosed = $("#date_closed").val();
//     var CloseBy = $("#close_by").val();
//     var remarks = $("#remarks").val();

//     var today = new Date();
//     DateCreated = new Date(DateCreated);
//     DateClosed = new Date(DateClosed);
//     if (DateCreated > today) {
//       alert("Invalid date");
//       return false;
//     }
//     else if (Status == 'OPEN')
//     {
//       if (DateClosed < DateCreated ){
//       alert("Date closed should be greater than date created!");
//       return false;
//     }
//     }

//     else if (DateClosed > today ){
//       alert("Invalid Closed_Date");
//       return false;
//     }

//     if (
//       Store != "" &&
//       DateCreated != "" &&
//       Concern != "" &&
//       Status != "" &&
//       Via != "" &&
//       ItSupport != "" &&
//       cat_id != "" &&
//       sub_id != ""
//     ) {
//       $.ajax({
//         url: "insert.php",
//         method: "POST",
//         data: new FormData(this),
//         contentType: false,
//         processData: false,
//         success: function (data) {
//           // alert(data);
//           // $("#report_form")[0].reset();
//           Swal.fire({
//              icon: 'success',
//              title: 'Your work has been saved',
//              showConfirmButton: false,
//              timer: 1500
//           });
//           $("#userModal").modal("hide");
//       //     setTimeout(function(){// wait for 5 secs(2)
//       //      location.reload(); // then reload the page.(3)
//       // }, 2000);
//         },
//       });
//     } else {
//       alert("All Fields are Required");
//     }
//   });
// }
