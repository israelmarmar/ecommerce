/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;
window.VueRouter = require('vue-router').default;

Vue.use(VueRouter);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const router = new VueRouter({
    mode: "history",
    routes: [
        { path: '/page/login', component: require('./components/auth/LoginComponent.vue').default, name: 'Login' },
        { path: '/page/cart', component: require('./components/products/CartComponent.vue').default, name: 'Cart' },
        { path: '/page/register', component: require('./components/auth/RegisterComponent.vue').default, name: 'Register' },
        { path: '/page/products', component: require('./components/products/ProductsComponent.vue').default, name: 'Products' },
        { path: '/page/orders', component: require('./components/products/OrdersComponent.vue').default, name: 'Orders' },
        { path: '/page/purchases', component: require('./components/products/PurchasesComponent.vue').default, name: 'Purchases' }
    ]
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    router,
    el: '#app',
});

