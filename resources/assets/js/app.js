/** Load javascript dependencies */
require('./bootstrap');

/** Include vuejs and vue-router */
import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

/** Define global components */
Vue.component('status', require('./components/status.vue'));
Vue.component('center-panel', require('./components/center-panel.vue'));

/** Define routes for router */
const routes = [
    { path: '/', component: require('./components/dashboard.vue') },
    { path: '/menuplan/create', component: require('./components/menuplan/create.vue') },
    { path: '/menuplan/:id/edit', component: require('./components/menuplan/edit.vue') },
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
});