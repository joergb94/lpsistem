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
                                
                                           <h4 class="text-primary" v-text="this.date"></h4>
                                        
                            </div>
                                   
         
                        </div>
                    </div>
                    <div class="card-body">
                            <div class="col-sm-12 text-center">
                                <h1>Bienvenido </h1>
                                <br>
                                <div id="demo" class="carousel slide" data-ride="carousel">

                                    <!-- Indicators -->
                                    <ul class="carousel-indicators">
                                        <li data-target="#demo" data-slide-to="0" class="active"></li>
                                        <li data-target="#demo" data-slide-to="1"></li>
                                        <li data-target="#demo" data-slide-to="2"></li>
                                    </ul>
                                    
                                    <!-- The slideshow -->
                                    <div class="carousel-inner">

                                        <div  v-for="(item,index) in dataGames" :key="index" :class="['carousel-item', (index === 0 ? 'active' : '')]">
                                            <div class="jumbotron bg-warning">
                                                <h1 v-text="item.games.name"></h1>
                                                <p class="text-primary" v-text="item.date"></p>
                                         
                                                <p v-if="item.game_schedule_details.length == 0"> A un no hay numero ganadores, tu puedes ser un ganador.</p>
                                                <h4 v-else class="text-center text-success" v-for="(detail,index) in item.game_schedule_details" :key="index">{{index+1 == 1?'1er':'2do' }} Numero <strong v-text="detail.number_win"></strong></h4>
                                               
                                        
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Left and right controls -->
                                    <a class="carousel-control-prev" href="#demo" data-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </a>
                                    <a class="carousel-control-next" href="#demo" data-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </a>
                                </div>
                                 <!-- The table-->
                            <div class="table-responsive">
                                <ul class="list-group">
                                    <li class="list-group-item text-center"  v-if="dataTicktes.length == 0">
                                         <h2><span class="badge  badge-pill badge-info">Data Not Found</span></h2>
                                    </li>
                                    <li class="list-group-item"  v-for="item in dataTicktes" :key="item.id">
                                        <div class="row text-center">
                                            <div class="col-sm-12 col-md-3 col-lg-3">
                                                <strong>No.ticket: <span v-text="item.id"></span></strong>
                                                <h6 v-if="item.winner == 1" class="text-success"> Ganador <i clsass="ti-star"></i></h6>
                                            </div>
                                            <div class="col-sm-12 col-md-3 col-lg-3 text-primary" v-text="item.date"></div>
                                            <div class="col-sm-12 col-md-3 col-lg-3">$<span v-text="item.total"></span> pesos</div>
                                            <div class="col-sm-12 col-md-3 col-lg-3">
                                                <button type="button" class="btn btn-danger btn-sm" @click="openModal('modal','detail',item)">
                                                    <i class="ti-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <br>
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
                                            <div class="col-sm-12 col-md-6 col-lg-6 text-center" >
                                                Numero:<strong v-text="item.game_number"></strong>
                                                <h6 v-if="item.winner == 1" class="text-success"> Ganador</h6>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6 text-center" >
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
<script src="./js/homeC.js"></script>