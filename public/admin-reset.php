<?php
/**
 * EMERGENCY ADMIN PASSWORD RESET
 * Upload this to your public folder and visit it in browser
 * DELETE IMMEDIATELY AFTER USE!
 */

// Bootstrap Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Set proper content type
header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Reset - StudEats</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Courier New', monospace; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        h1 { color: #667eea; margin-bottom: 20px; font-size: 24px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .credentials { background: #f8f9fa; border: 2px solid #667eea; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .credentials strong { color: #667eea; }
        .warning { background: #fff3cd; border: 1px solid #ffc107; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0; }
        a { 
            display: inline-block;
            background: #667eea; 
            color: white; 
            padding: 12px 30px; 
            text-decoration: none; 
            border-radius: 5px; 
            margin-top: 20px;
            transition: background 0.3s;
        }
        a:hover { background: #764ba2; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 StudEats Admin Password Reset</h1>
        
        <?php
        try {
            // Import necessary classes
            use App\Models\User;
            use Illuminate\Support\Facades\Hash;
            
            $adminEmail = 'admin@studeats.com';
            $adminPassword = 'admin123';
            
            // Check if admin exists
            $admin = User::where('email', $adminEmail)->first();
            
            if ($admin) {
                // Update existing admin
                $admin->password = Hash::make($adminPassword);
                $admin->email_verified_at = now();
                $admin->role = 'super_admin';
                $admin->is_active = true;
                $admin->suspended_at = null;
                $admin->save();
                
                echo '<div class="success">✅ <strong>SUCCESS!</strong> Admin password has been reset successfully.</div>';
                
                echo '<div class="credentials">';
                echo '<p><strong>Email:</strong> ' . htmlspecialchars($adminEmail) . '</p>';
                echo '<p><strong>Password:</strong> ' . htmlspecialchars($adminPassword) . '</p>';
                echo '<p><strong>Role:</strong> ' . htmlspecialchars($admin->role) . '</p>';
                echo '<p><strong>Status:</strong> Active</p>';
                echo '<p><strong>Admin ID:</strong> #' . $admin->id . '</p>';
                echo '</div>';
                
                echo '<div class="warning">⚠️ <strong>SECURITY WARNING:</strong><br>';
                echo '1. Delete this file immediately: <code>public/admin-reset.php</code><br>';
                echo '2. Change your password after logging in<br>';
                echo '3. Clear your browser cache if login still fails</div>';
                
                echo '<a href="/admin/login">→ Go to Admin Login Page</a>';
                
            } else {
                // Create new admin if doesn't exist
                $admin = User::create([
                    'name' => 'StudEats Admin',
                    'email' => $adminEmail,
                    'password' => Hash::make($adminPassword),
                    'email_verified_at' => now(),
                    'role' => 'super_admin',
                    'is_active' => true,
                    'timezone' => 'Asia/Manila',
                ]);
                
                echo '<div class="success">✅ <strong>SUCCESS!</strong> Admin account has been created.</div>';
                
                echo '<div class="credentials">';
                echo '<p><strong>Email:</strong> ' . htmlspecialchars($adminEmail) . '</p>';
                echo '<p><strong>Password:</strong> ' . htmlspecialchars($adminPassword) . '</p>';
                echo '<p><strong>Role:</strong> super_admin</p>';
                echo '<p><strong>Admin ID:</strong> #' . $admin->id . '</p>';
                echo '</div>';
                
                echo '<div class="warning">⚠️ <strong>SECURITY WARNING:</strong><br>';
                echo '1. Delete this file immediately: <code>public/admin-reset.php</code><br>';
                echo '2. Change your password after logging in</div>';
                
                echo '<a href="/admin/login">→ Go to Admin Login Page</a>';
            }
            
        } catch (Exception $e) {
            echo '<div class="error">❌ <strong>ERROR:</strong> ' . htmlspecialchars($e->getMessage()) . '</div>';
            echo '<div class="info"><strong>Debug Information:</strong><br>';
            echo 'File: ' . htmlspecialchars($e->getFile()) . '<br>';
            echo 'Line: ' . $e->getLine() . '</div>';
        }
        ?>
    </div>
</body>
</html>
