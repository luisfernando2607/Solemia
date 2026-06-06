<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket - Solemia</title>
    <style>
        @page { margin: 0; size: 80mm auto; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 72mm;
            margin: 0 auto;
            padding: 3mm;
            color: #000;
        }
        .header { text-align: center; margin-bottom: 3mm; }
        .header h1 { font-size: 18px; font-weight: bold; margin-bottom: 1mm; }
        .header p { font-size: 10px; line-height: 1.3; }
        .divider { border-top: 1px dashed #000; margin: 2mm 0; }
        .info-row { display: flex; justify-content: space-between; font-size: 10px; margin-bottom: 1mm; }
        .items { width: 100%; border-collapse: collapse; font-size: 10px; }
        .items td { padding: 1mm 0; vertical-align: top; }
        .items .qty { width: 8mm; text-align: center; }
        .items .name { padding-left: 1mm; }
        .items .price { width: 20mm; text-align: right; }
        .items .subtotal { width: 22mm; text-align: right; font-weight: bold; }
        .total-row { display: flex; justify-content: space-between; font-size: 11px; padding: 1mm 0; }
        .total-row.final { font-size: 14px; font-weight: bold; border-top: 2px solid #000; padding-top: 2mm; margin-top: 1mm; }
        .payment-info { text-align: center; margin-top: 3mm; font-size: 10px; }
        .payment-info p { margin-bottom: 1mm; }
        .footer { text-align: center; margin-top: 3mm; font-size: 9px; color: #555; }
        .footer p { margin-bottom: 0.5mm; }
        .thanks { text-align: center; margin-top: 3mm; font-size: 12px; font-weight: bold; }
        @media print {
            .no-print { display: none; }
            body { width: 72mm; }
        }
        .no-print { text-align: center; margin-bottom: 5mm; }
        .no-print button {
            background: #6B8E4E; color: white; border: none;
            padding: 8px 24px; border-radius: 8px; font-size: 14px; cursor: pointer;
        }
        .no-print button:hover { background: #5a7a42; }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">🖨 Imprimir ticket</button>
        <button onclick="window.close()" style="background:#6b7280;margin-left:8px;">Cerrar</button>
        <p style="margin-top:4mm;font-size:11px;color:#666;">Vista previa del ticket térmico (80mm)</p>
        <hr style="margin:3mm 0;">
    </div>

    <div class="header">
        <h1>{{ $restaurant['name'] }}</h1>
        <p>{{ $restaurant['ruc'] }}</p>
        <p>{{ $restaurant['address'] }}</p>
        <p>Tel: {{ $restaurant['phone'] }}</p>
    </div>

    <div class="divider"></div>

    <div class="info-row">
        <span>Ticket #{{ $order->id }}</span>
        <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
    </div>
    <div class="info-row">
        <span>{{ $order->table ? 'Mesa: ' . $order->table->number : 'Para llevar' }}</span>
        <span>Mesero: {{ $order->user->name }}</span>
    </div>

    <div class="divider"></div>

    <table class="items">
        @foreach ($order->items as $item)
            <tr>
                <td class="qty">{{ $item->quantity }}x</td>
                <td class="name">{{ $item->product->name }}</td>
                <td class="price">${{ number_format($item->unit_price, 2) }}</td>
                <td class="subtotal">${{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @if($item->notes)
                <tr><td colspan="4" style="font-size:9px;color:#666;padding-left:9mm;padding-top:0;">Nota: {{ $item->notes }}</td></tr>
            @endif
        @endforeach
    </table>

    <div class="divider"></div>

    <div class="total-row">
        <span>Subtotal</span>
        <span>${{ number_format($order->subtotal, 2) }}</span>
    </div>
    @if($order->discount > 0)
        <div class="total-row" style="color:#c00;">
            <span>Descuento</span>
            <span>-${{ number_format($order->discount, 2) }}</span>
        </div>
    @endif
    <div class="total-row">
        <span>IVA 15%</span>
        <span>${{ number_format($order->tax, 2) }}</span>
    </div>
    @if($order->tip > 0)
        <div class="total-row">
            <span>Propina</span>
            <span>${{ number_format($order->tip, 2) }}</span>
        </div>
    @endif
    <div class="total-row final">
        <span>TOTAL</span>
        <span>${{ number_format($order->total, 2) }}</span>
    </div>

    @if($payment)
        <div class="divider"></div>
        <div class="payment-info">
            <p><strong>Método de pago:</strong>
                {{ $payment->method === 'cash' ? 'Efectivo' : '' }}
                {{ $payment->method === 'credit_card' ? 'Tarjeta crédito' : '' }}
                {{ $payment->method === 'debit_card' ? 'Tarjeta débito' : '' }}
                {{ $payment->method === 'bank_transfer' ? 'Transferencia' : '' }}
                {{ $payment->method === 'qr_wallet' ? 'QR / Wallet' : '' }}
                {{ $payment->method === 'internal_credit' ? 'Crédito interno' : '' }}
            </p>
            @if($payment->cash_tendered)
                <p>Recibido: ${{ number_format($payment->cash_tendered, 2) }}</p>
                <p>Cambio: ${{ number_format($payment->cash_change ?? 0, 2) }}</p>
            @endif
            @if($payment->reference_number)
                <p>Ref: {{ $payment->reference_number }}</p>
            @endif
        </div>
    @endif

    <div class="divider"></div>

    @if($invoice)
        <div style="text-align:center;font-size:9px;margin-bottom:2mm;">
            <p><strong>Factura electrónica</strong></p>
            <p>Clave de acceso:</p>
            <p style="font-size:8px;word-break:break-all;">{{ $invoice->access_key }}</p>
            <p>Autorización: {{ $invoice->authorization_date ? $invoice->authorization_date->format('d/m/Y H:i') : 'Pendiente' }}</p>
            <p>Documento: {{ $invoice->sequential }}</p>
            @if($invoice->customer_ruc)
                <p>{{ $invoice->customer_name }} · {{ $invoice->customer_ruc }}</p>
            @endif
        </div>
        <div class="divider"></div>
    @endif

    <div class="thanks">
        <p>¡Oohh Solemia de mi corazón!</p>
        <p style="font-size:10px;font-weight:normal;">¡Gracias por su visita!</p>
    </div>

    <div class="footer">
        <p>www.solemia.com</p>
        <p>Términos y condiciones: Consulte en nuestro sitio web</p>
        <p>Original: Cliente | Copia: Emisor</p>
    </div>

    <script>
        window.onload = function() {
            // Auto-print after a short delay for the view to render
            setTimeout(function() {
                // Don't auto-print, let user click button
            }, 500);
        };
    </script>
</body>
</html>
