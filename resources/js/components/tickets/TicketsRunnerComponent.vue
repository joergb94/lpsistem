<template>
       <div class="col-sm-12">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-5">
                                 <h4 class="card-title mb-0">
                                     Tickets Vendidos
                                    <div class="btn-group">
                                        <select class="form-control text-center" v-model="status">
                                            <option value="all" >Todos</option>
                                            <option value="1" >Pagados</option>
                                            <option value="2" >Por pagar</option>
                                            <option value="3" >Ganadores</option>
                                        </select>
                                    </div> 
                                </h4>
                            </div>
                            <div class="col-sm-7 text-right">
                                 <button class="btn btn-success" @click="openModal('modal', 'add')">Nuevo Ticket +</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="row">
                                    <input type="date" v-model="date" @keyup.enter="ListTickets(1)" class="form-control col-sm-12 col-md-12 col-lg-12" placeholder="Texto a buscar">
                                    <select v-if="this.type !== 3" class="form-control col-sm-12 col-md-12 col-lg-12" v-model="seller">
                                        <option value="0">Selecione un vendedor</option>
                                        <option  v-bind:value="user">Yo</option>
                                        <option v-for="item in dataSeller" :key="item.id" v-bind:value="{ id:item.id,text:item.name}">
                                            {{ item.name }} {{item.last_name}}
                                        </option>   
                                    </select>
                                    <select class="form-control col-sm-12 col-md-6 col-lg-2" v-model="criterion">
                                        <option value="tickets.phone">Telefono</option>
                                        <option value="tickets.id">#</option>
                                    </select>
                                    
                                    <input type="text" v-model="search" @keyup.enter="ListTickets(1)" class="form-control col-sm-12 col-md-6 col-lg-8" placeholder="Texto a buscar">
                                
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
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="pagination.total == 0" class="text-center">
                                        <th colspan="9" class="text-center no-data">
                                            <h2><span class="badge  badge-pill badge-info">No Hay Tickets</span></h2>
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
                                        <td v-if="item.deleted_at == null" >
                                            <button type="button" class="btn btn-danger btn-sm" @click="openModal('modal','detail',item)">
                                                <i class="ti-eye"></i>
                                            </button>
                                            <button v-if="item.active == 0" type="button" class="btn btn-success btn-sm" @click="changeStatus(item)">
                                                <i class="ti-money"></i>
                                            </button>
                                            <button v-if="item.active == 1" type="button" class="btn btn-secondary btn-sm" @click="changeStatus(item)">
                                                <i class="ti-na"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary btn-sm" @click="DeleteOrRestore(item)">
                                                <i class="ti-trash"></i>
                                            </button>
                                        </td>
                                        <td v-if="item.deleted_at !== null">
                                            Sin acciones
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

                    <!-- Modal body Create/Edit -->
                    <div class="modal-body" v-if="action==1"> 
                                <div class="form-group col-sm-12 col-md-12 col-lg-12">
                                    <label for="email">Telefono del cliente:</label>
                                    <input type="number"  v-model="phone"  class="form-control" placeholder="Enter phone" id="phone">
                                </div>
                                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                                    <label for="pwd">Juego:</label>
                                    <select class="form-control" v-model="game" id="game" name="game">
                                        <option value="" >Seleciona un Juego</option>
                                        <option v-for="item in dataGames" :key="item.id" v-bind:value="{ id:item.id, text:item.name }">
                                            {{ item.name }} 
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                                    <label for="pwd">Tipo:</label>
                                    <select class="form-control" v-model="ticket_type" v-on:change="set_pay_to()" id="ticket_type" name="ticket_type">
                                        <option value="0">No Repetir</option>
                                        <option value="1">Repetir 1 semana</option>
                                        <option value="2">Repetir 2 semana</option>
                                        <option value="3">Repetir 3 semana</option>
                                        <option value="4">Repetir 4 semana</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 text-center">
                                    <h3><span class="badge badge-warning">Dias de juego</span></h3>
                                    <br>
                                        <div class="row">
                                            <div class="form-group col-sm-10 col-md-10 col-lg-10">
                                                <select class="form-control" v-model="day" id="day" name="day">
                                                        <option value="" >Seleciona un dia</option>
                                                        <option v-for="item in dataDays" :key="item.id" v-bind:value="{ id:item.id,text:item.name ,value:item.value}">
                                                            {{ item.name }}
                                                        </option>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-2 col-md-2 col-lg-2">
                                                <button type="button" class="btn btn-primary btn-block" @click="addDay()">Agregar </button>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 col-lg-12">
                                               <div class="row">
                                                   <div class="col-sm-12 text-center" v-if="dataNewDays.length == 0">
                                                       <h6> Sin dias</h6>
                                                   </div>
                                                   <div class="col-sm-4 text-center" v-for="(item,index) in dataNewDays" :key="index">
                                                       <div class="row">
                                                           <div class="col-sm-8" v-text="item.day.text">
                                                           </div>
                                                           <div class="col-sm-4">
                                                               <button type="button" class="btn btn-danger" v-on:click="removeDay(index)" >X</button>
                                                           </div>
                                                       </div>
                                                   </div>
                                               </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 text-center">
                                    <h3><span class="badge badge-warning">Jugada</span></h3>
                                    <br>
                                        <div class="col-sm-12">
                                             <select class="form-control col-sm-12 col-md-12 col-lg-12" v-model="figures" id="figures" name="figures">
                                                <option value="0" >Selecione el numero de cifras</option>
                                                <option v-for="(item,i) in dataFigure" :key="'A'+ i" v-bind:value="item">
                                                    #Cifras {{ item }}
                                                </option>
                                            </select>
                                        </div>
                                        <div v-if="figures > 0" class="row">
                                            <div class="form-group col-sm-12 col-md-6 col-lg-6">
                                                <label for="pwd">Numero:</label>
                                                <input type="number" maxlength="5" v-model="number"  class="form-control" placeholder="Enter total" id="number">
                                            </div>
                                            <div class="form-group col-sm-12 col-md-6 col-lg-6">
                                                <label for="pwd">Inversion:</label>
                                                <input type="number" step="0.01" v-model="subtotal"  class="form-control" placeholder="Enter total" id="subtotal">
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 col-lg-12">
                                                <button type="button" class="btn btn-primary btn-block" @click="addNumber()">Agregar al tickte</button>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-12 col-lg-12">
                                                <ul class="list-group">
                                                    <li class="list-group-item"  v-if="dataNumbers.length == 0">
                                                    <h6>Jugada vacía</h6>
                                                    </li>
                                                    <li class="list-group-item"  v-for="(item,index) in dataNumbers" :key="index">
                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-4 col-lg-4" v-text="item.number">
                                                            </div>
                                                            <div class="col-sm-12 col-md-4 col-lg-4">
                                                                $<span v-text="item.subtotal"></span> pesos
                                                            </div>
                                                            <div class="col-sm-12 col-md-4 col-lg-4">
                                                                <button type="button" class="btn btn-danger" v-on:click="removeNumber(index)" >-</button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                </div>
                                  <div class="form-group col-sm-12 col-md-6 col-lg-6">
                                    <label for="pwd">Pagar:</label>
                                    <select class="form-control" v-model="pay_to" id="ticket_type" v-on:change="get_pay_now()" name="ticket_type">
                                        <option value="-1">Ninguno</option>
                                        <option v-if="ticket_type >= 0"  value="0">Pagar el 1er</option>
                                        <option v-if="ticket_type >= 1"  value="1">Pagar el 1er y 2do </option>
                                        <option v-if="ticket_type >= 2"  value="2">Pagar el 1er, 2do y 3er</option>
                                        <option v-if="ticket_type >= 3"  value="3">Pagar el 1er, 2do , 3er y 4to</option>
                                        <option v-if="ticket_type >= 4"  value="4">Pagar Todos</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                                    <label for="pwd">Pagar ahora:</label>
                                    <h6 class="text-primary">$ {{pay_now}} Pesos</h6>
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
                    <!-- End Modal body  Create/Edit-->
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
                             <h3>Jugadas para:</h3>
                         </div>
                         <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <ul class="list-group">
                                <li class="list-group-item"  v-if="dataNumbers.length == 0">
                                    <h6>Jugada vacía</h6>
                                </li>
                                <li class="list-group-item"  v-for="item in dataNumbers" :key="item.id">
                                    <div class="row">
                                       <div class="col-sm-12 col-md-3 col-lg-3 text-center" >Numero:<strong v-text="item.game_number"></strong>
                                                <h6 v-if="item.winner == 1" class="text-warning"> Ganador <i class="ti-star"></i></h6></div>
                                            <div class="col-sm-12 col-md-3 col-lg-3 text-center" >Juego:<strong v-text="item.games.name"></strong>
                                                <h6 v-if="item.active == 1" class="text-success"> Pagado </h6>
                                            </div>
                                           <div class="col-sm-12 col-md-3 col-lg-3 text-center" >Inversion:$<strong v-text="item.bet"></strong> pesos <h6 v-if="item.winner == 1" class="text-warning" v-text="item.prize"></h6></div>
                                            <div class="col-sm-12 col-md-3 col-lg-3 text-center" >Fecha:<strong v-text="item.date_ticket"></strong>
                                                <button class="btn btn-warning" v-on:click="changeStatusDetail(item)"><i class="ti-money"></i></button>
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
                        <button type="button" class="btn btn-primary" v-if="action==1" @click="updateOrCreate(1)">Guardar</button>
                        <button type="button" class="btn btn-danger" @click="closeModal()" >Cancelar</button>
                    </div>

                    </div>
                    <!-- End Modal Create/edit-->
                    
                </div>
        </div>
             
    </div>
</template>
<script src="./js/ticket.js"></script>
