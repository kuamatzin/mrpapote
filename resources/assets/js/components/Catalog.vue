<template>
    <div>
        <nav class="breadcrumb" aria-label="breadcrumbs">
          <ul>
            <li :class="{ 'is-active' : active_section == 1}" @click="active_section = 1"><a href="#">Menú</a></li>
            <li :class="{ 'is-active' : active_section == 2}" @click="active_section = 2"><a href="#">Ingredientes</a></li>
          </ul>
        </nav>
        <div v-if="active_section == 1">
            <section class="hero is-primary">
              <div class="hero-body">
                <div class="container">
                  <h1 class="title animated bounceInDown">
                    Menú
                  </h1>
                  <h3 class="subtitle animated bounceInRight">
                    Administra tus categorías, subcategorías y productos en la siguiente sección
                  </h3>
                </div>
              </div>
            </section>
            <div class="tabs is-boxed">
              <ul>
                <li v-for="(category, index) in categories" @click="getSubcategories(category.id, index)" :class="{ 'is-active': tab_index == index }">
                  <a>
                    <span>
                    {{category.name}} 
                    </span>
                  </a>
                </li>
                <li>
                    <a>
                    <span>
                        <a class="button is-primary" @click="addCategory">Agregar&nbsp;<i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                    </span>
                  </a>
                </li>
              </ul>
            </div>
            
            <div v-if="subcategories.length <= 0 && addingSubcategory == false" style="width:100%" class="has-text-centered">
                <br>
                <h3 class="has-text-centered">Aun no existen subcategorias</h3>
                <button class="button is-large is-primary" @click="addingSubcategory = true">
                    <i class="fa fa-plus-circle icon is-large" aria-hidden="true"></i>
                </button>
            </div>
            <div class="columns animated bounceInRight" id="subcategories">
                
                <!-- List of subcategories -->
                <div class="column has-text-centered" v-if="subcategories.length" v-for="(subcategory, index) in subcategories">

                    <subcategory :subcategory="subcategory" :index="index" @subcategoryUpdated="subcategoryUpdated" @getProducts="getProducts"></subcategory>   


                </div>

                <!-- Adding Subcategory -->
                <div class="column has-text-centered animated bounceInRight" v-if="addingSubcategory">
                    <img src="https://cdn2.iconfinder.com/data/icons/flat-icons-19/128/Burger.png">
                    <div class="field">
                      <div class="control">
                        <input class="input" type="text" placeholder="Subcategoría" v-model="subcategory_name">
                      </div>
                    </div>
                    <div class="field is-grouped">
                      <p class="control">
                        <a class="button is-primary" @click="addSubcategory">Guardar</a>
                      </p>
                      <p class="control">
                        <a class="button" @click="addingSubcategory = false">Cancelar</a>
                      </p>
                    </div>
                </div>



                <!-- Button to add subcategory -->
                <div class="column has-text-centered hero-body" v-if="subcategories.length">
                    <button class="button is-large is-primary" @click="addingSubcategory = true">
                        <i class="fa fa-plus-circle icon is-large" aria-hidden="true"></i>
                    </button>
                </div>
            </div>  
        
            <table class="table animated bounceInRight" id="products">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <!--
                        <th>Activo</th>
                        -->
                        <th>Ingredientes</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>

                    <!-- Product Component -->
                    <tr v-for="(product, index) in products" is="product" :original_data="product" :index="index" @productDeleted="productDeleted" @personalizeProduct="personalizeProduct"></tr>

                    <!--  Add Product -->
                    <tr is="add-product" @productAdded="productAdded" :subcategory_id="active_subcategory"></tr>

                </tbody>
            </table>

            <add-category :activeModal="activeModal" @closeModal="closeModal" @newCategory="newCategory"></add-category> 

            <personalize-product :activeModal="activeModalPersonalizeProduct" @closeModal="closeModal" :personalize_product="personalize_product" @getProductPersonalized="getProductPersonalized" :button="'Guardar ingredientes'"></personalize-product>
        </div>

        <!-- Ingredients -->
        <div v-if="active_section == 2">
            <section class="hero is-primary">
              <div class="hero-body">
                <div class="container">
                  <h1 class="title animated bounceInDown">
                    Ingredientes
                  </h1>
                  <h3 class="subtitle animated bounceInRight">
                    Administra tus tus ingredientes en la siguiente sección
                  </h3>
                </div>
              </div>
            </section>

            <ingredients></ingredients>
        </div>

        <flash></flash>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.getCategories().then(data => {
                this.getSubcategories(data[0].id, 0)
            })
        },
        data() {
            return {
                active_section: 1,
                addingSubcategory: false,
                categories: '',
                subcategories: '',
                products: 0,
                tab_index: 0,
                activeModal: false,
                activeModalPersonalizeProduct: false,
                personalize_product: '',
                active_category: '',
                active_subcategory: ''
            }
        },
        methods: {
            getCategories(){
                return axios.get('/categories').then(({data}) => this.categories = data)
            },
            getSubcategories(id, index){
                this.active_category = id
                this.products = ''
                this.addingSubcategory = false
                axios.get('/subcategories/findByCategory/' + id).then(({data}) => this.subcategories = data)
                this.tab_index = index
                this.resetAnimation('subcategories', 'bounceInRight')
            },
            getProducts(id){
                this.active_subcategory = id;
                axios.get('/products/findBySubcategory/' + id).then(({data}) => {
                    this.products = data
                    this.products.length <= 0 ? this.resetAnimation('noProducts', 'pulse') : this.resetAnimation('products', 'bounceInRight')  
                })
            },
            addCategory(){
                this.activeModal = true
            },  
            addSubcategory(){
                axios.post('/subcategories', { category_id: this.active_category, name: this.subcategory_name }).then(data => {
                    this.getSubcategories(this.active_category, this.tab_index)
                    this.addingSubcategory = false
                });       
            },
            closeModal(){
                this.activeModal = false
                this.activeModalPersonalizeProduct = false
            },
            newCategory(){
                this.getCategories()
            },
            resetAnimation(div_id, animation){
                $('#' + div_id).removeClass("animated " + animation)
                setTimeout(() => $('#' + div_id).addClass("animated " + animation), 10)
            },
            subcategoryUpdated(name, index){
                this.subcategories[index].name = name;
            },
            productDeleted(index){
                this.products.splice(index, 1);
            },
            productAdded(new_product){
                console.log(new_product)
                this.products.push(new_product)
            },
            personalizeProduct(product){
                this.personalize_product = product
                this.activeModalPersonalizeProduct = true
            },
            getProductPersonalized(product){
                this.updateProductIngredients(product.id, product.product_ingredients)
            },
            updateProductIngredients(product_id, ingredients){
                axios.put('/products/' + product_id + '/ingredients', { ingredients: ingredients }).then((data) => {
                    flash('Ingredientes actualizados', 'success')})
            }
        }
    }
</script>