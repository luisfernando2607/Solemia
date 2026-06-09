<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\RestaurantSetting;
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
        $s = RestaurantSetting::current();
        $this->restaurant = [
            'name' => $s->trade_name,
            'ruc' => $s->ruc ?? '',
            'address' => $s->address ?? '',
            'phone' => $s->phone ?? '',
            'tax_rate' => (float)$s->tax_rate,
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
