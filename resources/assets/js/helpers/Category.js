export default class Category {
        constructor(){
        
        }

        getUserCategories(){
                return axios.get('/categories')
        }

        store(data){
                return axios.post('/categories', data)
        }
}