<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Happinest - Message Confirmation</title>
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #AC5512 0%, #8a4510 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .message-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #AC5512;
            margin: 20px 0;
        }
        .appointment-details {
            background: #e8f4fd;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #2196F3;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 0.9em;
        }
        .subject-badge {
            display: inline-block;
            background: #AC5512;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Happinest Animal Shelter</h1>
        <p>Thank you for contacting us!</p>
    </div>
    
    <div class="content">
        <p>Dear <strong>{{ $data['name'] }}</strong>,</p>
        
        <p>We have received your message and will get back to you as soon as possible. Here's a summary of your inquiry:</p>
        
        <div class="subject-badge">
            {{ ucfirst($data['subject']) }}
        </div>
        
        <div class="message-box">
            <h3>Your Message:</h3>
            <p>{{ $data['message'] }}</p>
        </div>
        
        @if($data['subject'] === 'adoption' && ($data['visitDate'] || $data['petInterest']))
        <div class="appointment-details">
            <h3>Appointment Details:</h3>
            @if($data['visitDate'])
                <p><strong>Preferred Date:</strong> {{ \Carbon\Carbon::parse($data['visitDate'])->format('F j, Y') }}</p>
            @endif
            @if($data['visitTime'])
                <p><strong>Preferred Time:</strong> {{ $data['visitTime'] }}</p>
            @endif
            @if($data['petInterest'])
                <p><strong>Pet Interested In:</strong> {{ $data['petInterest'] }}</p>
            @endif
        </div>
        
        <p><strong>Note:</strong> This appointment is not confirmed until you receive a confirmation from our team. We will contact you shortly to finalize the details.</p>
        @endif
        
        <p><strong>Our Contact Information:</strong><br>
        📍 Alsion, 6400 Sønderborg<br>
        📞 +45 20865858<br>
        ✉️ info@happinest.org</p>
        
        <p><strong>Opening Hours:</strong><br>
        Monday-Friday: 8:00 AM - 6:00 PM<br>
        Saturday-Sunday: 10:00 AM - 4:00 PM</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} Happinest Animal Shelter. All rights reserved.</p>
        <p>This is an automated message. Please do not reply to this email.</p>
    </div>
</body>
</html>