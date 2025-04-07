<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class BookingEventInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $bookingEvent;
    public $selectedAssets;
    public $bookingSlots;

    /**
     * Create a new message instance.
     */
    public function __construct($bookingEvent, $selectedAssets, $bookingSlots)
    {
        $this->bookingEvent = $bookingEvent;
        $this->selectedAssets = $selectedAssets;
        $this->bookingSlots = $bookingSlots;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Event Invoice Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'booking_events.invoice',
            with: [
                'bookingEvent' => $this->bookingEvent,
                'selectedAssets' => $this->selectedAssets,
                'bookingSlots' => $this->bookingSlots,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
