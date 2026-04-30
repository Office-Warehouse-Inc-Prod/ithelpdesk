<?php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CLP Card Verifier</title>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #ffc000;
        }

        .title {
            text-align: center;
            font-size: 48px;
            font-weight: 400;
            padding: 8px 0 20px;
            letter-spacing: 2px;
        }

        .black-line {
            height: 22px;
            background: #000;
        }

        .main-wrapper {
            padding: 90px 28px;
        }

        .verifier-box {
            background: #212529;
            border-radius: 6px;
            min-height: 500px;
            padding: 32px 55px;
        }

        #card_id {
            width: 100%;
            height: 150px;
            font-size: 72px;
            text-align: center;
            border-radius: 8px;
            border: 4px solid #1d75cf;
            outline: none;
            box-sizing: border-box;
        }

        .result-box {
            margin-top: 35px;
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            display: none;
        }

        .member-title {
            font-size: 34px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #111;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .detail-card {
            background: #f4f6f9;
            padding: 18px;
            border-radius: 8px;
            border-left: 6px solid #ffc000;
        }

        .label {
            font-size: 14px;
            color: #666;
            margin-bottom: 6px;
        }

        .value {
            font-size: 24px;
            font-weight: bold;
            color: #000;
        }

        .points {
            font-size: 42px;
            color: #198754;
        }

        .not-found {
            margin-top: 35px;
            background: #dc3545;
            color: #fff;
            font-size: 32px;
            text-align: center;
            padding: 30px;
            border-radius: 8px;
            display: none;
        }
    </style>
</head>
<body>

<div class="title">CLP CARD VERIFIER</div>
<div class="black-line"></div>

<div class="main-wrapper">
    <div class="verifier-box">

        <input 
            type="text" 
            id="card_id" 
            placeholder="SCAN / ENTER CARD ID"
            autocomplete="off"
            autofocus
        >

        <div class="not-found" id="notFound">
            CARD ID NOT FOUND
        </div>

        <div class="result-box" id="resultBox">
            <div class="member-title">
                Card ID: <span id="resCardID"></span>
            </div>

            <div class="details-grid">
                <div class="detail-card">
                    <div class="label">Member ID</div>
                    <div class="value" id="resMemberID"></div>
                </div>

                <div class="detail-card">
                    <div class="label">Member Code</div>
                    <div class="value" id="resMemberCode"></div>
                </div>

                <div class="detail-card">
                    <div class="label">Available / Final Points</div>
                    <div class="value points" id="resFinalPoints"></div>
                </div>

                <div class="detail-card">
                    <div class="label">Computed Points</div>
                    <div class="value" id="resComputedPoints"></div>
                </div>

                <div class="detail-card">
                    <div class="label">Last Transaction Date</div>
                    <div class="value" id="resLastTransact"></div>
                </div>

                <div class="detail-card">
                    <div class="label">Expiry Date</div>
                    <div class="value" id="resExpiry"></div>
                </div>

                <div class="detail-card">
                    <div class="label">Last Invoice No.</div>
                    <div class="value" id="resInvoice"></div>
                </div>

                <div class="detail-card">
                    <div class="label">Last Amount</div>
                    <div class="value" id="resAmount"></div>
                </div>

                <div class="detail-card">
                    <div class="label">Store Code</div>
                    <div class="value" id="resStoreCode"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
$(document).ready(function () {

    $('#card_id').on('keypress', function (e) {
        if (e.which === 13) {
            let cardID = $.trim($(this).val());

            if (cardID === '') {
                return;
            }

            verifyCard(cardID);
        }
    });

    function verifyCard(cardID) {
        $.ajax({
            url: 'verify_card.php',
            type: 'POST',
            dataType: 'json',
            data: {
                card_id: cardID
            },
            success: function (response) {
                if (response.success) {
                    let d = response.data;

                    $('#notFound').hide();

                    $('#resCardID').text(d.CardID);
                    $('#resMemberID').text(d.member_id);
                    $('#resMemberCode').text(d.member_code);
                    $('#resFinalPoints').text(formatNumber(d.final_points));
                    $('#resComputedPoints').text(formatNumber(d.computed_points));
                    $('#resLastTransact').text(d.last_transact_dt);
                    $('#resExpiry').text(d.Expiry);
                    $('#resInvoice').text(d.invc_no);
                    $('#resAmount').text('₱ ' + formatMoney(d.amt));
                    $('#resStoreCode').text(d.store_code);

                    $('#resultBox').fadeIn(150);
                } else {
                    $('#resultBox').hide();
                    $('#notFound').fadeIn(150);
                }

                $('#card_id').val('').focus();
            },
            error: function () {
                $('#resultBox').hide();
                $('#notFound').text('SYSTEM ERROR').fadeIn(150);
                $('#card_id').val('').focus();
            }
        });
    }

    function formatNumber(value) {
        return parseFloat(value || 0).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function formatMoney(value) {
        return parseFloat(value || 0).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

});
</script>

</body>
</html>