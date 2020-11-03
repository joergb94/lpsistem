<template>
       <div class="col-sm-12">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-5">
                                 <h4 class="card-title mb-0">
                                    Reporte Vendedores
                                    <div class="btn-group">
                                       <select class="form-control col-sm" v-on:change="ListUsers(1)" v-model="type">
                                                <option value="day">Dia</option>
                                                <option value="week">Semana</option>
                                                <option value="month">Mes</option>
                                        </select>
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
                                <div class="input-group">
                                    <input type="date" v-model="date" class="form-control col-sm-12 col-md-4 col-lg-2" placeholder="Texto a buscar">
                                    <select class="form-control col-sm-2" v-model="criterion">
                                        <option value="name">Nombre</option>
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
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Telefono</th>
                                <th>Nomina</th>
                                <th>Ganacia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="pagination.total == 0" class="text-center">
                                    <th colspan="9" class="text-center no-data">
                                        <h2><span class="badge  badge-pill badge-info">No hay Inoformacion</span></h2>
                                    </th>
                                </tr>

                                <tr v-for="item in dataUsers" :key="item.id">
                                    <td v-text="item.name"></td>
                                    <td v-text="item.last_name"></td>
                                    <td v-text="item.phone"></td>
                                    <td v-text="item.payroll > 0?'$'+item.payroll:'$'+0"></td>
                                    <td v-text="item.gain > 0?'$'+item.gain:'$'+0"></td>
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
        
    </div>
</template>
<script src="./js/payroll.js"></script>
