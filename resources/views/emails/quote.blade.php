<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Vehicle Quote from Fleethub</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            background-color: #1b9e8c;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 15px;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .button {
            display: inline-block;
            background-color: #1b9e8c;
            color: white !important;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .vehicle-info {
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .footer {
            font-size: 12px;
            text-align: center;
            margin-top: 20px;
            color: #666;
            padding: 10px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="Fleethub Logo" class="logo">
        <h1>Your Vehicle Quote</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $customer->name }},</p>
        
        <p>Thank you for your interest in leasing a vehicle through Fleethub.</p>
        
        <div class="vehicle-info">
            <h3>{{ $quote->vehicle->make }} {{ $quote->vehicle->model }}</h3>
            <p><strong>Specification:</strong> {{ $quote->vehicle->specification }}</p>
            <p><strong>Finance Type:</strong> {{ $quote->finance_type }}</p>
            <p><strong>Monthly Payment:</strong> £{{ number_format($quote->monthly_payment, 2) }}</p>
        </div>
        
        <p>We are pleased to provide you with a detailed quote for this vehicle. You can view all the details by clicking the button below:</p>
        
        <p style="text-align: center;">
            <a href="{{ $quoteUrl }}" class="button">View Your Quote</a>
        </p>
        
        <p>This quote is valid for 28 days from {{ $quote->created_at->format('d/m/Y') }} and is subject to vehicle availability and credit approval.</p>
        
        <p>If you have any questions about this quote or would like to proceed with an order, please don't hesitate to contact us.</p>
        
        <p>Best regards,<br>
        The Fleethub Team<br>
        07983 415924</p>
    </div>
    
    <div class="footer">
        <p>Fleethub Limited | <a href="mailto:info@fleethub.co.uk">info@fleethub.co.uk</a> | 07983 415924</p>
        <p>Finchale House, Belmont Business Park, Durham, England, DH1 1TW</p>
        <p>This email and any attachments are confidential and may contain legally privileged information.</p>
    </div>
</body>
</html>