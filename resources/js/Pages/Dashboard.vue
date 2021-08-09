<template>
	<transition name="slide-fade">
		<v-container fluid>
			<v-layout wrap>
				<v-flex xs12>
					<h3 class="text-center">Dashboard</h3>
				</v-flex>
			</v-layout>
			<v-layout wrap class="justify-end">
				<!--  -->
			</v-layout>
		</v-container>
	</transition>
</template>

<script>
	import layout from '@/Layouts/MainLayout'

	export default {
		name: 'Dashboard',
		layout,
		metaInfo () {
			return {
				title: 'Dashboard',
				titleTemplate: '%s | ' + this.$title,
			}
		},
	}
</script>

<style>
	 /**/
</style>
