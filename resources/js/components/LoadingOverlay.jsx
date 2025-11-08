import React from 'react';
import LoadingIndicator from './LoadingIndicator';

/**
 * LoadingOverlay Component
 * 
 * A full-screen overlay wrapper for the LoadingIndicator component.
 * Useful for blocking user interaction during critical operations.
 * 
 * @param {Object} props - Component props
 * @param {boolean} props.show - Whether to show the overlay
 * @param {string} props.message - Current loading message
 * @param {string[]} props.messages - Array of messages to cycle through
 * @param {function} props.onClose - Optional callback when overlay should close
 * @param {boolean} props.dismissible - Whether clicking outside closes the overlay (default: false)
 * @param {Object} props.indicatorProps - Props to pass to LoadingIndicator component
 * 
 * @example
 * <LoadingOverlay 
 *   show={isLoading}
 *   messages={["Processing payment...", "Verifying transaction..."]}
 *   indicatorProps={{ variant: 'spinner', size: 'large' }}
 * />
 */
const LoadingOverlay = ({
    show,
    message,
    messages,
    onClose = null,
    dismissible = false,
    indicatorProps = {}
}) => {
    if (!show) return null;

    const handleOverlayClick = (e) => {
        if (dismissible && e.target === e.currentTarget && onClose) {
            onClose();
        }
    };

    return (
        <div
            className="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center transition-all duration-300"
            onClick={handleOverlayClick}
            role="dialog"
            aria-modal="true"
            aria-label="Loading overlay"
        >
            <div
                className="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all duration-300 scale-100"
                onClick={(e) => e.stopPropagation()}
            >
                <LoadingIndicator
                    initialMessage={message}
                    messages={messages}
                    showProgress={true}
                    {...indicatorProps}
                />
                
                {dismissible && (
                    <button
                        onClick={onClose}
                        className="mt-6 w-full px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 transition-colors"
                        aria-label="Cancel loading"
                    >
                        Cancel
                    </button>
                )}
            </div>
        </div>
    );
};

export default LoadingOverlay;
