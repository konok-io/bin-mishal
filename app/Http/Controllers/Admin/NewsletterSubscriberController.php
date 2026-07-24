<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::latest();

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)->where('is_verified', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_verified', false);
            } elseif ($request->status === 'unsubscribed') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        $subscribers = $query->paginate(25);

        return view('admin.newsletter-subscribers.index', compact('subscribers'));
    }

    public function show($id)
    {
        $subscriber = NewsletterSubscriber::findOrFail($id);
        return view('admin.newsletter-subscribers.show', compact('subscriber'));
    }

    public function destroy($id)
    {
        $subscriber = NewsletterSubscriber::findOrFail($id);
        $subscriber->delete();

        return redirect()->route('admin.newsletter-subscribers.index')
            ->with('success', 'Subscriber deleted successfully.');
    }

    public function verify($id)
    {
        $subscriber = NewsletterSubscriber::findOrFail($id);
        $subscriber->verify();

        return redirect()->back()
            ->with('success', 'Subscriber verified successfully.');
    }

    public function unsubscribe($id)
    {
        $subscriber = NewsletterSubscriber::findOrFail($id);
        $subscriber->unsubscribe();

        return redirect()->route('admin.newsletter-subscribers.index')
            ->with('success', 'Subscriber unsubscribed successfully.');
    }

    public function subscribe($id)
    {
        $subscriber = NewsletterSubscriber::findOrFail($id);
        $subscriber->is_active = true;
        $subscriber->is_verified = true;
        $subscriber->subscribed_at = now();
        $subscriber->unsubscribed_at = null;
        $subscriber->save();

        return redirect()->route('admin.newsletter-subscribers.index')
            ->with('success', 'Subscriber subscribed successfully.');
    }

    public function export()
    {
        $subscribers = NewsletterSubscriber::active()
            ->select('email', 'name', 'subscribed_at')
            ->get();

        $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(['Email', 'Name', 'Subscribed At']);
        
        foreach ($subscribers as $subscriber) {
            $csv->insertOne([
                $subscriber->email,
                $subscriber->name,
                $subscriber->subscribed_at?->format('Y-m-d H:i:s'),
            ]);
        }

        return response((string) $csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="subscribers.csv"',
        ]);
    }
}
