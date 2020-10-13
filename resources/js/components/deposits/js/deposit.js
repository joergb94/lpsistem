export default {
    data () {
        return {
        dataUsers:[],
        id:'',
        bank:'',
        numDep:'',
        amount:'',
        description:'',
        password:'',
        imageDep:'',
        imagenMin:'',
        type_user:'',
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
            var url = '/deposits?page='+page+'&search='+this.search+'&criterion='+this.criterion+'&status='+this.status;
             axios.get(url)
            .then(function (response) {
                var respuesta= response.data;
                me.dataUsers = respuesta.Users.data;
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
        onFileChanged(e){
            let file = e.target.files[0];
            let fileSize = e.target.files[0].size 
            let reader = new FileReader(); //El objeto FileReader permite que las aplicaciones web lean ficheros
            reader.onload = (event) => { // Este evento se activa cada vez que la operación de lectura se ha completado satisfactoriamente.
                this.imageDep = event.target.result
            };
            reader.readAsDataURL(file);
        },
        updateOrCreate(action){
            
             let me = this;
             var url = '/deposits/add'
             var data = {
                'bank': this.bank,
                'numDep': this.numDep,
                'imageDep': this.imageDep,
                'amount': this.amount,
                'description':this.description,
                'type':this.type_user
            };

            if (action == 2){
                url = '/deposits/detail'
                var data = {
                    'id': this.id,
                    'name': this.name,
                    'description': this.description
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
                         axios.post('/deposits/deleteOrResotore',data).then(function (response) {
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
        changeStatus(item,nStatus){
            let me = this;
            var data = {
                'id': item.id,
                'nStatus': nStatus,
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
                         axios.post('/deposits/change_status',data).then(function (response) {
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
                            $('#imageDep').css('display','block');
                            $('#img-deposit').css('display','none');
                            $('#bank').prop("disabled", false);
                            $('#numDep').prop("disabled", false);
                            $('#amount').prop("disabled", false);
                            $('#description').prop("disabled", false);
                            this.titleModal = 'New Deposit';
                            this.bank= '';
                            this.numDep = '';
                            $('#imageDep').val('')
                            this.email = '';
                            this.amount = '';
                            this.description = '';
                            this.action = 1;
                            break;
                        }
                        case 'update':
                        {
                            
                            this.titleModal = 'View Deposit';
                            this.id = data.id;
                            $('#bank').prop("disabled", true);
                            $('#numDep').prop("disabled", true);
                            $('#amount').prop("disabled", true);
                            $('#description').prop("disabled", true);
                            this.numDep = data.numDep;
                            $("#bank option[value='"+data.bank+"']").prop("selected", true);
                            $('#imageDep').css('display','none');
                            $('#img-deposit').css('display','block');
                            $('#img-deposit').html('<img src="images/deposits/'+data.imageDep+'" style="display: block; margin-left: auto; margin-right: auto;" alt="Img" width="200" height="250">');
                            this.amount = data.amount;
                            this.description = data.description;
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
                this.description = '';
                 $.notifyClose();
                $("#myModal").modal('hide');
        },
    },
    mounted () {
       this.ListUsers(1);
    }
}