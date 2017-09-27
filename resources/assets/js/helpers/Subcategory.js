export default class Subcategory {
        constructor(){

        }

        getByCategory(category_id){
                return axios.get('/subcategories/findByCategory/' + category_id)       
        }

        store(data){
                return axios.post('/subcategories', data)
        }

        update(subcategory_id, data){
                return axios.put('/products/' + product_id + '/ingredients', data)
        }
}