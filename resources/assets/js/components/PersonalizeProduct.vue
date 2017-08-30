<template>
    <div>
        <div class="modal" :class="{ 'is-active': active }">
          <div class="modal-background"></div>
          <div class="modal-card">
            <header class="modal-card-head">
              <p class="modal-card-title">{{creation.name}}</p>
              <button class="delete" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">
                <h3 class="has-text-centered">Ingredientes</h3>
                <div class="columns">
                    <div class="column is-4" v-for="chunk in chunkedIngredients">
                        <div v-for="ingredient in chunk" style="padding: 8px">
                            <button class="button is-primary is-outlined" style="width:100%" @click="addIngredient(ingredient)">{{ingredient.name}}</button>
                        </div>
                    </div>
                </div>
                <h3 class="has-text-centered">Producto Personalizado:</h3>
                <div class="columns">
                    <div class="column is-6" v-for="chunk in chunkedProductIngredients">
                        <div v-for="ingredient in chunk" style="padding: 10px">
                            <div class="tags has-addons">
                              <span class="tag is-primary is-medium">{{ingredient.name}}</span>
                              <a class="tag is-delete is-medium" @click="removeFromProductIngredients(ingredient)"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
              <a class="button is-success" @click="createProduct">{{this.button}}</a>
              <a class="button" @click="closeModal">Cancelar</a>
            </footer>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['activeModal', 'id', 'name', 'ingredients', 'price', 'button', 'index'],
        computed: {
           chunkedIngredients () {
             return _.chunk(this.all_ingredients, Math.round(this.all_ingredients.length/3))
           },
           chunkedProductIngredients () {
             return _.chunk(this.creation.ingredients, Math.round(this.creation.ingredients.length/2))
           }
        },
        watch: {
            activeModal() {
                this.active = !this.active;
                this.active ? this.getIngredients() : 0
                this.creation.ingredients = this.ingredients
                this.creation.id = this.id
                this.creation.name = this.index != 'false' ? this.name : this.name + ' personalizado'
                this.creation.ingredients = this.ingredients
                this.creation.quantity = 1
                this.creation.index = this.index
                this.creation.price = this.price
            }
        },
        data() {
            return {
                creation: {
                    id: '',
                    name: '',
                    ingredients: [], // Personalizable ingredients
                    personalizable: true,
                    quantity: 1,
                    price: 0
                },
                active: this.activeModal,
                all_ingredients: '', // All ingredients from database
            }
        },
        methods: {
            closeModal(){
                this.$emit('closeModal')
            },
            getIngredients(){
                axios.get('/ingredients').then(({data}) => {
                    this.all_ingredients = data
                });
            },
            addIngredient(ingredient){
                this.creation.ingredients.push(ingredient)
            },
            removeFromProductIngredients(ingredient){
                let index = _.findIndex(this.creation.ingredients, ingredientItem => { return ingredientItem.id == ingredient.id })
                this.creation.ingredients.splice(index, 1);
            },
            createProduct(){
                this.$emit('getProductPersonalized', this.creation)
                this.closeModal()
            },
            resetValues(){
                this.creation.id = ''
                this.creation.name= ''
                this.creation.ingredients = []
                this.creation.personalizable = ''
                this.creation.quantity = ''
                this.creation.price = ''
            }
        }
    }
</script>



