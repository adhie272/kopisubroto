import './bootstrap';

import { createApp } from 'vue';

// Create Vue app instance
const app = createApp({});

// Register global components
// Example: import ExampleComponent from './components/ExampleComponent.vue'
// app.component('ExampleComponent', ExampleComponent)

// You can also register multiple components at once:
// import.meta.glob('./components/**/*.vue', { eager: true }).forEach((module, path) => {
//   const componentName = path.split('/').pop().replace(/\.vue$/, '');
//   app.component(componentName, module.default);
// });

// Mount Vue app to #app element
app.mount('#app');
