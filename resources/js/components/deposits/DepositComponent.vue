<template>
       <div class="col-sm-12">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-5">
                                 <h4 class="card-title mb-0">
                                     Depositos
                                    <div class="btn-group">
                                        <select class="form-control text-center" v-model="status">
                                            <option value="all" >Todos</option>
                                            <option value="1" >Confirmados</option>
                                            <option value="2">En Espera</option>
                                            <option value="D">Rechazado</option>
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
                                        <option value="name">No. Deposito</option>
                                        <option value="last_name">Usuario</option>
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
                                    <th>No. Deposito</th>
                                    <th>Usuario</th>
                                    <th>Cantidad</th>
                                    <th>Estado</th>

                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="pagination.total == 0" class="text-center">
                                    <th colspan="9" class="text-center no-data">
                                        <h2><span class="badge  badge-pill badge-info">Data Not Found</span></h2>
                                    </th>
                                </tr>

                                <tr v-for="item in dataUsers" :key="item.id">
                                    <td v-text="item.numDep"></td>
                                    <td v-text="item.name"></td>
                                    <td v-text="item.amount"></td>
                                    <td>
                                        <div v-if="item.status == 1">
                                            <span class="badge badge-warning">En Espera</span>
                                        </div>
                                        <div v-else-if="item.status == 0">
                                            <span class="badge badge-danger">Rechazado</span>
                                        </div>
                                        <div v-else-if="item.status == 2">
                                            <span class="badge badge-success">Confirmado</span>
                                        </div>

                                    </td>
                                    <td>
                                        <button type="button" style="background-color:#263238; color:#fff;" v-if="item.deleted_at == null" class="btn btn-default btn-sm" @click="openModal('modal', 'update', item)" >
                                          <i class="fa fa-eye"></i>
                                        </button>
                                        <button type="button" v-if="item.deleted_at == null && item.status == 2" class="btn btn-warning btn-sm" @click="changeStatus(item,1)" >
                                          <i class="fa fa-undo"></i>
                                        </button>
                                        <button type="button" v-if="item.deleted_at == null && item.status != 2 "  class="btn btn-success btn-sm" @click="changeStatus(item,2)" >
                                          <i class="fa fa-check-square"></i>
                                        </button>
                                        <button type="button" v-if="item.deleted_at == null && item.status == 1" class="btn btn-danger btn-sm" @click="changeStatus(item,0)">
                                          <i class="fa fa-ban"></i>
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
                    <form action="" enctype="multipart/form-data" >
                    <div class="modal-body">
                       
                            <div class="form-group">
                                <label for="email">Tipo de Deposito:</label>
                                <select class="form-control" name="bank" id="bank" v-model="bank">
                                    <option  value="">Ingrese tipo de deposito</option>
                                    <option value="1">Banco</option>
                                    <option value="2">Oxxo</option>
                                    <option value="3">Banco Movil</option>
                                </select>
                            </div>
                             <div class="form-group">
                                <label for="pwd">Numero de Deposito:</label>
                                <input type="number" v-model="numDep" class="form-control has-error "  onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"  min="1" id="numDep" name="numDep" placeholder="Ingrese numero de deposito" value="">
                            </div>
                            <div class="form-group">
                                <label for="pwd">Image o Foto del Ticket:</label>
                                <input type="file"  accept="image/*" class="form-control-file" id="imageDep" name="imageDep" @change="onFileChanged($event)">
                                <a href="javascript:void(0);" id="img-deposit" style="display:none;"></a>
                            </div>
                            <div class="form-group">
                                <label for="pwd">Cantidad:</label>
                                <input type="number" v-model="amount" step='0.01' min="1" max="10000" class="form-control" id="amount" name="amount" placeholder="00.00" value="">
                            </div>
                             <div class="form-group">
                                <label for="pwd">Descripcion:</label>
                                <textarea name="description" id="description" class="form-control" cols="10" rows="5" v-model="description"></textarea>
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

<script src="main.js"></script>
<script src="./js/deposit.js"></script>