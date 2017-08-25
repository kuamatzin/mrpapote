<template>
    <div>
        <div class="modal" :class="{ 'is-active': active }">
          <div class="modal-background"></div>
          <div class="modal-card">
            <header class="modal-card-head">
              <p class="modal-card-title">{{personalize_product.name}}</p>
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
        props: ['activeModal', 'personalize_product', 'button'],
        computed: {
           chunkedIngredients () {
             return _.chunk(this.ingredients, Math.round(this.ingredients.length/3))
           },
           chunkedProductIngredients () {
             return _.chunk(this.product_ingredients, Math.round(this.product_ingredients.length/2))
           }
        },
        watch: {
            personalize_product(){
                this.original_data = this.personalize_product
                this.original_ingredients = this.personalize_product.ingredients
            },
            /*
            product_ingredients() {
                this.$forceUpdate();
            },
            */
            activeModal() {
                this.active = !this.active;
                this.active ? this.getIngredients() : 0
            }
        },
        data() {
            return {
                original_data: '',
                active: this.activeModal,
                ingredients: '', 
                product_ingredients: ''
            }
        },
        methods: {
            closeModal(){
                this.$emit('closeModal');
            },
            getIngredients(){
                this.getOriginalIngredients()
                axios.get('/ingredients').then(({data}) => {
                    this.ingredients = data
                });
            },
            getOriginalIngredients(){
                axios.get('products/' + this.personalize_product.id + '/ingredients').then(({data}) => this.product_ingredients = data)
            },
            addIngredient(ingredient){
                this.product_ingredients.push(ingredient)
            },
            removeFromProductIngredients(ingredient){
                let index = _.findIndex(this.product_ingredients, ingredientItem => { return ingredientItem.id == ingredient.id })
                this.product_ingredients.splice(index, 1);
            },
            createProduct(){
                this.original_data.product_ingredients = this.product_ingredients
                this.original_data.quantity = 1
                this.original_data.personalizable = true
                this.product_ingredients = []
                this.$emit('getProductPersonalized', this.original_data)
                this.closeModal()
            }
        }
    }
</script>