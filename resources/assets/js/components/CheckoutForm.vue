<template>
    <div>
        <form method="POST">
            <input type="hidden" v-model="stripeToken">
            <input type="hidden" v-model="stripeEmail">

            <button type="submit" @click.prevent="getToken">Subscribe</button>
        </form>
    </div>
</template>

<script>
    export default {
        props: [],

        mounted(){
            this.stripe = StripeCheckout.configure({
              key: App.stripeKey,
              image: '/img/logo.png',
              locale: 'es',
              token: token => {
                this.stripeToken = token.id
                this.stripeEmail = token.email
                this.subscribe()
              }
            });
        },

        data(){
            return {
                stripe: '',
                stripeEmail: '',
                stripeToken: ''
            }
        },

        methods: {
            getToken(){
                this.stripe.open({
                    name: 'Premium',
                    description: 'Conviertete en premium',
                    zipCode: false,
                    currency: 'mxn',
                    email: App.user.email,
                    amount: 9900
                })
            },
            subscribe(){
                axios.post('subscribe/gold', {stripeToken: this.stripeToken, stripeEmail: this.stripeEmail }).then(({data}) => console.log(data))
            }
        }
    }
</script>