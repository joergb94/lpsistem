export default {
    data () {
        return {
        dataUsers:[],
        dataGames:[],
        id:'',
        number_win:'',
        number_win2:'',
        game_id:'',
        date:'',
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
        criterion : 'date',
        status : 1,
        search : '',
        dateS : '',

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
        ListUsers(page){
            let me = this;
            var url = '/schedule?page='+page+'&search='+this.search+'&criterion='+this.criterion+'&status='+this.status;
             axios.get(url)
            .then(function (response) {
                var respuesta= response.data;
                me.dataUsers = respuesta.Game_schedules.data;
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
             var url = '/schedule/add'
             var data = {
                    'id': this.id,
                    'number_win': this.number_win?this.number_win:'',
                    'number_win2': this.number_win2?this.number_win:'',
                    'game_id': this.game_id,
                    'date':this.date
            };

            if (action == 2){
                url = '/schedule/update'    
                var data = {
                    'id': this.id,
                    'number_win': this.number_win?this.number_win:'',
                    'number_win2': this.number_win2?this.number_win:'',
                    'game_id': this.game_id,
                    'date':this.date
                };
            }
           
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
             var m = "¿Estas segurop  que deseas eliminar la juego programado?";
             var mt = "Se eliminara la programcion";
             var btn = "Eliminalo";


            if(item.deleted_at != null){
                 m = "¿Estas segurop  que deseas restaurar la juego programado?";
                 mt = "Se restaura la programcion";
                 btn = "Restauralo";
            }

                Swal.fire({
                    title: m,
                    text:  mt,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si,'+btn+'!'
                }).then((result) => {
                    if (result.value) {
                         axios.post('/schedule/deleteOrResotore',data).then(function (response) {
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
           console.table(data)
            switch(model){
                case 'modal':
                {
                    switch(action){
                        case 'add':
                        {
                            this.titleModal = 'Programar juego';
                            this.number_win= '';
                            this.number_win2= '';
                            this.game_id = '';
                            this.date = '';
                            this.action = 1;
                            break;
                        }
                        case 'update':
                        {
                            
                            this.titleModal = 'Modificar Programacion del juego';
                            this.id= data.id;
                            this.number_win= data.game_schedule_details.length > 0?data.game_schedule_details[0].number_win:'';
                            this.number_win2= data.game_schedule_details.length > 1?data.game_schedule_details[1].number_win:'';
                            this.game_id =data.game_id;
                            this.date =data.date;
                            this.action = 2;
                            break;
                        }
                    }
                    $("#myModal").modal('show');
                }
            }
        },
        closeModal(){
                this.titleModal = '';
                this.number_win= '';
                this.number_win2= '';
                this.game_id = '';
                this.date = '';
                 $.notifyClose();
                $("#myModal").modal('hide');
        },
    },
    mounted () {
       this.ListUsers(1);
    }
}