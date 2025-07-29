<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $subscriber;
    public $confirmationUrl;

    public function __construct(NewsletterSubscriber $subscriber)
    {
        $this->subscriber = $subscriber;
        $this->confirmationUrl = route('newsletter.confirm', $subscriber->confirmation_token);
    }

    public function envelope()
    {
        return new Envelope(
            subject: __('ui.newsletter.confirm_subscription') . ' - ' . config('app.name')
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.newsletter-confirmation',
        );
    }
}