export default {
        data () {
            return {
            dataUsers:[],
            dataTicktes:[],
            date:'',
            tickets_pay_off:0,
            tickets_not_pay_off:0,
            incomes:0,
            not_pay:0,
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
            criterion : 'day',
            prize : 0,
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
            ListHome(page){
                let me = this;
                var url = '/home?page='+page+'&criterion='+this.criterion+'&date='+this.date;
                 axios.get(url)
                .then(function (response) {
                    var respuesta= response.data;
                    console.log(respuesta)
                    //me.dataUsers = respuesta.TableTickets.Tikets.data;
                    me.tickets_pay_off = respuesta.tickets_pay_off;
                    me.tickets_not_pay_off = respuesta.tickets_not_pay_off;
                    me.incomes = respuesta.incomes.total_bet;
                    me.not_pay = respuesta.not_pay.total_bet;
                    me.prize = respuesta.prizes.total_prize;
                    me.dataTicktes = respuesta.TableTickets.Tickets.data;
                    me.date = respuesta.date;
                    me.pagination = respuesta.TableTickets.pagination;
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
                me.ListHome(page)
            },
           openModal(model,action, data = ''){
               switch(model){
                   case 'modal':
                   {
                       switch(action){
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
                                   me.dataNewDays = answer.days;
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
        },
        mounted () {
           this.ListHome(1);
        }
    }

