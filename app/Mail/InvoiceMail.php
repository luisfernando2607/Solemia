<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public ?\App\Models\Payment $payment = null;
    public ?Invoice $invoice;
    public array $restaurant;

    public function __construct(Order $order, ?Invoice $invoice = null)
    {
        $this->order = $order;
        $this->payment = $order->payments()->where('status', 'approved')->first();
        $this->invoice = $invoice;
        $this->restaurant = [
            'name' => env('SRI_NOMBRE_COMERCIAL', 'Solemia'),
            'ruc' => env('SRI_RUC', '9999999999999'),
            'address' => env('SRI_DIR_MATRIZ', 'Av. Principal'),
            'phone' => env('APP_PHONE', ''),
        ];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Factura electrónica - ' . ($this->invoice?->sequential ?? 'Ticket #' . $this->order->id),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'cashier.receipt',
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if ($this->invoice && $this->invoice->xml_path && Storage::disk('public')->exists($this->invoice->xml_path)) {
            $attachments[] = Attachment::fromStorageDisk('public', $this->invoice->xml_path)
                ->as('factura_' . $this->invoice->sequential . '.xml')
                ->withMime('application/xml');
        }

        return $attachments;
    }
}
