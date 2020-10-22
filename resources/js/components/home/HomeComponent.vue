<template>
       <div class="col-sm-12">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-5">
                                 <h4 class="card-title mb-0">
                                     Inicio 
                                </h4>
                            </div>
                            <div class="col-sm-7 text-right">
                                 <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <input type="date" v-model="date" v-on:change="ListHome(1)" class="form-control col-sm-12 col-md-6 col-lg-6" placeholder="Texto a buscar">
                                            <select class="form-control col-sm-12 col-md-6 col-lg-6" v-on:change="ListHome(1)" v-model="criterion">
                                                <option value="day">Dia</option>
                                                <option value="week">Semana</option>
                                                <option value="month">Mes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                   
         
                        </div>
                    </div>
                    <div class="card-body">
                            <div class="col-sm-12 text-center">
                                <h1>Bienvenido </h1>
                                <br>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body  text-center"><h1 v-text="this.tickets_pay_off">0</h1>Tickets Pagados</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="card bg-danger text-white">
                                            <div class="card-body text-center"><h1 v-text="this.tickets_not_pay_off">0</h1>Tickets No Pagados</div>
                                        </div>
                                    </div>
                                     <div class="col-sm-3">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body text-center"><h1 v-text="this.prize > 0?this.prize:0">0</h1>Premios</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="card bg-success text-white">
                                            <div class="card-body text-center"><h1 v-text="this.incomes > 0?this.incomes:0">0</h1>Ingresos</div>
                                        </div>
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
                                    <th>Total</th>
                                    <th>Status</th>
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
                                        <td v-text="item.id"></td>
                                         <td v-text="item.phone"></td>
                                        <td v-text="item.total">
                                            <h6 v-if="item.winner == 1" class="text-warning"> Ganador <i class="ti-star"></i></h6>
                                        </td>
                                        <td>
                                            <div v-if="item.active == 1" class="text-center">
                                                <span class="badge badge-success">Pagado</span>
                                                <h6 v-if="item.winner == 1" class="text-warning"> Ganador <i class="ti-star"></i></h6>
                                            </div>
                                            <div v-else-if="item.active == 0" class="text-center">
                                                <span class="badge badge-danger">Por Pagar</span>
                                            </div>

                                        </td>
                                        <td v-text="item.date"></td>
                                        <td class="text-center" v-if="item.deleted_at == null" >
                                            <button type="button" class="btn btn-danger" @click="openModal('modal','detail',item)">
                                                <i class="ti-eye"></i>
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
                                <h3>Jugadas</h3>
                            </div>
                            <div class="form-group col-sm-12 col-md-12 col-lg-12">
                                <ul class="list-group">
                                    <li class="list-group-item"  v-if="dataNumbers.length == 0">
                                        <h6>Jugada vacía</h6>
                                    </li>
                                    <li class="list-group-item"  v-for="item in dataNumbers" :key="item.id">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-4 col-lg-4 text-center" >
                                                Numero:<strong v-text="item.game_number"></strong>
                                                <h6 v-if="item.winner == 1" class="text-success"> Ganador</h6>
                                            </div>
                                            <div class="col-sm-12 col-md-4 col-lg-4 text-center" >Juego:<strong v-text="item.game_number"></strong></div>
                                            <div class="col-sm-12 col-md-4 col-lg-4 text-center" >
                                                Inversion:$<strong v-text="item.bet"></strong> pesos
                                                <h6 v-if="item.winner == 1" class="text-success">Premio: <p v-text="item.prize"></p></h6>
                                            </div>                  
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
                            <button type="button" class="btn btn-danger" @click="closeModal()" >Close</button>
                        </div>

						</div>
					</div>
				</div>
    </div>
</template>
<script src="./js/home.js"></script>