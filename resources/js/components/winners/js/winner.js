export default {
    data () {
        return {
        dataTicktes:[],
        dataNumbers:[],
        dataGames:[],
        dataGamesDetail:[],
        dataDays:[],
        dataNewDays:[],
        dataFigure:[1,2,3,4,5],
        id:'',
        phone:'',
        total: 0,
        subtotal:'',
        number:'',
        game:'',
        games:'',
        game_detail:'0',
        figures:'0',
        day:'',
        multiplier:0,
        mTotal:0,
        ticket_type:'1',
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
        criterion : 'tickets.phone',
        date:'',
        status : 'all',
        search : '',
        norepeat: 0

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
                icon: 'glyphicon glyphicon-warning-sign',
                title: data.title,
                message: data.text,
            },{
                // settings
                element: 'body',
                position: null,
                type: data.type,
                allow_dismiss: true,
                newest_on_top: false,
                showProgressbar: false,
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                },
                onShow: null,
                onShown: null,
                onClose: null,
                onClosed: null,
                icon_type: 'class',
                template: '<div data-notify="container" class="col-xs-10 col-sm-3 alert alert-{0} text-center" role="alert">' +
                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                    '<span data-notify="icon"></span> ' +
                    '<span data-notify="title">{1}</span> ' +
                    '<span data-notify="message">{2}</span>' +
                    '<div class="progress" data-notify="progressbar">' +
                        '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                    '</div>' +
                '</div>' 
            });
        },
        erros(e){
            $.notifyClose();
            $.each(e,function (k,message) {
               $.notify({
                   // options
                   icon: 'glyphicon glyphicon-warning-sign',
                   title: "Error!",
                   message: message,
               },{
                   // settings
                   element: 'body',
                   position: null,
                   type: "danger",
                   allow_dismiss: true,
                   newest_on_top: false,
                   showProgressbar: false,
                   animate: {
                       enter: 'animated fadeInDown',
                       exit: 'animated fadeOutUp'
                   },
                   onShow: null,
                   onShown: null,
                   onClose: null,
                   onClosed: null,
                   icon_type: 'class',
                   template: '<div data-notify="container" class="col-xs-10 col-sm-3 alert alert-{0} text-center" role="alert">' +
                       '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                       '<span data-notify="icon"></span> ' +
                       '<span data-notify="title">{1}</span> ' +
                       '<span data-notify="message">{2}</span>' +
                       '<div class="progress" data-notify="progressbar">' +
                           '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                       '</div>' +
                   '</div>' 
               });

            });
        },
        ListTickets(page){
            let me = this;
            var url = '/winners?page='+page+'&search='+this.search+'&criterion='+this.criterion+'&status='+this.status+'&date='+this.date;
                url +='&game='+this.game.game_id+'&game_schedule='+this.game.id+'&game_detail='+this.game_detail.number+'&figure='+this.figures;
    
             axios.get(url)
            .then(function (response) {
                var answer= response.data;
                me.dataTicktes = answer.Tickets.data;
                me.date = answer.Date;
                me.dataGame =me.get_games();
                me.dataDays = answer.Days;
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
             var url = '/winners/add'
             var data = {
                'phone': this.phone,
                'game':this.game,
                'ticket_type':this.ticket_type,
                'total':me.total,
                'dataNumbers':me.dataNumbers,
                'dataNewDays':me.dataNewDays,
            };
            axios.post(url,data).then(function (response) {

                me.closeModal();
                var answer = response.data;
                me.message(answer);

                me.ListTickets('');
            }).catch(function (error) {
                console.log(error.response.data.errors);
                var e = error.response.data.errors;
                me.erros(e);
            })              
        },
        DeleteOrRestore(item){
            let me = this;
            var data = {
                'id': item.id,
                };
             var m = "¿Deseas Eliminar el Ticket?";
             var mt = "El Ticket sera eliminado";
             var btn = "eliminalo";


            if(item.deleted_at != null){
                 m = "¿Deseas Recuperar el Ticket?";
                 mt = "El Ticket sera recuperado";
                 btn = "recuperalo";
            }

                Swal.fire({
                    title: m,
                    text:  mt,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, '+btn+'!'
                }).then((result) => {
                    if (result.value) {
                         axios.post('/winners/deleteOrResotore',data).then(function (response) {
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
            var id = item.id;
            var type = item.type?item.type:1;
            
             var m = "¿Deseas confirmar que el Ticket Gano?";
             var mt = "Selecione la posicion del numero ganador";
             var btn = "conviertelo";
             var dataSW={ 
                title: m,
                text:  mt,
                icon: 'warning',
                input: 'select',
                inputOptions: {
                  '1': '1er',
                  '2': '2do',
                },
                inputValue:type,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, '+btn+'!'
            }


            if(item.winner == 1){
                 m = "¿Deseas cancelar que el Gane del Ticket?";
                 mt = "El Gane del ticket sera cancelado";
                 btn = "cancelalo";

                 dataSW={ 
                    title: m,
                    text:  mt,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, '+btn+'!'
                }

                 
            }
             Swal.fire(dataSW).then((result) => {
                    var data ={'id':id, 'type_game':result.value?result.value:0}
                    if (result.value) {
                         axios.post('/winners/win',data).then(function (response) {
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
                            this.multiplier = 0;
                            this.mTotal = 0;
                            this.ticket_type = '1';
                            this.dataNumbers =[];
                            this.dataNewDays = [];
                            this.action = 1;

                            $("#myModal").modal('show');
                            break;
                        }
                        case 'detail':
                        {   let me = this;
                            me.action = 2;
                            axios.get('/tickets/detail?id='+data.ticket_id).then(function (response) {
                                var answer = response.data;
                                console.log(answer)
                                me.titleModal = 'Info Ticket Numero: '+answer.ticket.id;
                                me.dataNumbers = answer.ticketDetail;
                                me.total = answer.ticket.total;
                                me.phone =answer.client.phone;
                                me.dataNewDays = answer.days;
                                //me.games.answer.gamet.name;
                              
                                $('#send-text').html(`<a class="btn btn-block btn-success text-white" href="https://wa.me/52${answer.client.phone}" target="_blank" style="color:#000;">
                                                        Enviar mensaje &nbsp; 
                                                        <i style="font-size:18px;" class="fa fa-mobile-phone"></i>
                                                    </a>`);
                                
                               
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
                this.multiplier = 0;
                this.mTotal = 0;
                this.ticket_type = '1';
                this.subtotal = '';
                this.dataNumbers =[];
                this.dataNewDays = [];
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
                    me.message({title:'Error',text:'El campo Inversion debe ser menor a 500.00',type:'danger'});
                    return false;
                }else if(this.subtotal <= 0){
                    me.message({title:'Error',text:'El campo Inversion debe ser mayor a 0',type:'danger'});
                    return false;
                }

                if(this.dataNumbers.push({
                    number: this.number,
                    subtotal: Number.parseFloat(this.subtotal),
                }))
                {   
                    let sumtotal = me.total > 0 
                                    ? parseFloat(me.mTotal) + parseFloat(this.subtotal) 
                                    : parseFloat(this.subtotal);
                    
                    this.mTotal = sumtotal;

                    let multipliert = me.dataNewDays.length > 0 
                                    ? parseFloat(me.mTotal) * parseFloat(me.dataNewDays.length) 
                                    : parseFloat(me.mTotal);
                                    
                    this.total = parseFloat(multipliert);

                    this.number = ''
                    this.subtotal = ''
                    me.message({title:'Listo',text:'Se AGREGO con exito el Numero',type:'success'});
                }
                


               
        },
        removeNumber(index){
            let me = this;
            let sub = this.dataNumbers[index];
            let sumtotal = me.total > 0 
                                    ? parseFloat(me.mTotal) - parseFloat(sub.subtotal)
                                    : 0;
                    
            this.mTotal = sumtotal;

            let multipliert = me.dataNewDays.length > 0 
                                    ? parseFloat(me.mTotal) * parseFloat(me.dataNewDays.length) 
                                    : parseFloat(me.mTotal);
                                    
            this.total = parseFloat(multipliert);

             if(this.dataNumbers.splice(index, 1))
             {
                 me.message({title:'Listo',text:'Se ELIMINO con exito el Numero',type:'success'});
             }
               
        },
        addDay() {
            let me = this;
            let uniqueNames = 0;
            let day = this.day.id;
            
            $.each(me.dataNewDays, function(i, el){
                if(el.day.id == day){
                    uniqueNames +=1;
                }
            });
            
            if(uniqueNames > 0){
                me.message({title:'Error',text:'El Dia no se puede repetir',type:'danger'});
                return false;

            }
            
            if(this.day.length == 0){
                    me.message({title:'Error',text:'El campo Dia es requerido',type:'danger'});
                    return false;

            }

            if(this.dataNewDays.push({
                    day: this.day,
                }))
                {   

                    
                    let multipliert = me.dataNewDays.length > 0 
                    ? parseFloat(me.mTotal) * parseFloat(me.dataNewDays.length) 
                    : parseFloat(me.mTotal)*1;
                    
                    this.total = parseFloat(multipliert);

                     this.day = ''
                    me.message({title:'Listo',text:'Se AGREGO con exito el Dia',type:'success'});
                }
                


               
        },
        removeDay(index){
            let me = this;
             if(this.dataNewDays.splice(index, 1))
             {  
                let multipliert = me.dataNewDays.length > 0 
                    ? parseFloat(me.mTotal) * parseFloat(me.dataNewDays.length) 
                    : parseFloat(me.mTotal)*1;
                    
                    this.total = parseFloat(multipliert);

                me.message({title:'Listo',text:'Se ELIMINO con exito el Dia',type:'success'});
             }
               
        },
        get_numbers(){
            let me = this;
            var id = this.game.id; 
            axios.get('/gameSchD/detail?id='+id).then(function (response) {
                var answer = response.data;
                me.dataGamesDetail = answer;
                me.game_detail ='0';
            }).catch(function (error) {});

        }, 
        get_games(){
            let me = this;
            var date = this.date; 
            axios.get('/gameSch/detail?date='+date).then(function (response) {
                var answer = response.data;
                me.dataGames = answer;

            }).catch(function (error) {});

        }

    },
    mounted () {
       this.ListTickets(1);
    }
}