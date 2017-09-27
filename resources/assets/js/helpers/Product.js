export default class Product {
        constructor(){

        }

        getBySubcategory(subcategory_id){
                return axios.get('/products/findBySubcategory/' + subcategory_id)
        }
}