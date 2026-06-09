<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket - {{ $restaurant['name'] }} #{{ $order->id }}</title>
    <style>
        @page { margin: 0; size: 80mm auto; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            width: 72mm;
            margin: 0 auto;
            padding: 3mm;
            color: #111;
            line-height: 1.45;
        }

        /* ── Header ── */
        .logo { text-align: center; font-size: 20px; font-weight: bold; letter-spacing: 1px; margin-bottom: 2px; }
        .tagline { text-align: center; font-size: 9px; letter-spacing: 2px; text-transform: uppercase; color: #555; margin-bottom: 3px; }
        .header-info { text-align: center; font-size: 10px; line-height: 1.5; color: #333; }

        /* ── Separadores ── */
        .divider-solid { border: none; border-top: 1px solid #111; margin: 5px 0; }
        .divider-dash  { border: none; border-top: 1px dashed #999; margin: 4px 0; }

        /* ── Info de orden ── */
        .row { display: flex; justify-content: space-between; font-size: 10px; margin-bottom: 1px; }

        /* ── Tabla de ítems ── */
        .items-header { display: flex; font-size: 9px; color: #666; margin-bottom: 3px; }
        .items-header .col-name { flex: 1; padding-left: 26px; }
        .items-header .col-unit { width: 18mm; text-align: right; }
        .items-header .col-total { width: 20mm; text-align: right; }

        .item-row { display: flex; font-size: 11px; margin-bottom: 2px; }
        .item-qty   { width: 26px; flex-shrink: 0; }
        .item-name  { flex: 1; }
        .item-unit  { width: 18mm; text-align: right; flex-shrink: 0; font-size: 10px; color: #555; }
        .item-total { width: 20mm; text-align: right; flex-shrink: 0; font-weight: bold; }

        .item-note { font-size: 9px; color: #666; padding-left: 26px; margin-bottom: 2px; }

        /* ── Totales ── */
        .total-row { display: flex; justify-content: space-between; font-size: 11px; padding: 1px 0; }
        .total-row.discount { color: #cc0000; }
        .total-final {
            display: flex; justify-content: space-between;
            font-size: 15px; font-weight: bold;
            border-top: 2px solid #111;
            padding-top: 5px; margin-top: 3px;
        }

        /* ── Pago ── */
        .pay-method { text-align: center; font-size: 11px; font-weight: bold; margin: 3px 0; letter-spacing: 1px; }
        .pay-row { display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 1px; }
        .pay-row.highlight { font-weight: bold; }

        /* ── Factura electrónica ── */
        .invoice-block { text-align: center; font-size: 9px; line-height: 1.5; color: #333; }
        .invoice-key { font-size: 8px; word-break: break-all; color: #555; margin-top: 1px; }

        /* ── Pie ── */
        .thanks-main { text-align: center; font-size: 13px; font-weight: bold; margin: 3px 0 1px; }
        .thanks-sub  { text-align: center; font-size: 10px; color: #444; }
        .footer-block { text-align: center; font-size: 9px; color: #666; line-height: 1.7; margin-top: 4px; }

        /* ── Botones (solo pantalla) ── */
        @media print {
            .no-print { display: none; }
            body { width: 72mm; }
        }
        .no-print {
            text-align: center; margin-bottom: 5mm;
            display: flex; align-items: center; justify-content: center; gap: 6mm;
        }
        .no-print button {
            border: none; cursor: pointer; border-radius: 50%;
            width: 44px; height: 44px;
            display: flex; align-items: center; justify-content: center;
            transition: background .2s; outline: none;
        }
        .no-print button svg { width: 22px; height: 22px; display: block; }
        .btn-print { background: #6B8E4E; color: #fff; }
        .btn-print:hover { background: #5a7a42; }
        .btn-close  { background: #e5e7eb; color: #374151; }
        .btn-close:hover { background: #d1d5db; }
    </style>
</head>
<body>

    {{-- Botones solo en pantalla --}}
    <div class="no-print">
        <button onclick="window.print()" class="btn-print" title="Imprimir ticket">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
        </button>
        <button onclick="window.close()" class="btn-close" title="Cerrar">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Encabezado --}}
    <div class="logo">&#10038; {{ $restaurant['name'] }} &#10038;</div>
    <div class="tagline">Ristorante</div>
    <div class="header-info">
        <div>{{ $restaurant['ruc'] }}</div>
        <div>{{ $restaurant['address'] }}</div>
        <div>Tel: {{ $restaurant['phone'] }}</div>
    </div>

    <div class="divider-solid"></div>

    {{-- Info de orden --}}
    <div class="row">
        <span>Ticket <strong>#{{ $order->id }}</strong></span>
        <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
    </div>
    <div class="row">
        <span>
            @if($order->table)
                Mesa: <strong>{{ $order->table->number }}</strong>
            @else
                Para llevar
            @endif
        </span>

        <span>
            Mesero: <strong>{{ $order->user->name }}</strong>
        </span>
    </div>
    <div class="divider-dash"></div>

    {{-- Cabecera de ítems --}}
    <div class="items-header">
        <span class="col-name">DESCRIPCIÓN</span>
        <span class="col-unit">P.UNIT</span>
        <span class="col-total">TOTAL</span>
    </div>

    {{-- Ítems --}}
    @foreach ($order->items as $item)
        <div class="item-row">
            <span class="item-qty">{{ $item->quantity }}x</span>
            <span class="item-name">{{ $item->product?->name ?? '—' }}</span>
            <span class="item-unit">${{ number_format($item->unit_price, 2) }}</span>
            <span class="item-total">${{ number_format($item->subtotal, 2) }}</span>
        </div>
        @if($item->notes)
            <div class="item-note">&#8627; {{ $item->notes }}</div>
        @endif
    @endforeach

    <div class="divider-dash"></div>

    {{-- Totales --}}
    <div class="total-row"><span>Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
    @if($order->discount > 0)
        <div class="total-row discount"><span>Descuento</span><span>-${{ number_format($order->discount, 2) }}</span></div>
    @endif
    <div class="total-row"><span>IVA {{ $restaurant['tax_rate'] }}%</span><span>${{ number_format($order->tax, 2) }}</span></div>
    @if($order->tip > 0)
        <div class="total-row"><span>Propina</span><span>${{ number_format($order->tip, 2) }}</span></div>
    @endif
    <div class="total-final">
        <span>TOTAL</span>
        <span>${{ number_format($order->total, 2) }}</span>
    </div>

    {{-- Pago --}}
    @if($payment)
        <div class="divider-solid"></div>

        <div class="pay-method">
            &mdash;&nbsp;
            @switch($payment->method)
                @case('cash')           Efectivo        @break
                @case('credit_card')    Tarjeta crédito @break
                @case('debit_card')     Tarjeta débito  @break
                @case('bank_transfer')  Transferencia   @break
                @case('qr_wallet')      QR / Wallet     @break
                @case('internal_credit')Crédito interno @break
                @default                {{ $payment->method }}
            @endswitch
            &nbsp;&mdash;
        </div>

        @if($payment->cash_tendered)
            <div class="pay-row"><span>Recibido</span><span>${{ number_format($payment->cash_tendered, 2) }}</span></div>
            <div class="pay-row highlight"><span>Cambio</span><span>${{ number_format($payment->cash_change ?? 0, 2) }}</span></div>
        @endif

        @if($payment->reference_number)
            <div class="pay-row"><span>Ref.</span><span>{{ $payment->reference_number }}</span></div>
        @endif
    @endif

    {{-- Factura electrónica --}}
    @if($invoice)
        <div class="divider-dash"></div>
        <div class="invoice-block">
            <div><strong>FACTURA ELECTRÓNICA</strong></div>
            <div>Documento: <strong>{{ $invoice->sequential }}</strong></div>
            <div>Autorización: {{ $invoice->authorization_date ? $invoice->authorization_date->format('d/m/Y H:i') : 'Pendiente' }}</div>
            @if($invoice->customer_ruc)
                <div>{{ $invoice->customer_name }} &middot; {{ $invoice->customer_ruc }}</div>
            @endif
            <div>Clave de acceso:</div>
            <div class="invoice-key">{{ $invoice->access_key }}</div>
        </div>
    @endif

    <div class="divider-dash"></div>

    {{-- Cierre --}}
    <div class="thanks-main">¡Oh, {{ $restaurant['name'] }} de mi corazón!</div>
    <div class="thanks-sub">¡Gracias por su visita!</div>

    <div class="footer-block">
        Original: Cliente &middot; Copia: Emisor<br>
        Términos y condiciones: consulte en nuestro sitio web
    </div>

</body>
</html>