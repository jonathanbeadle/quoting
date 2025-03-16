<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Vehicle Order from Fleethub</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .wrapper {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .container {
            padding: 20px;
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
            border-radius: 5px;
            margin: 20px 0;
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
            background-color: #ffffff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 0;
        }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
        <tr>
            <td>
                <div class="wrapper">
                    <div class="header">
                        <img src="{{ config('app.url') }}/images/logo.png" alt="Fleethub Logo" class="logo">
                        <h1>Your Vehicle Order</h1>
                    </div>
                    
                    <div class="container">
                        <div class="content">
                            <p>Dear {{ $customer->name }},</p>
                            
                            <p>Thank you for placing your order with Fleethub.</p>
                            
                            <div class="vehicle-info">
                                <h3>{{ $order->vehicle->make }} {{ $order->vehicle->model }}</h3>
                                <p><strong>Specification:</strong> {{ $order->vehicle->specification }}</p>
                                <p><strong>Finance Type:</strong> {{ $order->finance_type }}</p>
                                <p><strong>Contract Length:</strong> {{ $order->contract_length }} months</p>
                                <p><strong>Annual Mileage:</strong> {{ number_format($order->annual_mileage) }} miles</p>
                                <p><strong>Monthly Payment:</strong> £{{ number_format($order->monthly_payment, 2) }}</p>
                            </div>
                            
                            <p>You can view your complete order details and next steps by clicking the button below:</p>
                            
                            <p style="text-align: center;">
                                <a href="{{ $orderUrl }}" class="button">View Your Order</a>
                            </p>
                            
                            <p>This order is valid for 28 days from {{ $order->created_at->format('d/m/Y') }} and is subject to vehicle availability and credit approval.</p>
                            
                            <p>A member of our team will be in touch shortly to guide you through the next steps. If you have any questions in the meantime, please don't hesitate to contact us.</p>
                            
                            <p>Best regards,<br>
                            The Fleethub Team<br>
                            07983 415924</p>
                        </div>
                        
                        <div class="footer">
                            <p>Fleethub Limited | <a href="mailto:info@fleethub.co.uk">info@fleethub.co.uk</a> | 07983 415924</p>
                            <p>Finchale House, Belmont Business Park, Durham, England, DH1 1TW</p>
                            <p>This email and any attachments are confidential and may contain legally privileged information.</p>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>