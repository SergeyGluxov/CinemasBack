require('./bootstrap');

import { createApp } from 'vue';
import hello from './components/ExampleComponent.vue';
import contentTable from './components/AllContentTableComponent';
import content from './components/ContentTableComponent';
import feedTable from './components/AllFeedTableComponent';

let app=createApp({})
app.component('example-component' , hello);
app.component('content-table-component' , contentTable);
app.component('content-component' , content);
app.component('feed-table-component' , feedTable);

app.mount("#app")
