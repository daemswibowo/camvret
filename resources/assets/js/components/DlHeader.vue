<template>

	<header class="app-header navbar">
		<button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
			<span class="navbar-toggler-icon"></span>
		</button>
		<a class="navbar-brand" href="#">
			<img class="navbar-brand-full" src="https://dummyimage.com/89x25/cccccc/999999.jpg" width="89" height="25" alt="CoreUI Logo">
			<img class="navbar-brand-minimized" src="https://dummyimage.com/30x30/cccccc/999999.jpg" width="30" height="30" alt="CoreUI Logo">
		</a>
		<button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
			<span class="navbar-toggler-icon"></span>
		</button>
		<ul class="nav navbar-nav d-md-down-none">
			<li class="nav-item px-3">
				<a class="nav-link" href="#">Dashboard</a>
			</li>
			<li class="nav-item px-3">
				<a class="nav-link" href="#">Users</a>
			</li>
			<li class="nav-item px-3">
				<a class="nav-link" href="#">Settings</a>
			</li>
		</ul>
		<ul class="nav navbar-nav ml-auto">
			<li class="nav-item d-md-down-none">
				<a class="nav-link" href="#">
					<i class="icon-bell"></i>
					<span class="badge badge-pill badge-danger">5</span>
				</a>
			</li>
			<li class="nav-item d-md-down-none">
				<a class="nav-link" href="#">
					<i class="icon-list"></i>
				</a>
			</li>
			<li class="nav-item d-md-down-none">
				<a class="nav-link" href="#">
					<i class="icon-location-pin"></i>
				</a>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
					<img class="img-avatar" :src="image">
				</a>
				<div class="dropdown-menu dropdown-menu-right">
					<div class="dropdown-header text-center">
						<strong>Settings</strong>
					</div>
					<router-link class="dropdown-item" :to="{name: 'Edit Profile'}"><i class="fa fa-user"></i> Profile</router-link>
					<a class="dropdown-item" href="javascript:void(0)" @click="logout"><i class="fa fa-lock"></i> Logout</a>
				</div>
			</li>
		</ul>
		<button class="navbar-toggler aside-menu-toggler d-md-down-none" type="button" data-toggle="aside-menu-lg-show">
			<span class="navbar-toggler-icon"></span>
		</button>
		<button class="navbar-toggler aside-menu-toggler d-lg-none" type="button" data-toggle="aside-menu-show">
			<span class="navbar-toggler-icon"></span>
		</button>
	</header>

</template>

<script>
	import avatar from '../../img/avatar.png'

	export default {
		data () {
			return {
				userdata: '',
				image: '',
			}
		},

		mounted () {
			this.userdata = Me;
			this.getImage();
			this.$events.listen('refresh-profile', event => this.getProfile());
		},

		methods: {
			logout () {
				$('#logout-form').submit();
			},

			getProfile () {
				let vm = this;
				axios.get(this.$config.api+'profile')
				.then(res => {
					vm.userdata = res.data;
					this.getImage();
				})
				.catch(err => {
					vm.handleError(err);
				});
			},

			getImage () {
				if (this.userdata.image) {
					var img = JSON.parse(this.userdata.image);
					if (this.$config.symlink) {
						this.image = img.url;
					} else {
						this.image = '/storage/'+img.path;
					}
				} else {
					this.image = avatar;
				}
			}
		}
	}
</script>