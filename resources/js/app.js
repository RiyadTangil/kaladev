/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import {createApp} from 'vue';
import DefaultComponent from "./components/DefaultComponent";
import router from './router';
import store from './store';
import axios from 'axios';
import i18n from "./i18n";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import VueSimpleAlert from "vue3-simple-alert";
import VueNextSelect from 'vue-next-select';
import 'vue-next-select/dist/index.css';
import VueApexCharts from "vue3-apexcharts";
import ENV from './config/env';
import DEV_CONFIG from './config/dev';

// Determine if we're in development mode
const isDev = process.env.NODE_ENV !== 'production';

/* Start tooltip alert code */
const options = {
    timeout: 2000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: "button",
    icon: true,
    rtl: false
};
/* End tooltip alert code */


/* Start axios code*/
const API_URL = ENV.API_URL;
const API_KEY = ENV.API_KEY;

axios.defaults.baseURL = API_URL + '/api';
axios.interceptors.request.use(
    config => {
        config.headers['x-api-key'] = API_KEY;
        if (localStorage.getItem('vuex')) {
            const vuex = JSON.parse(localStorage.getItem('vuex'));
            const token = vuex.auth.authToken;
            const language = vuex.globalState.lists.language_code;
            config.headers['Authorization'] = token ? `Bearer ${token}` : '';
            config.headers['x-localization'] = language;
        }
        return config;
    },
    error => Promise.reject(error),
);
/* End axios code */

const app = createApp({});
app.component('default-component', DefaultComponent);
app.component('vue-select', VueNextSelect)
app.use(router)
app.use(store)
app.use(VueSimpleAlert)
app.use(VueApexCharts)
app.use(Toast, options)
app.use(i18n)

// Helper function to get a more readable component name
function getReadableComponentName(vm) {
    if (!vm) return 'Unknown';
    
    // Try to get the component file path
    if (vm.$options && vm.$options.__file) {
        // Extract just the component name from the file path
        const filePath = vm.$options.__file;
        const fileName = filePath.split('/').pop();
        return fileName;
    }
    
    // Try to get the component name
    if (vm.$options && vm.$options.name) {
        return vm.$options.name;
    }
    
    // Try to get the tag name
    if (vm.$el && vm.$el.tagName) {
        return vm.$el.tagName.toLowerCase();
    }
    
    return 'Anonymous Component';
}

// Add global error handler
app.config.errorHandler = (err, vm, info) => {
    // Get component name
    const componentName = getReadableComponentName(vm);
    const componentPath = vm?.$options?.__file || 'Unknown path';
    
    // Format the error message with component information
    const errorMessage = isDev && DEV_CONFIG.showComponentNames 
        ? `[Vue Error]: Error in ${componentName} (${componentPath}): ${err.message}`
        : err.message;
    
    // Log detailed error information in development mode
    if (isDev && DEV_CONFIG.verboseErrors) {
        console.error(errorMessage);
        console.error(`Additional Info: ${info}`);
        console.error(err);
        
        // Try to extract the original source location
        if (err.stack) {
            const stackLines = err.stack.split('\n');
            console.error('Error location:');
            stackLines.slice(1, 4).forEach(line => {
                // Extract file path and line number from stack trace
                const match = line.match(/\((.*?):(\d+):(\d+)\)/);
                if (match) {
                    const [, filePath, lineNum, colNum] = match;
                    console.error(`  File: ${filePath}`);
                    console.error(`  Line: ${lineNum}, Column: ${colNum}`);
                } else {
                    console.error(`  ${line.trim()}`);
                }
            });
        }
    } else {
        console.error(err);
    }
    
    // Show toast notification in development mode
    if (isDev) {
        const toast = app.config.globalProperties.$toast;
        if (toast) {
            toast.error(errorMessage);
        }
    }
};

// Add warning handler
app.config.warnHandler = (msg, vm, trace) => {
    const componentName = vm?.$options?.__file || 'Unknown component';
    console.warn(`[Vue Warning]: Warning in ${componentName}: ${msg}`);
    if (trace) console.warn(`Trace: ${trace}`);
};

app.mount('#app');
