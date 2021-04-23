<template>
	<transition name="bounce">
		<v-card>
			<v-card-title>Permissions</v-card-title>
			<v-card-text>
				<v-data-table :items="permissions" no-data-text="No permission attached to this user." :headers="headers">
					<template v-slot:[`header.detach`]="{ header }">
						<v-btn small class="" @click="detachAll" title="Remove all permissions from this user" v-if="permissions.length > 0">Detach all</v-btn>
					</template>
					<template v-slot:[`item.detach`]="{ item }">
						<td class="noprint">
							<v-btn class="" small text @click="detachPermission(item)" title="Detach permission from user" :loading="detaching == item.id">
								Detach
							</v-btn>
						</td>
					</template>
				</v-data-table>
			</v-card-text>
		</v-card>
	</transition>
</template>

<script>
	export default {
		name: 'UserPermissions',
		props: ['user'],
		data() {
			return {
				detaching: '',
				headers: [
					{
						text: 'Name', value: 'name'
					},
					{
						text: 'Description', value: 'label'
					},
					{
						text: 'Detach', value: 'detach', align: 'right', sortable: false
					}
				]
			}
		},
		methods: {
			detachPermission(permission) {
				swal({
					title: permission.name,
					text: 'Detach this permission from the user?',
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: 'red',
					confirmButtonText:'Ok',
					closeOnConfirm: false,
					showLoaderOnConfirm: true
				},
				function(){
					this.detaching = permission.id
					this.$http.post('permissions/'+permission.id+'/users/'+this.user.id+'/detach')
					.then(response => {
						swal({title:'Detach Success',type:'success',timer:1500})
						this.detaching = ''
						this.$emit('reloaduser')
					})
					.catch(err => {
						this.detaching = '',
						swal('Error '+err.status+': '+err.statusText,'Failed to Detach','error')
					})
				}.bind(this))
			},
			detachAll(){
				swal({
					title: 'Detach all permissions from <strong>'+this.user.email+'</strong>',
					html: true,
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: 'red',
					confirmButtonText:'Ok',
					closeOnConfirm: false,
					showLoaderOnConfirm: true
				},
				function(){
					this.$http.post('permissions/users/'+this.user.id+'/detach')
					.then(response => {
						swal({title:'User striped of all permissions',type:'success',timer:1500})
						this.$emit('reloaduser')
					})
					.catch(err => {
						swal('Error '+err.status+': '+err.statusText,'Failed to Remove permissions from user','error')
					})
				}.bind(this))
			}
		},
		computed: {
			permissions() {
				var perms = this.user.permissions
				return _.uniqBy(perms, 'id')
			}
		}
	}
</script>

<style>
	/**/
</style>
