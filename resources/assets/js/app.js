require('./bootstrap');

import Vue from 'vue';
import VueConfig from 'vue-config';
import Router from './router';
import Paginate from 'vuejs-paginate'
import VueMoment from 'vue-moment';
import VueEvents from 'vue-events'
import VueHead from 'vue-head'
import {ContentLoader} from 'vue-content-loader';
import VeeValidate from 'vee-validate';
import VSelect from 'vue-select';

Vue.use(VeeValidate, {
	events: 'blur',
});
Vue.use(VueHead);
Vue.use(VueEvents);
Vue.use(VueMoment);
Vue.use(VueConfig, {
	api: '/services/',
	symlink: false, // change to true, if your server support symlink
});

Vue.component('paginate', Paginate)
Vue.component('dl-app', require('./app.vue'));
Vue.component('dl-menu-child', require('./pages/management/menu/DlMenuChild.vue'));
Vue.component('dl-sidebar-menu', require('./components/DlSidebarMenu.vue'));
Vue.component('dl-404', require('./404.vue'));
Vue.component('content-loader', ContentLoader);
Vue.component('ContentLoader', ContentLoader);
Vue.component('dl-loading', require('./components/DlLoading.vue'));
Vue.component('dl-loading-tr', require('./components/DlLoadingTr.vue'));
Vue.component('v-select', VSelect);
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);
window.axios.defaults.timeout = 30000;

axios.interceptors.response.use(null, function(error) {
	if (error.response.status === 401 || error.response.status === 419) {
		swal({
			title: 'Session Expired!',
			type: 'info',
			text: 'Your session is up! Please login again!',
			confirmButtonText: 'Login',
			allowOutsideClick: false,
		}).then(res => {
			app.$router.go('/login');
			swal({
				title: '<i class="fa fa-refresh fa-spin"></i>',
				text: 'Please wait...',
				showConfirmButton: false,
				allowOutsideClick: false
			})
		});
	}
	return Promise.reject(error);
});

Vue.mixin({
	data () {
		return {
			appname: 'App',
			user: {
				name: '',
				roles: [],
				permissions: []
			}
		}
	},

	mounted () {
		if (Me) {
			this.getRoles(Me.roles);
			this.getPermissions(Me.permissions);
		}
	},

	methods: {
		swalDelete () {
			return swal({
				title: 'Apakah Anda yakin?',
				text: "Anda tidak dapat mengembalikan data ini lagi!",
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Ya, hapus saja!',
				cancelButtonText: 'Batal',
				allowEscapeKey: false,
			});
		},

		swalMassDelete () {
			return swal({
				title: 'Anda yakin ?',
				text: "Semua data yang dipilih akan dihapus!",
				type: 'question',
				showCancelButton: true,
				allowEscapeKey: false,
				confirmButtonText: 'Ya, lanjutkan!',
				cancelButtonText: 'Batal',
			})
		},

		swalLoading () {
			swal({
				title: 'Sedang Menghapus',
				text: 'Harap tunggu, mohon jangan tutup jendela ini.',
				allowEscapeKey: false,
				allowOutsideClick: false,
				onOpen: () => {
					swal.showLoading();
				}
			})
		},

		delete (id) {
			let vm = this;
			return axios.delete(id)
		},

		getRoles (data) {
			data.forEach(role => {
				this.user.roles.push(role.name);
				this.getPermissions(role.permissions);
			});
		},

		getPermissions (data) {
			data.forEach(perm => {
				this.user.permissions.push(perm.name);
			});
		},

		handleError (err, timeout=3000) {
			if (err.response.status) {
				let title = 'Something went wrong! ('+err.response.status+')';
				let msg = '';
				switch (err.response.status) {
					default:
					if (err.response.data.message) {
						msg = err.response.data.message;
					} else {
						msg = err;
					}
					break;
				}

				if (err.response.status!=401 || err.response.status!=419) {
					toastr.error(msg, title, {progressBar: true, timeOut: timeout});
				} 
			}
		},

		can (permissions=[]) {
			let count = 0;
			if (Array.isArray(permissions)) {
				permissions.forEach(p => {
					if (this.user.permissions.indexOf(p) >= 0) {
						count += 1;
					}
				});
			} else {
				if (this.user.permissions.indexOf(permissions) >= 0) {
					count += 1;
				} 
			}

			if (count > 0) {
				return true;
			} else {
				return false;
			}
		},
	}
})
const app = new Vue({
	el: '#app',
	router: Router
});
