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
                                        <button type="button" v-if="item.deleted_at == null" class="btn btn-danger btn-sm" @click="changeStatus(item)">
                                          <i class="ti-loop"></i>
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
                       
                            <div class="form-group">
                                <label for="email">Nombre:</label>
                                <input type="text"  v-model="name"  class="form-control" placeholder="Enter Name" id="name">
                            </div>
                             <div class="form-group">
                                <label for="pwd">Apellido:</label>
                                <input type="text" v-model="last_name"  class="form-control" placeholder="Enter last_name" id="last_name">
                            </div>
                            <div class="form-group">
                                <label for="pwd">Telefono:</label>
                                <input type="text" v-model="phone"  class="form-control" placeholder="Enter phone" id="phone">
                            </div>
                            <div class="form-group">
                                <label for="pwd">Correo:</label>
                                <input type="text" v-model="email"  class="form-control" placeholder="Enter email" id="email">
                            </div>
                             <div class="form-group">
                                <label for="pwd">Contraseña:</label>
                                <input type="text" v-model="password"  class="form-control" placeholder="Enter password" id="password">
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