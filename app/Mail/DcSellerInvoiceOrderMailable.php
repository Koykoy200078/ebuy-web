<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DcSellerInvoiceOrderMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $order;
    public $transactioninfo;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order, $transactioninfo)
    {
        $this->order = $order;
        $this->transactioninfo = $transactioninfo;
        
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function build()
    {
        $transactionId = $this->transactioninfo->image_pdf;
        $subject = "Your Order Invoice";
        $filePath = public_path($transactionId);
        return $this->subject($subject)
                    ->view('admin.invoice.seller-generate-invoice')
                    ->attach($filePath);
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Invoice Order Mailable',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'admin.invoice.seller-generate-invoice',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }

}
