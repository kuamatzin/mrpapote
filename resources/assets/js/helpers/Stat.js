export default class Stat {
        constructor(){

        }

        getGeneral(start_month, end_month){
                return axios.get('statistics/generalStats?start_date=' + start_month + '&end_date=' + end_month)
        }

        getByProduct(product_id, start_month, end_month){
                return axios.get('/statistics/product/' + product_id + '?start_date=' + start_month + '&end_date=' + end_month)      
        }

        getBySubcategory(subcategory_id, start_month, end_month){
                return axios.get('/statistics/subcategory/' + subcategory_id + '?start_date=' + start_month + '&end_date=' + end_month)
        }

        getBySubcategoryWithAllProducts(subcategory_id, month){
                return axios.get('/statistics/subcategoryProducts/' + subcategory_id + '?month=' + month)
        }
}