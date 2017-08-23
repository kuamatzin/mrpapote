<template>
<div style="padding: 15px">
  <table class="table animated bounceInRight">
    <thead>
      <tr>
        <th></th>
        <th>Nombre</th>
        <th>Editar</th>
        <th>Eliminar</th>
      </tr>
    </thead>
    <tbody>
      <tr is="ingredient-item" v-for="(ingredient, index) in ingredients" :original_ingredient="ingredient" :index="index" @ingredientDeleted="ingredientDeleted"></tr>
      
      <!--  Add Product -->
      <tr is="add-ingredient" @newIngredientAdded="newIngredientAdded"></tr>
    </tbody>
  </table>
</div>
</template>

<script>
    import IngredientItem from './IngredientItem'
    import AddIngredient from './AddIngredient'

    export default {
        components: { IngredientItem, AddIngredient },

        mounted(){
          this.getIngredients()
        },
        data(){
            return {
              ingredients: ''
            }
        },

        methods: {
            getIngredients(){
              axios.get('/ingredients').then(({data}) => this.ingredients = data)
            },
            newIngredientAdded(ingredient){
              this.ingredients.push(ingredient);
            },
            ingredientDeleted(index){
              this.ingredients.splice(index, 1)
            }
        }
    }
</script>