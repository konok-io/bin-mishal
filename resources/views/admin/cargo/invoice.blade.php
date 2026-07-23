<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargo Invoice - {{ $cargo->tracking_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #333; }
        .invoice { max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; margin-bottom: 30px; border-bottom: 2px solid #006C35; padding-bottom: 20px; }
        .company h1 { color: #006C35; font-size: 24px; margin-bottom: 5px; }
        .company p { color: #666; font-size: 12px; }
        .invoice-info { text-align: right; }
        .invoice-info h2 { color: #006C35; font-size: 20px; }
        .invoice-info p { margin-bottom: 5px; }
        .invoice-number { font-size: 18px; font-weight: bold; color: #333; margin-top: 10px; }
        .details { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .box { width: 48%; border: 1px solid #ddd; padding: 15px; border-radius: 5px; }
        .box h4 { color: #006C35; margin-bottom: 10px; font-size: 14px; text-transform: uppercase; }
        .box p { margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #006C35; color: #fff; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #ddd; }
        .text-right { text-align: right; }
        .totals { width: 300px; margin-left: auto; }
        .totals tr td { border: none; padding: 5px 12px; }
        .totals tr.total { background: #f8f9fa; font-weight: bold; font-size: 16px; }
        .footer { text-align: center; margin-top: 50px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px; }
        .qr-placeholder { width: 80px; height: 80px; border: 1px dashed #ddd; display: flex; align-items: center; justify-content: center; color: #999; font-size: 10px; }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="header">
            <div class="company">
                <h1>Bin Mishal Travel</h1>
                <p>Cargo & Logistics Services</p>
                <p>Riyadh, Kingdom of Saudi Arabia</p>
                <p>CR: XXXXXXXX | VAT: XXXXXXXXX</p>
            </div>
            <div class="invoice-info">
                <h2>CARGO INVOICE</h2>
                <p>Invoice #: INV-{{ str_pad($cargo->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p>Date: {{ $cargo->created_at->format('d M Y') }}</p>
                <p class="invoice-number">{{ $cargo->tracking_number }}</p>
            </div>
        </div>

        <div class="details">
            <div class="box">
                <h4>Sender Information</h4>
                <p><strong>{{ $cargo->sender_name }}</strong></p>
                <p>{{ $cargo->sender_address }}</p>
                <p>{{ $cargo->sender_city }}</p>
                <p>Phone: {{ $cargo->sender_phone }}</p>
                @if($cargo->sender_email)
                <p>Email: {{ $cargo->sender_email }}</p>
                @endif
            </div>
            <div class="box">
                <h4>Receiver Information</h4>
                <p><strong>{{ $cargo->receiver_name }}</strong></p>
                <p>{{ $cargo->receiver_address }}</p>
                <p>{{ $cargo->receiver_city }}</p>
                <p>Phone: {{ $cargo->receiver_phone }}</p>
                @if($cargo->receiver_email)
                <p>Email: {{ $cargo->receiver_email }}</p>
                @endif
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Type</th>
                    <th class="text-right">Weight</th>
                    <th class="text-right">Declared Value</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {{ $cargo->cargo_description ?? 'Cargo Package' }}
                        <br><small class="text-muted">Qty: {{ $cargo->quantity }}</small>
                    </td>
                    <td>{{ $cargo->cargoType?->name ?? 'General' }}</td>
                    <td class="text-right">{{ $cargo->weight }} kg</td>
                    <td class="text-right">SAR {{ number_format($cargo->declared_value, 2) }}</td>
                    <td class="text-right">SAR {{ number_format($cargo->shipping_cost, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="totals">
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">SAR {{ number_format($cargo->shipping_cost, 2) }}</td>
            </tr>
            <tr>
                <td>VAT (15%):</td>
                <td class="text-right">SAR {{ number_format($cargo->vat_amount, 2) }}</td>
            </tr>
            @if($cargo->discount_amount > 0)
            <tr style="color: green;">
                <td>Discount:</td>
                <td class="text-right">-SAR {{ number_format($cargo->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr class="total">
                <td>Total Amount:</td>
                <td class="text-right">SAR {{ number_format($cargo->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td colspan="2"><hr></td>
            </tr>
            <tr>
                <td>Payment Status:</td>
                <td class="text-right"><strong>{{ strtoupper($cargo->payment_status) }}</strong></td>
            </tr>
        </table>

        <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <p><strong>Terms & Conditions:</strong></p>
                <ul style="font-size: 11px; color: #666;">
                    <li>Goods once delivered cannot be returned.</li>
                    <li>Delivery time is estimated and may vary.</li>
                    <li>Insurance available upon request.</li>
                </ul>
            </div>
            <div class="qr-placeholder">QR Code</div>
        </div>

        <div class="footer">
            <p>Bin Mishal Travel & Logistics | www.binmishal.com | info@binmishal.com</p>
            <p>Thank you for choosing our services!</p>
        </div>
    </div>
</body>
</html>
