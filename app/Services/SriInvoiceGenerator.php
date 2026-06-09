<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\RestaurantSetting;
use DOMDocument;
use Illuminate\Support\Facades\Storage;

class SriInvoiceGenerator
{
    protected array $config;
    protected float $taxRate;

    public function __construct()
    {
        $s = RestaurantSetting::current();
        $this->config = [
            'ruc' => $s->ruc ?? '9999999999999',
            'razon_social' => $s->legal_name ?? $s->trade_name ?? 'Restaurante',
            'nombre_comercial' => $s->trade_name ?? 'Restaurante',
            'dir_matriz' => $s->address ?? 'Av. Principal',
            'ambiente' => $s->sri_environment === 'production' ? '2' : '1',
            'tipo_emision' => '1',
            'obligado_contabilidad' => 'NO',
            'contribuyente_especial' => '',
        ];
        $this->taxRate = (float)($s->tax_rate ?? 15);
    }

    public function generateXml(Order $order, string $customerName = '', string $customerRuc = '', string $customerEmail = '', string $customerAddress = ''): string
    {
        $sequential = $this->nextSequential();
        $accessKey = $this->generateAccessKey($sequential);

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $factura = $dom->createElement('factura');
        $factura->setAttribute('id', 'comprobante');
        $factura->setAttribute('version', '1.0.0');
        $dom->appendChild($factura);

        // Info Tributaria
        $infoTributaria = $dom->createElement('infoTributaria');
        $infoTributaria->appendChild($dom->createElement('ambiente', $this->config['ambiente']));
        $infoTributaria->appendChild($dom->createElement('tipoEmision', $this->config['tipo_emision']));
        $infoTributaria->appendChild($dom->createElement('razonSocial', $this->config['razon_social']));
        $infoTributaria->appendChild($dom->createElement('nombreComercial', $this->config['nombre_comercial']));
        $infoTributaria->appendChild($dom->createElement('ruc', $this->config['ruc']));
        $infoTributaria->appendChild($dom->createElement('claveAcceso', $accessKey));
        $infoTributaria->appendChild($dom->createElement('codDoc', '01'));
        $infoTributaria->appendChild($dom->createElement('estab', '001'));
        $infoTributaria->appendChild($dom->createElement('ptoEmi', '001'));
        $infoTributaria->appendChild($dom->createElement('secuencial', $sequential));
        $infoTributaria->appendChild($dom->createElement('dirMatriz', $this->config['dir_matriz']));
        $factura->appendChild($infoTributaria);

        // Info Factura
        $infoFactura = $dom->createElement('infoFactura');
        $infoFactura->appendChild($dom->createElement('fechaEmision', $order->created_at->format('d/m/Y')));
        $infoFactura->appendChild($dom->createElement('dirEstablecimiento', '001'));
        $infoFactura->appendChild($dom->createElement('obligadoContabilidad', $this->config['obligado_contabilidad']));
        if ($this->config['contribuyente_especial']) {
            $infoFactura->appendChild($dom->createElement('contribuyenteEspecial', $this->config['contribuyente_especial']));
        }

        $tipoIdentificacion = strlen($customerRuc) === 13 ? '04' : (strlen($customerRuc) === 10 ? '05' : '07');
        $infoFactura->appendChild($dom->createElement('tipoIdentificacionComprador', $tipoIdentificacion));
        $infoFactura->appendChild($dom->createElement('razonSocialComprador', $customerName ?: 'CONSUMIDOR FINAL'));
        $infoFactura->appendChild($dom->createElement('identificacionComprador', $customerRuc ?: '9999999999999'));
        $infoFactura->appendChild($dom->createElement('direccionComprador', $customerAddress ?: ''));
        $infoFactura->appendChild($dom->createElement('totalSinImpuestos', number_format($order->subtotal, 2, '.', '')));
        $infoFactura->appendChild($dom->createElement('totalDescuento', number_format($order->discount, 2, '.', '')));
        $infoFactura->appendChild($dom->createElement('propina', number_format($order->tip, 2, '.', '')));

        $totalConImpuestos = $dom->createElement('totalConImpuestos');
        $totalImpuesto = $dom->createElement('totalImpuesto');
        $totalImpuesto->appendChild($dom->createElement('codigo', '2'));
        $totalImpuesto->appendChild($dom->createElement('codigoPorcentaje', '2'));
        $totalImpuesto->appendChild($dom->createElement('baseImponible', number_format($order->subtotal - $order->discount, 2, '.', '')));
        $totalImpuesto->appendChild($dom->createElement('valor', number_format($order->tax, 2, '.', '')));
        $totalConImpuestos->appendChild($totalImpuesto);
        $infoFactura->appendChild($totalConImpuestos);

        $infoFactura->appendChild($dom->createElement('importeTotal', number_format($order->total, 2, '.', '')));
        $infoFactura->appendChild($dom->createElement('moneda', $this->config['ambiente'] === '2' ? 'DOLAR' : 'DOLAR'));
        $infoFactura->appendChild($dom->createElement('pagos'));
        $infoFactura->appendChild($dom->createElement('valorRetIva', '0'));
        $infoFactura->appendChild($dom->createElement('valorRetRenta', '0'));
        $factura->appendChild($infoFactura);

        // Detalles
        $detalles = $dom->createElement('detalles');
        foreach ($order->items as $item) {
            $detalle = $dom->createElement('detalle');
            $detalle->appendChild($dom->createElement('codigoPrincipal', $item->product->sku ?? sprintf('P%05d', $item->product_id)));
            $detalle->appendChild($dom->createElement('descripcion', htmlspecialchars($item->product->name)));
            $detalle->appendChild($dom->createElement('cantidad', (string) $item->quantity));
            $detalle->appendChild($dom->createElement('precioUnitario', number_format($item->unit_price, 2, '.', '')));
            $detalle->appendChild($dom->createElement('descuento', '0.00'));
            $detalle->appendChild($dom->createElement('precioTotalSinImpuesto', number_format($item->subtotal, 2, '.', '')));

            $impuestos = $dom->createElement('impuestos');
            $impuesto = $dom->createElement('impuesto');
            $impuesto->appendChild($dom->createElement('codigo', '2'));
            $impuesto->appendChild($dom->createElement('codigoPorcentaje', '2'));
            $impuesto->appendChild($dom->createElement('tarifa', number_format($this->taxRate, 2, '.', '')));
            $base = $item->subtotal * ($item->subtotal / max($order->subtotal, 1));
            $impuesto->appendChild($dom->createElement('baseImponible', number_format($item->subtotal, 2, '.', '')));
            $impuesto->appendChild($dom->createElement('valor', number_format($item->subtotal * ($this->taxRate / 100), 2, '.', '')));
            $impuestos->appendChild($impuesto);
            $detalle->appendChild($impuestos);

            $detalles->appendChild($detalle);
        }
        $factura->appendChild($detalles);

        $xml = $dom->saveXML();

        // Save XML
        $xmlPath = sprintf('invoices/%s/%s.xml', date('Y/m'), $accessKey);
        Storage::disk('public')->put($xmlPath, $xml);

        // Create Invoice record
        Invoice::create([
            'order_id' => $order->id,
            'type' => 'factura',
            'sequential' => $sequential,
            'access_key' => $accessKey,
            'xml_path' => $xmlPath,
            'sri_status' => 'draft',
            'customer_name' => $customerName ?: null,
            'customer_ruc' => $customerRuc ?: null,
            'customer_email' => $customerEmail ?: null,
            'customer_address' => $customerAddress ?: null,
        ]);

        return $accessKey;
    }

    protected function nextSequential(): string
    {
        $last = Invoice::where('sequential', 'like', '001-001-%')->orderBy('id', 'desc')->first();
        $next = $last ? (int) substr($last->sequential, -9) + 1 : 1;

        return sprintf('001-001-%09d', $next);
    }

    protected function generateAccessKey(string $sequential): string
    {
        $date = now()->format('dmy');
        $tipoComp = '01';
        $ruc = $this->config['ruc'];
        $ambiente = $this->config['ambiente'];
        $serie = '001001';
        $codigoNumerico = str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
        $tipoEmision = $this->config['tipo_emision'];

        $digitosVerificadores = '21';

        $base = $date.$tipoComp.$ruc.$ambiente.$serie.$sequential.$codigoNumerico.$tipoEmision;
        $mod = $this->mod11($base);

        return $base.$mod;
    }

    protected function mod11(string $number): string
    {
        $factors = [2, 3, 4, 5, 6, 7];
        $total = 0;
        $len = strlen($number);
        $factorIndex = 0;

        for ($i = $len - 1; $i >= 0; $i--) {
            $total += (int) $number[$i] * $factors[$factorIndex];
            $factorIndex = ($factorIndex + 1) % 6;
        }

        $remainder = $total % 11;
        $checkDigit = 11 - $remainder;

        if ($checkDigit === 11) {
            return '0';
        }
        if ($checkDigit === 10) {
            return '1';
        }

        return (string) $checkDigit;
    }
}
