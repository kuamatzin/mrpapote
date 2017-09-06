<template>
<div class="container" v-cloak>
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
        <div class="container card card-2">
            <div class="tabs is-boxed">
                <ul>
                    <li v-for="(category, index) in categories" @click="getSubcategories(category.id, index)" :class="{ 'is-active': tab_category == index }">
                        <a>
                            <span>
                                {{category.name}}
                            </span>
                        </a>
                    </li>
                    <li v-if="add_category">
                            <a a style="margin-bottom: -3px">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="Nombre" v-model="category_name" required>
                                  </div>
                                </div>
                                <div class="field is-grouped is-pulled-right" style="padding-left:10px">
                                    <button class="button is-primary is-small" @click="addCategory">
                                      Guardar
                                    </button>
                                    <a class="button is-small"  @click="cancelAddCategory">
                                      Cancelar
                                    </a>
                                </div>
                            </a>
                        </li>
                    <li>
                        <a>
                            <span @click="add_category = true">
                                Agregar&nbsp; Categoría &nbsp;<i class="fa fa-plus-circle" aria-hidden="true"></i>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="animated bounceInRight" id="subcategories" v-if="categories.length">
                <!-- List of subcategories -->
                <div class="container">
                    <div class="tabs">
                      <ul>
                        <li v-for="(subcategory, index) in subcategories" :class="{ 'is-active' : tab_subcategory == index}"><a style="margin-bottom: -3px" @click="getProducts(subcategory.id, index)">{{subcategory.name}}</a></li>
                        <li v-if="add_subcategory">
                            <a a style="margin-bottom: -3px">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="Nombre" v-model="subcategory_name" required>
                                  </div>
                                </div>
                                <div class="field is-grouped is-pulled-right" style="padding-left:10px">
                                    <button class="button is-primary is-small" @click="addSubcategory">
                                      Guardar
                                    </button>
                                    <a class="button is-small"  @click="cancelAddSubcategory">
                                      Cancelar
                                    </a>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a style="margin-bottom: -3px" @click="add_subcategory = true" v-if="add_subcategory == false">
                                Agregar Subcategoría
                                &nbsp;
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            </a>
                        </li>
                      </ul>
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="products" v-if="subcategories.length">
                <br>
                <table class="table animated bounceInRight" id="products" v-if="active_subcategory != ''">
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
                        <tr v-for="(product, index) in products" is="product" :original_data="product" :index="index" @productDeleted="productDeleted" @personalizeProduct="personalizeProduct">
                        </tr>
                        <tr is="add-product" @productAdded="productAdded" :subcategory_id="active_subcategory">
                        </tr>
                    </tbody>
                </table>
            </div>

            <personalize-product
              :activeModal="activeModalPersonalizeProduct"
              :id="personalize_product.id"
              :name="personalize_product.name"
              :ingredients="personalize_product.ingredients"
              :price="personalize_product.price"
              :button="'Guardar ingredientes'"
              @getProductPersonalized="getProductPersonalized"
              @closeModal="closeModal">
            </personalize-product>
        </div>
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
        <div class="card card-2">
            <ingredients>
            </ingredients>
        </div>
    </div>
    <flash></flash>
</div>
</template>


<script>
    import PersonalizeProduct from './PersonalizeProduct.vue'
    import AddProduct from './AddProduct.vue'
    import Ingredients from './Ingredients.vue'
    import Product from './Product.vue'

    export default {
        components: { PersonalizeProduct, AddProduct, Ingredients, Product },

        mounted() {
            this.getCategories().then(data => {
                this.getSubcategories(data[0].id, 0)
            })
        },
        data() {
            return {
                active_section: 1,
                categories: '',
                subcategories: '',
                products: 0,
                tab_category: 1000,
                tab_subcategory: 1000,
                activeModal: false,
                activeModalPersonalizeProduct: false,
                personalize_product: '',
                active_category: '',
                active_subcategory: '',
                subcategory_name: '',
                category_name: '',
                add_subcategory: false,
                add_category: false
            }
        },
        methods: {
            getCategories(){
                return axios.get('/categories').then(({data}) => this.categories = data)
            },
            getSubcategories(category_id, index){
                this.active_subcategory = ''
                this.active_category = category_id
                this.products = ''
                this.add_subcategory = false
                axios.get('/subcategories/findByCategory/' + category_id).then(({data}) => {
                    this.subcategories = data
                    this.subcategories.length ? this.getProducts(index+1, 0) : 0
                })
                this.tab_category = index
                this.resetAnimation('subcategories', 'bounceInRight')
            },
            getProducts(subcategory_id, subcategory_index){
                this.tab_subcategory = subcategory_index
                this.active_subcategory = subcategory_id;
                axios.get('/products/findBySubcategory/' + subcategory_id).then(({data}) => {
                    this.products = data
                    this.products.length <= 0 ? this.resetAnimation('noProducts', 'pulse') : this.resetAnimation('products', 'bounceInRight')
                })
            },
            addCategory(){
                axios.post('/categories', { name: this.category_name }).then(({data}) => {
                    this.getCategories()
                    this.cancelAddCategory()
                });
            },
            addSubcategory(){
                axios.post('/subcategories', { category_id: this.active_category, name: this.subcategory_name }).then(data => {
                    this.subcategory_name = ''
                    this.getSubcategories(this.active_category, this.subcategories.length)
                    this.add_subcategory = false
                });
            },
            closeModal(){
                this.activeModal = false
                this.activeModalPersonalizeProduct = false
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
                this.updateProductIngredients(product.id, product.ingredients)
            },
            updateProductIngredients(product_id, ingredients){
                axios.put('/products/' + product_id + '/ingredients', { ingredients: ingredients }).then((data) => {
                    flash('Ingredientes actualizados', 'success')})
            },
            cancelAddSubcategory(){
                this.add_subcategory = false
                this.subcategory_name = ''
            },
            cancelAddCategory(){
                this.add_category = false
                this.category_name = ''
            }
        }
    }
</script>