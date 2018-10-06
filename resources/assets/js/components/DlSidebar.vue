<template>
	<div class="sidebar">
	    <nav class="sidebar-nav">
	      <dl-sidebar-menu ulClass="nav" :menus="items"></dl-sidebar-menu>
	    </nav>
	    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
	  </div>
</template>

<script>
	export default {
		
		data () {
			return {
				items: []
			}
		},

		mounted () {
			this.getItems();
			this.$events.listen('menu-updated', eventData => this.getItems());
		},

		methods: {
			getItems () {
				let vm = this;
				axios.get(this.$config.api+'management/menu')
				.then(res => {
					vm.items = res.data;
				})
				.catch(err => {

				});
			}
		}
	}
</script>