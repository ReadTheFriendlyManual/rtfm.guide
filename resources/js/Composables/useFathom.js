/**
 * Fathom Analytics composable for event tracking
 *
 * Provides a simple interface to track custom events in Fathom Analytics.
 * The Fathom script is only loaded in production (see app.blade.php).
 *
 * In development, events are logged to console instead of being tracked.
 */
export function useFathom() {
    const isProduction = import.meta.env.PROD

    /**
     * Track a custom event in Fathom Analytics
     *
     * @param {string} eventName - The name/code of the event to track
     * @param {number} value - Optional monetary value in cents (e.g., 100 = $1.00)
     */
    const trackEvent = (eventName, value = 0) => {
        if (typeof window === 'undefined') {
            return
        }

        if (isProduction && window.fathom) {
            window.fathom.trackEvent(eventName, { _value: value })
        } else if (!isProduction) {
            // Log events in development for debugging
            console.log(
                `[Fathom] Event tracked: ${eventName}${value ? ` (value: ${value})` : ''}`
            )
        }
    }

    return {
        trackEvent,
        isProduction
    }
}
