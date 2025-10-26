@extends('layouts.app')

@section('title', 'Terms of Service')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 overflow-x-hidden">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="px-8 py-6 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-3xl font-bold text-gray-900">Terms of Service</h1>
                        <p class="mt-2 text-sm text-gray-600">Last updated: {{ date('F j, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-4 py-8 sm:px-8">
                <div class="prose prose-lg prose-gray max-w-none overflow-hidden">
                    <style>
                        .prose { word-wrap: break-word; overflow-wrap: break-word; }
                        .prose * { max-width: 100%; }
                    </style>
                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Agreement to Terms</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            By accessing and using StudEats ("Service"), you agree to be bound by these Terms of Service ("Terms"). If you disagree with any part of these terms, then you may not access the Service.
                        </p>
                        <p class="text-gray-700 leading-relaxed">
                            These Terms apply to all visitors, users, and others who access or use the Service.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Description of Service</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            StudEats is a meal planning application designed specifically for Filipino students. Our Service provides:
                        </p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700">
                            <li>Personalized meal recommendations based on budget and dietary preferences</li>
                            <li>BMI calculations and health-conscious meal planning</li>
                            <li>Recipe database with nutritional information</li>
                            <li>Meal planning tools and calendar features</li>
                            <li>Budget tracking for meal expenses</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. User Accounts</h2>
                        <h3 class="text-xl font-medium text-gray-800 mb-3">Account Creation</h3>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            To access certain features of our Service, you must register for an account. When you create an account, you must provide accurate and complete information.
                        </p>
                        
                        <h3 class="text-xl font-medium text-gray-800 mb-3">Account Security</h3>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            You are responsible for safeguarding the password and for activities that occur under your account. You must:
                        </p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700">
                            <li>Choose a strong, unique password</li>
                            <li>Keep your login credentials confidential</li>
                            <li>Notify us immediately of any unauthorized use of your account</li>
                            <li>Log out from your account at the end of each session</li>
                        </ul>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Email Verification</h3>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            You must verify your email address to access full features of the Service. We use a secure OTP (One-Time Password) system for verification.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Acceptable Use</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            You agree to use the Service only for lawful purposes and in accordance with these Terms. You agree not to:
                        </p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700">
                            <li>Use the Service for any illegal or unauthorized purpose</li>
                            <li>Violate any applicable local, state, national, or international law</li>
                            <li>Transmit any harmful, offensive, or inappropriate content</li>
                            <li>Attempt to gain unauthorized access to our systems</li>
                            <li>Interfere with or disrupt the Service or servers</li>
                            <li>Create multiple accounts or share accounts with others</li>
                            <li>Provide false or misleading health information</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Health and Dietary Information</h2>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Important Health Disclaimer</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>StudEats provides general nutritional guidance and meal planning tools. Our recommendations are not a substitute for professional medical advice, diagnosis, or treatment.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-gray-700 leading-relaxed mb-4">
                            You acknowledge and agree that:
                        </p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700">
                            <li>You should consult with a healthcare professional before making significant dietary changes</li>
                            <li>Our BMI calculations and calorie recommendations are estimates based on general formulas</li>
                            <li>Individual nutritional needs may vary based on medical conditions, medications, and other factors</li>
                            <li>You are responsible for ensuring the accuracy of health information you provide</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Content and Intellectual Property</h2>
                        <h3 class="text-xl font-medium text-gray-800 mb-3">Our Content</h3>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            The Service and its original content, features, and functionality are owned by StudEats and are protected by international copyright, trademark, patent, trade secret, and other intellectual property laws.
                        </p>
                        
                        <h3 class="text-xl font-medium text-gray-800 mb-3">User Content</h3>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            You retain rights to any content you submit, post, or display on the Service. By submitting content, you grant us a worldwide, non-exclusive, royalty-free license to use, copy, reproduce, process, adapt, modify, publish, transmit, display, and distribute such content.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Privacy and Data Protection</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            Your privacy is important to us. Our Privacy Policy explains how we collect, use, and protect your information when you use our Service. By using our Service, you agree to the collection and use of information in accordance with our Privacy Policy.
                        </p>
                        <p class="text-gray-700 leading-relaxed">
                            <a href="{{ route('privacy-policy') }}" class="text-green-600 hover:text-green-800 underline">View our Privacy Policy</a>
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Service Availability</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            We strive to provide continuous access to our Service, but we do not guarantee uninterrupted access. The Service may be unavailable due to:
                        </p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700">
                            <li>Scheduled maintenance and updates</li>
                            <li>Technical difficulties or system outages</li>
                            <li>Events beyond our reasonable control</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">9. Limitation of Liability</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            In no event shall StudEats, its directors, employees, partners, agents, suppliers, or affiliates be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from your use of the Service.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">10. Termination</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            We may terminate or suspend your account and access to the Service immediately, without prior notice, for conduct that we believe violates these Terms or is harmful to other users, us, or third parties.
                        </p>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            You may terminate your account at any time by contacting us. Upon termination, your right to use the Service will cease immediately.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">11. Changes to Terms</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            We reserve the right to modify or replace these Terms at any time. If a revision is material, we will provide at least 30 days notice prior to any new terms taking effect.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">12. Governing Law</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            These Terms shall be governed by and construed in accordance with the laws of the Republic of the Philippines, without regard to its conflict of law provisions.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">13. Contact Information</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            If you have any questions about these Terms of Service, please contact us:
                        </p>
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <ul class="text-gray-700 space-y-2">
                                <li><strong>Email:</strong> legal@studeats.com</li>
                                <li><strong>Support:</strong> support@studeats.com</li>
                                <li><strong>Address:</strong> StudEats Legal Team<br>
                                123 University Avenue<br>
                                Metro Manila, Philippines</li>
                            </ul>
                        </div>
                    </section>

                    <div class="border-t border-gray-200 pt-8">
                        <p class="text-sm text-gray-500 text-center">
                            By using StudEats, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="mt-8 text-center">
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                <svg class="-ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection