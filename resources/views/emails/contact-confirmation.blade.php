<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Contacting Us</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #2563eb; padding: 30px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
        <h1 style="color: #ffffff; margin: 0;">Thank You for Contacting Us!</h1>
    </div>

    <div style="background-color: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-radius: 5px;">
        <p>Dear {{ $lead->name }},</p>
        
        <p>Thank you for reaching out to <strong>MODERN BUSINESS SOLUTION & CONSULTANCY</strong>. We have received your inquiry and our team will review it shortly.</p>

        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 0; font-weight: bold;">Your Inquiry Summary:</p>
            <p style="margin: 5px 0 0 0;">{{ \Illuminate\Support\Str::limit($lead->message, 150) }}</p>
        </div>

        <p>We typically respond within 24-48 hours during business days. If your inquiry is urgent, please feel free to call us at <strong>+88 01868-196716</strong>.</p>

        <p>In the meantime, you can:</p>
        <ul>
            <li>Visit our website to learn more about our services</li>
            <li>Read our blog for the latest business insights</li>
            <li>Check out our case studies to see how we've helped other businesses</li>
        </ul>

        <p>We look forward to assisting you with your business needs.</p>

        <p style="margin-top: 30px;">
            Best regards,<br>
            <strong>The MBSC Team</strong>
        </p>
    </div>

    <div style="margin-top: 20px; padding: 15px; background-color: #f3f4f6; border-radius: 5px; font-size: 12px; color: #6b7280; text-align: center;">
        <p style="margin: 0;">
            <strong>MODERN BUSINESS SOLUTION & CONSULTANCY</strong><br>
            Email: mbscfirm@gmail.com<br>
            Phone: +88 01868-196716<br>
            Address: Burma Razu Building (02nd Floor), Besides Fancy Tailors, Kotowali, Chattogram
        </p>
    </div>
</body>
</html>
