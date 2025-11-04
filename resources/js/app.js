import { createApp } from 'vue'
import './bootstrap'

// Import TinyMCE CSS
import 'tinymce/skins/ui/oxide/skin.min.css'
import 'tinymce/skins/ui/oxide/content.min.css'
import 'tinymce/skins/content/default/content.min.css'

// Suppress TinyMCE passive event listener warnings (performance warnings, not errors)
// These are harmless warnings from TinyMCE's internal event handling
if (typeof console !== 'undefined' && console.warn) {
    const originalWarn = console.warn
    console.warn = function(...args) {
        const message = args[0]?.toString() || ''
        // Filter out TinyMCE passive event listener warnings
        if (message.includes('non-passive event listener') && 
            (message.includes('touchstart') || message.includes('touchmove') || message.includes('theme.min.js'))) {
            return // Suppress this warning
        }
        originalWarn.apply(console, args)
    }
}

// NOW import components
import VueExample from './components/VueExample.vue'
import RentalCalendar from './components/RentalCalendar.vue'
import RentalForm from './components/RentalForm.vue'
import StripePaymentForm from './components/StripePaymentForm.vue'
import EditHTMLPage from './components/EditHTMLPage.vue'

// Create Vue app instance
const app = createApp({})

// Register global components
app.component('vue-example', VueExample)
app.component('rental-calendar', RentalCalendar)
app.component('rental-form', RentalForm)
app.component('stripe-payment-form', StripePaymentForm)

// Make Vue available globally for Blade templates
window.Vue = createApp

// Store mounted app instances for cleanup
const mountedApps = new WeakMap();

// Function to cleanup removed Vue components
function cleanupRemovedComponents() {
    // Find all elements that were marked as mounted but are no longer in the DOM
    document.querySelectorAll('[data-vue-mounted]').forEach(element => {
        if (!document.contains(element)) {
            // Element was removed from DOM, cleanup
            const app = mountedApps.get(element);
            if (app && typeof app.unmount === 'function') {
                try {
                    app.unmount();
                    console.log('Unmounted Vue app for removed element');
                } catch (e) {
                    console.warn('Error unmounting Vue app:', e);
                }
            }
            mountedApps.delete(element);
        } else if (element.__vue_app__) {
            // Store the app instance for later cleanup
            mountedApps.set(element, element.__vue_app__);
        }
    });
}

// Function to mount Vue components
function mountVueComponents() {
    // First, cleanup any removed components
    cleanupRemovedComponents();
    
    // Find all Vue component elements that haven't been mounted yet
    const vueElements = document.querySelectorAll('[data-vue-component]');
    console.log('Found Vue elements:', vueElements.length);
    
    vueElements.forEach((element, index) => {
        // Check if Vue is already mounted on this element
        if (element.__vue_app__) {
            console.log('Vue already mounted on element, skipping:', element);
            // Update mounted marker if missing
            if (!element.hasAttribute('data-vue-mounted')) {
                element.setAttribute('data-vue-mounted', 'true');
                mountedApps.set(element, element.__vue_app__);
            }
            return;
        }
        
        // If marked as mounted but no app instance, remove the marker (stale state)
        if (element.hasAttribute('data-vue-mounted')) {
            console.log('Removing stale mounted marker from element:', element);
            element.removeAttribute('data-vue-mounted');
        }
        
        const componentName = element.dataset.vueComponent;
        let props = {};
        
        try {
            props = JSON.parse(element.dataset.props || '{}');
        } catch (e) {
            console.error('Error parsing props:', e);
            props = {};
        }
        
        console.log(`Mounting component ${index + 1}:`, componentName, element);
        
        // Create a new Vue app instance for this element
        let componentToMount;
        if (componentName === 'RentalCalendar') {
            console.log('Registering RentalCalendar component');
            componentToMount = RentalCalendar;
        } else if (componentName === 'RentalForm') {
            console.log('Registering RentalForm component');
            componentToMount = RentalForm;
        } else if (componentName === 'VueExample') {
            console.log('Registering VueExample component');
            componentToMount = VueExample;
        } else if (componentName === 'EditHTMLPage') {
            console.log('Registering EditHTMLPage component');
            componentToMount = EditHTMLPage;
        } else {
            console.log('Using fallback component for:', componentName);
            componentToMount = VueExample; // Default fallback
        }
        
        try {
            const elementApp = createApp(componentToMount, props);
            
            // Register all components for this app instance
            elementApp.component('rental-calendar', RentalCalendar);
            elementApp.component('rental-form', RentalForm);
            elementApp.component('vue-example', VueExample);
            elementApp.component('stripe-payment-form', StripePaymentForm);
            elementApp.component('edit-html-page', EditHTMLPage);
            
            // Ensure the element is properly in the DOM
            if (element && element.nodeType === Node.ELEMENT_NODE && document.contains(element)) {
                // Mark as mounted and store app instance BEFORE mounting to prevent race conditions
                element.setAttribute('data-vue-mounted', 'true');
                elementApp.mount(element);
                mountedApps.set(element, elementApp);
                console.log('Successfully mounted component:', componentName, 'with props:', props);
            } else {
                console.error('Invalid element for mounting:', element);
                element.removeAttribute('data-vue-mounted');
            }
        } catch (error) {
            console.error('Error mounting component:', componentName, error);
            // Remove the marker if mounting failed so we can try again
            element.removeAttribute('data-vue-mounted');
            mountedApps.delete(element);
        }
    });
}

// Mount on initial DOM load
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, mounting Vue components...');
    // Small delay to ensure DOM is fully ready
    setTimeout(mountVueComponents, 100);
});

// Mount when Livewire navigates (SPA navigation)
document.addEventListener('livewire:navigated', function() {
    console.log('Livewire navigated, mounting Vue components...');
    setTimeout(mountVueComponents, 150);
});

// Mount when Livewire updates DOM
document.addEventListener('livewire:update', function() {
    console.log('Livewire updated, mounting Vue components...');
    setTimeout(mountVueComponents, 150);
});

// Mount when Livewire component loads
document.addEventListener('livewire:load', function() {
    console.log('Livewire component loaded, mounting Vue components...');
    setTimeout(mountVueComponents, 150);
});

// Use MutationObserver as a fallback to catch any DOM updates
if (typeof MutationObserver !== 'undefined') {
    let mountTimeout = null;
    
    const observer = new MutationObserver(function(mutations) {
        let shouldMount = false;
        
        mutations.forEach(function(mutation) {
            // Check for removed nodes and cleanup
            if (mutation.removedNodes.length > 0) {
                mutation.removedNodes.forEach(function(node) {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        // Cleanup any Vue components in removed nodes
                        const vueElements = node.querySelectorAll ? node.querySelectorAll('[data-vue-mounted]') : [];
                        vueElements.forEach(el => {
                            const app = mountedApps.get(el);
                            if (app && typeof app.unmount === 'function') {
                                try {
                                    app.unmount();
                                } catch (e) {
                                    console.warn('Error unmounting removed component:', e);
                                }
                            }
                            mountedApps.delete(el);
                        });
                        // Also check if the node itself is a Vue component
                        if (node.hasAttribute && node.hasAttribute('data-vue-mounted')) {
                            const app = mountedApps.get(node);
                            if (app && typeof app.unmount === 'function') {
                                try {
                                    app.unmount();
                                } catch (e) {
                                    console.warn('Error unmounting removed component:', e);
                                }
                            }
                            mountedApps.delete(node);
                        }
                    }
                });
            }
            
            if (mutation.addedNodes.length > 0) {
                // Check if any added node is or contains a Vue component
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        // Check if the node itself is a Vue component
                        if (node.hasAttribute && node.hasAttribute('data-vue-component')) {
                            shouldMount = true;
                            return;
                        }
                        // Check if it contains Vue components
                        if (node.querySelectorAll && node.querySelectorAll('[data-vue-component]').length > 0) {
                            shouldMount = true;
                            return;
                        }
                    }
                });
            }
        });
        
        if (shouldMount) {
            // Debounce mounting to avoid excessive calls
            if (mountTimeout) {
                clearTimeout(mountTimeout);
            }
            mountTimeout = setTimeout(function() {
                console.log('MutationObserver detected new Vue components, mounting...');
                mountVueComponents();
            }, 100);
        }
    });
    
    // Start observing when DOM is ready
    if (document.body) {
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    } else {
        document.addEventListener('DOMContentLoaded', function() {
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        });
    }
}

// Export the app instance for use in components
export default app