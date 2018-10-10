<template>
	<div>
		<div v-if="can(['Create Users','Update Users'])" class="modal fade" :id="modal.id" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">{{ modal.method == 'post'?'Create':'Edit' }} {{ modal.title }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form action="javascript:void(0)" novalidate class="form-horizontal" @submit.prevent="onSubmit">
						<div class="modal-body">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="name" class="control-label">Nama <span class="text-danger">*</span></label>

										<input :disabled="loadings.submit" type="text" name="name" v-model="form.name" class="form-control" :class="{'is-invalid':form.errors.name || errors.has('name')}" required v-validate>
										<div class="invalid-feedback">
											{{ form.errors.name?form.errors.name[0]:errors.has('name')?errors.first('name'):'' }}
										</div>
									</div>
									<div class="form-group">
										<label for="email" class="control-label">Email <span class="text-danger">*</span></label>
										<input :disabled="loadings.submit" type="email" name="email" v-model="form.email" class="form-control" :class="{'is-invalid':form.errors.email || errors.has('email')}" required v-validate>
										<div class="invalid-feedback">
											{{ form.errors.email?form.errors.email[0]:errors.has('email')?errors.first('email'):'' }}
										</div>

									</div>
									<div class="alert alert-info" v-if="modal.method=='put'">
										<i class="icon-info"></i> Hanya masukkan password jika Anda ingin mengubahnya.
									</div>
									<div class="form-group">
										<label for="password" class="control-label">Password</label>
										<input :disabled="loadings.submit" type="password" name="password" v-model="form.password" class="form-control" :class="{'is-invalid':form.errors.password || errors.has('password')}">
										<div class="invalid-feedback">
											{{ form.errors.password?form.errors.password[0]:errors.has('password')?errors.first('password'):'' }}
										</div>
									</div>
									<div class="form-group">
										<label for="password_confirmation" class="control-label">Konfirmasi Password</label>
										<input :disabled="loadings.submit" type="password" name="password_confirmation" v-model="form.password_confirmation" class="form-control" :class="{'is-invalid':form.errors.password_confirmation}">
										<div v-if="form.errors.password_confirmation" class="invalid-feedback">
											{{ form.errors.password_confirmation[0] }}
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="roles" class="control-label">Peran</label>
										<p v-if="loadings.roles">Loading...</p>
										<v-select v-show="!loadings.roles" v-model="form.roles" multiple :options="roles" :disabled="loadings.roles" label="name"></v-select>
										<div v-if="form.errors.roles" class="invalid-feedback">
											{{ form.errors.roles[0] }}
										</div>
									</div>
									<div class="form-group" v-if="can('Give Permissions To User')">
										<label for="roles" class="control-label">Izin Createan</label>
										<p v-if="loadings.permissions">Loading...</p>
										<v-select v-show="!loadings.permissions" v-model="form.permissions" multiple :options="permissions" :disabled="loadings.permissions" label="name"></v-select>
										<div v-if="form.errors.permissions" class="invalid-feedback">
											{{ form.errors.permissions[0] }}
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" :disabled="loadings.submit" class="btn btn-primary">Save</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="card card-accent-primary">
					<div class="card-header">

						<i class="icon-list"></i> Manage {{ modal.title }} <small v-if="loadings.items">loading...</small>
						<div class="card-header-actions">
							<a v-if="can('Delete Users') && selectedItems.length>0" href="javascript:void(0)" @click="selectedItems=[]" has-tooltip="true" data-placement="top" title="Clear Selection" class="card-header-action btn-setting">
								<i class="icon-close"></i> Clear Selection
							</a>
							<a v-if="can('Delete Users') && selectedItems.length>0" href="javascript:void(0)" @click="massDelete" has-tooltip="true" data-placement="top" title="Delete selected item" class="card-header-action btn-setting text-danger">
								<i class="icon-trash"></i> Delete
							</a>
							<a href="javascript:void(0)" v-if="can('Create Users')" @click="create" has-tooltip="true" data-placement="top" title="Create" class="card-header-action btn-setting">
								<i class="icon-plus"></i>
							</a>
							<a href="javascript:void(0)" @click="refresh" has-tooltip="true" data-placement="top" title="Refresh" class="card-header-action btn-setting">
								<i class="icon-refresh"></i>
							</a>
							<a href="javascript:void(0)">
								<input type="text" v-model="search" @keyup="getSearch" placeholder="Search" class="card-search">
							</a>
						</div>

					</div>
					<div class="card-body p-0 table-responsive">
						<table class="table table-hover mb-0">
							<thead>
								<tr>
									<th width="1"></th>
									<th>Nama</th>
									<th>Email</th>
									<th>Peran</th>
									<th v-if="can('Give Permissions To User')">Izin Createan</th>
									<th>Dibuat Pada</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<dl-loading-tr v-if="loadings.items" v-for="(i, key) in 3" :key="key" :jumlah="can('Give Permissions To User')?7:6" :bulet_first="true"></dl-loading-tr>
								<tr v-if="!loadings.items" v-for="(item, key) in items.data" :key="key">
									<td>
										<div class="form-check form-check-inline mr-1">
											<input class="form-check-input" type="checkbox" :value="item.id" v-model="selectedItems">
										</div>
									</td>
									<td>{{ item.name }}</td>
									<td>{{ item.email }}</td>
									<td>
										<span v-if="item.roles.length == 0">Tidak ada</span>
										<span class="badge badge-primary" v-if="item.roles" v-for="role in item.roles">
											{{ role.name }}
										</span>
									</td>
									<td v-if="can('Give Permissions To User')">
										<span v-if="item.permissions.length == 0">Tidak ada</span>
										<span class="badge badge-primary" v-if="item.permissions" v-for="permission in item.permissions">
											{{ permission.name }}
										</span>
									</td>
									<td>{{ item.created_at | moment("dddd, MMMM Do YYYY") }}</td>
									<td>
										<a v-if="can('Update Users')" href="javascript:void(0)" @click="edit(item)" has-tooltip="true" data-placement="top" title="Refresh" class="card-header-action btn-setting">
											<i class="icon-pencil"></i> Edit
										</a>

										<a href="javascript:void(0)" v-if="can('Delete Users')" @click="destroy(item.id)" has-tooltip="true" data-placement="top" title="Refresh" class="card-header-action btn-setting text-danger">
											<i class="icon-trash"></i> Delete
										</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="card-footer">

						<paginate v-if="items" :page-count="items.last_page?items.last_page:1" :page-range="3" :margin-pages="2" :click-handler="handlePagination" :prev-text="'Prev'" :next-text="'Next'" container-class="pagination" page-class="page-item" page-link-class="page-link" prev-class="page-item" prev-link-class="page-link" next-class="page-item" next-link-class="page-link"></paginate>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		head: {
			title: function () {
				return {
					inner: 'Management » User',
					separator: ' ',
				}
			}
		},
		data () {
			return {
				url: '',
				items: [],
				search: '',
				loadings: {
					items: false,
					submit: false,
					roles: false,
					permissions: false,
				},
				timer: null,
				modal: {
					id: 'user',
					title: 'Users',
					method: 'post'
				},
				form: {
					id: '',
					name: '',
					email: '',
					password: '',
					password_confirmation: '',
					roles: [],
					permissions: [],
					errors: []
				},
				roles: [],
				permissions: [],
				selectedItems: [],
			}
		},
		
		mounted () {
			this.url = this.$config.api+'management/user';
			if (this.can('Read Users')) {
				this.getIndex();
				this.getPerans();
				this.getIzins();
			}
		},

		methods: {
			getIzins () {
				let vm = this;
				vm.loadings.permissions = true;
				axios.get(this.$config.api+'management/permission?all=true')
				.then(res => {
					vm.permissions = res.data;
					vm.loadings.permissions = false;
				})
				.catch(err => {
					vm.handleError(err);
					vm.loadings.permissions = false;
				});
			},
			getPerans () {
				let vm = this;
				vm.loadings.roles = true;
				axios.get(this.$config.api+'management/role?all=true')
				.then(res => {
					vm.roles = res.data;
					vm.loadings.roles = false;
				})
				.catch(err => {
					vm.handleError(err);
					vm.loadings.roles = false;
				});
			},

			handlePagination (page) {
				this.getIndex(page);
			},

			getSearch () {
				this.loadings.items = true;
				if (this.timer) {
					clearTimeout(this.timer);
				}
				this.timer = setTimeout(() => {
					this.getIndex();
				}, 500)
			},

			refresh () {
				this.search = '';
				this.getIndex();
			},

			getIndex (page=null) {
				let vm = this;
				let url = this.url;
				if (page) {
					url = this.url+"?page="+page
				}

				if (vm.search) {
					url = this.url+"?search="+vm.search
				}

				vm.loadings.items = true;
				axios.get(url)
				.then(res => {
					vm.loadings.items = false;
					vm.items = res.data;
				})
				.catch(err => {
					vm.handleError(err);
					vm.loadings.items = false;
				});
			},

			create () {
				this.resetForm();
				this.modal.method = 'post';
				$('#'+this.modal.id).appendTo('body').modal('show');
			},

			edit (item) {
				this.resetForm();
				this.modal.method = 'put';
				this.form.id = item.id;
				this.form.name = item.name;
				this.form.email = item.email;
				this.form.roles = item.roles;
				this.form.permissions = item.permissions;
				$('#'+this.modal.id).appendTo('body').modal('show');
			},

			destroy (id) {
				let vm = this;
				this.swalDelete()
				.then((result) => {
					if (result.value) {
						vm.delete(vm.url+'/'+id).then(res => {
							vm.getIndex();
						})
						.catch(err => {
							vm.handleError(err);
						});
					}
				})
			},

			massDelete () {
				let vm = this;

				if (this.selectedItems.length>0) {
					this.swalMassDelete().then(result => {
						if (result.value) {
							vm.swalLoading();

							Object.keys(vm.selectedItems).forEach(key => {
								vm.delete(vm.url+'/'+vm.selectedItems[parseInt(key)]).then(res => {
									vm.items.data.splice(vm.items.data.indexOf(vm.selectedItems[parseInt(key)]),1);
									if (vm.selectedItems.length==1) {
										vm.getIndex();
										swal.close();
									} 
									vm.selectedItems.splice(parseInt(key), 1);
								})
								.catch(err => {
									if (vm.selectedItems.length==1) {
										swal.close();
									} 
									vm.handleError(err);
								});

							});
						}
					})
				}
			},

			onSubmit () {
				this.$validator.validateAll().then((result) => {
					if (result) {
						let vm = this;
						let url = this.url;
						let dismiss = false;
						vm.loadings.submit = true;
						if (this.modal.method=='put') {
							url = this.url+'/'+this.form.id;
							dismiss = true;
						}

						let data = this.form;
						data.roles = this.form.roles.map(x => x.name);
						data.permissions = this.form.permissions.map(x => x.name);

						axios[this.modal.method](url, data)
						.then(res => {
							this.resetForm();
							toastr.success('Berhasil diSave!');
							this.getIndex();
							if (dismiss) {
								$('#'+this.modal.id).modal('hide');
							}
							vm.loadings.submit = false;
						})
						.catch(err => {
							vm.handleError(err);
							if (err.response.data.errors) {
								vm.form.errors = err.response.data.errors;
							}
							vm.loadings.submit = false;
						});
					}
				})
			},

			resetForm () {
				this.errors.clear();
				this.form = {
					id: '',
					name: '',
					email: '',
					password: '',
					password_confirmation: '',
					roles: [],
					permissions: [],
					errors: []
				}
			},
		}
	}
</script>