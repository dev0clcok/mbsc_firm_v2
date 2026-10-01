<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnquiryRequest;
use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Throwable;

class EnquiryController extends Controller
{
    /** Enquiries one connection may send in each window. */
    private const LIMIT = 5;

    private const WINDOW_SECONDS = 600;

    public function store(StoreEnquiryRequest $request): RedirectResponse
    {
        // Bots that fill the honeypot get the same response as a real
        // visitor, but nothing is stored or emailed.
        if ($request->isSpam()) {
            return back()->with('success', 'Your enquiry has been sent.');
        }

        // Only enquiries that were stored count towards the limit, so a
        // visitor correcting mistakes in the form is never locked out.
        $key = 'enquiries-sent:'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, self::LIMIT)) {
            $minutes = max(1, (int) ceil(RateLimiter::availableIn($key) / 60));

            return back()->withInput()->withErrors([
                'form' => 'Several enquiries have already been sent from this connection, so this one was not sent. '
                    .'Try again in '.$minutes.' '.($minutes === 1 ? 'minute' : 'minutes').'.',
            ]);
        }

        $enquiry = Enquiry::create([
            ...$request->safe()->except('website'),
            'ip_address' => $request->ip(),
        ]);

        RateLimiter::hit($key, self::WINDOW_SECONDS);

        $this->notify($enquiry);

        return back()->with('success', 'Your enquiry has been sent.');
    }

    private function notify(Enquiry $enquiry): void
    {
        $to = SiteSetting::get('enquiry_email') ?: SiteSetting::get('email');

        if (! $to) {
            return;
        }

        // The enquiry is already saved; a mail failure must not turn a
        // successful submission into an error for the visitor.
        try {
            Mail::to($to)->send(new EnquiryReceived($enquiry));
        } catch (Throwable $e) {
            Log::error('Enquiry notification could not be queued.', [
                'enquiry_id' => $enquiry->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
