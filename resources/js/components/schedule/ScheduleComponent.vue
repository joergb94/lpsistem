<template>
       <div class="col-sm-12">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-5">
                                 <h4 class="card-title mb-0">
                                     Calendario de Juegos
                                    <div class="btn-group">
                                        
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
                                        <option value="number_win">1er Ganador</option>
                                         <option value="number_win2">2do Ganador</option>
                                        <option value="date">Fecha</option>
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
                                    <th>1er Ganador</th>
                                    <th>2do Ganador</th>
                                    <th>Juego</th>
                                    <th>Fecha</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="pagination.total == 0" class="text-center">
                                    <th colspan="5" class="text-center no-data">
                                        <h2><span class="badge  badge-pill badge-info">Data Not Found</span></h2>
                                    </th>
                                </tr>

                                <tr v-for="item in dataUsers" :key="item.id">
                                    <td v-text="item.number_win"></td>
                                    <td v-text="item.number_win2"></td>
                                    <td v-text="item.games.name"></td>
                                    <td v-text="item.date"></td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" v-if="item.deleted_at == null"  @click="openModal('modal', 'update', item)" >
                                          <i class="ti-pencil"></i>
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
                                <label for="email">Juego:</label>
                                 <select class="form-control" v-model="game_id" id="game" name="game">
                                        <option value="" >Seleciona un Juego</option>
                                        <option v-for="item in dataGames" :key="item.id" v-bind:value="item.id">
                                            {{ item.name }}
                                        </option>
                                    </select>
                            </div>
                             <div class="form-group">
                                <label for="pwd">Numero ganador:</label>
                                <input type="text"  v-model="number_win"  class="form-control" placeholder="Enter name" id="number_win">
                            </div>
                             <div class="form-group">
                                <label for="pwd">2do Numero ganador:</label>
                                <input type="text"  v-model="number_win2"  class="form-control" placeholder="Enter name" id="number_win2">
                            </div>
                            <div class="form-group">
                                <label for="pwd">Fecha:</label>
                                <input type="date"  v-model="date"  class="form-control" placeholder="Enter date" id="date">
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
<script src="./js/schedule.js"></script>