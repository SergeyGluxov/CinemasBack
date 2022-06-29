require('./bootstrap');

import { createApp } from 'vue';
import hello from './components/ExampleComponent.vue';
import contentTable from './components/AllContentTableComponent';
import content from './components/ContentTableComponent';

let app=createApp({})
app.component('example-component' , hello);
app.component('content-table-component' , contentTable);
app.component('content-component' , content);

app.mount("#app")
