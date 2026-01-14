<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
        <h1 style="color: #2563eb; margin-top: 0;">New Contact Form Submission</h1>
    </div>

    <div style="background-color: #ffffff; padding: 20px; border: 1px solid #e5e7eb; border-radius: 5px;">
        <h2 style="color: #1f2937; margin-top: 0;">Contact Information</h2>
        
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px 0; font-weight: bold; width: 150px;">Name:</td>
                <td style="padding: 8px 0;">{{ $lead->name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">Email:</td>
                <td style="padding: 8px 0;"><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></td>
            </tr>
            @if($lead->phone)
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">Phone:</td>
                <td style="padding: 8px 0;"><a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a></td>
            </tr>
            @endif
            @if($lead->company)
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">Company:</td>
                <td style="padding: 8px 0;">{{ $lead->company }}</td>
            </tr>
            @endif
            @if($lead->service)
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">Service:</td>
                <td style="padding: 8px 0;">{{ $lead->service->title }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding: 8px 0; font-weight: bold; vertical-align: top;">Message:</td>
                <td style="padding: 8px 0;">{{ $lead->message }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 20px; padding: 15px; background-color: #f3f4f6; border-radius: 5px; font-size: 12px; color: #6b7280;">
        <p style="margin: 0;">This email was sent from the MODERN BUSINESS SOLUTION & CONSULTANCY website contact form.</p>
        <p style="margin: 5px 0 0 0;">Submitted on: {{ $lead->created_at->format('F j, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>
