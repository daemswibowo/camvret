<template>
	<div>
		<div v-if="can(['Create Menus','Update Menus'])" class="modal fade" :id="modal.id" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">{{ modal.method == 'post'?'Tambah':'Edit' }} {{ modal.title }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form action="javascript:void(0)" @submit.prevent="onSubmit">
						<div class="modal-body">
							<div class="modal fade" id="iconList" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog modal-lg" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title">Icon by Simple Line icons</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body p-0 loading-bg" style="height: 450px;">
											<iframe src="http://simplelineicons.com/" frameborder="0" width="100%" height="100%"></iframe>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="title" class="control-label">Judul</label>
										<input :disabled="loadings.submit" type="text" name="title" v-model="form.title" class="form-control" :class="{'is-invalid':form.errors.title}">
										<div v-if="form.errors.title" class="invalid-feedback">
											{{ form.errors.title[0] }}
										</div>
									</div>
									<div class="form-group">
										<label for="icon" class="control-label">Simbol (ikon) <a href="javascript:void(0)" @click="iconList"><i class="icon-eye"></i></a></label>
										<input :disabled="loadings.submit" type="text" name="icon" v-model="form.icon" class="form-control" :class="{'is-invalid':form.errors.icon}">
										<div v-if="form.errors.icon" class="invalid-feedback">
											{{ form.errors.icon[0] }}
										</div>
										<br>
										<div class="alert alert-info">
											<i class="icon-info"></i> Ikon adalah sebuah nama kelas yang akan diaplikasikan ke tag <strong>i</strong>.
											Contoh penulisan ikon yang benar: 
											<span class="badge badge-danger">icon-user</span>
											<span class="badge badge-danger">fa fa-user</span>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="to" class="control-label">Ke</label>
										<input :disabled="loadings.submit" type="text" name="to" v-model="form.to" class="form-control" :class="{'is-invalid':form.errors.to}">
										<div v-if="form.errors.to" class="invalid-feedback">
											{{ form.errors.to[0] }}
										</div>
									</div>
									<div class="form-group">
										<label for="type" class="control-label">Tipe</label>
										<div class="form-inline">
											<div class="form-check pr-3">
												<label class="form-check-label">
													<input type="radio" class="form-check-input" name="type" v-model="form.type" value="vue"> Vue
												</label>
											</div>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input" name="type" v-model="form.type" value="link"> Link
												</label>
											</div>
										</div>
										<div v-if="form.errors.type" class="invalid-feedback">
											{{ form.errors.type[0] }}
										</div>
									</div>
									<div class="form-group">
										<label for="permissions" class="control-label">Izin</label>
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
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
							<button type="submit" :disabled="loadings.submit" class="btn btn-primary">Simpan</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="row">

			<div class="col-md-12">
				<div class="card card-accent-primary">
					<div class="card-header">

						<i class="icon-list"></i> Kelola {{ modal.title }} <small v-if="loadings.items">loading...</small>
						<div class="card-header-actions">
							<a v-if="can('Create Menus')" href="javascript:void(0)" @click="create" has-tooltip="true" data-placement="top" title="Tambah" class="card-header-action btn-setting">
								<i class="icon-plus"></i>
							</a>
							<a href="javascript:void(0)" @click="refresh" has-tooltip="true" data-placement="top" title="Segarkan" class="card-header-action btn-setting">
								<i class="icon-refresh"></i>
							</a>
						</div>

					</div>
					<div class="card-body" :class="{'loading-bg':loadings.items}" style="min-height: 100px;">
						<dl-menu-child @edit="edit" @destroy="destroy" olClass="sortable" :menus="items"></dl-menu-child>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		head: {
			title: {
				inner: 'Management » Menu',
				separator: ' ',
			}
		},
		data () {
			return {
				tag: '<i class="icon-user"></i>',
				url: '',
				items: [],
				search: '',
				loadings: {
					items: false,
					submit: false,
					permissions: false,
				},
				timer: null,
				modal: {
					id: 'menu',
					title: 'Menu',
					method: 'post'
				},
				form: {
					id: '',
					title: '',
					icon: '',
					to: '',
					type: 'vue',
					permissions: [],
					errors: []
				},
				permissions: [],
			}
		},
		
		mounted () {
			this.url = this.$config.api+'management/menu';
			if (this.can('Read Menus')) {
				this.getIzins();
				this.getIndex();
				this.initSortable();
			}
		},

		methods: {
			initSortable () {
				var self = this;
				$('ol.sortable').nestedSortable({
					forcePlaceholderSize: true,
					handle: 'div',
					helper: 'clone',
					items: 'li',
					opacity: .6,
					placeholder: 'placeholder',
					revert: 250,
					tabSize: 25,
					tolerance: 'pointer',
					toleranceElement: '> div',
					maxLevels: 5,

					isTree: true,
					expandOnHover: 0,
					startCollapsed: false
				});

				$('#serialize').click(function(){
					serialized = $('ol.sortable').nestedSortable('serialize');
					$('#serializeOutput').text(serialized+'\n\n');
				})

				$('ol.sortable').mouseup(function(event) {
					setTimeout(() => {
						var arraied = $(this).nestedSortable('toArray', {startDepthCount: 0});
						axios.put(self.url+'/update', arraied)
						.then(function (res) {
							self.$events.fire('menu-updated', 'Refresh menu dong');
						})
						.catch(function (error) {
							self.handleError(error);
						});
					}, 500)
				});

				$('#toHierarchy').click(function(e){
					hiered = $('ol.sortable').nestedSortable('toHierarchy', {startDepthCount: 0});
					hiered = dump(hiered);
					(typeof($('#toHierarchyOutput')[0].textContent) != 'undefined') ?
					$('#toHierarchyOutput')[0].textContent = hiered : $('#toHierarchyOutput')[0].innerText = hiered;
				})

				$('#toArray').click(function(e){
					arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
					arraied = dump(arraied);
					(typeof($('#toArrayOutput')[0].textContent) != 'undefined') ?
					$('#toArrayOutput')[0].textContent = arraied : $('#toArrayOutput')[0].innerText = arraied;
				})
			},
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
					vm.$events.fire('menu-updated', 'Refresh menu dong');
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
				this.form.title = item.title;
				this.form.to = item.to;
				this.form.icon = item.icon;
				this.form.type = item.type;
				this.form.permissions = item.permissions;
				$('#'+this.modal.id).appendTo('body').modal('show');
			},

			destroy (id) {
				let vm = this;
				this.swalDelete()
				.then((result) => {
					if (result.value) {
						axios.delete(vm.url+'/'+id)
						.then(res => {
							vm.getIndex();
						})
						.catch(err => {
							vm.handleError(err);
						});
					}
				})
			},

			onSubmit () {
				let vm = this;
				let url = this.url;
				let dismiss = false;
				vm.loadings.submit = true;
				if (this.modal.method=='put') {
					url = this.url+'/'+this.form.id;
					dismiss = true;
				}

				let data = this.form;
				data.permissions = this.form.permissions.map(x => x.name);

				axios[this.modal.method](url, data)
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
			},

			iconList () {
				$('#iconList').appendTo('body').modal('show');
			},

			resetForm () {
				this.form = {
					id: '',
					title: '',
					icon: '',
					to: '',
					type: 'vue',
					permissions: [],
					errors: []
				}
			},
		}
	}
</script>