# LoadingIndicator Component Documentation

## Overview

The LoadingIndicator component is a versatile, accessible, and highly customizable loading indicator system for the StudEats application. It provides smooth animations, multiple variants, theme support, and comprehensive accessibility features.

## Features

- ✨ **Multiple Variants**: Spinner, dots, pulse, and progress bar
- 📱 **Responsive Design**: Adapts to all screen sizes
- 🎨 **Theme Support**: Light, dark, and auto modes
- ♿ **Accessibility**: ARIA labels, proper contrast, reduced motion support
- 🔄 **Auto-cycling Messages**: Smooth transitions between status messages
- 📊 **Progress Tracking**: Optional progress bar visualization
- 🎯 **Flexible API**: Easy integration with callbacks and events
- 🪝 **Custom Hook**: `useLoadingState` for managing loading states

## Installation

The component is already integrated into the StudEats application. The files are located at:

- Component: `resources/js/components/LoadingIndicator.jsx`
- Overlay: `resources/js/components/LoadingOverlay.jsx`
- Hook: `resources/js/hooks/useLoadingState.js`
- Styles: `resources/css/loading-indicator.css`

### Import the Component

```jsx
import LoadingIndicator from '@/components/LoadingIndicator';
import LoadingOverlay from '@/components/LoadingOverlay';
import useLoadingState from '@/hooks/useLoadingState';
```

## Basic Usage

### Simple Loading Indicator

```jsx
<LoadingIndicator />
```

This displays a medium-sized spinner with the default message "Please wait...".

### With Custom Message

```jsx
<LoadingIndicator 
  initialMessage="Processing your meal plan..."
/>
```

### With Multiple Messages (Auto-cycling)

```jsx
<LoadingIndicator 
  messages={[
    "Analyzing your preferences...",
    "Calculating nutrition...",
    "Generating meal plan..."
  ]}
  transitionInterval={2000}
/>
```

## Component API

### LoadingIndicator Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `initialMessage` | string | "Please wait..." | Initial message to display |
| `messages` | string[] | [...] | Array of messages to cycle through |
| `transitionInterval` | number | 2000 | Time in ms between message transitions |
| `autoTransition` | boolean | true | Whether to automatically cycle messages |
| `size` | 'small' \| 'medium' \| 'large' | 'medium' | Size of the indicator |
| `theme` | 'light' \| 'dark' \| 'auto' | 'auto' | Color theme |
| `variant` | 'spinner' \| 'dots' \| 'pulse' \| 'progress' | 'spinner' | Visual style |
| `onStageComplete` | function | null | Callback when a message stage completes |
| `onAllStagesComplete` | function | null | Callback when all messages shown |
| `className` | string | '' | Additional CSS classes |
| `showProgress` | boolean | false | Show progress bar |

### LoadingOverlay Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `show` | boolean | required | Whether to show the overlay |
| `message` | string | - | Single loading message |
| `messages` | string[] | - | Multiple messages to cycle |
| `dismissible` | boolean | false | Allow clicking outside to close |
| `onClose` | function | null | Callback when overlay closes |
| `indicatorProps` | object | {} | Props to pass to LoadingIndicator |

## Advanced Usage

### 1. Full-Screen Loading Overlay

```jsx
function MealPlanGenerator() {
  const [isGenerating, setIsGenerating] = useState(false);
  
  const generateMealPlan = async () => {
    setIsGenerating(true);
    try {
      // Your async operation
      await api.generateMealPlan();
    } finally {
      setIsGenerating(false);
    }
  };
  
  return (
    <>
      <button onClick={generateMealPlan}>Generate Meal Plan</button>
      
      <LoadingOverlay
        show={isGenerating}
        messages={[
          "Analyzing your dietary preferences...",
          "Calculating nutritional requirements...",
          "Selecting recipes...",
          "Finalizing meal plan..."
        ]}
        indicatorProps={{
          variant: 'spinner',
          size: 'large',
          showProgress: true
        }}
      />
    </>
  );
}
```

### 2. Using the useLoadingState Hook

```jsx
function DataProcessor() {
  const {
    isLoading,
    currentMessage,
    startLoading,
    stopLoading,
    setStage,
    nextStage,
    progress
  } = useLoadingState({
    messages: [
      "Validating input...",
      "Processing data...",
      "Saving results...",
      "Complete!"
    ],
    minLoadingTime: 1000,
    onComplete: () => {
      console.log('Processing complete!');
    }
  });
  
  const processData = async () => {
    startLoading();
    
    // Stage 1
    await validateInput();
    nextStage();
    
    // Stage 2
    await processData();
    nextStage();
    
    // Stage 3
    await saveResults();
    
    await stopLoading();
  };
  
  return (
    <>
      <button onClick={processData} disabled={isLoading}>
        Process Data
      </button>
      
      {isLoading && (
        <LoadingIndicator
          messages={[currentMessage]}
          autoTransition={false}
          showProgress={true}
        />
      )}
    </>
  );
}
```

### 3. Inline Loading Indicator

```jsx
<button className="flex items-center gap-2">
  {isSubmitting && (
    <LoadingIndicator 
      size="small" 
      variant="spinner"
      messages={[""]}
    />
  )}
  Submit
</button>
```

### 4. Stage Completion Callbacks

```jsx
<LoadingIndicator
  messages={[
    "Step 1: Loading...",
    "Step 2: Processing...",
    "Step 3: Finalizing..."
  ]}
  onStageComplete={(stageIndex) => {
    console.log(`Stage ${stageIndex + 1} complete`);
    // Perform stage-specific actions
  }}
  onAllStagesComplete={() => {
    console.log('All stages complete!');
    // Navigate or update UI
  }}
/>
```

### 5. Custom Styling and Themes

```jsx
// Light theme
<LoadingIndicator 
  theme="light"
  variant="spinner"
  size="large"
/>

// Dark theme
<LoadingIndicator 
  theme="dark"
  variant="pulse"
  size="medium"
/>

// Auto (follows system preference)
<LoadingIndicator 
  theme="auto"
  variant="dots"
/>
```

## Variants

### Spinner (Default)
Rotating circular spinner - classic loading indicator.

```jsx
<LoadingIndicator variant="spinner" />
```

### Dots
Three animated dots that pulse in sequence.

```jsx
<LoadingIndicator variant="dots" />
```

### Pulse
Pulsing circle with expanding ring animation.

```jsx
<LoadingIndicator variant="pulse" />
```

### Progress
Horizontal progress bar with continuous animation.

```jsx
<LoadingIndicator variant="progress" />
```

## Real-World Examples

### Example 1: Recipe Generation

```jsx
function RecipeGenerator() {
  const [isGenerating, setIsGenerating] = useState(false);
  
  const generateRecipes = async () => {
    setIsGenerating(true);
    try {
      await api.generateRecipes();
    } finally {
      setIsGenerating(false);
    }
  };
  
  return (
    <>
      <button onClick={generateRecipes}>Generate Recipes</button>
      
      <LoadingOverlay
        show={isGenerating}
        messages={[
          "Analyzing your preferences...",
          "Searching recipe database...",
          "Calculating nutrition...",
          "Finalizing recommendations..."
        ]}
        indicatorProps={{
          variant: 'spinner',
          size: 'large',
          showProgress: true
        }}
      />
    </>
  );
}
```

### Example 2: BMI Calculator with Loading

```jsx
function BMICalculator() {
  const { isLoading, withLoading } = useLoadingState({
    messages: ["Calculating BMI..."],
    minLoadingTime: 500
  });
  
  const calculateBMI = async (height, weight) => {
    return await withLoading(async () => {
      const response = await api.calculateBMI({ height, weight });
      return response.data;
    });
  };
  
  return (
    <form onSubmit={handleSubmit}>
      {/* Form fields */}
      
      {isLoading && (
        <LoadingIndicator 
          variant="dots" 
          size="small"
        />
      )}
    </form>
  );
}
```

### Example 3: Meal Plan Import

```jsx
function MealPlanImporter() {
  const {
    isLoading,
    currentMessage,
    startLoading,
    stopLoading,
    setStage
  } = useLoadingState({
    messages: [
      "Uploading file...",
      "Parsing meal data...",
      "Validating nutrition info...",
      "Creating meal plan...",
      "Import complete!"
    ]
  });
  
  const importMealPlan = async (file) => {
    startLoading();
    
    // Stage 1: Upload
    const uploadResponse = await uploadFile(file);
    setStage(1);
    
    // Stage 2: Parse
    const parsedData = await parseData(uploadResponse);
    setStage(2);
    
    // Stage 3: Validate
    const validatedData = await validate(parsedData);
    setStage(3);
    
    // Stage 4: Create
    await createMealPlan(validatedData);
    setStage(4);
    
    await stopLoading();
  };
  
  return (
    <>
      <FileUpload onUpload={importMealPlan} />
      
      {isLoading && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center">
          <div className="bg-white p-8 rounded-lg">
            <LoadingIndicator
              messages={[currentMessage]}
              autoTransition={false}
              variant="spinner"
              size="large"
              showProgress={true}
            />
          </div>
        </div>
      )}
    </>
  );
}
```

## Accessibility

The component includes comprehensive accessibility features:

### ARIA Attributes
- `role="status"` and `role="alert"` for screen readers
- `aria-live="polite"` for dynamic content updates
- `aria-busy="true"` to indicate loading state
- `aria-label` for descriptive labels
- `aria-valuenow`, `aria-valuemin`, `aria-valuemax` for progress bars

### Visual Accessibility
- High contrast ratios for text and indicators
- Support for high contrast mode
- Proper color combinations for colorblind users

### Reduced Motion
- Respects `prefers-reduced-motion` media query
- Animations disabled for users who prefer reduced motion

## Styling and Customization

### Custom CSS Classes

```jsx
<LoadingIndicator 
  className="my-custom-class"
/>
```

### Tailwind CSS Integration

The component uses Tailwind CSS classes and supports dark mode:

```jsx
<div className="dark">
  <LoadingIndicator theme="auto" />
</div>
```

### Custom Animation Timing

```css
/* In your custom CSS */
.loading-indicator-custom {
  animation-duration: 0.8s !important;
}
```

## Best Practices

1. **Use Appropriate Variants**: Choose the variant that best fits your use case
   - Spinner: General purpose
   - Dots: Subtle, inline loading
   - Pulse: Attention-grabbing
   - Progress: Long operations

2. **Message Clarity**: Use clear, action-oriented messages
   - ✅ "Generating your meal plan..."
   - ❌ "Loading..."

3. **Timing**: Set appropriate transition intervals
   - Quick operations: 1000-1500ms
   - Long operations: 2000-3000ms

4. **Minimum Loading Time**: Use `minLoadingTime` to prevent flashing
   ```jsx
   const { withLoading } = useLoadingState({ minLoadingTime: 1000 });
   ```

5. **Error Handling**: Always handle errors and stop loading
   ```jsx
   try {
     await operation();
   } catch (error) {
     // Handle error
   } finally {
     stopLoading();
   }
   ```

6. **Accessibility**: Always provide meaningful messages for screen readers

7. **Performance**: Use overlay sparingly to avoid blocking user interaction

## Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Mobile browsers (iOS Safari, Chrome Mobile)
- Fallback for older browsers without animation support

## Troubleshooting

### Component Not Showing

Check that:
1. `show` prop is `true` (for LoadingOverlay)
2. CSS is properly imported
3. Component is properly imported

### Messages Not Cycling

Verify:
1. `autoTransition={true}`
2. `messages` array has multiple items
3. `transitionInterval` is set appropriately

### Progress Bar Not Moving

Ensure:
1. `showProgress={true}`
2. `messages` array is not empty
3. Component is re-rendering properly

## Contributing

To extend or modify the component:

1. Edit the source files in `resources/js/components/`
2. Update styles in `resources/css/loading-indicator.css`
3. Add examples to `LoadingIndicator.stories.jsx`
4. Update this documentation

## License

Part of the StudEats application. See main application license.

## Support

For issues or questions:
- Check the examples in `LoadingIndicator.stories.jsx`
- Review this documentation
- Contact the development team
