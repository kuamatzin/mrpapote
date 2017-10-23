export default class Order {
        constructor(){

        }

        getProductsFromOrder(order_id){
                return axios.get('/orders/' + order_id)
        }

        store(data){
                return axios.post('/orders', data)
        }

        update(order_id, data){
                return axios.put('/orders/' + order_id, data)
        }

}