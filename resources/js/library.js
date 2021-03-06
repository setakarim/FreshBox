/**
 * List Library Library
 */
import vueHeadful from 'vue-headful';
import DatePicker from 'vue2-datepicker';
import iosAlertView from 'vue-ios-alertview';
import VueSweetalert2 from 'vue-sweetalert2';
import VueHtmlToPaper from 'vue-html-to-paper';
import { ModelListSelect } from 'vue-search-select';
import 'vue-search-select/dist/VueSearchSelect.css'
import 'sweetalert2/dist/sweetalert2.css';
import 'sweetalert2/dist/sweetalert2.all.js';
// import Vue from 'vue'
// import VueMoment from 'vue-moment'
// import moment from 'moment-timezone'

// Vue.use(VueMoment, {
//     moment,
// })

const options = {
    name: '_blank',
    specs: [
        'fullscreen=yes',
        'titlebar=yes',
        'scrollbars=yes'
    ],
    styles: [
        bootstrap,
        '../../public/assets/css/style.css',
        '../../public/assets/css/components.css',
        '../../public/css/custom.css',
    ]
};

Vue.use(VueSweetalert2);
Vue.use(VueHtmlToPaper, options);
Vue.use(iosAlertView);
Vue.use(DatePicker);
Vue.component('vue-headful', vueHeadful);
Vue.component('model-list-select', ModelListSelect);

Vue.component("laravel-pagination", require('laravel-vue-pagination'));
