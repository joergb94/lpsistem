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
        criterion : 'phone',
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
        ListTickets(page){
            let me = this;
            var url = '/tickets?page='+page+'&search='+this.search+'&criterion='+this.criterion+'&status='+this.status;
             axios.get(url)
            .then(function (response) {
                var answer= response.data;
                me.dataTicktes = answer.Tickets.data;
                me.dataGames = answer.Games;
                me.pagination= answer.pagination;
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
            me.ListTickets(page)
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
                var answer = response.data;
                me.message(answer);

                me.ListTickets('');
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
             var m = "Do you want to deleted Ticket?";
             var mt = "The Ticket will be delete";
             var btn = "Delete";


            if(item.deleted_at != null){
                 m = "Do you want to restored Ticket?";
                 mt = "The Ticket will be restore";
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
                         axios.post('/tickets/deleteOrResotore',data).then(function (response) {
                                var answer = response.data;
                                me.message(answer);
                                me.ListTickets();
                            
                            }).catch(function (error) {
                               
                            }) 
                    }
                }) 
        },
        changeStatus(item){
            let me = this;
            var data = {
                'id': item.id,
                };
             var m = "Do you want to deactived Ticket?";
             var mt = "The Ticket will be deactived";
             var btn = "Deactived";


            if(item.active == 0){
                 m = "Do you want to actived Ticket?";
                 mt = "The Ticket will be actived";
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
                         axios.post('/tickets/change_status',data).then(function (response) {
                                me.ListTickets();
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
        openModal(model,action, data = ''){
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
                            $("#myModal").modal('show');
                            break;
                        }
                        case 'detail':
                        {   let me = this;
                            me.action = 2;
                            axios.get('/tickets/detail?id='+data.id).then(function (response) {
                                var answer = response.data;
                                console.log(answer)
                                me.titleModal = 'Info Ticket Numero: '+answer.ticket.id;
                                me.dataNumbers = answer.ticketDetail;
                                me.total = answer.ticket.total;
                                me.phone =answer.client.phone;

                                if(answer.client.created_at == answer.client.updated_at){
                                     $('#send-text').html(`<a class="btn btn-block btn-primary text-white" href="https://wa.me/52${answer.client.phone}?text=USUARIO%20${answer.client.email}%20CONTRASEÑA:%20${answer.client.phone}%20" target="_blank" style="color:#000;">
                                                        Enviar Usuario y Contraseña &nbsp; 
                                                        <i style="font-size:18px;" class="fa fa-mobile-phone"></i>
                                                    </a>
                                                    <a class="btn btn-block btn-success text-white" href="https://wa.me/52${answer.client.phone}" target="_blank" style="color:#000;">
                                                        Enviar mensaje &nbsp; 
                                                        <i style="font-size:18px;" class="fa fa-mobile-phone"></i>
                                                    </a>`);
                                }else{
                                     $('#send-text').html(`<a class="btn btn-block btn-success text-white" href="https://wa.me/52${answer.client.phone}" target="_blank" style="color:#000;">
                                                        Enviar mensaje &nbsp; 
                                                        <i style="font-size:18px;" class="fa fa-mobile-phone"></i>
                                                    </a>`);
                                }
                               

                                $("#myModal").modal('show');
                            }).catch(function (error) {}) 
                           
                            break;
                        }
                    }
                    
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
                this.client ='';
                $('#send-text').html('');
                 $.notifyClose();
                $("#myModal").modal('hide');
        },
        addNumber() {
            let me = this;
                if(this.number.length == 0 || this.number.length > 5){
                    me.message({title:'Error',text:'El campo Numero es incorrecto',type:'danger'});
                    return false;
                }

                if(this.subtotal.length == 0){
                    me.message({title:'Error',text:'El campo Inversion es requerido',type:'danger'});
                    return false;

                }else if(this.subtotal > 500){
                    me.message({title:'Error',text:'El campo Inversion debe ser menor a 500.00 pesos',type:'danger'});
                    return false;
                }

                if(this.game.length == 0){
                    me.message({title:'Error',text:'El campo Juego es requerido',type:'danger'});
                    return false;
                }
                
                if(this.dataNumbers.push({
                    number: this.number,
                    game:this.game,
                    subtotal: Number.parseFloat(this.subtotal),
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
       this.ListTickets(1);
    }
}