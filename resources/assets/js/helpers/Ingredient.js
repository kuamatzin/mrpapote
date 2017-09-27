export default class Ingredient {
        constructor(){

        }

        getUserIngredients(){
                return axios.get('/ingredients')
        }
}