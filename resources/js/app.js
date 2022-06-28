require('./bootstrap');

import { createApp } from 'vue';
import hello from './components/ExampleComponent.vue';
import contentTable from './components/ContentTableComponent';

let app=createApp({})
app.component('example-component' , hello);
app.component('content-table-component' , contentTable);

app.mount("#app")
