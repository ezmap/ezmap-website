import Vue from 'vue';
import Map from './components/Map.vue';

Vue.filter('nl2br', function (value) {
    return value.replace(/\n/g, "<br>");
});

new Vue({
    el: '#app',
    components: {Map}
});

