<template>
       <div class="col-sm-12">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-5">
                                 <h4 class="card-title mb-0">
                                     Numeros Ganadores
                                    <div class="btn-group">
                                    </div> 
                                </h4>
                            </div>
                            <div class="col-sm-7 text-right">
                                
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="row">
                                    <input type="date" v-model="date" v-on:change="get_games()" class="form-control col-sm-12 col-md-4 col-lg-2" placeholder="Texto a buscar">
                                     <select class="form-control col-sm-12 col-md-4 col-lg-2" v-model="game" v-on:change="get_numbers()" id="game" name="game">
                                        <option value="" >Seleciona un Juego</option>
                                        <option v-for="item in dataGames" :key="'a'+item.id" v-bind:value="{ id:item.id, game_id:item.game_id}">
                                            {{ item.games.name }} #{{item.id}}
                                        </option>
                                    </select>

                                    <select class="form-control col-sm-12 col-md-4 col-lg-2" v-model="game_detail" id="game" name="game">
                                        <option value="0" >Selecione numero ganador</option>
                                        <option v-for="item in dataGamesDetail" :key="item.id" v-bind:value="{ type:item.type, number:item.number_win }">
                                            {{ item.number_win }} <h6 class="text-success" v-text="item.type == 1?'1er':'2do'"></h6>
                                        </option>
                                    </select>
                                     <select class="form-control col-sm-12 col-md-4 col-lg-2" v-model="figures" id="figures" name="figures">
                                        <option value="0" >Todas las cifras</option>
                                        <option v-for="(item,i) in dataFigure" :key="'A'+ i" v-bind:value="item">
                                            #Cifras {{ item }}
                                        </option>
                                    </select>
                                    <input type="text" v-model="search" @keyup.enter="ListTickets(1)" class="form-control col-sm-12 col-md-4 col-lg-2" placeholder="Texto a buscar">
                                
                                    <button type="button" @click="ListTickets(1)" class="btn btn-primary col-sm-12 col-md-12 col-lg-2"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                         <!-- The table-->
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                    <th>#</th>
                                    <th>Telefono</th>
                                    <th>Jugada</th>
                                    <th>Fecha</th>
                                    <th>actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="pagination.total == 0" class="text-center">
                                        <th colspan="9" class="text-center no-data">
                                            <h2><span class="badge  badge-pill badge-info">Data Not Found</span></h2>
                                        </th>
                                    </tr>

                                    <tr v-for="item in dataTicktes" :key="item.id">
                                        <td>
                                            No.<strong v-text="item.number"></strong>
                                            <h6 v-if="item.winner == 1" class="text-warning"> Ganador </h6>
                                        </td>
                                         <td v-text="item.phone"></td>
                                        <td>
                                            <h6 v-text="item.bet"></h6>
                                            <h6 v-if="item.winner == 1" class="text-warning" v-text="item.prize"></h6>
                                        </td>
                                        <td v-text="item.date"></td>
                                        <td v-if="item.deleted_at == null" >
                                            <button type="button" class="btn btn-danger btn-sm" @click="openModal('modal','detail',item)">
                                                <i class="ti-eye"></i>
                                            </button>
                                            <button v-if="item.winner == 0" type="button" class="btn btn-success btn-sm" @click="changeStatus(item)">
                                                <i class="ti-medall"></i>
                                            </button>
                                            <button v-if="item.winner == 1" type="button" class="btn btn-secondary btn-sm" @click="changeStatus(item)">
                                                <i class="ti-medall"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary btn-sm" @click="DeleteOrRestore(item)">
                                                <i class="ti-trash"></i>
                                            </button>
                                        </td>
                                        <td v-if="item.deleted_at !== null">
                                            No actions
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
                         <!-- End Table -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- The Modal -->
                <div class="modal" id="myModal">
                <div class="modal-dialog modal-sm modal-lg">
                    <!-- The Modal Create/edit-->
                    <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"  v-text="titleModal"></h4>
                        <button type="button"  class="close" @click="closeModal()" >&times;</button>
                    </div>

                    <!-- Modal body Detail-->
                    <div class="modal-body" v-if="action==2">
                         <div class="form-group col-sm-12 col-md-12 col-lg-12 text-center">
                            <label for="email">Telefono del cliente:</label>
                            <label><strong v-text="phone"></strong></label>
                            <div class="col-sm-12" id="send-text">
                            </div>
                             
                         </div>
                         <div class="form-group col-sm-12 col-md-12 col-lg-12 text-center">
                             <h3>Dias de juego</h3>
                         </div>
                         <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <ul class="list-group">
                                <li class="list-group-item"  v-if="dataNewDays.length == 0">
                                    <h6>Sin dias</h6>
                                </li>
                                <li class="list-group-item"  v-for="item in dataNewDays" :key="item.id">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-6 text-center" >Dia:<strong v-text="item.name"></strong></div>
                                        <div class="col-sm-12 col-md-6 col-lg-6 text-center" >Fecha:<strong v-text="item.date"></strong></div>               
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 text-center">
                             <h3>Jugadas para <strong class="text-primary" v-text="dataNumbers[0].games.name"></strong></h3>
                         </div>
                         <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <ul class="list-group">
                                <li class="list-group-item"  v-if="dataNumbers.length == 0">
                                    <h6>Jugada vacía</h6>
                                </li>
                                <li class="list-group-item"  v-for="item in dataNumbers" :key="item.id">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4 col-lg-4 text-center" >Numero:<strong v-text="item.game_number"></strong>
                                        <h6 v-if="item.winner == 1" class="text-warning"> Ganador <i class="ti-star"></i></h6></div>
                                        <div class="col-sm-12 col-md-4 col-lg-4 text-center" >Juego:<strong v-text="item.game_number"></strong></div>
                                        <div class="col-sm-12 col-md-4 col-lg-4 text-center" >Inversion:$<strong v-text="item.bet"></strong> pesos</div>                  
                                    </div>
                                </li>
                            </ul>
                        </div>
                          <div class="form-group col-sm-12 col-md-12 col-lg-12 text-left">
                            <label for="email">Total Jugadas:</label>
                            <label >$ {{mTotal}} Pesos</label>
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 text-left">
                            <label for="email">Numero de dias:</label>
                            <label v-text="dataNewDays.length" ></label>
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 text-left">
                            <label for="email">Total por dias:</label>
                            <label >$ {{total}} Pesos</label>
                        </div> 
                    </div>
                    <!-- End Modal body Detail-->
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" v-if="action==1" @click="updateOrCreate(1)">Save</button>
                        <button type="button" class="btn btn-danger" @click="closeModal()" >Close</button>
                    </div>

                    </div>
                    <!-- End Modal Create/edit-->
                    
                </div>
        </div>
             
    </div>
</template>
<script src="main.js"></script>
<script src="./js/winner.js"></script>
