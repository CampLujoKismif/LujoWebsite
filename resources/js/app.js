import { createApp } from 'vue'
import './bootstrap'
import VueExample from './components/VueExample.vue'
import RentalCalendar from './components/RentalCalendar.vue'
import RentalForm from './components/RentalForm.vue'
import StripePaymentForm from './components/StripePaymentForm.vue'

// Create Vue app instance
const app = createApp({})

// Register global components
app.component('vue-example', VueExample)
app.component('rental-calendar', RentalCalendar)
app.component('rental-form', RentalForm)
app.component('stripe-payment-form', StripePaymentForm)

// Make Vue available globally for Blade templates
window.Vue = createApp

// Function to mount Vue components
function mountVueComponents() {
    // Find all Vue component elements that haven't been mounted yet
    const vueElements = document.querySelectorAll('[data-vue-component]:not([data-vue-mounted])');
    console.log('Found Vue elements to mount:', vueElements.length);
    
    if (vueElements.length === 0) {
        return;
    }
    
    vueElements.forEach((element, index) => {
        // Double check it hasn't been mounted (race condition protection)
        if (element.hasAttribute('data-vue-mounted')) {
            console.log('Element already marked as mounted, skipping:', element);
            return;
        }
        
        // Check if Vue is already mounted on this element
        if (element.__vue_app__) {
            console.log('Vue already mounted on element, skipping:', element);
            element.setAttribute('data-vue-mounted', 'true');
            return;
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
            
            // Ensure the element is properly in the DOM
            if (element && element.nodeType === Node.ELEMENT_NODE && document.contains(element)) {
                // Mark as mounted BEFORE mounting to prevent race conditions
                element.setAttribute('data-vue-mounted', 'true');
                elementApp.mount(element);
                console.log('Successfully mounted component:', componentName, 'with props:', props);
            } else {
                console.error('Invalid element for mounting:', element);
                element.removeAttribute('data-vue-mounted');
            }
        } catch (error) {
            console.error('Error mounting component:', componentName, error);
            // Remove the marker if mounting failed so we can try again
            element.removeAttribute('data-vue-mounted');
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
                        if (node.querySelectorAll && node.querySelectorAll('[data-vue-component]:not([data-vue-mounted])').length > 0) {
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