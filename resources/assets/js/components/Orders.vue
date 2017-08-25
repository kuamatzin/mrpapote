<template>
<div>
    <create-order v-show="new_order" @orderCreated="orderCreated" :active_order="active_order" :update="update"></create-order>
    <div id="orders" v-show="new_order == false">
        <section class="hero is-primary">
          <div class="hero-body">
            <div class="container">
              <h1 class="title animated bounceInDown">
                Órdenes
              </h1>
              <h3 class="subtitle animated bounceInRight">
                {{today}}
              </h3>
            </div>
          </div>
        </section>
        <div class="card card-2" style="padding:20px">
            <div class="columns">
                <div class="column is-2">
                    <a class="button is-primary is-outlined" @click="newOrder">Nueva Orden</a>
                </div>
                <div class="column is-2">
                    <datepicker 
                        :value="date" 
                        placeholder="Selecciona Fecha" 
                        language="es" 
                        :inline="false" 
                        @selected="getOrdersByDate" 
                        :input-class="'input is-primary'">
                    </datepicker>
                </div>
            </div>       
            <table class="table animated bounceInUp">
                <thead>
                    <tr>
                        <th><abbr title="Position">Número</abbr></th>
                        <th><abbr title="Played">Nombre</abbr></th>
                        <th><abbr title="Drawn">Entregado</abbr></th>
                        <th>Pagado</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Detalles</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr is="order-item" v-for="(order, index) in orders" :order="order" :index="index" @updateOrder="updateOrder" @orderDeleted="orderDeleted"></tr>
                </tbody>
            </table>

            <h2>Total: ${{total_orders}}</h2>
        </div>
    </div>
</div>
</template>

<script>
import moment from 'moment'
import Datepicker from 'vuejs-datepicker'
moment.locale('es');

export default {
    components: { Datepicker },
    computed: {
        total_orders(){
            let total = 0
            for (var i = this.orders.length - 1; i >= 0; i--) {
                total += this.orders[i].total
            }
            return total
        }
    },
    mounted() {
        this.getOrders();
    },
    data() {
        return {
            date: new Date(),
            orders: '',
            active_order: '',
            update: false,
            new_order: false,
            today: moment().format('dddd D MMMM YYYY')
        }
    },
    methods: {
        getOrders(){
            axios.get('/orders').then(({data}) => this.orders = data)
        },
        orderCreated(){
            this.getOrders();
            this.new_order = false
            this.update = false
        },
        newOrder(){
            this.new_order = true
        },
        updateOrder(order){
            this.active_order = order
            this.update = true
            this.new_order = true
        },
        getOrdersByDate(date){
            this.datepicker = false;
            this.today = moment(date).format('dddd D MMMM YYYY')
            axios.get('/orders?date=' + moment(date).format('YYYY-MM-DD')).then(({data}) => this.orders = data)
        },
        orderDeleted(index){
            console.log(index)
            this.orders.splice(index, 1)
        }
    }
}

</script>