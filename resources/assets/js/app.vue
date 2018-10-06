<template>
	<div>	
		<dl-404 v-if="not_found"></dl-404>
		<template v-else>
			<dl-header></dl-header>
			<div class="app-body">
				<dl-sidebar></dl-sidebar>

				<main class="main">
					<dl-breadcrumb></dl-breadcrumb>
					<div class="container-fluid">
						<div class="animated fadeIn">
							<router-view></router-view>
						</div>
					</div>
				</main>
				<dl-aside></dl-aside>
			</div>
			<footer class="app-footer">
				<div>
					<a href="https://coreui.io">CoreUI</a>
					<span>&copy; 2018 creativeLabs.</span>
				</div>
				<div class="ml-auto">
					<span>Powered by</span>
					<a href="https://coreui.io">CoreUI</a>
				</div>
			</footer>
		</template>
	</div>
</template>
<script>
	import DlHeader from './components/DlHeader.vue';
	import DlSidebar from './components/DlSidebar.vue';
	import DlBreadcrumb from './components/DlBreadcrumb.vue';
	import DlAside from './components/DlAside.vue';

	export default {
		components: {
			DlHeader,
			DlSidebar,
			DlBreadcrumb,
			DlAside,
		},
		data () {
			return {
				not_found: false,
			}
		},
		mounted () {
			this.checkPermission();
		},

		watch: {
			'$route': 'checkPermission'
		},
		methods: {

			checkPermission () {
				if (!this.can(this.$route.meta.permission)) {
					this.not_found = true
				} else {
					this.not_found = false;
				}
			}
		},
	}
</script>