
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import BootstrapVue from 'bootstrap-vue'

Vue.use(BootstrapVue);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

/*Vue.component('terminal-component', require('./components/TerminalComponent').default);
Vue.component('volume-component', require('./components/VolumeComponent').default);
Vue.component('depth-component', require('./components/DepthComponent').default);
Vue.component('my-orders-component', require('./components/MyOrdersComponent').default);
Vue.component('settings-component', require('./components/SettingsComponent').default);
Vue.component('login-component', require('./components/LoginComponent').default);
Vue.component('user_profile_component', require('./components/UserProfileComponent').default);
Vue.component('wm-exchanger', require('./components/WMExchangerComponent').default);
Vue.component('log-markets', require('./components/LogMarketComponent').default);
Vue.component('log-orders', require('./components/LogOrdersComponent').default);
Vue.component('binance-dex-depth', require('./components/BinanceDexDepthComponent').default);
Vue.component('options_global', require('./components/OptionGlobalComponent').default)*/;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});
