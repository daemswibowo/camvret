<template>
	<ol class="breadcrumb">
		<li class="breadcrumb-item">{{ appname }}</li>
		<li class="breadcrumb-item" v-for="item in items" :class="{'active':item.active}">
			<router-link v-if="item.nav && item.nav.type=='vue'" :to="item.nav.to">{{ item.title }}</router-link>
			<a v-else-if="item.nav && item.nav.type=='link'" :href="item.nav.to">
				{{ item.title }}
			</a>
			<template v-else>
				{{ item.title }}
			</template>
		</li>
		<li class="breadcrumb-menu d-md-down-none">
			<div class="btn-group" role="group" aria-label="Button group">
				<a class="btn" href="#">
					<i class="icon-speech"></i>
				</a>
				<a class="btn" href="./"><i class="icon-graph"></i>  Dashboard</a>
				<a class="btn" href="#"><i class="icon-settings"></i>  Settings</a>
			</div>
		</li>
	</ol>
</template>
<script>
	export default {
		data () {
			return {
				items: [],
			}
		},
		mounted () {
			this.getBreadcrumbs();
		},
		watch: {
			'$route': 'getBreadcrumbs'
		},
		methods: {
			getBreadcrumbs () {
				this.items = [];
				if (this.$route.meta.breadcrumbs) {
					this.items = this.$route.meta.breadcrumbs;
				}
			}
		}
	}
</script>