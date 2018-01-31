/** Load javascript dependencies */
require('./bootstrap');

/** Include vuejs and vue-router */
import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

/** Define global components */
Vue.component('status', require('./components/Status.vue'));
Vue.component('panel', require('./components/Panel.vue'));

/** Define routes for router */
const routes = [
    { path: '/', component: require('./components/Dashboard.vue') },
    { path: '/menuplan/create', component: require('./components/menuplan/create.vue') },
	{ path: '*', component: require('./components/NotFound.vue') },
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