require('./bootstrap');

import { createApp } from 'vue';
import hello from './components/ExampleComponent.vue';
import contentTable from './components/AllContentTableComponent';
import userTable from './components/UsersTableComponent';
import content from './components/ContentTableComponent';
import feedsTable from './components/AllFeedTableComponent';
import feedTable from './components/FeedTableComponent';
import editModel from './components/EditModelComponent';
import passportClients from './components/passport/Clients.vue';
import passportAuthorizedClients from './components/passport/AuthorizedClients';
import passportPersonalAccessTokens from './components/passport/PersonalAccessTokens';

let app=createApp({})
app.component('example-component' , hello);
app.component('content-table-component' , contentTable);
app.component('users-table-component' , userTable);
app.component('content-component' , content);
app.component('all-feed-table-component' , feedsTable);
app.component('feed-table-component' , feedTable);
app.component('alert-box' , editModel);
app.component('passport-clients' , passportClients);
app.component('passport-authorized-clients' , passportAuthorizedClients);
app.component('passport-personal-access-tokens' , passportPersonalAccessTokens);

app.mount("#app")
