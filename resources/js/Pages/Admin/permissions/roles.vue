<template>
	<transition name="bounce">
		<v-container fluid>
			<v-card>
				<v-card-title>
					<v-spacer></v-spacer>
					<v-text-field label="Search" v-model="filterBy" prepend-icon="search" :disabled="roles.length < 1" :append-icon="filterBy.length > 0 ? 'close' : ''" :append-icon-cb="() => (filterBy = '')"></v-text-field>
				</v-card-title>
				<v-card-text>
					<v-data-table :items="roles" :headers="headers" :search="filterBy">
						<template v-slot:[`item.name`]="{ item }">
							<inertia-link :href="'/admin/roles/' + item.id">{{item.name}}</inertia-link>
						</template>
					</v-data-table>
				</v-card-text>
			</v-card>
		</v-container>
	</transition>
</template>

<script>
	export default {
		name: 'permission-role',
		props: ['permission'],
		data() {
			return {
				filterBy: '',
				headers: [
					{
						text: 'Name', value: 'name'
					},
					{
						text: 'Description', value: 'label'
					},
				]
			}
		},
		computed: {
			roles () {
				return this.permission.roles
			}
		}
	}
</script>

<style>
	/**/
</style>
