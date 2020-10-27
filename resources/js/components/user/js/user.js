  export default {
        data () {
            return {
            dataUsers:[],
            dataType:[],
            id:'',
            name:'',
            last_name:'',
            phone:'',
            email:'',
            password:'',
            password_confirmation:'',
            type:'',
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
            ListUsers(page){
                let me = this;
                var url = '/users?page='+page+'&search='+this.search+'&criterion='+this.criterion+'&status='+this.status;
                 axios.get(url)
                .then(function (response) {
                    var respuesta= response.data;
                    me.dataUsers = respuesta.Users.data;
                    me.dataType = respuesta.TypeUser;
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
                 var url = '/users/add'
                 var data = {
                    'name': this.name,
                    'last_name': this.last_name,
                    'phone': this.phone,
                    'email':this.email,
                    'password':this.password,
                    'password_confirmation':this.password_confirmation,
                    'type':this.type
                };

                if (action == 2){
                    url = '/users/update'
                    var data = {
                        'id': this.id,
                        'name': this.name,
                        'last_name': this.last_name,
                        'phone': this.phone,
                        'email':this.email,
                        'password':this.password,
                        'type':this.type
                    };
                }else if(action == 3){
                        var url = '/users/password'
                        var data = {
                            'id': this.id,
                            'password':this.password,
                            'password_confirmation':this.password_confirmation,
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
                                this.titleModal = 'New User';
                                this.name= '';
                                this.last_name = '';
                                this.type = '';
                                this.phone = '';
                                this.email = '';
                                this.password = '';
                                this.password_confirm ='',
                                this.action = 1;
                                break;
                            }
                            case 'update':
                            {  
                                this.titleModal = 'Update User';
                                this.id = data.id;
                                this.type = data.type_user_id;
                                this.name = data.name;
                                this.last_name = data.last_name;
                                this.phone = data.phone;
                                this.email = data.email;
                                this.password = data.password;
                                this.action = 2;
                                break;
                            }
                            case 'password':
                            {  
                                this.titleModal = 'Change password';
                                this.id = data.id;
                                this.password ='';
                                this.password_confirm ='',
                                this.action = 3;
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
                    this.type ='';
                    this.email = '';
                    this.password = '';
                    this.password_confirm ='',
                     $.notifyClose();
                    $("#myModal").modal('hide');
            },
        },
        mounted () {
           this.ListUsers(1);
        }
    }
