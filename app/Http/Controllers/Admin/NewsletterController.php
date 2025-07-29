<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::latest();

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'confirmed':
                    $query->confirmed();
                    break;
                case 'pending':
                    $query->whereNull('confirmed_at');
                    break;
                case 'unsubscribed':
                    $query->whereNotNull('unsubscribed_at');
                    break;
                case 'active':
                    $query->active();
                    break;
            }
        }

        // Search by email
        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        $subscribers = $query->paginate(20);

        // Statistics
        $stats = [
            'total' => NewsletterSubscriber::count(),
            'confirmed' => NewsletterSubscriber::confirmed()->count(),
            'pending' => NewsletterSubscriber::whereNull('confirmed_at')->count(),
            'active' => NewsletterSubscriber::active()->count(),
            'unsubscribed' => NewsletterSubscriber::whereNotNull('unsubscribed_at')->count(),
        ];

        return view('admin.newsletter.index', compact('subscribers', 'stats'));
    }

    public function export()
    {
        $subscribers = NewsletterSubscriber::active()
            ->select('email', 'name', 'confirmed_at', 'language_code')
            ->get();

        $csv = "Email,Name,Confirmed At,Language\n";
        
        foreach ($subscribers as $subscriber) {
            $csv .= sprintf(
                "%s,%s,%s,%s\n",
                $subscriber->email,
                $subscriber->name ?? '',
                $subscriber->confirmed_at ? $subscriber->confirmed_at->format('Y-m-d H:i:s') : '',
                $subscriber->language_code
            );
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="subscribers.csv"');
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();

        return redirect()->route('admin.newsletter.index')
            ->with('success', 'Suscriptor eliminado exitosamente');
    }
}