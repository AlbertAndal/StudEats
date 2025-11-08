import React, { useState } from 'react';
import LoadingIndicator from './LoadingIndicator';
import LoadingOverlay from './LoadingOverlay';
import useLoadingState from '../hooks/useLoadingState';

/**
 * Example usage and documentation for LoadingIndicator components
 * This file serves as both documentation and testing playground
 */

// Example 1: Basic Usage
export const BasicExample = () => {
    return (
        <div className="p-8 space-y-8">
            <h2 className="text-2xl font-bold mb-4">Basic Loading Indicator</h2>
            
            <div className="bg-gray-100 dark:bg-gray-900 p-8 rounded-lg">
                <LoadingIndicator />
            </div>
        </div>
    );
};

// Example 2: Different Variants
export const VariantsExample = () => {
    const variants = ['spinner', 'dots', 'pulse', 'progress'];
    
    return (
        <div className="p-8 space-y-8">
            <h2 className="text-2xl font-bold mb-4">Loading Variants</h2>
            
            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                {variants.map(variant => (
                    <div key={variant} className="bg-gray-100 dark:bg-gray-900 p-8 rounded-lg">
                        <h3 className="text-lg font-semibold mb-4 capitalize">{variant}</h3>
                        <LoadingIndicator variant={variant} />
                    </div>
                ))}
            </div>
        </div>
    );
};

// Example 3: Different Sizes
export const SizesExample = () => {
    const sizes = ['small', 'medium', 'large'];
    
    return (
        <div className="p-8 space-y-8">
            <h2 className="text-2xl font-bold mb-4">Loading Sizes</h2>
            
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                {sizes.map(size => (
                    <div key={size} className="bg-gray-100 dark:bg-gray-900 p-8 rounded-lg">
                        <h3 className="text-lg font-semibold mb-4 capitalize">{size}</h3>
                        <LoadingIndicator size={size} />
                    </div>
                ))}
            </div>
        </div>
    );
};

// Example 4: Custom Messages
export const CustomMessagesExample = () => {
    const customMessages = [
        "Analyzing your meal preferences...",
        "Calculating nutritional values...",
        "Generating meal plan...",
        "Finalizing recommendations..."
    ];
    
    return (
        <div className="p-8 space-y-8">
            <h2 className="text-2xl font-bold mb-4">Custom Messages (Auto-cycling)</h2>
            
            <div className="bg-gray-100 dark:bg-gray-900 p-8 rounded-lg">
                <LoadingIndicator 
                    messages={customMessages}
                    transitionInterval={2000}
                    showProgress={true}
                />
            </div>
        </div>
    );
};

// Example 5: With Progress Bar
export const WithProgressExample = () => {
    return (
        <div className="p-8 space-y-8">
            <h2 className="text-2xl font-bold mb-4">Loading with Progress Bar</h2>
            
            <div className="bg-gray-100 dark:bg-gray-900 p-8 rounded-lg">
                <LoadingIndicator 
                    showProgress={true}
                    messages={["Please wait...", "Processing...", "Almost done..."]}
                />
            </div>
        </div>
    );
};

// Example 6: Loading Overlay
export const OverlayExample = () => {
    const [showOverlay, setShowOverlay] = useState(false);
    
    return (
        <div className="p-8 space-y-8">
            <h2 className="text-2xl font-bold mb-4">Loading Overlay</h2>
            
            <button
                onClick={() => setShowOverlay(true)}
                className="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
                Show Loading Overlay
            </button>
            
            <LoadingOverlay
                show={showOverlay}
                messages={["Processing your request...", "Fetching data...", "Finalizing..."]}
                dismissible={true}
                onClose={() => setShowOverlay(false)}
                indicatorProps={{
                    variant: 'spinner',
                    size: 'large',
                    showProgress: true
                }}
            />
        </div>
    );
};

// Example 7: Using the Hook
export const HookExample = () => {
    const { 
        isLoading, 
        currentMessage, 
        startLoading, 
        stopLoading, 
        nextStage,
        progress
    } = useLoadingState({
        messages: [
            "Step 1: Validating input...",
            "Step 2: Processing data...",
            "Step 3: Saving changes...",
            "Step 4: Complete!"
        ],
        minLoadingTime: 1000,
        onComplete: () => console.log('Loading complete!')
    });
    
    const simulateOperation = async () => {
        startLoading();
        
        for (let i = 0; i < 4; i++) {
            await new Promise(resolve => setTimeout(resolve, 1500));
            if (i < 3) nextStage();
        }
        
        await stopLoading();
    };
    
    return (
        <div className="p-8 space-y-8">
            <h2 className="text-2xl font-bold mb-4">Using useLoadingState Hook</h2>
            
            <div className="space-y-4">
                <button
                    onClick={simulateOperation}
                    disabled={isLoading}
                    className="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {isLoading ? 'Processing...' : 'Start Multi-Stage Operation'}
                </button>
                
                {isLoading && (
                    <div className="bg-gray-100 dark:bg-gray-900 p-8 rounded-lg">
                        <LoadingIndicator 
                            messages={[currentMessage]}
                            autoTransition={false}
                            showProgress={true}
                        />
                        <div className="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">
                            Progress: {Math.round(progress)}%
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
};

// Example 8: Inline Loading
export const InlineExample = () => {
    const [isLoading, setIsLoading] = useState(false);
    
    return (
        <div className="p-8 space-y-8">
            <h2 className="text-2xl font-bold mb-4">Inline Loading Indicators</h2>
            
            <div className="space-y-4">
                <button
                    onClick={() => setIsLoading(!isLoading)}
                    className="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center gap-2"
                >
                    {isLoading && (
                        <LoadingIndicator 
                            size="small" 
                            variant="spinner"
                            messages={[""]}
                            className="inline-flex"
                        />
                    )}
                    {isLoading ? 'Loading...' : 'Click to Load'}
                </button>
                
                <div className="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                    {isLoading && <LoadingIndicator size="small" variant="dots" messages={[""]} />}
                    <span>Processing your request...</span>
                </div>
            </div>
        </div>
    );
};

// Example 9: Theme Variations
export const ThemeExample = () => {
    return (
        <div className="p-8 space-y-8">
            <h2 className="text-2xl font-bold mb-4">Theme Variations</h2>
            
            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div className="bg-white p-8 rounded-lg border-2 border-gray-200">
                    <h3 className="text-lg font-semibold mb-4">Light Theme</h3>
                    <LoadingIndicator theme="light" />
                </div>
                
                <div className="bg-gray-900 p-8 rounded-lg">
                    <h3 className="text-lg font-semibold mb-4 text-white">Dark Theme</h3>
                    <LoadingIndicator theme="dark" />
                </div>
            </div>
        </div>
    );
};

// Complete Demo Page
export const CompleteDemo = () => {
    return (
        <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
            <div className="max-w-7xl mx-auto space-y-16">
                <div className="text-center">
                    <h1 className="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        StudEats Loading Indicator Component
                    </h1>
                    <p className="text-lg text-gray-600 dark:text-gray-400">
                        A comprehensive loading component with multiple variants and features
                    </p>
                </div>
                
                <BasicExample />
                <VariantsExample />
                <SizesExample />
                <CustomMessagesExample />
                <WithProgressExample />
                <OverlayExample />
                <HookExample />
                <InlineExample />
                <ThemeExample />
            </div>
        </div>
    );
};

export default CompleteDemo;
