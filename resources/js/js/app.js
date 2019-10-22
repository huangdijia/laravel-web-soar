import Vue from 'vue';
import axios from 'axios';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

Vue.component('soar', require('./components/Soar.vue'));

new Vue({
    el: '#web-soar',
});
