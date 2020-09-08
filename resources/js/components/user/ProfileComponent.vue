<template>
       <div class="col-sm-12">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-5">
                                 <h4 class="card-title mb-0">
                                    Mi Perfil
                                </h4>
                            </div>
                            <div class="col-sm-7 text-right">
                                        <button type="button" class="btn btn-warning btn-sm" @click="openModal('modal', 'update', dataUser)" >
                                          <i class="ti-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" @click="openModal('modal', 'password', dataUser)">
                                          <i class="ti-key"></i>
                                        </button>
                            </div>
                                   
         
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-sm-12">
                             <div class="form-group">
                                <label for="email">Nombre:</label>
                                <label v-text="dataUser.name"></label>
                            </div>
                             <div class="form-group">
                                <label for="pwd">Apellido:</label>
                                <label v-text="dataUser.last_name"></label>
                            </div>
                            <div class="form-group">
                                <label for="pwd">Telefono:</label>
                                <label v-text="dataUser.phone"></label>
                            </div>
                            <div class="form-group">
                                <label for="pwd">Correo:</label>
                                <label v-text="dataUser.email"></label>
                            </div>
                             <div class="form-group">
                                <label for="pwd">Fecha de Creacion:</label>
                               <label v-text="dataUser.created_at"></label>
                            </div>
                            
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
                       
                            <div class="form-group" v-if="action==2">
                                <label for="email">Nombre:</label>
                                <input type="text"  v-model="name"  class="form-control" placeholder="Enter Name" id="name">
                            </div>
                             <div class="form-group" v-if="action==2">
                                <label for="pwd">Apellido:</label>
                                <input type="text" v-model="last_name"  class="form-control" placeholder="Enter last_name" id="last_name">
                            </div>
                            <div class="form-group" v-if="action==2">
                                <label for="pwd">Telefono:</label>
                                <input type="text" v-model="phone"  class="form-control" placeholder="Enter phone" id="phone">
                            </div>
                            <div class="form-group" v-if="action==2">
                                <label for="pwd">Correo:</label>
                                <input type="text" v-model="email"  class="form-control" placeholder="Enter email" id="email">
                            </div>

                             <div class="form-group"  v-if="action==1">
                                <label for="pwd">Contraseña:</label>
                                <input type="text" v-model="password"  class="form-control" placeholder="Enter password" id="password">
                            </div>
                             <div class="form-group"  v-if="action==1">
                                <label for="pwd">Confirmar Contraseña:</label>
                                <input type="text" v-model="password_confirm"  class="form-control" placeholder="Enter password" id="password">
                            </div>
                       
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" v-if="action==1" @click="updateOrCreate(1)">Save</button>
                        <button type="button" class="btn btn-primary" v-if="action==2" @click="updateOrCreate(2)">Update</button>
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
            dataUser:[],
            id:'',
            name:'',
            last_name:'',
            phone:'',
            email:'',
            password:'',
            password_confirm:'',
            type_user:'',
            titleModal:'',
            action:0,
            offset : 3,
            criterion : 'name',
            status : 1,
            search : ''

            }
        },
        methods : {
            ListUsers(page){
                let me = this;
                var url = '/profile';
                 axios.get(url)
                .then(function (response) {
                    var respuesta= response.data;
                    me.dataUser = respuesta.Users;
                  
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
                 var url = '/profile/password'
                 var data = {
                    'password':this.password,  
                };

                if (action == 2){
                    url = '/profile/update'
                    var data = {
                            'id': this.id,
                            'name': this.name,
                            'last_name': this.last_name,
                            'phone': this.phone,
                            'email':this.email,
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
                             axios.post('/profile/deleteOrResotore',data).then(function (response) {
                                    me.ListUsers();
                                    $.notify({
                                                // options
                                                title: "Success!",
                                                message:"Exito",
                                            },{
                                                // settings
                                                type: 'success'
                                            });

                                }).catch(function (error) {}) 
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
                             axios.post('/profile/change_status',data).then(function (response) {
                                    me.ListUsers();
                                    $.notify({
                                                // options
                                                title: "Success!",
                                                message:"Exito",
                                            },{
                                                // settings
                                                type: 'success'
                                            });

                                }).catch(function (error) {}) 
                        }
                    }) 
                   
            },
            openModal(model, action, data = []){
               
                switch(model){
                    case 'modal':
                    {
                        switch(action){
                            case 'password':
                            {
                                this.titleModal = 'Cambiar Contraseña';
                                this.password = '';
                                this.action = 1;
                                break;
                            }
                            case 'update':
                            {
                                
                                this.titleModal = 'Actualizar Informacion';
                                this.id = data.id;
                                this.name = data.name;
                                this.last_name = data.last_name;
                                this.phone = data.phone;
                                this.email = data.email;
                                this.action = 2;
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
                     this.email = '';
                     this.password = '';
                     this.action = '';
                     $.notifyClose();
                    $("#myModal").modal('hide');
            },
        },
        mounted () {
           this.ListUsers();
        }
    }
</script>
