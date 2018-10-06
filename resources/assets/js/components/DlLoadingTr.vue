<template>
	<tr>
		<td v-for="(l, key) in jumlah">
			<dl-loading v-if="bulet_first && key==0" :styles="{'height': '20px','width': '20px','border-radius': '10px'}" />
			<dl-loading v-if="bulet_first && key > 0" :styles="{'height': '20px','width': '100%','border-radius': '10px'}" />
			<dl-loading v-if="!bulet_first" :styles="{'height': '20px','width': '100%','border-radius': '10px'}" />
		</td>
	</tr>
</template>

<script>
	export default {
		props: ['jumlah','bulet_first']
	}
</script>