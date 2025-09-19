@extends('emails.layout')

@section('content')
    <div class="greeting">Hello {{ $user->name }}!</div>

    <div class="content-section">
        <p>Welcome to <strong>StudEats</strong>! Your account has been created successfully and you're now ready to start your healthy eating journey.</p>
        
        <p>StudEats is designed specifically for Filipino students who want to eat well without breaking the bank. We're here to help you plan balanced, nutritious meals that fit your budget and lifestyle.</p>
    </div>

    @if($user->daily_budget || ($user->dietary_preferences && count($user->dietary_preferences) > 0))
        <div class="highlight-box">
            <strong>Your Profile Summary:</strong>
            @if($user->daily_budget)
                <p><strong>Daily Budget:</strong> ₱{{ number_format($user->daily_budget, 2) }}</p>
            @endif
            
            @if($user->dietary_preferences && count($user->dietary_preferences) > 0)
                <p><strong>Dietary Preferences:</strong> {{ implode(', ', array_map('ucfirst', $user->dietary_preferences)) }}</p>
            @endif
            
            <p><em>We'll use this information to create personalized meal recommendations just for you!</em></p>
        </div>
    @endif

    <div class="content-section">
        <p><strong>Here's what you can do with StudEats:</strong></p>
        <ul>
            <li><strong>Browse Recipes:</strong> Discover budget-friendly Filipino and international dishes</li>
            <li><strong>Create Meal Plans:</strong> Plan your weekly meals with automatic budget tracking</li>
            <li><strong>Track Nutrition:</strong> Monitor your daily nutritional intake and goals</li>
            <li><strong>Get Recommendations:</strong> Receive personalized meal suggestions based on your preferences</li>
            <li><strong>Build Shopping Lists:</strong> Generate smart grocery lists to save time and money</li>
        </ul>
    </div>

    <div class="content-section">
        <p><strong>Ready to get started?</strong></p>
        <p>Click the button below to access your dashboard and begin planning your first meal:</p>
        
        <div style="text-align: center;">
            <a href="{{ route('dashboard') }}" class="button">Start Planning Meals</a>
        </div>
    </div>

    <div class="content-section">
        <p><strong>Need help getting started?</strong></p>
        <ul>
            <li>Complete your profile for better recommendations</li>
            <li>Check out our featured recipes for inspiration</li>
            <li>Browse sample meal plans from other students</li>
            <li>Join our community for tips and support</li>
        </ul>
    </div>

    <div class="highlight-box">
        <p><strong>Pro Tip:</strong> Start by setting your weekly budget goal and exploring recipes under ₱100 per serving. Our algorithm will help you find the perfect balance of nutrition and affordability!</p>
    </div>

    <div class="content-section">
        <p>Welcome to the StudEats family! We're excited to help you develop healthy eating habits that will last a lifetime.</p>
        
        <p>Happy meal planning!</p>
    </div>
@endsection