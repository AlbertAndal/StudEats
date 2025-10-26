<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            // Log the contact form submission
            Log::info('Contact form submission', [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => substr($request->message, 0, 100) . '...',
            ]);

            // In a real application, you would send an email here
            // For now, we'll just flash a success message
            
            return redirect()->route('contact.show')->with('success', 'Thank you for your message! We\'ll get back to you soon at ' . $request->email);
        } catch (\Exception $e) {
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'email' => $request->email,
            ]);

            return redirect()->route('contact.show')->with('error', 'Sorry, there was an error sending your message. Please try again or contact us directly at studeats23@gmail.com');
        }
    }
}