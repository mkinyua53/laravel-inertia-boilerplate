<template>
	<transition name="slide-fade">
		<v-container fluid>
			<v-layout wrap class="justify-center">
				<v-flex xs12>
					<h3 class="text-center">Permissions</h3>
				</v-flex>
				<v-flex md8 sm10 xs12>
					<v-card>
						<v-card-title>
							<v-spacer></v-spacer>
							<v-text-field label="Search" v-model="filterBy" prepend-icon="search" :disabled="permissions.length < 1"></v-text-field>
						</v-card-title>
						<v-card-text>
							<v-data-table :items="permissions" :headers="headers" :search="filterBy" dense>
								<template v-slot:[`item.name`]="{ item }">
									<inertia-link :href="'/admin/permissions/' + item.id">{{ item.name }}</inertia-link>
								</template>
							</v-data-table>
						</v-card-text>
					</v-card>
				</v-flex>
			</v-layout>
		</v-container>
	</transition>
</template>

<script>
	import layout from '@/Layouts/Admin'

	export default {
		name: 'permissions',
		props: [
			'permissions'
		],
		layout,
		data() {
			return {
				filterBy: '',
				headers: [
					{ text: 'Name', value: 'name' },
					{ text: 'Description', value: 'label' }
				]
			}
		},
		metaInfo () {
			return {
				title: 'Permissions'
			}
		},
	}
</script>

<style>
	/**/
</style>
