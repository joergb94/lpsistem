/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
require('./bootstrap-notify');


 

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('admin-tickets-component', require('./components/tickets/TicketsRunnerComponent.vue').default);
Vue.component('management-tickets-component', require('./components/tickets/TicketsComponent.vue').default);
Vue.component('user-tickets-component', require('./components/tickets/UserTicketsComponent.vue').default);
Vue.component('user-component', require('./components/user/UsersComponent.vue').default);
Vue.component('profile-component', require('./components/user/ProfileComponent.vue').default);
Vue.component('home-component', require('./components/home/HomeComponent.vue').default);
Vue.component('deposit-component', require('./components/deposits/DepositComponent.vue').default);
Vue.component('schedule-component', require('./components/schedule/ScheduleComponent.vue').default);
Vue.component('winner-component', require('./components/winners/WinnersComponent.vue').default);




/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data:{
        menu: 0
    },
    methods :{
        general(){
            $.ajax({
                type: "GET",
                url:'/coins',
                success: function (data) {
                    console.log(data)
                    var coins = data['coins'] > 0 ? { class:'text-success', quantity:data['coins']}:{ class:'text-danger', quantity:'00.00'} ;
                    console.log(coins)
                    info = `<a class="nav-link ${coins['class']}" href="#">
                                <i class="fa fa-money"></i> 
                                    ${coins['quantity']}
                                </a>`
            
                    $("#coins-user").html(info); 
                },
                error: function (data) {
                    console.log('Error:', data);
                    $("#coins-user").html(`<a class="nav-link text-danger" href="#">
                                            <i class="fa fa-money"></i> 
                                                00.00
                                            </a>`);   
                }
            }); 
        }
    },
    mounted () {
        
     }
});


 