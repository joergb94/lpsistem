<template>
       <div class="col-sm-12">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-5">
                                 <h4 class="card-title mb-0">
                                     Tickets
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
                                <th>Total</th>
                                <th>Status</th>
                                <th>created_at</th>
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
                                    <td v-text="item.total"></td>
                                    <td>
                                          <div v-if="item.active == 1">
                                            <span class="badge badge-success">Actived</span>
                                        </div>
                                        <div v-else-if="item.active == 0">
                                            <span class="badge badge-danger">Deactivated</span>
                                        </div>

                                    </td>
                                    <td v-text="item.created_at"></td>
                                    <td>
                                        <button type="button" v-if="item.deleted_at == null" class="btn btn-danger btn-sm" @click="changeStatus(item)">
                                          <i class="ti-eye"></i>
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
                <div class="modal-dialog modal-sm modal-lg">
                    <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"  v-text="titleModal"></h4>
                        <button type="button"  class="close" @click="closeModal()" >&times;</button>
                    </div>

                    <!-- Modal body -->
                    <form action="">
                    <div class="modal-body"> 
                            <div class="form-group col-sm-12 col-md-12 col-lg-12">
                                <label for="email">Telefono del cliente:</label>
                                <input type="text"  v-model="phone"  class="form-control" placeholder="Enter phone" id="phone">
                            </div>
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 text-center">
                                <h3><span class="badge badge-warning">Jugada</span></h3>
                                    <div class="row">
                                         <div class="form-group col-sm-12 col-md-4 col-lg-4">
                                            <label for="pwd">Numero:</label>
                                            <input type="number" maxlength="5" v-model="number"  class="form-control" placeholder="Enter total" id="number">
                                        </div>
                                         <div class="form-group col-sm-12 col-md-4 col-lg-4">
                                            <label for="pwd">Juego:</label>
                                            <select class="form-control" v-model="game" id="game" name="game">
                                                <option value="" >Seleciona un Juego</option>
                                                <option v-for="item in dataGames" :key="item.id" v-bind:value="{ id:item.id, text:item.name }">
                                                    {{ item.name }}
                                                </option>
                                            </select>
                                        </div>
                                         <div class="form-group col-sm-12 col-md-4 col-lg-4">
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
                                                        <div class="col-sm-12 col-md-3 col-lg-3" v-text="item.number">
                                                        </div>
                                                        <div class="col-sm-12 col-md-3 col-lg-3" v-text="item.game.text">
                                                        </div>
                                                         <div class="col-sm-12 col-md-3 col-lg-3" v-text="item.subtotal">
                                                        </div>
                                                         <div class="col-sm-12 col-md-3 col-lg-3">
                                                            <button type="button" class="btn btn-danger" v-on:click="removeNumber(index)" >-</button>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 text-left">
                                <label>Total:</label>
                                $<label v-text="total"></label>
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
            dataTicktes:[],
            dataNumbers:[],
            dataGames:[],
            id:'',
            phone:'',
            total: 0,
            subtotal:'',
            number:'',
            game:'',
            titleModal:'',
            action:0,
            page:1,
            users: 1,
            pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
            offset : 3,
            criterion : 'name',
            status : 1,
            search : ''

            }
        },
        computed:{
            isActived: function(){
              return this.pagination.current_page;
            },
            pagesNumber: function(){
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        methods : {
            message(data){
                $.notifyClose();
                  $.notify({
                                // options
                                title:data.title,
                                message:data.text,
                            },{
                                // settings
                                type:data.type
                            });
            },
            ListUsers(page){
                let me = this;
                var url = '/tickets?page='+page+'&search='+this.search+'&criterion='+this.criterion+'&status='+this.status;
                 axios.get(url)
                .then(function (response) {
                    var respuesta= response.data;
                    me.dataTicktes = respuesta.Tickets.data;
                    me.dataGames = respuesta.Games;
                    me.pagination= respuesta.pagination;
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
                 var url = '/tickets/add'
                 var data = {
                    'phone': this.phone,
                    'total':me.total,
                    'dataNumbers':me.dataNumbers
                };
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
                             axios.post('/users/deleteOrResotore',data).then(function (response) {
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
                             axios.post('/users/change_status',data).then(function (response) {
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
                            case 'add':
                            {
                                this.titleModal = 'Nuevo Ticket';
                                this.phone = '';
                                this.total = '';
                                this.number = '';
                                this.subtotal = '';
                                this.dataNumbers =[];
                                this.action = 1;
                                break;
                            }
                        }
                        $("#myModal").modal('show');
                    }
                }
            },
            closeModal(){
                    this.titleModal = '';
                    this.phone = '';
                    this.total = '';
                    this.number = '';
                    this.subtotal = '';
                    this.dataNumbers =[];
                     $.notifyClose();
                    $("#myModal").modal('hide');
            },
            addNumber() {
                let me = this;
                    if(this.number.length == 0){
                        me.message({title:'Error',text:'El campo Numero es requerido',type:'danger'});
                        return false;
                    }

                    if(this.subtotal.length == 0){
                        me.message({title:'Error',text:'El campo Inversion es requerido',type:'danger'});
                        return false;
                    }

                    if(this.game.length == 0){
                        me.message({title:'Error',text:'El campo Juego es requerido',type:'danger'});
                        return false;
                    }
                    
                    if(this.dataNumbers.push({
                        number: this.number,
                        game:this.game,
                        subtotal: this.subtotal,
                    }))
                    {   
                        let sumtotal = me.total > 0 ? parseFloat(me.total) + parseFloat(this.subtotal) : parseFloat(this.subtotal);
                        this.total = parseFloat(sumtotal);
                        this.number = ''
                        this.subtotal = ''
                        this.game = ''
                        me.message({title:'Listo',text:'Se AGREGO con exito el Numero',type:'success'});
                    }
                    


                   
            },
            removeNumber(index){
                let me = this;
                let sub = this.dataNumbers[index];
                let sumtotal = me.total > 0 ? parseFloat(me.total) - parseFloat(sub.subtotal) : 0;
                this.total = parseFloat(sumtotal);

                 if(this.dataNumbers.splice(index, 1))
                 {
                     me.message({title:'Listo',text:'Se ELIMINO con exito el Numero',type:'success'});
                 }
                   
            }
        },
        mounted () {
           this.ListUsers(1);
        }
    }
</script>
