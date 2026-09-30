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
use Throwable;

class EnquiryController extends Controller
{
    public function store(StoreEnquiryRequest $request): RedirectResponse
    {
        // Bots that fill the honeypot get the same response as a real
        // visitor, but nothing is stored or emailed.
        if (! $request->isSpam()) {
            $enquiry = Enquiry::create([
                ...$request->safe()->except('website'),
                'ip_address' => $request->ip(),
            ]);

            $this->notify($enquiry);
        }

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
