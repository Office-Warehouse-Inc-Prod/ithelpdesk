<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price Verifier</title>

    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link rel="stylesheet" href="../css/4bootstrap.min.css">

    <script src="../js/moment.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Font Awesome -->
    <script src="https://use.fontawesome.com/f942a1dc17.js"></script>

    <!-- Barcode Scanner Library -->
    <script src="https://unpkg.com/@zxing/library@latest"></script>

    <!-- OCR Library -->
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>

    <style>
        body {
            background-color: #FFC108;
            font-family: 'Raleway', sans-serif;
        }

        .card {
            background-color: #212529;
            padding: 20px;
        }

        hr {
            position: relative;
            border: none;
            height: 12px;
            background: black;
            margin-bottom: 30px;
        }

        #barcodePreview {
            width: 100%;
            max-width: 500px;
            display: none;
            border: 5px solid #FFC108;
            margin-top: 15px;
        }

        .pv-btn {
            margin: 5px;
            font-size: 18px;
        }

        .ocr-note {
            font-size: 14px;
            color: white;
            margin-top: 10px;
        }
    </style>

</head>

<body>

<div class="pv_header justify-content-center">
    <div class="col-md-12 text-center">
        <h3>PRICE VERIFIER</h3>
    </div>
</div>

<hr>

<div class="col-md-12 col-sm-12 col-12">
    <div class="card">

        <div class="container">

            <div class="col-md-12">
                <input style="font-size:50px;"
                       type="text"
                       name="pr_vr"
                       id="pr_vr"
                       class="numbers form form-control text-center input-lg"
                       autocomplete="off">

                <input type="hidden" name="SBS_NO" id="SBS_NO" value="<?php echo $_SESSION['SBS_NO']; ?>">
                <input type="hidden" name="PRICE_LVL" id="PRICE_LVL" value="<?php echo $_SESSION['PRICE_LVL']; ?>">
            </div>

            <div class="text-center mt-3">

                <button type="button" id="btnStartScan" class="btn btn-warning btn-lg pv-btn">
                    <i class="fa fa-camera"></i> Scan Barcode
                </button>

                <button type="button" id="btnStopScan" class="btn btn-danger btn-lg pv-btn" style="display:none;">
                    Stop Scan
                </button>

                <button type="button" id="btnReadNumber" class="btn btn-info btn-lg pv-btn" style="display:none;">
                    Read Number
                </button>

            </div>

            <div class="text-center">
                <video id="barcodePreview" autoplay muted playsinline></video>
                <canvas id="ocrCanvas" style="display:none;"></canvas>

                <p id="scanInstruction" class="ocr-note" style="display:none;">
                    If barcode is not scanning, tap <b>Read Number</b> to detect the printed item code.
                </p>
            </div>

        </div>

        <br>

        <div class="row mt-4">

            <div class="col-md-12">
                <h3>
                    <p class="text-white text-center" id="pr_vr_dtls"></p>
                </h3>
            </div>

            <div class="col-md-12 mt-4">
                <h1>
                    <p class="text-white text-center" id="pr_vr_price"></p>
                </h1>
            </div>

            <div class="col-md-12">
                <br><br><br><br><br>
            </div>

        </div>

    </div>
</div>

<div class="col-md-12 col-sm-12 col-12">
    <div class="info-box shadow">
        <span class="info-box-icon bg-warning"></span>
        <div class="info-box-content"></div>
    </div>
</div>

</body>
</html>

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

    function isValidBarcode(code) {
        // Allows short item code, UPC, EAN-13, CODE128 numeric values
        return /^[0-9]{4,14}$/.test(code);
    }

    function getPriceVerifier(kprvr) {

        let sbs_no = $('#SBS_NO').val();
        let price_lvl = $('#PRICE_LVL').val();

        if (!kprvr) {
            return;
        }

        $('#pr_vr_dtls').html('Checking item...');
        $('#pr_vr_price').html('');

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
                console.log(data);
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

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert("Camera is not available. Please use HTTPS or allow camera permission in your browser.");
            return;
        }

        if (typeof ZXing === 'undefined') {
            alert('Barcode scanner library not loaded.');
            return;
        }

        const hints = new Map();

        const formats = [
            ZXing.BarcodeFormat.EAN_13,
            ZXing.BarcodeFormat.UPC_A,
            ZXing.BarcodeFormat.CODE_128,
            ZXing.BarcodeFormat.CODE_39,
            ZXing.BarcodeFormat.ITF
        ];

        hints.set(ZXing.DecodeHintType.POSSIBLE_FORMATS, formats);

        codeReader = new ZXing.BrowserMultiFormatReader(hints);

        $('#barcodePreview').show();
        $('#btnStartScan').hide();
        $('#btnStopScan').show();
        $('#btnReadNumber').show();
        $('#scanInstruction').show();

        isScanning = true;

        codeReader.decodeFromVideoDevice(null, 'barcodePreview', function(result, err) {

            if (result && isScanning) {

                let scannedCode = result.text.trim();

                console.log('Scanned Barcode:', scannedCode);

                if (!isValidBarcode(scannedCode)) {
                    return;
                }

                $('#pr_vr').val(scannedCode);

                getPriceVerifier(scannedCode);

                stopScanner();
            }

        }).catch(function(error) {

            console.error(error);

            if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
                alert('Camera requires HTTPS. Please open this page using HTTPS.');
            } else {
                alert('Camera permission was denied or blocked. Please allow camera access in your browser settings.');
            }

            stopScanner();
        });

    });

    $('#btnStopScan').on('click', function () {
        stopScanner();
    });

    $('#btnReadNumber').on('click', function () {
        readNumberFromCamera();
    });

    function readNumberFromCamera() {

        let video = document.getElementById('barcodePreview');
        let canvas = document.getElementById('ocrCanvas');
        let context = canvas.getContext('2d');

        if (!video || video.readyState !== video.HAVE_ENOUGH_DATA) {
            alert('Camera is not ready yet.');
            return;
        }

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        $('#btnReadNumber').prop('disabled', true).text('Reading...');

        Tesseract.recognize(canvas, 'eng', {
            logger: function(m) {
                console.log(m);
            }
        }).then(function(result) {

            let text = result.data.text || '';

            console.log('OCR Result:', text);

            let numbers = text.match(/\d{4,14}/g);

            if (numbers && numbers.length > 0) {

                let detectedNumber = numbers[0];

                console.log('Detected Number:', detectedNumber);

                $('#pr_vr').val(detectedNumber);

                getPriceVerifier(detectedNumber);

                stopScanner();

            } else {
                alert('No readable number detected. Please move closer and try again.');
            }

        }).catch(function(error) {

            console.error(error);
            alert('Unable to read number from camera.');

        }).finally(function() {

            $('#btnReadNumber').prop('disabled', false).text('Read Number');

        });
    }

    function stopScanner() {

        isScanning = false;

        if (codeReader) {
            codeReader.reset();
            codeReader = null;
        }

        $('#barcodePreview').hide();
        $('#btnStartScan').show();
        $('#btnStopScan').hide();
        $('#btnReadNumber').hide();
        $('#scanInstruction').hide();

        $('#btnReadNumber').prop('disabled', false).text('Read Number');

        $('#pr_vr').focus();
    }

});

</script>