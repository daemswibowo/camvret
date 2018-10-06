<template>
	<div>
		<div class="row">
			<div class="col-md-12">
				<passport-clients></passport-clients>
				<passport-authorized-clients></passport-authorized-clients>
				<passport-personal-access-tokens></passport-personal-access-tokens>
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
					title: 'Izin Pengguna',
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
							toastr.success('Berhasil disimpan!');
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