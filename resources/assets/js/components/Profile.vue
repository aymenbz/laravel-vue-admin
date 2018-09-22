<template>
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-12">
            <div class="card card-widget widget-user">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header text-white" style="background: url('/img/user_cover.png') center center;">
                <h3 class="widget-user-username">{{ this.form.name }}</h3>
                <h5 class="widget-user-desc">Web Developer</h5>
              </div>
              <div class="widget-user-image">
                <img class="img-circle" src="/img/man.svg" alt="User Avatar">
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">3,200</h5>
                      <span class="description-text">SALES</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">13,000</h5>
                      <span class="description-text">FOLLOWERS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4">
                    <div class="description-block">
                      <h5 class="description-header">35</h5>
                      <span class="description-text">PRODUCTS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
            </div>
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active show" href="#activity" data-toggle="tab">Activity</a></li>
                  <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                 
                  <!-- /.tab-pane -->
                  <div class="tab-pane active show" id="activity">
                    <!-- The timeline -->
                    <h2>User Activity</h2>
                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="settings">
                    <form class="form-horizontal">
                      <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">Name</label>

                        <div class="col-sm-10">
                          <input type="email" v-model="form.name" class="form-control" id="inputName" placeholder="Name">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                        <div class="col-sm-10">
                          <input type="email" v-model="form.email" class="form-control" id="inputEmail" placeholder="Email">
                        </div>
                      </div>
                     
                      <div class="form-group">
                        <label for="inputExperience" class="col-sm-2 control-label">Experience</label>

                        <div class="col-sm-10">
                          <textarea class="form-control" v-model="form.bio" id="inputExperience" placeholder="Experience"></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputProfile" class="col-sm-2 control-label">Profile Photo</label>

                        <div class="col-sm-10">
                          <input type="file" @change="updateProfile" id="inputProfile">
                          <small class="text-danger" style="display: inherit;"> {{this.error}} </small>
                        </div>
                        
                      </div>
                      <div class="form-group">
                        <label for="inputPaspport" class="col-sm-4 control-label">Passport (leave empty if not changing)</label>

                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="inputPaspport" placeholder="Passport">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" @click.prevent="updateInfo" class="btn btn-success">Update</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: new Form({
                        id: "",
                        name: "",
                        email: "",
                        password: "",
                        type: "",
                        bio: "",
                        photo: ""
                    }),
                    error: ''
            }
        },
        methods: {
          updateProfile(e) {
            let file = e.target.files[0];
              var reader = new FileReader();
              if(file['size'] < 2111775) {
               reader.onloadend = (file) => {
                this.form.photo = reader.result;
              }
              reader.readAsDataURL(file);

              } else {
                swal({
                  type: 'error',
                  title: 'Ooops...',
                  text: 'You are uploading a large file'
                })
              }
            
          },
          updateInfo() {
            this.$Progress.start();
            this.form.put('api/profile')
            .then(() => {
             
              this.$Progress.finish();
            })
            .catch((error) => {
              this.$Progress.fail();
            })
          },
        },
        mounted() {
            console.log('Component mounted.')
        },


        created() {
            axios.get('/api/profile').then(({data}) => this.form.fill(data))
        }
    }
</script>
