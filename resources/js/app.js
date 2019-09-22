window.Vue = require('vue');

require('./bootstrap');
require('./component');
require('./library');

import Router from './router';



axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

const app = new Vue({
    el: '#app',
    router: Router,
    data() {
        return {
            user: AuthUser,
        }
    },
    methods: {
        userCan(permission) {
            if (this.user && this.user.allPermissions.includes(permission)) {
                return true;
            }
            return false;
        },
        MakeUrl(path) {
            return BaseUrl(path);
        }
    }
});
