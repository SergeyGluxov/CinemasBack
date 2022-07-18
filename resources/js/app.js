require('./bootstrap');

import { createApp } from 'vue';
import hello from './components/ExampleComponent.vue';
import contentTable from './components/AllContentTableComponent';
import content from './components/ContentTableComponent';
import feedsTable from './components/AllFeedTableComponent';
import feedTable from './components/FeedTableComponent';
import editModel from './components/EditModelComponent';

let app=createApp({})
app.component('example-component' , hello);
app.component('content-table-component' , contentTable);
app.component('content-component' , content);
app.component('all-feed-table-component' , feedsTable);
app.component('feed-table-component' , feedTable);
app.component('alert-box' , editModel);

app.mount("#app")
