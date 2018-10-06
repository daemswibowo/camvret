<template>
	<ul :class="ulClass">
		<li class="nav-item" v-for="(menu,i) in menus" :key="i" :class="{'nav-dropdown':menu.childs.length>0}" v-if="can(menu.permissions.map(x=>x.name))">
			<a v-if="menu.type=='link' || menu.childs.length > 0" class="nav-link" @click.prevent="handleOpen" :class="{'nav-dropdown-toggle':menu.childs.length>0}" href="#">
				<i :class="'nav-icon '+menu.icon"></i> {{ menu.title }}
			</a>
			<router-link v-else class="nav-link" :to="parseJson(menu.to)">
				<i :class="'nav-icon '+menu.icon"></i> {{ menu.title }}
			</router-link>
			<dl-sidebar-menu ulClass="nav-dropdown-items" v-if="menu.childs.length > 0" :menus="menu.childs"></dl-sidebar-menu>
		</li>
	</ul>
</template>

<script>
	export default {
		props: ['ulClass','menus'],
		methods: {
			parseJson(string) {
				return JSON.parse(string);
			},

			handleOpen (e) {
				var dropdown = e.target;
				$(dropdown).parent().toggleClass('open');
			}
		}
	}
</script>