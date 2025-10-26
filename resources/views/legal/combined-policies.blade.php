@extends('layouts.app')

@section('title', 'Privacy Policy & Terms of Service')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 overflow-x-hidden">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Legal Policies</h1>
            <p class="text-lg text-gray-600">Privacy Policy & Terms of Service</p>
            <div class="w-24 h-1 bg-green-500 mx-auto mt-4"></div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 max-h-[85vh]">
            
            <!-- Privacy Policy Column -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <div class="flex items-center text-white">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-xl font-bold">Privacy Policy</h2>
                            <p class="text-sm text-blue-100">Last updated: {{ date('F j, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="h-full max-h-[calc(85vh-80px)] overflow-y-auto p-6">
                    <div class="prose prose-sm max-w-none text-gray-700">
                        
                        <section class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Introduction</h3>
                            <p class="text-sm leading-relaxed mb-3">
                                Welcome to StudEats ("we," "our," or "us"). We are committed to protecting your personal information and your right to privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our meal planning application and related services.
                            </p>
                            <p class="text-sm leading-relaxed">
                                If you have any questions or concerns about this privacy policy or our practices with regard to your personal information, please contact us at privacy@studeats.com.
                            </p>
                        </section>

                        <section class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Information We Collect</h3>
                            
                            <h4 class="text-base font-medium text-gray-800 mb-2">Personal Information</h4>
                            <p class="text-sm leading-relaxed mb-2">We collect personal information that you provide to us when you:</p>
                            <ul class="list-disc pl-5 mb-3 text-sm text-gray-700 space-y-1">
                                <li>Register for an account</li>
                                <li>Complete your profile information</li>
                                <li>Create meal plans</li>
                                <li>Contact us for support</li>
                            </ul>

                            <h4 class="text-base font-medium text-gray-800 mb-2">Health and Dietary Information</h4>
                            <p class="text-sm leading-relaxed mb-2">To provide personalized meal recommendations, we may collect:</p>
                            <ul class="list-disc pl-5 mb-3 text-sm text-gray-700 space-y-1">
                                <li>Height and weight for BMI calculations</li>
                                <li>Dietary preferences and restrictions</li>
                                <li>Activity level</li>
                                <li>Budget preferences</li>
                            </ul>

                            <h4 class="text-base font-medium text-gray-800 mb-2">Automatically Collected Information</h4>
                            <p class="text-sm leading-relaxed mb-2">When you access our service, we automatically collect:</p>
                            <ul class="list-disc pl-5 mb-3 text-sm text-gray-700 space-y-1">
                                <li>Device information (IP address, browser type, operating system)</li>
                                <li>Usage data (pages visited, time spent, features used)</li>
                                <li>Log files and cookies</li>
                            </ul>
                        </section>

                        <section class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">How We Use Your Information</h3>
                            <p class="text-sm leading-relaxed mb-2">We use the information we collect for various purposes, including:</p>
                            <ul class="list-disc pl-5 mb-3 text-sm text-gray-700 space-y-1">
                                <li>Providing and maintaining our service</li>
                                <li>Personalizing meal recommendations based on your health profile</li>
                                <li>Processing transactions and managing your account</li>
                                <li>Sending you updates, security alerts, and support messages</li>
                                <li>Analyzing usage patterns to improve our service</li>
                                <li>Ensuring the security and integrity of our platform</li>
                            </ul>
                        </section>

                        <section class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Information Sharing and Disclosure</h3>
                            <p class="text-sm leading-relaxed mb-2">We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except in the following circumstances:</p>
                            <ul class="list-disc pl-5 mb-3 text-sm text-gray-700 space-y-1">
                                <li><strong>Service Providers:</strong> We may share information with trusted third-party service providers who assist us in operating our service</li>
                                <li><strong>Legal Requirements:</strong> We may disclose information when required by law or to protect our rights and safety</li>
                                <li><strong>Business Transfers:</strong> In the event of a merger, acquisition, or asset sale, user information may be transferred</li>
                            </ul>
                        </section>

                        <section class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Data Security</h3>
                            <p class="text-sm leading-relaxed mb-2">We implement appropriate technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. These measures include:</p>
                            <ul class="list-disc pl-5 mb-3 text-sm text-gray-700 space-y-1">
                                <li>Encryption of sensitive data in transit and at rest</li>
                                <li>Regular security assessments and updates</li>
                                <li>Access controls and authentication mechanisms</li>
                                <li>Employee training on data protection practices</li>
                            </ul>
                        </section>

                        <section class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Your Rights and Choices</h3>
                            <p class="text-sm leading-relaxed mb-2">You have certain rights regarding your personal information:</p>
                            <ul class="list-disc pl-5 mb-3 text-sm text-gray-700 space-y-1">
                                <li><strong>Access:</strong> You can request access to your personal information</li>
                                <li><strong>Correction:</strong> You can update or correct your information through your profile settings</li>
                                <li><strong>Deletion:</strong> You can request deletion of your account and associated data</li>
                                <li><strong>Portability:</strong> You can request a copy of your data in a structured format</li>
                                <li><strong>Opt-out:</strong> You can unsubscribe from promotional communications</li>
                            </ul>
                        </section>

                        <section class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Contact Information</h3>
                            <p class="text-sm leading-relaxed mb-2">If you have any questions about this Privacy Policy or our data practices, please contact us:</p>
                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                <ul class="text-sm text-gray-700 space-y-1">
                                    <li><strong>Email:</strong> privacy@studeats.com</li>
                                    <li><strong>Address:</strong> StudEats Privacy Team<br>
                                    123 University Avenue<br>
                                    Metro Manila, Philippines</li>
                                </ul>
                            </div>
                        </section>

                    </div>
                </div>
            </div>

            <!-- Terms of Service Column -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                    <div class="flex items-center text-white">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-xl font-bold">Terms of Service</h2>
                            <p class="text-sm text-green-100">Last updated: {{ date('F j, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="h-full max-h-[calc(85vh-80px)] overflow-y-auto p-6">
                    <div class="prose prose-sm max-w-none text-gray-700">
                        
                        <section class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">1. Agreement to Terms</h3>
                            <p class="text-sm leading-relaxed mb-3">
                                By accessing and using StudEats ("Service"), you agree to be bound by these Terms of Service ("Terms"). If you disagree with any part of these terms, then you may not access the Service.
                            </p>
                            <p class="text-sm leading-relaxed">
                                These Terms apply to all visitors, users, and others who access or use the Service.
                            </p>
                        </section>

                        <section class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">2. Description of Service</h3>
                            <p class="text-sm leading-relaxed mb-2">StudEats is a meal planning application designed specifically for Filipino students. Our Service provides:</p>
                            <ul class="list-disc pl-5 mb-3 text-sm text-gray-700 space-y-1">
                                <li>Personalized meal recommendations based on budget and dietary preferences</li>
                                <li>BMI calculations and health-conscious meal planning</li>
                                <li>Recipe database with nutritional information</li>
                                <li>Meal planning tools and calendar features</li>
                                <li>Budget tracking for meal expenses</li>
                            </ul>
                        </section>

                        <section class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">3. User Accounts</h3>
                            <h4 class="text-base font-medium text-gray-800 mb-2">Account Creation</h4>
                            <p class="text-sm leading-relaxed mb-3">
                                To access certain features of our Service, you must register for an account. When you create an account, you must provide accurate and complete information.
                            </p>
                            
                            <h4 class="text-base font-medium text-gray-800 mb-2">Account Security</h4>
                            <p class="text-sm leading-relaxed mb-2">You are responsible for safeguarding the password and for activities that occur under your account. You must:</p>
                            <ul class="list-disc pl-5 mb-3 text-sm text-gray-700 space-y-1">
                                <li>Choose a strong, unique password</li>
                                <li>Keep your login credentials confidential</li>
                                <li>Notify us immediately of any unauthorized use of your account</li>
                                <li>Log out from your account at the end of each session</li>
                            </ul>

                            <h4 class="text-base font-medium text-gray-800 mb-2">Email Verification</h4>
                            <p class="text-sm leading-relaxed mb-3">
                                You must verify your email address to access full features of the Service. We use a secure OTP (One-Time Password) system for verification.
                            </p>
                        </section>

                        <section class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">4. Acceptable Use</h3>
                            <p class="text-sm leading-relaxed mb-2">You agree to use the Service only for lawful purposes and in accordance with these Terms. You agree not to:</p>
                            <ul class="list-disc pl-5 mb-3 text-sm text-gray-700 space-y-1">
                                <li>Use the Service for any illegal or unauthorized purpose</li>
                                <li>Violate any applicable local, state, national, or international law</li>
                                <li>Transmit any harmful, offensive, or inappropriate content</li>
                                <li>Attempt to gain unauthorized access to our systems</li>
                                <li>Interfere with or disrupt the Service or servers</li>
                                <li>Create multiple accounts or share accounts with others</li>
                                <li>Provide false or misleading health information</li>
                            </ul>
                        </section>

                        <section class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">5. Health and Dietary Information</h3>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-3">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-yellow-800">Important Health Disclaimer</h4>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>StudEats provides general nutritional guidance and meal planning tools. Our recommendations are not a substitute for professional medical advice, diagnosis, or treatment.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-sm leading-relaxed mb-2">You acknowledge and agree that:</p>
                            <ul class="list-disc pl-5 mb-3 text-sm text-gray-700 space-y-1">
                                <li>You should consult with a healthcare professional before making significant dietary changes</li>
                                <li>Our BMI calculations and calorie recommendations are estimates based on general formulas</li>
                                <li>Individual nutritional needs may vary based on medical conditions, medications, and other factors</li>
                                <li>You are responsible for ensuring the accuracy of health information you provide</li>
                            </ul>
                        </section>

                        <section class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">6. Content and Intellectual Property</h3>
                            <h4 class="text-base font-medium text-gray-800 mb-2">Our Content</h4>
                            <p class="text-sm leading-relaxed mb-3">
                                The Service and its original content, features, and functionality are owned by StudEats and are protected by international copyright, trademark, patent, trade secret, and other intellectual property laws.
                            </p>
                            
                            <h4 class="text-base font-medium text-gray-800 mb-2">User Content</h4>
                            <p class="text-sm leading-relaxed mb-3">
                                You retain rights to any content you submit, post, or display on the Service. By submitting content, you grant us a worldwide, non-exclusive, royalty-free license to use, copy, reproduce, process, adapt, modify, publish, transmit, display, and distribute such content.
                            </p>
                        </section>

                        <section class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">7. Privacy and Data Protection</h3>
                            <p class="text-sm leading-relaxed mb-2">
                                Your privacy is important to us. Our Privacy Policy explains how we collect, use, and protect your information when you use our Service. By using our Service, you agree to the collection and use of information in accordance with our Privacy Policy.
                            </p>
                        </section>

                        <section class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">8. Limitation of Liability</h3>
                            <p class="text-sm leading-relaxed mb-3">
                                In no event shall StudEats, its directors, employees, partners, agents, suppliers, or affiliates be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from your use of the Service.
                            </p>
                        </section>

                        <section class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">9. Contact Information</h3>
                            <p class="text-sm leading-relaxed mb-2">If you have any questions about these Terms of Service, please contact us:</p>
                            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                <ul class="text-sm text-gray-700 space-y-1">
                                    <li><strong>Email:</strong> legal@studeats.com</li>
                                    <li><strong>Support:</strong> support@studeats.com</li>
                                    <li><strong>Address:</strong> StudEats Legal Team<br>
                                    123 University Avenue<br>
                                    Metro Manila, Philippines</li>
                                </ul>
                            </div>
                        </section>

                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-xs text-gray-500 text-center">
                                By using StudEats, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service.
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- Navigation Footer -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                <svg class="-ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Back to Dashboard
            </a>
            <div class="flex gap-4">
                <a href="{{ route('privacy-policy') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium underline">View Full Privacy Policy</a>
                <a href="{{ route('terms-of-service') }}" class="text-green-600 hover:text-green-800 text-sm font-medium underline">View Full Terms of Service</a>
            </div>
        </div>

        <!-- Copyright -->
        <div class="mt-8 text-center">
            <p class="text-xs text-gray-400">© 2025 StudEats. A Capstone Project by Jhet Reuel De Ramos, Allen Antonio, and John Albert Andal. All rights reserved.</p>
        </div>
    </div>
</div>

<style>
/* Custom scrollbar for better UX */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Smooth scrolling */
.overflow-y-auto {
    scroll-behavior: smooth;
}

/* Mobile responsive adjustments */
@media (max-width: 1023px) {
    .grid {
        grid-template-columns: 1fr;
        max-height: none;
    }
    
    .max-h-\[calc\(85vh-80px\)\] {
        max-height: 60vh;
    }
}

/* Ensure text remains readable at all sizes */
@media (max-width: 640px) {
    .prose-sm {
        font-size: 0.875rem;
        line-height: 1.5;
    }
    
    .text-lg {
        font-size: 1rem;
    }
    
    .text-xl {
        font-size: 1.125rem;
    }
}
</style>
@endsection