export default class Expense {
        constructor(){

        }

        getByMonth(month){
                return axios.get('/expenses/getByMonth?month=' + month)
        }

        update(id, data){
                return axios.post('/expenses/' + id, data)
        }

        delete(id){
                return axios.delete('/expenses/' + id)
        }
}