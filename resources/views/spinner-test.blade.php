<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spinner Animation Test</title>
    <style>
        body {
            margin: 0;
            padding: 40px;
            font-family: system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            padding: 60px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 600px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2.5rem;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 40px;
            font-size: 1.1rem;
        }
        
        .spinner-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px;
            margin: 40px 0;
        }
        
        .spinner-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }
        
        .spinner-label {
            font-size: 0.9rem;
            color: #666;
            font-weight: 600;
        }
        
        /* Pure CSS Spinner Animation */
        .spinner {
            display: inline-block;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        
        .spinner-xs {
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top-color: #3b82f6;
            border-right-color: #3b82f6;
        }
        
        .spinner-sm {
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top-color: #10b981;
            border-right-color: #10b981;
        }
        
        .spinner-md {
            width: 24px;
            height: 24px;
            border: 2px solid transparent;
            border-top-color: #f59e0b;
            border-right-color: #f59e0b;
        }
        
        .spinner-lg {
            width: 32px;
            height: 32px;
            border: 3px solid transparent;
            border-top-color: #ef4444;
            border-right-color: #ef4444;
        }
        
        .spinner-xl {
            width: 40px;
            height: 40px;
            border: 4px solid transparent;
            border-top-color: #8b5cf6;
            border-right-color: #8b5cf6;
        }
        
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
        
        .status {
            margin-top: 40px;
            padding: 20px;
            background: #f0fdf4;
            border: 2px solid #22c55e;
            border-radius: 10px;
            color: #166534;
            font-weight: 600;
        }
        
        .test-info {
            margin-top: 30px;
            padding: 20px;
            background: #f3f4f6;
            border-radius: 10px;
            text-align: left;
            font-size: 0.9rem;
            color: #374151;
        }
        
        .test-info strong {
            color: #1f2937;
        }
        
        .refresh-btn {
            margin-top: 30px;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .refresh-btn:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔄 Spinner Animation Test</h1>
        <p class="subtitle">Testing if CSS animations are working</p>
        
        <div class="spinner-container">
            <div class="spinner-item">
                <div class="spinner spinner-xs"></div>
                <span class="spinner-label">XS</span>
            </div>
            <div class="spinner-item">
                <div class="spinner spinner-sm"></div>
                <span class="spinner-label">SM</span>
            </div>
            <div class="spinner-item">
                <div class="spinner spinner-md"></div>
                <span class="spinner-label">MD</span>
            </div>
            <div class="spinner-item">
                <div class="spinner spinner-lg"></div>
                <span class="spinner-label">LG</span>
            </div>
            <div class="spinner-item">
                <div class="spinner spinner-xl"></div>
                <span class="spinner-label">XL</span>
            </div>
        </div>
        
        <div class="status">
            ✅ If you see these spinners rotating, CSS animations are working!
        </div>
        
        <div class="test-info">
            <strong>What to check:</strong><br>
            • All 5 spinners should be spinning smoothly<br>
            • Each spinner is a different size and color<br>
            • Animation should be continuous and smooth<br>
            • If NOT spinning: Clear browser cache (Ctrl+Shift+Delete) or try hard refresh (Ctrl+F5)
        </div>
        
        <button class="refresh-btn" onclick="location.reload()">
            🔄 Refresh Test
        </button>
        
        <div style="margin-top: 30px; padding: 15px; background: #e0e7ff; border-radius: 8px;">
            <strong>Next Steps:</strong><br>
            <a href="{{ url('/loading-spinners-demo') }}" style="color: #4f46e5; text-decoration: none; font-weight: 600;">
                → View Full FlyonUI Spinner Demo
            </a>
        </div>
    </div>
</body>
</html>
