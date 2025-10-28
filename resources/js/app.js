import { createApp } from 'vue'
import './bootstrap'
import VueExample from './components/VueExample.vue'
import RentalCalendar from './components/RentalCalendar.vue'
import RentalForm from './components/RentalForm.vue'

// Create Vue app instance
const app = createApp({})

// Register global components
app.component('vue-example', VueExample)
app.component('rental-calendar', RentalCalendar)
app.component('rental-form', RentalForm)

// Make Vue available globally for Blade templates
window.Vue = createApp

// Auto-mount Vue components
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, looking for Vue components...');
    
    // Small delay to ensure DOM is fully ready
    setTimeout(() => {
        const vueElements = document.querySelectorAll('[data-vue-component]');
        console.log('Found Vue elements:', vueElements.length);
    
    vueElements.forEach((element, index) => {
        const componentName = element.dataset.vueComponent;
        const props = JSON.parse(element.dataset.props || '{}');
        
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
        
        const elementApp = createApp(componentToMount);
        
        // Register all components for this app instance
        elementApp.component('rental-calendar', RentalCalendar);
        elementApp.component('rental-form', RentalForm);
        elementApp.component('vue-example', VueExample);
        
        // Mount the app to this specific element
        try {
            // Ensure the element is properly in the DOM
            if (element && element.nodeType === Node.ELEMENT_NODE) {
                elementApp.mount(element);
                console.log('Successfully mounted component:', componentName);
            } else {
                console.error('Invalid element for mounting:', element);
            }
        } catch (error) {
            console.error('Error mounting component:', componentName, error);
        }
    });
    }, 100); // 100ms delay
});

// Export the app instance for use in components
export default app