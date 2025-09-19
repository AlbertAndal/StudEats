@extends('emails.layout')

@section('content')
    <div class="greeting">Hello {{ $user->name }}!</div>

    <div class="content-section">
        @switch($formType)
            @case('meal_plan')
                <p>Great news! Your meal plan has been created successfully.</p>
                <p>You now have a personalized weekly meal plan designed around your preferences and budget. It's time to start cooking and enjoying healthy, affordable meals!</p>
                @break
                
            @case('recipe_submission')
                <p>Thank you for submitting your recipe to StudEats!</p>
                <p>We appreciate you sharing your culinary creations with our community. Our team will review your recipe and notify you once it's approved and live on our platform.</p>
                @break
                
            @case('profile_update')
                <p>Your profile has been updated successfully!</p>
                <p>Thanks for keeping your information current. This helps us provide you with more accurate meal recommendations and better budget planning.</p>
                @break
                
            @case('contact_form')
                <p>We've received your message and will respond within 24 hours.</p>
                <p>Thank you for reaching out to StudEats support. Our team is reviewing your inquiry and will get back to you as soon as possible.</p>
                @break
                
            @case('feedback')
                <p>Thank you for taking the time to provide feedback!</p>
                <p>Your input is invaluable in helping us improve StudEats for all users. We carefully review every piece of feedback we receive.</p>
                @break
                
            @default
                <p>Your form has been submitted successfully!</p>
                <p>We've received your information and will process it according to our standard procedures.</p>
        @endswitch
    </div>

    @if($submissionId)
        <div class="highlight-box">
            <p><strong>Submission Reference:</strong></p>
            <div class="code-display">{{ $submissionId }}</div>
            <p><em>Please save this reference number for your records. You can use it to track the status of your submission or when contacting support.</em></p>
        </div>
    @endif

    @if(!empty($submissionData))
        <div class="content-section">
            <p><strong>Submission Summary:</strong></p>
            <div class="highlight-box">
                @foreach($submissionData as $key => $value)
                    @if(is_array($value))
                        <p><strong>{{ ucwords(str_replace(['_', '-'], ' ', $key)) }}:</strong> {{ implode(', ', $value) }}</p>
                    @else
                        <p><strong>{{ ucwords(str_replace(['_', '-'], ' ', $key)) }}:</strong> {{ $value }}</p>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    @if(!empty($nextSteps))
        <div class="content-section">
            <p><strong>What's Next?</strong></p>
            <ul>
                @foreach($nextSteps as $step)
                    <li>{{ $step }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="content-section">
        <p><strong>Ready to continue?</strong></p>
        <p>Click the button below to access the relevant section of your account:</p>
        
        <div style="text-align: center;">
            @switch($formType)
                @case('meal_plan')
                    <a href="{{ route('meal-plans.index') }}" class="button">View Your Meal Plans</a>
                    @break
                    
                @case('recipe_submission')
                    <a href="{{ route('recipes.index') }}" class="button">Browse All Recipes</a>
                    @break
                    
                @case('profile_update')
                    <a href="{{ route('profile.show') }}" class="button">View Your Profile</a>
                    @break
                    
                @default
                    <a href="{{ route('dashboard') }}" class="button">Go to Dashboard</a>
            @endswitch
        </div>
    </div>

    @if(in_array($formType, ['meal_plan', 'recipe_submission']))
        <div class="highlight-box">
            @if($formType === 'meal_plan')
                <p><strong>Meal Planning Tips:</strong></p>
                <ul>
                    <li>Check your meal plan regularly and adjust as needed</li>
                    <li>Generate a shopping list before going to the market</li>
                    <li>Consider batch cooking to save time during busy days</li>
                    <li>Track your spending to stay within budget</li>
                </ul>
            @else
                <p><strong>Recipe Review Process:</strong></p>
                <ul>
                    <li>Our team reviews recipes within 3-5 business days</li>
                    <li>We check for nutritional accuracy and clear instructions</li>
                    <li>Approved recipes appear in search results immediately</li>
                    <li>You'll be notified via email once your recipe is live</li>
                </ul>
            @endif
        </div>
    @endif

    <div class="content-section">
        @switch($formType)
            @case('meal_plan')
                <p>Enjoy your new meal plan! Remember, healthy eating is a journey, not a destination. Take it one meal at a time.</p>
                @break
                
            @case('recipe_submission')
                <p>Thank you for contributing to the StudEats community! Your recipe might just become someone's new favorite meal.</p>
                @break
                
            @case('profile_update')
                <p>Your updated preferences will help us serve you better. Keep exploring new recipes and meal plans!</p>
                @break
                
            @case('contact_form')
                <p>We're here to help you make the most of StudEats. Thank you for reaching out!</p>
                @break
                
            @case('feedback')
                <p>Your voice matters to us. Thank you for helping us build a better StudEats experience!</p>
                @break
                
            @default
                <p>Thank you for using StudEats. We're here to support your healthy eating journey!</p>
        @endswitch
    </div>
@endsection