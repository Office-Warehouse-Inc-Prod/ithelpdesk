<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

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
        * {
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            min-height: 100%;
            margin: 0;
            padding: 0;
            background-color: #FFC108;
            font-family: 'Raleway', sans-serif;
        }

        body {
            overflow-x: hidden;
        }

        .page-wrapper {
            width: 100%;
            min-height: 100vh;
            padding: 12px;
            display: flex;
            flex-direction: column;
        }

        .pv-header {
            text-align: center;
            padding: 12px 8px 8px 8px;
        }

        .pv-header h3 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            color: #212529;
            letter-spacing: 1px;
        }

        .header-line {
            width: 100%;
            height: 8px;
            background-color: #212529;
            border: none;
            margin: 10px 0 14px 0;
        }

        .pv-card {
            width: 100%;
            background-color: #212529;
            border-radius: 18px;
            padding: 16px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.25);
        }

        .input-label {
            color: #ffffff;
            font-size: 14px;
            margin-bottom: 6px;
            text-align: center;
        }

        #pr_vr {
            width: 100%;
            height: 64px;
            font-size: 30px;
            font-weight: 700;
            text-align: center;
            border-radius: 14px;
            border: 3px solid #FFC108;
            outline: none;
        }

        #pr_vr:focus {
            border-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(255, 193, 8, 0.35);
        }

        .button-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
            margin-top: 14px;
        }

        .pv-btn {
            width: 100%;
            min-height: 52px;
            border-radius: 14px;
            font-size: 18px;
            font-weight: 700;
            border: none;
        }

        #btnStartScan {
            background-color: #FFC108;
            color: #212529;
        }

        #btnStopScan {
            background-color: #dc3545;
            color: #ffffff;
        }

        #btnReadNumber {
            background-color: #17a2b8;
            color: #ffffff;
        }

        .camera-box {
            width: 100%;
            margin-top: 14px;
            display: none;
        }

        #barcodePreview {
            width: 100%;
            max-height: 52vh;
            object-fit: cover;
            border-radius: 16px;
            border: 4px solid #FFC108;
            background-color: #000000;
        }

        .scan-note {
            display: none;
            margin-top: 10px;
            padding: 10px;
            border-radius: 12px;
            background-color: rgba(255,255,255,0.08);
            color: #ffffff;
            font-size: 14px;
            text-align: center;
            line-height: 1.4;
        }

        .result-box {
            margin-top: 18px;
            padding: 16px 10px;
            min-height: 160px;
            border-radius: 16px;
            background-color: rgba(255,255,255,0.06);
        }

        #pr_vr_dtls {
            color: #ffffff;
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            line-height: 1.35;
            margin: 0;
            word-break: break-word;
        }

        #pr_vr_price {
            color: #ffffff;
            text-align: center;
            font-size: 42px;
            font-weight: 900;
            margin: 18px 0 0 0;
            word-break: break-word;
        }

        .footer-box {
            width: 100%;
            min-height: 40px;
            margin-top: 14px;
            border-radius: 14px;
            background-color: rgba(33, 37, 41, 0.25);
        }

        #ocrCanvas {
            display: none;
        }

        @media screen and (min-width: 768px) {
            .page-wrapper {
                max-width: 720px;
                margin: 0 auto;
                padding: 20px;
            }

            .pv-header h3 {
                font-size: 32px;
            }

            #pr_vr {
                height: 74px;
                font-size: 42px;
            }

            .button-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            #pr_vr_dtls {
                font-size: 26px;
            }

            #pr_vr_price {
                font-size: 54px;
            }
        }

        @media screen and (max-width: 380px) {
            .pv-header h3 {
                font-size: 21px;
            }

            #pr_vr {
                height: 58px;
                font-size: 25px;
            }

            .pv-btn {
                min-height: 48px;
                font-size: 16px;
            }

            #pr_vr_dtls {
                font-size: 18px;
            }

            #pr_vr_price {
                font-size: 36px;
            }
        }
    </style>

</head>

<body>

<div class="page-wrapper">

    <div class="pv-header">
        <h3>PRICE VERIFIER</h3>
    </div>

    <div class="header-line"></div>

    <div class="pv-card">

        <div class="input-label">
            Scan or enter barcode / item code
        </div>

        <input
            type="text"
            name="pr_vr"
            id="pr_vr"
            class="numbers form-control"
            autocomplete="off"
            inputmode="numeric"
            pattern="[0-9]*"
            placeholder="Barcode / Item Code">

        <input type="hidden" name="SBS_NO" id="SBS_NO" value="<?php echo $_SESSION['SBS_NO']; ?>">
        <input type="hidden" name="PRICE_LVL" id="PRICE_LVL" value="<?php echo $_SESSION['PRICE_LVL']; ?>">

        <div class="button-grid">

            <button type="button" id="btnStartScan" class="pv-btn">
                <i class="fa fa-camera"></i> Scan Barcode
            </button>

            <button type="button" id="btnStopScan" class="pv-btn" style="display:none;">
                Stop Scan
            </button>

            <button type="button" id="btnReadNumber" class="pv-btn" style="display:none;">
                Read Number
            </button>

        </div>

        <div class="camera-box" id="cameraBox">
            <video id="barcodePreview" autoplay muted playsinline></video>
            <canvas id="ocrCanvas"></canvas>
        </div>

        <div class="scan-note" id="scanInstruction">
            If the barcode is blurry or glossy, tap <b>Read Number</b> to detect the printed item code.
        </div>

        <div class="result-box">

            <p id="pr_vr_dtls"></p>

            <p id="pr_vr_price"></p>

        </div>

    </div>

    <div class="footer-box"></div>

</div>

</body>
</html>

<script type="text/javascript">

$(document).ready(function () {

    $("#pr_vr").focus();

    $('.numbers').keyup(function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    function isValidBarcode(code) {
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

    $('#pr_vr').on('keypress', function (e) {

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

        $('#cameraBox').show();
        $('#barcodePreview').show();

        $('#btnStartScan').hide();
        $('#btnStopScan').show();
        $('#btnReadNumber').show();
        $('#scanInstruction').show();

        $('#pr_vr_dtls').html('Point the camera to the barcode...');
        $('#pr_vr_price').html('');

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
        $('#pr_vr_dtls').html('Reading printed number...');
        $('#pr_vr_price').html('');

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
                $('#pr_vr_dtls').html('No readable number detected.');
                $('#pr_vr_price').html('');
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

        $('#cameraBox').hide();
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