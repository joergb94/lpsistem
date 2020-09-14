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
                             <div class="form-group">
                                <label for="pwd">Ultima actualizacion:</label>
                               <label v-text="dataUser.updated_at"></label>
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
                                <input type="text" v-model="password_confirmation"  class="form-control" placeholder="Enter password" id="password">
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
<script src="./js/profile.js"></script>