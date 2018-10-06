<template>
	<div class="row">
		<div class="col-md-4">
			<div class="card card-accent-primary">
				<div class="card-header">
					<i class="icon-picture"></i> Foto Profil <small v-if="loadings.image"><i>Mengunggah...</i></small>
				</div>
				<form action="javascript:void(0)" enctype="multipart/form-data" @submit.prevent="onSubmitImage">
					<div class="card-body text-center">
						<div class="crop borderize circle centered mb-3" style="width: 150px; height: 150px;">
							<img :src="image" alt="">
						</div>
						<input :disabled="loadings.image" type="file" class="form-control" :class="{'is-invalid':errors.image}" ref="image" @change="onFileChange" accept="image/*">
						<div v-if="errors.image" class="invalid-feedback">
							{{ errors.image[0] }}
						</div>
					</div>
					<div class="card-footer">
						<button :disabled="loadings.image" type="submit" class="btn btn-primary pull-right"><i class="fa fa-save"></i> Simpan</button>
						<div class="clearfix"></div>
					</div>
				</form>
			</div>
		</div>
		<div class="col-md-8">
			<div class="card card-accent-primary">
				<div class="card-header">
					<i class="icon-settings"></i> Umum
				</div>
				<form action="javascript:void(0)" @submit.prevent="onSubmit" class="form-horizontal">
					<div class="card-body">
						<div class="form-group">
							<div class="row">
								<label for="name" class="control-label col-sm-4">Nama</label>
								<div class="col-sm-8">
									<input :class="{'is-invalid':form.errors.name}" type="text" name="name" v-model="form.name" class="form-control">
									<div v-if="form.errors.name" class="invalid-feedback">
										{{ form.errors.name[0] }}
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<label for="email" class="control-label col-sm-4">Email</label>
								<div class="col-sm-8">
									<input :class="{'is-invalid':form.errors.email}" type="email" name="email" v-model="form.email" class="form-control">
									<div v-if="form.errors.email" class="invalid-feedback">
										{{ form.errors.email[0] }}
									</div>
								</div>
							</div>
						</div>
						<legend>Ubah Kata Sandi</legend>

						<div class="form-group">
							<div class="row">
								<label for="password" class="control-label col-sm-4">Kata Sandi</label>
								<div class="col-sm-8">
									<input :class="{'is-invalid':form.errors.password}" type="password" name="password" v-model="form.password" class="form-control">
									<div v-if="form.errors.password" class="invalid-feedback">
										{{ form.errors.password[0] }}
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<label for="password_confirmation" class="control-label col-sm-4">Konfirmasi Kata Sandi</label>
								<div class="col-sm-8">
									<input :class="{'is-invalid':form.errors.password_confirmation}" type="password" name="password_confirmation" v-model="form.password_confirmation" class="form-control">
									<div v-if="form.errors.password_confirmation" class="invalid-feedback">
										{{ form.errors.password_confirmation[0] }}
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save"></i> Simpan</button>
						<div class="clearfix"></div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</div>
</template>
<script>
	import avatar from '../../img/avatar.png'
	
	export default {
		data () {
			return {
				url: '',
				image: null,
				file: null,
				errors: [],
				loadings: {
					image: false,
				},
				form: {
					name: '',
					email: '',
					password: '',
					password_confirmation:'',
					errors: [],
				}
			}
		},

		mounted () {
			this.url = this.$config.api+'profile';
			this.form.name = Me.name;
			this.form.email = Me.email;
			if (Me.image) {
				if (this.$config.symlink) {
					this.image = JSON.parse(Me.image).url;
				} else {
					this.image = '/storage/'+JSON.parse(Me.image).path;
				}
			} else {
				this.image = avatar;

			}
		},
		
		methods: {
			onFileChange(e) {
				this.errors = [];
				let files = e.target.files || e.dataTransfer.files;

				if (!files.length)
					return;
				
				const formData = new FormData();
				Array.from(Array(files.length).keys())
				.map(x => {
					formData.append('image', files[x], files[x].name);
				});

				this.file = formData;

				this.createImage(files[0]);
			},

			createImage(file) {
				let reader = new FileReader();
				let vm = this;
				reader.onload = (e) => {
					vm.image = e.target.result;
				};
				reader.readAsDataURL(file);
			},

			onSubmitImage(){
				let erh = true;
				if (!this.file) {
					toastr.warning("Silahkan pilih file gambar terlebih dahulu!");
					erh = false;
				}
				let vm = this;
				vm.errors = [];
				vm.loadings.image = true;
				axios.post(this.$config.api+'profile/update/image', this.file)
				.then(res => {
					vm.$events.fire('refresh-profile');
					vm.loadings.image = false;
					toastr.success('Gambar profil telah diubah!');
				})
				.catch(err => {
					if (erh) {
						vm.handleError(err);
						
					}
					if (err.response.data.errors) {
						vm.errors = err.response.data.errors;
					}
					vm.loadings.image = false;
				});
			},

			onSubmit () {
				let vm = this;
				axios.post(this.url, this.form)
				.then(res => {
					toastr.success('Profil disimpan!');
				})
				.catch(err => {
					vm.handleError(err);
					if (err.response.data.errors) {
						vm.form.errors = err.response.data.errors;
					}
				})
			}
		}
	}
</script>