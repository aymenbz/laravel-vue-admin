<template>
      <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users Table</h3>

                <div class="card-tools">
                 <button class="btn btn-success" @click="newModal"><i class="fa fa-user-plus fa-fw"></i> Add New</button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Registed At</th>
                    <th>Modify</th>
                  </tr>
                  <tr v-for="user in users" :key="user.id">
                    <td> {{user.id}} </td>
                    <td>{{user.name}}</td>
                    <td>{{user.email}}</td>
                    <td>{{user.type | upTxt}}</td>
                    <td>{{user.created_at | myDate}}</td>
                    <td>
                        <a href="#" @click="editModal(user)"> 
                            <i class="fa fa-edit"></i>
                        </a>
                        /
                        <a href="#" @click="deleteUser(user.id)"> 
                            <i class="fa fa-trash text-danger" ></i>
                        </a>
                    </td>
                  </tr>
                  
               
                </tbody></table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- Modal -->
            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="editMode" id="addNew">Edit User</h5>
                    <h5 class="modal-title" v-show="!editMode" id="addNew">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                 <form @submit.prevent="editMode ? updateUser() : createUser()">
                <div class="modal-body">
                        <div class="form-group">
                        <input v-model="form.name" type="text" name="name" placeholder="Name"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="form-group">
                        <input v-model="form.email" type="text" name="email" placeholder="Email"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                        <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="form-group">
                        <textarea v-model="form.bio" type="text" name="bio" placeholder="Short Bio For User"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                        <has-error :form="form" field="bio"></has-error>
                        </div>
                        <div class="form-group">
                            <select name="type" v-model="form.type" class="form-control" :class="{'is-invalid': form.errors.has('type')}">
                                <option value="">Select User Role</option>
                                <option value="admin">Admin</option>
                                <option value="user">Standard User</option>
                                <option value="author">Author</option>
                            </select>
                            <has-error :form="form" field="type"></has-error>
                        </div>

                        <div class="form-group">
                        <input v-model="form.password" type="password" name="password" placeholder="Password"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                        <has-error :form="form" field="password"></has-error>
                        </div>

                       

                        <!-- <button :disabled="form.busy" type="submit" class="btn btn-primary">Log In</button> -->
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" v-show="editMode" class="btn btn-success">Update</button>
                    <button type="submit" v-show="!editMode" class="btn btn-success">Create</button>
                </div>
                 </form>
                </div>
            </div>
            </div>
        </div>
</template>

<script>
export default {
  data() {
    return {
      editMode: false,
      users: [],
      form: new Form({
        id: "",
        name: "",
        email: "",
        password: "",
        type: "",
        bio: "",
        photo: ""
      })
    };
  },
  methods: {
    editModal(user) {
      this.editMode = true;
      this.form.reset();
      $('#addNew').modal('show');
      this.form.fill(user);
    },
    newModal() {
      this.editMode = false;
      this.form.reset();
      $('#addNew').modal('show');
    },
    loadUsers() {
      axios.get("api/user").then(({ data }) => {
        this.users = data.data;
      });
    },
    createUser() {
      this.$Progress.start();
      this.form
        .post("api/user")
        .then(data => {
          Fire.$emit("AfterCreate", data);
          $("#addNew").modal("hide");
          toast({
            type: "success",
            title: "User Created successfully"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          toast({
            type: "error",
            title: "Error Creating User"
          });
          this.$Progress.fail();
        });
    },
    deleteUser(id) {
      swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(result => {

        // Send Request To The User
        if (result.value) {
        this.form.delete('api/user/'+id).then((data) => {
          swal("Deleted!", "Your file has been deleted.", "success");
          Fire.$emit("AfterCreate", data);
        
        }).catch(() => {
            swal("Failed!", "There was something wrong.", "warning");
        })
        }
      });
    },
    updateUser() {
      this.$Progress.start();
      this.form.put('api/user/'+this.form.id)
      .then((data) => {
        $("#addNew").modal("hide");
         swal("Updated!", "User been Updated.", "success");
         Fire.$emit("AfterCreate", data);
         this.$Progress.finish();
      })
      .catch(() => {
      this.$Progress.fail();
      })
    }
  },
  created() {
    this.loadUsers();
    Fire.$on("AfterCreate", () => {
      this.loadUsers();
    });
    //setInterval(() => {this.loadUsers()}, 3000)
  }
};
</script>
