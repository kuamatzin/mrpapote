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

        <br><br>
        <a class="button is-primary is-outlined" @click="newOrder">Nueva Orden</a>
        <br><br>
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
                </tr>
            </thead>
            <tbody>
                <tr is="order-item" v-for="(order, index) in orders" :order="order" :index="index" @updateOrder="updateOrder"></tr>
            </tbody>
        </table>

        <h2>Total: ${{total_orders}}</h2>
    </div>
</div>
</template>

<script>
import moment from 'moment'
moment.locale('es');

export default {
    computed: {
        total_orders(){
            console.log("HEY")
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
        }
    }
}

</script>