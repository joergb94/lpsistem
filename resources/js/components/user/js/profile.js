export default {
        data () {
            return {
            dataUser:[],
            id:'',
            name:'',
            last_name:'',
            phone:'',
            email:'',
            password:'',
            password_confirmation:'',
            type_user:'',
            titleModal:'',
            action:0,
            offset : 3,
            criterion : 'name',
            status : 1,
            search : ''

            }
        },
        methods : {
            ListUsers(page){
                let me = this;
                var url = '/profile';
                 axios.get(url)
                .then(function (response) {
                    var respuesta= response.data;
                    me.dataUser = respuesta.Users;
                  
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
                 var url = '/profile/password'
                 var data = {
                    'id': this.id,
                    'password':this.password,
                    'password_confirmation':this.password_confirmation,  
                };

                if (action == 2){
                    url = '/profile/update'
                    var data = {
                            'id': this.id,
                            'name': this.name,
                            'last_name': this.last_name,
                            'phone': this.phone,
                            'email':this.email,
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
                             axios.post('/profile/deleteOrResotore',data).then(function (response) {
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
                             axios.post('/profile/change_status',data).then(function (response) {
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
                            case 'password':
                            {
                                this.titleModal = 'Cambiar Contraseña';
                                this.id = data.id;
                                this.password = '';
                                this.action = 1;
                                break;
                            }
                            case 'update':
                            {
                                
                                this.titleModal = 'Actualizar Informacion';
                                this.id = data.id;
                                this.name = data.name;
                                this.last_name = data.last_name;
                                this.phone = data.phone;
                                this.email = data.email;
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
                     this.name= '';
                     this.name = '';
                     this.phone = '';
                     this.email = '';
                     this.password = '';
                     this.action = '';
                     $.notifyClose();
                    $("#myModal").modal('hide');
            },
        },
        mounted () {
           this.ListUsers();
        }
    }
