<template>
	<transition name="slide-fade">
		<v-container fluid>
			<v-layout wrap>
				<v-flex xs12 class="pa-1">
					<v-card>
						<v-card-text>
							<ul class="list-group">
								<li class="list-group-item"><b>Permission</b> - {{permission.name}}</li>
								<li class="list-group-item"><b>Description</b> - {{permission.label}}</li>
							</ul>
						</v-card-text>
					</v-card>
				</v-flex>
				<v-flex md6 sm6 xs12>
					<attach :permission="permission" :users="users" class="pa-1"></attach>
					<users :permission="permission" class="pa-1"></users>
				</v-flex>
				<v-flex md6 sm6 xs12 class="pa-1">
					<roles :permission="permission"></roles>
				</v-flex>
			</v-layout>
		</v-container>
	</transition>
</template>

<script>
	import layout from '@/Layouts/Admin'

	export default {
		name: 'Permission',
		layout,
		props: [
			'permission', 'users'
		],
		components: {
			users: () => import(/* webpackChunkName: "js/admin" */ './users.vue'),
			roles: () => import(/* webpackChunkName: "js/admin" */ './roles.vue'),
			attach: () => import(/* webpackChunkName: "js/admin" */ './attach.vue')
		},
		data() {
			return {
				loading: true
			}
		},
		metaInfo () {
			return {
				title: 'Permission | ' + this.permission.name
			}
		},
		methods: {
			//
		},
	}
</script>

<style>
	/**/
</style>
