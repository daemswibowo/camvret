<template>
	<ol :class="olClass">
		<li v-for="(menu,i) in menus" :id="'menu_'+menu.id" :key="i">
			<div class="card card-accent-primary mb-1">
				<div class="card-header">
					<i :class="menu.icon"></i> {{ menu.title }}
					<div class="card-header-actions">
						<a href="#" has-tooltip="true" data-placement="top" title="Detail" class="card-header-action btn-minimize" data-toggle="collapse" :data-target="'#detail_'+menu.id" aria-expanded="true">
							<i class="icon-eye"></i>
						</a>
						<a href="javascript:void(0)" @click="edit(menu)" has-tooltip="true" data-placement="top" title="Edit" class="card-header-action btn-setting">
							<i class="icon-pencil"></i>
						</a>
						<a href="javascript:void(0)" @click="destroy(menu.id)" has-tooltip="true" data-placement="top" title="Delete" class="card-header-action btn-close text-danger">
							<i class="icon-trash"></i>
						</a>
					</div>
				</div>
				<div class="card-body collapse" :id="'detail_'+menu.id">
					<p><strong>Title:</strong> {{ menu.title }}</p>
					<p><strong>Icon:</strong> {{ menu.icon }}</p>
					<p><strong>To:</strong> {{ menu.to }}</p>
					<p><strong>Type:</strong> {{ menu.type }}</p>
					<p><strong>Permissions:</strong> 
						<span class="badge badge-primary" v-for="permission in menu.permissions.map(x=>x.name)">{{ permission }}</span>
					</p>
				</div>
			</div>
			<dl-menu-child v-if="menu.childs.length > 0" @edit="edit" @destroy="destroy" :menus="menu.childs"></dl-menu-child>
		</li>
	</ol>
</template>

<script>
	import DlMenuChild from './DlMenuChild.vue';

	export default {
		components: {
			DlMenuChild
		},
		props: ['olClass','menus'],
		mounted () {
			this.$nextTick(function () {
				$('[has-tooltip="true"]').tooltip();
			});
		},
		methods: {
			edit (item) {
				this.$emit('edit', item);
			},

			destroy (id) {
				this.$emit('destroy', id);
			}
		}
	}
</script>