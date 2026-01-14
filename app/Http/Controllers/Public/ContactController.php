<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\ContactInformation;
use App\Models\Lead;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function index(): Response
    {
        $services = Service::parents()->active()->get();
        $contactInfo = ContactInformation::orderBy('sort_order')->get();

        return Inertia::render('public/Contact', [
            'services' => $services,
            'contactInfo' => $contactInfo,
        ]);
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        $lead = Lead::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'service_id' => $request->service_id,
            'message' => $request->message,
            'source' => 'contact_form',
            'status' => 'new',
        ]);

        // Send notification to admin
        try {
            Mail::to(config('mail.admin_email', 'mbscfirm@gmail.com'))
                ->send(new \App\Mail\NewLeadNotification($lead));

            // Send confirmation to user
            Mail::to($lead->email)
                ->send(new \App\Mail\ContactConfirmation($lead));
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send email: ' . $e->getMessage());
        }

        return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }
}
