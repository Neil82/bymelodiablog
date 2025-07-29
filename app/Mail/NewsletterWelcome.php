<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public $subscriber;
    public $unsubscribeUrl;

    public function __construct(NewsletterSubscriber $subscriber)
    {
        $this->subscriber = $subscriber;
        $this->unsubscribeUrl = route('newsletter.unsubscribe', $subscriber->unsubscribe_token);
    }

    public function envelope()
    {
        return new Envelope(
            subject: __('ui.newsletter.welcome') . ' - ' . config('app.name')
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.newsletter-welcome',
        );
    }
}