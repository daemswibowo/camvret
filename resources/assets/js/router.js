import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from './pages/home.vue';

// Management pages
import Permission from './pages/management/permission/index.vue';
import Role from './pages/management/role/index.vue';
import User from './pages/management/user/index.vue';
import Menu from './pages/management/menu/index.vue';

import Profile from './pages/profil.vue';

Vue.use(VueRouter);
let path = '/dashboard/';
let management = path+'management/';
export default new VueRouter ({
	mode: 'history',
	linkActiveClass: 'active',
	routes: [
	{
		path: path, 
		redirect: {name: 'Read Home'},
		meta: {
			permission: 'public',
		}
	},
	{
		path: path+'profile',
		name: 'Edit Profile',
		component: Profile,
		meta: {
			permission: 'Edit Profile',
			breadcrumbs: [
				{title: 'Profile'},
				{title: 'Edit', active: true},
			],
		}
	},
	{
		path: path+'home', 
		component: Home,
		name: 'Read Home',
		meta: {
			permission: 'Read Home',
			breadcrumbs: [
				{title: 'Home', active: true},
			],
		} 
	},

	{
		path: management+'permission',
		component: Permission,
		name: 'Manage Permissions',
		meta: {
			permission: 'Manage Permissions',
			breadcrumbs: [
				{title: 'Management'},
				{title: 'Permission', active: true},
			],
		}
	},

	{
		path: management+'role',
		component: Role,
		name: 'Manage Roles',
		meta: {
			permission: 'Manage Roles',
			breadcrumbs: [
				{title: 'Management'},
				{title: 'Role', active: true},
			],
		}
	},

	{
		path: management+'user',
		component: User,
		name: 'Manage Users',
		meta: {
			permission: 'Manage Users',
			breadcrumbs: [
				{title: 'Management'},
				{title: 'User', active: true},
			],
		}
	},

	{
		path: management+'menu',
		component: Menu,
		name: 'Manage Menus',
		meta: {
			permission: 'Manage Menus',
			breadcrumbs: [
				{title: 'Management'},
				{title: 'Menu', active: true},
			],
		}
	},

	{
		path: management+'oauth-client',
		component: require('./pages/management/oauth-clients'),
		name: 'Manage Oauth Clients',
		meta: {
			permission: 'Manage Oauth Clients',
			breadcrumbs: [
				{title: 'Management'},
				{title: 'Oauth Client', active: true},
			],
		}
	},

	]
});