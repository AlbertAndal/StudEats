<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'StudEats' }}</title>
    <style>
        /* Reset styles */
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        /* Base styles */
        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            background-color: #f4f4f4;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
        }

        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        /* Header */
        .email-header {
            background: linear-gradient(135deg, #16a085 0%, #2ecc71 100%);
            padding: 30px 20px;
            text-align: center;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #ffffff;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            font-size: 32px;
        }

        /* Content */
        .email-content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .content-section {
            margin-bottom: 25px;
        }

        .content-section p {
            margin: 0 0 15px 0;
            line-height: 1.6;
        }

        .content-section ul {
            margin: 10px 0;
            padding-left: 20px;
        }

        .content-section li {
            margin-bottom: 8px;
        }

        /* Buttons */
        .button {
            display: inline-block;
            padding: 14px 28px;
            background-color: #16a085;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #138f7a;
        }

        /* Highlight boxes */
        .highlight-box {
            background-color: #f8f9fa;
            border-left: 4px solid #16a085;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 6px 6px 0;
        }

        .highlight-box strong {
            color: #16a085;
        }

        /* Code/ID display */
        .code-display {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 15px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 2px;
            margin: 15px 0;
        }

        /* Footer */
        .email-footer {
            background-color: #2c3e50;
            color: #95a5a6;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }

        .email-footer a {
            color: #16a085;
            text-decoration: none;
        }

        .email-footer a:hover {
            text-decoration: underline;
        }

        .social-links {
            margin: 20px 0;
        }

        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #16a085;
            text-decoration: none;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }
            
            .email-content {
                padding: 25px 20px !important;
            }
            
            .email-header {
                padding: 25px 20px !important;
            }
            
            .logo {
                font-size: 24px !important;
            }
            
            .button {
                display: block !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .email-container {
                background-color: #1a1a1a !important;
            }
            
            .email-content {
                color: #e0e0e0 !important;
            }
            
            .greeting {
                color: #ffffff !important;
            }
            
            .highlight-box {
                background-color: #2d2d2d !important;
                color: #e0e0e0 !important;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <a href="{{ config('app.url') }}" class="logo">
                <span class="logo-icon">🍽️</span>
                StudEats
            </a>
        </div>

        <!-- Content -->
        <div class="email-content">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>StudEats</strong> - Smart Meal Planning for Students</p>
            <p>Helping Filipino students eat healthy on a budget</p>
            
            <div class="social-links">
                <a href="#">Facebook</a>
                <a href="#">Instagram</a>
                <a href="#">Twitter</a>
            </div>
            
            <p>
                <small>
                    You received this email because you have an account with StudEats.<br>
                    If you no longer wish to receive these emails, you can 
                    <a href="#">unsubscribe here</a>.
                </small>
            </p>
            
            <p>
                <small>
                    © {{ date('Y') }} StudEats. All rights reserved.<br>
                    <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
                </small>
            </p>
        </div>
    </div>
</body>
</html>