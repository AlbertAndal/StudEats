@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 overflow-x-hidden">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-blue-50/50 to-white border-b border-gray-200 rounded-t-lg px-8 py-6">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-blue-500/10 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900">Privacy Policy</h1>
                        <p class="mt-1 text-gray-600">Last updated: {{ date('F j, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
            <div class="px-4 py-8 sm:px-8">
                <div class="prose prose-lg prose-gray max-w-none overflow-hidden">
                    <style>
                        .prose { word-wrap: break-word; overflow-wrap: break-word; }
                        .prose * { max-width: 100%; }
                    </style>
                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Introduction</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            Welcome to StudEats ("we," "our," or "us"). We are committed to protecting your personal information and your right to privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our meal planning application and related services.
                        </p>
                        <p class="text-gray-700 leading-relaxed">
                            If you have any questions or concerns about this privacy policy or our practices with regard to your personal information, please contact us at privacy@studeats.com.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Information We Collect</h2>
                        
                        <h3 class="text-xl font-medium text-gray-800 mb-3">Personal Information</h3>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            We collect personal information that you provide to us when you:
                        </p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700">
                            <li>Register for an account</li>
                            <li>Complete your profile information</li>
                            <li>Create meal plans</li>
                            <li>Contact us for support</li>
                        </ul>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Health and Dietary Information</h3>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            To provide personalized meal recommendations, we may collect:
                        </p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700">
                            <li>Height and weight for BMI calculations</li>
                            <li>Dietary preferences and restrictions</li>
                            <li>Activity level</li>
                            <li>Budget preferences</li>
                        </ul>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Automatically Collected Information</h3>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            When you access our service, we automatically collect:
                        </p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700">
                            <li>Device information (IP address, browser type, operating system)</li>
                            <li>Usage data (pages visited, time spent, features used)</li>
                            <li>Log files and cookies</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">How We Use Your Information</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            We use the information we collect for various purposes, including:
                        </p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700">
                            <li>Providing and maintaining our service</li>
                            <li>Personalizing meal recommendations based on your health profile</li>
                            <li>Processing transactions and managing your account</li>
                            <li>Sending you updates, security alerts, and support messages</li>
                            <li>Analyzing usage patterns to improve our service</li>
                            <li>Ensuring the security and integrity of our platform</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Information Sharing and Disclosure</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except in the following circumstances:
                        </p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700">
                            <li><strong>Service Providers:</strong> We may share information with trusted third-party service providers who assist us in operating our service</li>
                            <li><strong>Legal Requirements:</strong> We may disclose information when required by law or to protect our rights and safety</li>
                            <li><strong>Business Transfers:</strong> In the event of a merger, acquisition, or asset sale, user information may be transferred</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Data Security</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            We implement appropriate technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. These measures include:
                        </p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700">
                            <li>Encryption of sensitive data in transit and at rest</li>
                            <li>Regular security assessments and updates</li>
                            <li>Access controls and authentication mechanisms</li>
                            <li>Employee training on data protection practices</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Your Rights and Choices</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            You have certain rights regarding your personal information:
                        </p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700">
                            <li><strong>Access:</strong> You can request access to your personal information</li>
                            <li><strong>Correction:</strong> You can update or correct your information through your profile settings</li>
                            <li><strong>Deletion:</strong> You can request deletion of your account and associated data</li>
                            <li><strong>Portability:</strong> You can request a copy of your data in a structured format</li>
                            <li><strong>Opt-out:</strong> You can unsubscribe from promotional communications</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Cookies and Tracking Technologies</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            We use cookies and similar tracking technologies to enhance your experience on our service. You can control cookie preferences through your browser settings, though this may affect some functionality.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Children's Privacy</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            Our service is intended for users who are at least 18 years old. We do not knowingly collect personal information from children under 18. If you believe we have collected information from a child under 18, please contact us immediately.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Changes to This Privacy Policy</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            We may update this Privacy Policy from time to time. We will notify you of any material changes by posting the new policy on this page and updating the "Last Updated" date. We encourage you to review this policy periodically.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Contact Information</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            If you have any questions about this Privacy Policy or our data practices, please contact us:
                        </p>
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-lg p-6 border border-blue-200">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                                        <svg class="h-5 w-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <ul class="text-blue-900 space-y-2">
                                        <li class="flex items-center gap-2">
                                            <strong>Email:</strong> 
                                            <a href="mailto:privacy@studeats.com" class="text-blue-700 hover:text-blue-800 hover:underline">privacy@studeats.com</a>
                                        </li>
                                        <li><strong>Address:</strong> StudEats Privacy Team<br>
                                        123 University Avenue<br>
                                        Metro Manila, Philippines</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="mt-8 text-center">
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                <svg class="-ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection