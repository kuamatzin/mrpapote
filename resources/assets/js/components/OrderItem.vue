<template>
    <tr>
        <td>{{indx+1}}</td>
        <td style="color: #00d1b2">{{order.name}}</td>
        <td>
            <a class="button" :class="{ 'is-primary': order.delivered, 'is-loading': loading_delivered }" @click="updateDelivered">
                <i class="fa fa-check-square-o" aria-hidden="true" v-if="order.delivered"></i>
                <i class="fa fa-square-o" aria-hidden="true" v-else></i>
            </a>
        </td>
        <td>
            <a class="button" :class="{ 'is-primary': order.payed, 'is-loading': loading_payed }" @click="updatePayed">
                <i class="fa fa-check-square-o" aria-hidden="true" v-if="order.payed"></i>
                <i class="fa fa-square-o" aria-hidden="true" v-else></i>
            </a>
        </td>
        <td>${{order.total}}</td>
        <td>{{formatDate(order.created_at)}}</td>
        <td>
            <a class="button is-primary" @click="updateOrder()">Detalles</a>
        </td>
        <td>
            <a class="button is-danger" @click="deleteOrder">Eliminar</a>
        </td>
    </tr>
</template>

<script>
    import moment from 'moment'
    moment.locale('es');
    export default {
        props: ['order', 'index'],

        data(){
            return {
                update: false,
                new_order: false,
                loading_payed: false,
                loading_delivered: false,
                indx: parseInt(this.index)
            }
        },

        methods: {
            formatDate(date){
                return moment(date).fromNow()
            },
            updatePayed(){
                this.loading_payed = true
                axios.put('/orders/updatePayed/' +  this.order.id, { payed: this.order.payed }).then(({data}) => {
                    this.order.payed = !this.order.payed
                    this.loading_payed = false
                });
            },
            updateDelivered(){
                this.loading_delivered = true
                axios.put('/orders/updateDelivered/' +  this.order.id, { delivered: this.order.delivered }).then(({data}) => {
                    this.order.delivered = !this.order.delivered
                    this.loading_delivered = false
                });
            },
            updateOrder(order){
                this.$emit('updateOrder', this.order)
            },
            deleteOrder(){
                axios.delete('/orders/' + this.order.id).then(data => this.$emit('orderDeleted', this.indx))
            }
        }

    }
</script>