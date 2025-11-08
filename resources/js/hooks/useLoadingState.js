import { useState, useCallback, useRef, useEffect } from 'react';

/**
 * Custom hook for managing loading states with messages
 * 
 * @param {Object} options - Configuration options
 * @param {string[]} options.messages - Array of loading messages
 * @param {number} options.minLoadingTime - Minimum time to show loading (ms)
 * @param {function} options.onStageComplete - Callback for stage completion
 * @param {function} options.onComplete - Callback when loading completes
 * 
 * @returns {Object} Loading state and control functions
 * 
 * @example
 * const { isLoading, currentMessage, startLoading, stopLoading, setStage } = useLoadingState({
 *   messages: ["Please wait...", "Processing..."],
 *   minLoadingTime: 1000,
 *   onComplete: () => console.log('Done!')
 * });
 */
const useLoadingState = ({
    messages = ["Please wait...", "Processing...", "Fetching data..."],
    minLoadingTime = 0,
    onStageComplete = null,
    onComplete = null
} = {}) => {
    const [isLoading, setIsLoading] = useState(false);
    const [currentMessageIndex, setCurrentMessageIndex] = useState(0);
    const startTimeRef = useRef(null);
    const stageCallbacksRef = useRef({});

    useEffect(() => {
        if (isLoading && onStageComplete) {
            onStageComplete(currentMessageIndex);
        }
    }, [currentMessageIndex, isLoading, onStageComplete]);

    /**
     * Start loading with optional initial message index
     */
    const startLoading = useCallback((initialIndex = 0) => {
        setIsLoading(true);
        setCurrentMessageIndex(initialIndex);
        startTimeRef.current = Date.now();
    }, []);

    /**
     * Stop loading with minimum time enforcement
     */
    const stopLoading = useCallback(async () => {
        if (minLoadingTime > 0 && startTimeRef.current) {
            const elapsed = Date.now() - startTimeRef.current;
            const remaining = minLoadingTime - elapsed;
            
            if (remaining > 0) {
                await new Promise(resolve => setTimeout(resolve, remaining));
            }
        }

        setIsLoading(false);
        setCurrentMessageIndex(0);
        startTimeRef.current = null;

        if (onComplete) {
            onComplete();
        }
    }, [minLoadingTime, onComplete]);

    /**
     * Set a specific loading stage/message
     */
    const setStage = useCallback((index) => {
        if (index >= 0 && index < messages.length) {
            setCurrentMessageIndex(index);
            
            // Execute stage-specific callback if registered
            const callback = stageCallbacksRef.current[index];
            if (callback) {
                callback();
            }
        }
    }, [messages.length]);

    /**
     * Move to next stage
     */
    const nextStage = useCallback(() => {
        setCurrentMessageIndex(prev => 
            prev < messages.length - 1 ? prev + 1 : prev
        );
    }, [messages.length]);

    /**
     * Move to previous stage
     */
    const previousStage = useCallback(() => {
        setCurrentMessageIndex(prev => 
            prev > 0 ? prev - 1 : prev
        );
    }, []);

    /**
     * Register a callback for a specific stage
     */
    const onStageEnter = useCallback((index, callback) => {
        stageCallbacksRef.current[index] = callback;
    }, []);

    /**
     * Execute an async operation with loading state
     */
    const withLoading = useCallback(async (operation, stageIndex = 0) => {
        try {
            startLoading(stageIndex);
            const result = await operation();
            await stopLoading();
            return result;
        } catch (error) {
            await stopLoading();
            throw error;
        }
    }, [startLoading, stopLoading]);

    return {
        isLoading,
        currentMessage: messages[currentMessageIndex],
        currentMessageIndex,
        startLoading,
        stopLoading,
        setStage,
        nextStage,
        previousStage,
        onStageEnter,
        withLoading,
        progress: messages.length > 1 
            ? ((currentMessageIndex + 1) / messages.length) * 100 
            : 0
    };
};

export default useLoadingState;
