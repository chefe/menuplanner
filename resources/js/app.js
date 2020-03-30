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

/** Include vue svg icon */
import VueSVGIcon from 'vue-svgicon'
Vue.use(VueSVGIcon)

/** Include vue-progressbar and configure it */
import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
    color: 'rgb(31, 157, 85)',
    failedColor: 'rgb(204, 31, 26)',
    height: '2px'
});

/** Define global components */
Vue.component('status', require('./components/utilities/status.vue').default);
Vue.component('center-panel', require('./components/utilities/center-panel.vue').default);
Vue.component('form-item', require('./components/utilities/form-item.vue').default);
Vue.component('icon', require('./components/utilities/icon.vue').default);
Vue.component('editor', require('./components/utilities/editor.vue').default);
Vue.component('delete-button', require('./components/utilities/delete-button.vue').default);
Vue.component('page-title', require('./components/utilities/page-title.vue').default);
Vue.component('modal', require('./components/utilities/modal.vue').default);

/** Define routes for router */
const routes = [
    { path: '/', component: require('./components/dashboard.vue').default },
    { path: '/menuplan/create', component: require('./components/menuplan/create.vue').default },
    { path: '/menuplan/:id', component: require('./components/menuplan/show.vue').default },
    { path: '/menuplan/:id/edit', component: require('./components/menuplan/edit.vue').default },
    { path: '/meal/:id/edit', component: require('./components/meal/edit.vue').default },
    { path: '/purchase/:id/edit', component: require('./components/purchase/edit.vue').default },
    { path: '/menuplan/:id/items', component: require('./components/item/index.vue').default },
    { path: '/menuplan/:id/shopping-list', component: require('./components/shoppinglist/index.vue').default },
    { path: '/menuplan/:id/share', component: require('./components/share/index.vue').default },
	{ path: '*', component: require('./components/not-found.vue').default },
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
