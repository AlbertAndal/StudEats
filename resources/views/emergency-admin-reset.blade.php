<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reset - StudEats</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        h1 { color: #667eea; margin-bottom: 30px; font-size: 28px; text-align: center; }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .credentials {
            background: #f8f9fa;
            border: 2px solid #667eea;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .credentials p { margin: 10px 0; }
        .credentials strong { color: #667eea; }
        .warning {
            background: #fff3cd;
            border: 2px solid #ffc107;
            color: #856404;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        a {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
        }
        a:hover { background: #764ba2; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Admin Password Reset</h1>
        
        @if(isset($message))
            <div class="success">{!! $message !!}</div>
            
            <div class="credentials">
                <p><strong>Email:</strong> {{ $admin->email }}</p>
                <p><strong>Password:</strong> admin123</p>
                <p><strong>Role:</strong> {{ $admin->role }}</p>
                <p><strong>Status:</strong> Active</p>
                <p><strong>Admin ID:</strong> #{{ $admin->id }}</p>
            </div>
            
            <div class="warning">
                <strong>⚠️ SECURITY WARNING:</strong><br>
                1. Delete this route from routes/web.php<br>
                2. Change your password after logging in<br>
                3. Clear browser cache if needed
            </div>
            
            <a href="/admin/login">→ Go to Admin Login</a>
        @endif
        
        @if(isset($error))
            <div class="error">❌ Error: {{ $error }}</div>
        @endif
    </div>
</body>
</html>
