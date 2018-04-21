/** Load javascript dependencies */
require('./bootstrap');

/** Include vuejs and vue-router */
import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

/** Include vue-i18n and use it */
import VueI18n from 'vue-i18n'
import messages from './messages.js'
Vue.use(VueI18n)
const i18n = new VueI18n({
  locale: document.querySelector('html').getAttribute('lang'),
  messages, 
});

/** Include vue-progressbar and configure it */
import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
    color: 'rgb(31, 157, 85)',
    failedColor: 'rgb(204, 31, 26)',
    height: '2px'
});

/** Define global components */
Vue.component('status', require('./components/status.vue'));
Vue.component('center-panel', require('./components/center-panel.vue'));
Vue.component('form-item', require('./components/form-item.vue'));

/** Define routes for router */
const routes = [
    { path: '/', component: require('./components/dashboard.vue') },
    { path: '/menuplan/create', component: require('./components/menuplan/create.vue') },
    { path: '/menuplan/:id', component: require('./components/menuplan/show.vue') },
    { path: '/menuplan/:id/edit', component: require('./components/menuplan/edit.vue') },
    { path: '/menuplan/:id/meal/create', component: require('./components/meal/create.vue') },
    { path: '/meal/:id/edit', component: require('./components/meal/edit.vue') },
    { path: '/menuplan/:id/items', component: require('./components/item/index.vue') },
    { path: '/menuplan/:id/shopping-list', component: require('./components/shoppinglist/index.vue') },
    { path: '/menuplan/:id/share', component: require('./components/share/index.vue') },
	{ path: '*', component: require('./components/not-found.vue') },
];

/** Setup router */
window.router = new VueRouter({
    mode: 'history',
    routes: routes,
});

/** Create vuejs app */
const app = new Vue({
    el: '#app',
    router: router,
    i18n: i18n,
});