
<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    
  <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
<link rel="stylesheet" href="../css/4bootstrap.min.css" />
<script src="../js/moment.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://use.fontawesome.com/f942a1dc17.js"></script>

<style>

body{

  background-color: #FFC108;

}

.card{

background-color: #212529;

}


hr {
        position: relative;
        border: none;
        height: 12px;
        background: black;
        margin-bottom: 50px;
    }


    

</style>
<div class="pv_header justify-content-center">
    <div class="col-md-12 text-center">
    <h3>PRICE VERIFIER</h3>

    </div>
</div>
<hr>






<div class="col-md-12 col-sm-12 col-12 ">
<div class="card">
    
<div class="container">

        <div class="col-md-12">
            <input style="font-size:50px;" type="text" name="pr_vr" id="pr_vr" class="numbers form form-control text-center input-lg">
            <input type="hidden" name="SBS_NO" id="SBS_NO" value="<?php echo $_SESSION['SBS_NO']; ?>">
           <input type="hidden" name="PRICE_LVL" id="PRICE_LVL" value="<?php echo $_SESSION['PRICE_LVL']; ?>">
           <div class="text-center mt-3">
    <button type="button" id="btnStartScan" class="btn btn-warning btn-lg">
        <i class="fa fa-camera"></i> Scan Barcode
    </button>

    <button type="button" id="btnStopScan" class="btn btn-danger btn-lg" style="display:none;">
        Stop Scan
    </button>
</div>

<div class="text-center mt-3">
    <video id="barcodePreview" style="width:100%; max-width:500px; display:none; border:5px solid #FFC108;" autoplay muted playsinline></video>
</div>
        </div>

    </div>

    <br>
    <!-- <div class="row">

    <div class="col-md-3">
        <h3><p class="text-white">012554</p></h3>
    </div>
    <div class="col-md-3">
        <h3><p class="text-white">DESCRIPTION</p></h3>
    </div>
    <div class="col-md-3">
        <h3><p class="text-white">ATTR</p></h3>
    </div>
    <div class="col-md-3">
        <h3><p class="text-white">SIZE</p></h3>
    </div>

    </div> -->
<div class="row mt-4">

<div class="col-md-12">
    <h3><p class="text-white text-center" id="pr_vr_dtls"></p></h3>
    </div>
    <div class="col-md-12 mt-4">
    <h1><p class="text-white text-center" id="pr_vr_price"></p></h1>

    </div>
    <div class="col-md-12">
        <br>
        <br>   
        <br>
        <br>
        <br>
    </div>
</div>


    
    

                      
                             
</div>

          </div>
               <!-- /.col -->
               <div class="col-md-12 col-sm-12 col-12">
            <div class="info-box shadow">
              <span class="info-box-icon bg-warning"></span>

              <div class="info-box-content">
                <!-- <span class="font-weight-bold text-center">Mobile Contact Nos:</span> -->
               
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>

</div>

  
</body>
</html>

<script src="https://unpkg.com/@zxing/library@latest"></script>
<!-- <script type="text/javascript">

$(document).ready(function () {
    // alert("working");
$("#pr_vr").focus();
    zoomIn(2.0);

    function zoomIn(zoomLev) {
    if (zoomLev > 1) {
        if (typeof (document.body.style.zoom) != "undefined") {
            $(document.body).css('zoom', zoomLev);
        }else {
            // Mozilla doesn't support zoom, use -moz-transform to scale and compensate for lost width
            $('#divWrap').css({
                "-moz-transform": 'scale(" + zoomLev + ")',
                width: $(window).width() / zoomLev
            });
        }
    }
}
$('.numbers').keyup(function () {
         this.value = this.value.replace(/[^0-9\.]/g,'');
      });

 
   


       f_prvr();
function f_prvr(){

  $('#pr_vr').keypress(function (e) { 
    let kprvr = $('#pr_vr').val();
    let sbs_no = $('#SBS_NO').val();
    let price_lvl = $('#PRICE_LVL').val();



    if(e.which == 13) {
      
        // alert('You pressed enter!');
        // f_prvr();
        // $('#pr_vr').select();
        console.log(kprvr, sbs_no, price_lvl);
	    _getprvr(kprvr,sbs_no,price_lvl);
	            $('#pr_vr').select();
        return false;
        jQuery(this).blur();
        jQuery('#submit').focus().click();
    }
    // console.log(kprvr)
   
    function _getprvr(kprvr,sbs_no,price_lvl){
  $.post('fetch.php',{kprvr:kprvr, sbs_no:sbs_no,price_lvl:price_lvl, operation:'pv_res'},function(data){
    let pr_data = jQuery.parseJSON(data); 
const pvres = pr_data;
console.log(pvres[0].FDetails)
console.log(pvres[0].Price_WT)
$('#pr_vr_dtls').html(pvres[0].FDetails);
$('#pr_vr_price').html(pvres[0].Price_WT);
//  console.log(data);

  })
 
}
// setTimeout(function(){// wait for 5 secs(2)
//   // f_prvr();



//    // then reload the page.(3)
//       }, 500); 


});

}      


});


</script> -->


<script type="text/javascript">

$(document).ready(function () {

    $("#pr_vr").focus();
    zoomIn(2.0);

    function zoomIn(zoomLev) {
        if (zoomLev > 1) {
            if (typeof (document.body.style.zoom) != "undefined") {
                $(document.body).css('zoom', zoomLev);
            } else {
                $('#divWrap').css({
                    "-moz-transform": 'scale(' + zoomLev + ')',
                    width: $(window).width() / zoomLev
                });
            }
        }
    }

    $('.numbers').keyup(function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    function isValidEAN13(code) {
        return /^[0-9]{13}$/.test(code);
    }

    function getPriceVerifier(kprvr) {
        let sbs_no = $('#SBS_NO').val();
        let price_lvl = $('#PRICE_LVL').val();

        if (!kprvr) {
            return;
        }

        console.log(kprvr, sbs_no, price_lvl);

        $.post('fetch.php', {
            kprvr: kprvr,
            sbs_no: sbs_no,
            price_lvl: price_lvl,
            operation: 'pv_res'
        }, function(data) {

            let pr_data;

            try {
                pr_data = jQuery.parseJSON(data);
            } catch (e) {
                $('#pr_vr_dtls').html('Invalid server response.');
                $('#pr_vr_price').html('');
                return;
            }

            if (!pr_data || pr_data.length === 0) {
                $('#pr_vr_dtls').html('Item not found.');
                $('#pr_vr_price').html('');
                return;
            }

            $('#pr_vr_dtls').html(pr_data[0].FDetails);
            $('#pr_vr_price').html(pr_data[0].Price_WT);

            $('#pr_vr').select();
        });
    }

    $('#pr_vr').keypress(function (e) {
        if (e.which == 13) {
            let kprvr = $('#pr_vr').val().trim();

            getPriceVerifier(kprvr);

            $('#pr_vr').select();
            return false;
        }
    });

    let codeReader = null;
    let isScanning = false;

    $('#btnStartScan').on('click', function () {

        if (typeof ZXing === 'undefined') {
            alert('Barcode scanner library not loaded.');
            return;
        }

        codeReader = new ZXing.BrowserMultiFormatReader();

        $('#barcodePreview').show();
        $('#btnStartScan').hide();
        $('#btnStopScan').show();

        isScanning = true;

        codeReader.decodeFromVideoDevice(null, 'barcodePreview', function(result, err) {

            if (result && isScanning) {
                let scannedCode = result.text.trim();

                console.log('Scanned:', scannedCode);

                // EAN-13 only
                if (!isValidEAN13(scannedCode)) {
                    return;
                }

                $('#pr_vr').val(scannedCode);

                getPriceVerifier(scannedCode);

                stopScanner();
            }

        }).catch(function(error) {
            console.error(error);
            alert('Camera access failed. Please allow camera permission.');
            stopScanner();
        });

    });

    $('#btnStopScan').on('click', function () {
        stopScanner();
    });

    function stopScanner() {
        isScanning = false;

        if (codeReader) {
            codeReader.reset();
            codeReader = null;
        }

        $('#barcodePreview').hide();
        $('#btnStartScan').show();
        $('#btnStopScan').hide();

        $('#pr_vr').focus();
    }

});
</script>