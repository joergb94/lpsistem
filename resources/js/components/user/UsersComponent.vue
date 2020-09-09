<template>
       <div class="col-sm-12">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-5">
                                 <h4 class="card-title mb-0">
                                     User
                                    <div class="btn-group">
                                        <select class="form-control text-center" v-model="status">
                                            <option value="all" >All</option>
                                            <option value="1" >Actived</option>
                                            <option value="2">Deactived</option>
                                            <option value="D">Delete</option>
                                        </select>
                                    </div> 
                                </h4>
                            </div>
                            <div class="col-sm-7 text-right">
                                 <button class="btn btn-success" @click="openModal('modal', 'add')">New</button>
                            </div>
                                   
         
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <select class="form-control col-sm-2" v-model="criterion">
                                        <option value="name">name</option>
                                        <option value="last_name">Apellido</option>
                                        <option value="Phone">Telefono</option>
                                    </select>
                                    
                                    <input type="text" v-model="search" @keyup.enter="ListUsers(1)" class="form-control" placeholder="Texto a buscar">
                                 
                                    <button type="submit" @click="ListUsers(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Telefono</th>
                                <th>Status</th>
                                <th>created_at</th>
                                <th>Updated_at</th>
                                <th>deleted_at</th>
                                <th>actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="pagination.total == 0" class="text-center">
                                    <th colspan="9" class="text-center no-data">
                                        <h2><span class="badge  badge-pill badge-info">Data Not Found</span></h2>
                                    </th>
                                </tr>

                                <tr v-for="item in dataUsers" :key="item.id">
                                    <td v-text="item.id"></td>
                                    <td v-text="item.name"></td>
                                    <td v-text="item.last_name"></td>
                                    <td v-text="item.phone"></td>
                                    <td>
                                          <div v-if="item.active == 1">
                                            <span class="badge badge-success">Actived</span>
                                        </div>
                                        <div v-else-if="item.active == 0">
                                            <span class="badge badge-danger">Deactivated</span>
                                        </div>

                                    </td>
                                    <td v-text="item.created_at"></td>
                                    <td v-text="item.updated_at"></td>
                                    <td v-text="item.deleted_at"></td>
                                    <td>
                                        <button type="button" v-if="item.deleted_at == null" class="btn btn-warning btn-sm" @click="openModal('modal', 'update', item)" >
                                          <i class="ti-pencil"></i>
                                        </button>
                                        <button type="button" v-if="item.deleted_at == null" class="btn btn-danger btn-sm" @click="openModal('modal', 'password', item)">
                                          <i class="ti-key"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" @click="DeleteOrRestore(item)">
                                          <i class="ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                            <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="pageChange(pagination.current_page - 1)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="pageChange(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="pageChange(pagination.current_page + 1)">Sig</a>
                                </li>
                            </ul>
                            </nav>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- The Modal -->
                <div class="modal" id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"  v-text="titleModal"></h4>
                        <button type="button"  class="close" @click="closeModal()" >&times;</button>
                    </div>

                    <!-- Modal body -->
                    <form action="">
                    <div class="modal-body">
                       
                            <div class="form-group" v-if="action==1||action==2">
                                <label for="email">Nombre:</label>
                                <input type="text"  v-model="name"  class="form-control" placeholder="Enter Name" id="name">
                            </div>
                            <div class="form-group" v-if="action==1||action==2">
                                <label for="pwd">Tipo de Usuario:</label>
                                <select class="form-control text-center" v-model="type">
                                            <option value="">Selecione Tipo</option>
                                            <option value="2" >Adminitrativo</option>
                                            <option value="3">Vendedor</option>
                                </select>
                            </div>
                             <div class="form-group" v-if="action==1||action==2">
                                <label for="pwd">Apellido:</label>
                                <input type="text" v-model="last_name"  class="form-control" placeholder="Enter last_name" id="last_name">
                            </div>
                            <div class="form-group" v-if="action==1||action==2">
                                <label for="pwd">Telefono:</label>
                                <input type="text" v-model="phone"  class="form-control" placeholder="Enter phone" id="phone">
                            </div>
                            <div class="form-group" v-if="action==1||action==2">
                                <label for="pwd">Correo:</label>
                                <input type="text" v-model="email"  class="form-control" placeholder="Enter email" id="email">
                            </div>

                             <div class="form-group" v-if="action==1||action==3">
                                <label for="pwd">Contraseña:</label>
                                <input type="password" v-model="password"  class="form-control" placeholder="Enter password" id="password">
                            </div>
                            <div class="form-group" v-if="action==1||action==3">
                                <label for="pwd">Confirmar Contraseña:</label>
                                <input type="password" v-model="password_confirmation"  class="form-control" placeholder="Enter confirm password" id="password_confirmation">
                            </div>
                       
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" v-if="action==1" @click="updateOrCreate(1)">Save</button>
                        <button type="button" class="btn btn-primary" v-if="action==2" @click="updateOrCreate(2)">Update</button>
                        <button type="button" class="btn btn-primary" v-if="action==3" @click="updateOrCreate(3)">Update</button>
                        <button type="button" class="btn btn-danger" @click="closeModal()" >Close</button>
                    </div>
                     </form>

                    </div>
                </div>
                </div>
        
    </div>
</template>

<script>
    export default {
        data () {
            return {
            dataUsers:[],
            id:'',
            name:'',
            last_name:'',
            phone:'',
            email:'',
            password:'',
            password_confirmation:'',
            type:'',
            titleModal:'',
            action:0,
            page:1,
            users: 1,
            pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
            offset : 3,
            criterion : 'name',
            status : 1,
            search : ''

            }
        },
        computed:{
            isActived: function(){
              return this.pagination.current_page;
            },
            pagesNumber: function(){
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        methods : {
            ListUsers(page){
                let me = this;
                var url = '/users?page='+page+'&search='+this.search+'&criterion='+this.criterion+'&status='+this.status;
                 axios.get(url)
                .then(function (response) {
                    var respuesta= response.data;
                    me.dataUsers = respuesta.Users.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            pageChange(page){
                let me = this;
                console.log(me.pagination.current_page)
                console.log(page)

                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.ListUsers(page)
            },
            updateOrCreate(action){
                 let me = this;
                 var url = '/users/add'
                 var data = {
                    'name': this.name,
                    'last_name': this.last_name,
                    'phone': this.phone,
                    'email':this.email,
                    'password':this.password,
                    'password_confirmation':this.password_confirmation,
                    'type':this.type
                };

                if (action == 2){
                    url = '/users/update'
                    var data = {
                        'id': this.id,
                        'name': this.name,
                        'last_name': this.last_name,
                        'phone': this.phone,
                        'email':this.email,
                        'password':this.password,
                        'type':this.type
                    };
                }else if(action == 3){
                        var url = '/users/password'
                        var data = {
                            'id': this.id,
                            'password':this.password,
                            'password_confirmation':this.password_confirmation,
                        };
                }
               
                axios.post(url,data).then(function (response) {

                    me.closeModal();
                    me.ListUsers('');

                     $.notify({
                                // options
                                title: "Success!",
                                message:"Exito",
                            },{
                                // settings
                                type: 'success'
                            });

                }).catch(function (error) {
                    console.log(error.response.data.errors);
                    var e = error.response.data.errors;
                      $.notifyClose();
                    
                     $.each(e,function (k,message) {
                            $.notify({
                                // options
                                title: "Error!",
                                message:message,
                            },{
                                // settings
                                type: 'danger'
                            });
                        });
                })              
            },
            DeleteOrRestore(item){
                let me = this;
                var data = {
                    'id': item.id,
                    };
                 var m = "Do you want to deleted User?";
                 var mt = "The User will be delete";
                 var btn = "Delete";


                if(item.deleted_at != null){
                     m = "Do you want to restored User?";
                     mt = "The User will be restore";
                     btn = "Restore";
                }

                    Swal.fire({
                        title: m,
                        text:  mt,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, eliminalo!'
                    }).then((result) => {
                        if (result.value) {
                             axios.post('/users/deleteOrResotore',data).then(function (response) {
                                    me.ListUsers();
                                    $.notify({
                                                // options
                                                title: "Success!",
                                                message:"Exito",
                                            },{
                                                // settings
                                                type: 'success'
                                            });

                                }).catch(function (error) {

                                    console.log(error.response.data.errors);
                                        var e = error.response.data.errors;
                                        $.notifyClose();
                                        
                                        $.each(e,function (k,message) {
                                                $.notify({
                                                    // options
                                                    title: "Error!",
                                                    message:message,
                                                },{
                                                    // settings
                                                    type: 'danger'
                                                });
                                            });
                                }) 
                        }
                    }) 
            },
            changeStatus(item){
                let me = this;
                var data = {
                    'id': item.id,
                    };
                 var m = "Do you want to deactived User?";
                 var mt = "The User will be deactived";
                 var btn = "Deactived";


                if(item.active == 0){
                     m = "Do you want to actived User?";
                     mt = "The User will be actived";
                     btn = "Actived";
                }
                 Swal.fire({
                        title: m,
                        text:  mt,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, eliminalo!'
                    }).then((result) => {
                        if (result.value) {
                             axios.post('/users/change_status',data).then(function (response) {
                                    me.ListUsers();
                                    $.notify({
                                                // options
                                                title: "Success!",
                                                message:"Exito",
                                            },{
                                                // settings
                                                type: 'success'
                                            });

                                }).catch(function (error) {
                                    console.log(error.response.data.errors);
                                        var e = error.response.data.errors;
                                        $.notifyClose();
                                        
                                        $.each(e,function (k,message) {
                                                $.notify({
                                                    // options
                                                    title: "Error!",
                                                    message:message,
                                                },{
                                                    // settings
                                                    type: 'danger'
                                                });
                                            });
                                }) 
                        }
                    }) 
                   
            },
            openModal(model, action, data = []){
               
                switch(model){
                    case 'modal':
                    {
                        switch(action){
                            case 'add':
                            {
                                this.titleModal = 'New User';
                                this.name= '';
                                this.last_name = '';
                                this.type = '';
                                this.phone = '';
                                this.email = '';
                                this.password = '';
                                this.password_confirm ='',
                                this.action = 1;
                                break;
                            }
                            case 'update':
                            {  
                                this.titleModal = 'Update User';
                                this.id = data.id;
                                this.type = data.type_user_id;
                                this.name = data.name;
                                this.last_name = data.last_name;
                                this.phone = data.phone;
                                this.email = data.email;
                                this.password = data.password;
                                this.action = 2;
                                break;
                            }
                            case 'password':
                            {  
                                this.titleModal = 'Change password';
                                this.id = data.id;
                                this.password ='';
                                this.password_confirm ='',
                                this.action = 3;
                                break;
                            }
                        }
                        $("#myModal").modal('show');
                    }
                }
            },
            closeModal(){
                    this.titleModal = '';
                    this.name= '';
                    this.name = '';
                    this.phone = '';
                    this.type ='';
                    this.email = '';
                    this.password = '';
                    this.password_confirm ='',
                     $.notifyClose();
                    $("#myModal").modal('hide');
            },
        },
        mounted () {
           this.ListUsers(1);
        }
    }
</script>
