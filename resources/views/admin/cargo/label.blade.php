<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Label - {{ $cargo->tracking_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .label { width: 4in; border: 2px solid #000; padding: 10px; margin: 20px; }
        .header { text-align: center; border-bottom: 1px solid #000; padding-bottom: 10px; margin-bottom: 10px; }
        .header h1 { font-size: 14px; }
        .tracking { font-size: 20px; font-weight: bold; text-align: center; margin: 15px 0; letter-spacing: 2px; }
        .section { margin-bottom: 15px; }
        .section h4 { background: #000; color: #fff; padding: 3px 8px; font-size: 11px; margin-bottom: 5px; }
        .section p { margin: 2px 0; }
        .barcode { text-align: center; margin: 15px 0; }
        .barcode img { max-width: 100%; height: 60px; }
        .barcode-text { font-family: monospace; font-size: 14px; letter-spacing: 3px; }
        .footer { text-align: center; font-size: 10px; color: #666; margin-top: 10px; }
        @media print {
            body { margin: 0; }
            .label { border: 2px solid #000; margin: 0; page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="label">
        <div class="header">
            <h1>BIN MISHAL CARGO</h1>
            <small>Saudi Arabia - Bangladesh Cargo Service</small>
        </div>

        <div class="tracking">{{ $cargo->tracking_number }}</div>

        <div class="section">
            <h4>FROM (SENDER)</h4>
            <p><strong>{{ $cargo->sender_name }}</strong></p>
            <p>{{ $cargo->sender_address }}</p>
            <p>{{ $cargo->sender_city }}, Saudi Arabia</p>
            <p>Tel: {{ $cargo->sender_phone }}</p>
        </div>

        <div class="section">
            <h4>TO (RECEIVER)</h4>
            <p><strong>{{ $cargo->receiver_name }}</strong></p>
            <p>{{ $cargo->receiver_address }}</p>
            <p>{{ $cargo->receiver_city }}</p>
            <p>{{ $cargo->receiverZone?->name ?? '' }}</p>
            <p>Tel: {{ $cargo->receiver_phone }}</p>
        </div>

        <div class="section">
            <h4>SHIPMENT INFO</h4>
            <table width="100%">
                <tr>
                    <td>Weight:</td>
                    <td><strong>{{ $cargo->weight }} kg</strong></td>
                    <td>Type:</td>
                    <td><strong>{{ $cargo->cargoType?->name ?? 'General' }}</strong></td>
                </tr>
                <tr>
                    <td>Qty:</td>
                    <td><strong>{{ $cargo->quantity }}</strong></td>
                    <td>Value:</td>
                    <td><strong>SAR {{ number_format($cargo->declared_value, 2) }}</strong></td>
                </tr>
            </table>
        </div>

        <div class="barcode">
            <div class="barcode-text">{{ $cargo->tracking_number }}</div>
            <div style="font-size: 10px; margin-top: 5px;">📦 USE BARCODE SCANNER</div>
        </div>

        <div class="footer">
            <p>Handle with care | Do not expose to moisture</p>
            <p>www.binmishal.com | +966 XX XXX XXXX</p>
        </div>
    </div>
</body>
</html>
