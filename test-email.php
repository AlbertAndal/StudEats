<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

echo "Testing email configuration...\n\n";
echo "Mail Driver: " . config('mail.default') . "\n";
echo "Mail Host: " . config('mail.mailers.smtp.host') . "\n";
echo "Mail Port: " . config('mail.mailers.smtp.port') . "\n";
echo "Mail Username: " . config('mail.mailers.smtp.username') . "\n";
echo "Mail From: " . config('mail.from.address') . "\n\n";

echo "Attempting to send test email...\n";

try {
    Mail::raw('🍽️ This is a test email from StudEats! If you receive this, your Gmail SMTP configuration is working correctly.', function ($message) {
        $message->to('ryuigarena@gmail.com')
            ->subject('StudEats Test Email - ' . date('Y-m-d H:i:s'));
    });
    
    echo "✓ Email sent successfully!\n";
    echo "Check your inbox at ryuigarena@gmail.com (and spam folder)\n";
} catch (\Exception $e) {
    echo "✗ Email failed: " . $e->getMessage() . "\n";
    echo "\nFull error:\n";
    echo $e->getTraceAsString() . "\n";
}
