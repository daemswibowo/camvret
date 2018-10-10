<template>
	<div>
		<div v-if="can(['Create Permissions','Update Permissions'])" class="modal fade" :id="modal.id" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">{{ modal.method == 'post'?'Create':'Edit' }} {{ modal.title }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form action="javascript:void(0)" novalidate @submit.prevent="onSubmit">
						<div class="modal-body">
							<div class="form-group">
								<label for="name" class="control-label">Name <span class="text-danger">*</span></label>
								<input :disabled="loadings.submit" type="text" name="name" v-model="form.name" class="form-control" :class="{'is-invalid':form.errors.name || errors.has('name')}" required v-validate>
								<div v-if="form.errors.name || errors.has('name')" class="invalid-feedback">
									{{ form.errors.name?form.errors.name[0]:errors.has('name')?errors.first('name'):'' }}
								</div>
							</div>
							<div class="form-group">
								<label for="guard_name" class="control-label">Guard Name <span class="text-danger">*</span></label>
								<input :disabled="loadings.submit" type="text" name="guard_name" v-model="form.guard_name" class="form-control" :class="{'is-invalid':form.errors.guard_name || errors.has('guard_name')}" required v-validate>
								<div v-if="form.errors.guard_name || errors.has('guard_name')" class="invalid-feedback">
									{{ form.errors.guard_name?form.errors.guard_name[0]:errors.has('guard_name')?errors.first('guard_name'):'' }}
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
							<a v-if="can('Delete Permissions') && selectedItems.length>0" href="javascript:void(0)" @click="selectedItems=[]" has-tooltip="true" data-placement="top" title="Clear Selection" class="card-header-action btn-setting">
								<i class="icon-close"></i> Clear Selection
							</a>
							<a v-if="can('Delete Permissions') && selectedItems.length>0" href="javascript:void(0)" @click="massDelete" has-tooltip="true" data-placement="top" title="Delete selected item" class="card-header-action btn-setting text-danger">
								<i class="icon-trash"></i> Delete
							</a>
							<a v-if="can('Create Permissions')" href="javascript:void(0)" @click="create" has-tooltip="true" data-placement="top" title="Create" class="card-header-action btn-setting">
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
									<th width="1">ID</th>
									<th>Name</th>
									<th>Guard Name</th>
									<th>#</th>
								</tr>
							</thead>
							<tbody>
								<dl-loading-tr v-if="loadings.items" v-for="(i, key) in 3" :key="key" :jumlah="5" :bulet_first="true"></dl-loading-tr>
								<tr v-if="!loadings.items" v-for="(item, key) in items.data" :key="key">
									<td>
										<div class="form-check form-check-inline mr-1">
											<input class="form-check-input" type="checkbox" :value="item.id" v-model="selectedItems">
										</div>
									</td>
									<td>{{ item.id }}</td>
									<td>{{ item.name }}</td>
									<td>{{ item.guard_name }}</td>
									<td>
										<a href="javascript:void(0)" v-if="can('Update Permissions')" @click="edit(item)" class="card-header-action btn-setting">
											<i class="icon-pencil"></i> Edit
										</a>

										<a v-if="can('Delete Permissions')" href="javascript:void(0)" @click="destroy(item.id)" class="card-header-action btn-setting text-danger">
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
	import { Validator } from 'vee-validate';
	export default {
		head: {
			title: function () {
				return {
					inner: 'Management » Permission',
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
				},
				timer: null,
				modal: {
					id: 'permission',
					title: 'Permissions',
					method: 'post'
				},
				form: {
					id: '',
					name: '',
					guard_name: 'web',
					errors: []
				},
				selectedItems: [],
			}
		},

		created () {
			const dict = {
				custom: {
					guard_name: {
						required: 'The guard name field is required.',
					}
				}
			};

			Validator.localize('en', dict);

			this.$validator.localize('en', dict);
		},

		mounted () {
			this.url = this.$config.api+'management/permission';
			if (this.can('Read Permissions')) {
				this.getIndex();
			}
		},

		methods: {
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
				this.form.guard_name = item.guard_name;
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

						axios[this.modal.method](url, this.form)
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
				});
			},

			resetForm () {
				this.errors.clear();
				this.form = {
					name: '',
					guard_name: 'web',
					errors: [],
				}
			},
		}
	}
</script>