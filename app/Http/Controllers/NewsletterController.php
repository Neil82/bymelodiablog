<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterConfirmation;
use App\Mail\NewsletterWelcome;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255'
        ]);

        // Check if already subscribed
        $existingSubscriber = NewsletterSubscriber::where('email', $request->email)->first();

        if ($existingSubscriber) {
            if ($existingSubscriber->isConfirmed()) {
                return response()->json([
                    'success' => false,
                    'message' => __('ui.newsletter.already_subscribed')
                ], 400);
            } else {
                // Resend confirmation email
                $this->sendConfirmationEmail($existingSubscriber);
                
                return response()->json([
                    'success' => true,
                    'message' => __('ui.newsletter.confirmation_resent')
                ]);
            }
        }

        // Create new subscriber
        $subscriber = NewsletterSubscriber::create([
            'email' => $request->email,
            'name' => $request->name,
            'confirmation_token' => NewsletterSubscriber::generateConfirmationToken(),
            'unsubscribe_token' => NewsletterSubscriber::generateUnsubscribeToken(),
            'language_code' => app()->getLocale(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Send confirmation email
        $this->sendConfirmationEmail($subscriber);

        return response()->json([
            'success' => true,
            'message' => __('ui.newsletter.check_email_to_confirm')
        ]);
    }

    /**
     * Confirm subscription
     */
    public function confirm($token)
    {
        $subscriber = NewsletterSubscriber::where('confirmation_token', $token)->first();

        if (!$subscriber) {
            return redirect()->route('home')->with('error', __('ui.newsletter.invalid_token'));
        }

        if ($subscriber->isConfirmed()) {
            return redirect()->route('home')->with('info', __('ui.newsletter.already_confirmed'));
        }

        $subscriber->confirm();

        // Send welcome email
        $this->sendWelcomeEmail($subscriber);

        return redirect()->route('home')->with('success', __('ui.newsletter.confirmed_successfully'));
    }

    /**
     * Unsubscribe
     */
    public function unsubscribe($token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->first();

        if (!$subscriber) {
            return redirect()->route('home')->with('error', __('ui.newsletter.invalid_unsubscribe_token'));
        }

        $subscriber->unsubscribe();

        return redirect()->route('home')->with('success', __('ui.newsletter.unsubscribed_successfully'));
    }

    /**
     * Send confirmation email
     */
    private function sendConfirmationEmail(NewsletterSubscriber $subscriber)
    {
        try {
            Mail::to($subscriber->email)->send(new NewsletterConfirmation($subscriber));
        } catch (\Exception $e) {
            \Log::error('Failed to send confirmation email: ' . $e->getMessage());
        }
    }

    /**
     * Send welcome email
     */
    private function sendWelcomeEmail(NewsletterSubscriber $subscriber)
    {
        try {
            Mail::to($subscriber->email)->send(new NewsletterWelcome($subscriber));
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }
    }
}