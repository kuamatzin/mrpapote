<template>
    <div class="container">
        <div class="columns">
            <div class="column is-5">
                <div class="card">
                  <header class="card-header">
                    <p class="card-header-title is-3">
                      Orden de {{name}}
                    </p>
                    <span class="tag is-primary is-large" style="margin-right: 20px; margin-top: 12px" v-if="total_price">Total: ${{total_price}}</span>
                  </header>
                  <div class="card-content">
                    <div class="content">
                      <div v-if="order_products.length > 0">
                          <table class="table">
                              <thead>
                                  <tr>
                                      <th>Nombre</th>
                                      <th>Cantidad</th>
                                      <th>Precio</th>
                                      <th></th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr v-for="order_product in order_products">
                                      <td v-if="order_product.product_id">{{order_product.product.name}}</td>
                                      <td v-else>{{order_product.name}}</td>
                                      <td>{{order_product.quantity}}</td>
                                      <td v-if="order_product.product_id">${{order_product.product.price}}</td>
                                      <td v-else>${{order_product.price}}</td>
                                      <td>
                                          <a class="button is-danger is-outlined" @click="removeProductFromOrder(order_product)">Quitar</a>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                      <div v-else class="has-text-centered">
                          <h4>No hay productos en la orden</h4>
                      </div>
                    </div>
                    
                  </div>
                  <footer class="card-footer">
                    <a class="card-footer-item" @click="saveOrder">Guardar</a>
                  </footer>
                </div>
            </div>

            <div class="column is-7">
                <div class="field">
                  <label class="label">Nombre</label>
                  <div class="control">
                    <input class="input" type="text" v-model="name">
                  </div>
                </div>
                <hr>
                
                <h3 class="panel-heading">Menú</h3>

                <div class="tabs is-boxed">
                  <ul>
                    <li v-for="(category, index) in categories" @click="getSubcategories(category.id, index)" :class="{ 'is-active': tab_index == index }">
                      <a>
                        <span>{{category.name}}</span>
                      </a>
                    </li>
                  </ul>
                </div>

                <div class="columns">
                    <div class="column has-text-centered" v-for="subcategory in subcategories" @click="getProducts(subcategory.id)">
                        <img src="https://cdn2.iconfinder.com/data/icons/flat-icons-19/128/Burger.png">
                        <p>{{subcategory.name}}</p>
                    </div>
                </div>  

                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody v-for="product in products">
                        <tr>
                            <td>{{product.name}}</td>
                            <td>${{product.price}}</td>
                            <td></td>
                            <td>
                                <a class="button is-success is-outlined" @click="addProductToOrder(product, product.subcategory.category.name)">Agregar a orden</a>
                            </td>
                            <td>
                                <a class="button is-info is-outlined" @click="personalizeProduct(product)">Personalizar</a>
                            </td>
                        </tr>
                    </tbody>
                </table>        
            </div>
        </div>

        <personalize-product :activeModal="activeModal" @closeModal="closeModal" :personalize_product="personalize_product" @getProductPersonalized="getProductPersonalized" :button="'Agregar a la orden'"></personalize-product>

    </div>
</template>

<script>
    import sweetalert from 'sweetalert'

    export default {
        props: ['active_order', 'update'],
        watch: {
          active_order(order){
            //Show ther order
            this.setDataOrder()
          }
        },
        mounted() {
            this.getCategories()
        },
        data() {
            return {
                name: '',
                categories: '',
                subcategories: '',
                products: '',
                total_price: 0,
                order_products: [],
                tab_index: 999,
                activeModal: false,
                personalize_product: ''
            }
        },
        methods: {
            setDataOrder(){
              this.getCategories()
              this.name = this.active_order.name
              this.total_price = this.active_order.total
              this.getDataOrder()
            },
            getDataOrder(){
              axios.get('/orders/' + this.active_order.id).then(({data}) => {
                this.order_products = data.products
              })
            },
            getCategories(){
                axios.get('/categories').then(({data}) => {
                  this.categories = data
                  this.categories ? this.getSubcategories(this.categories[0].id, 0) : false
                })
            },
            getSubcategories(id, index){
                axios.get('/subcategories/findByCategory/' + id).then(({data}) => this.subcategories = data)
                this.tab_index = index
            },
            getProducts(id){
                axios.get('/products/findBySubcategory/' + id).then(({data}) => this.products = data)
            },
            addProductToOrder(product, category){
                let index = _.findIndex(this.order_products, productItem => { return productItem.id == product.id })
                //Product added before, need to update quantity and price
                if(index > -1) {
                    this.order_products[index].quantity ++
                }
                //New product in the order
                else {
                    product.quantity = 1
                    product.personalizable = false
                    this.order_products.push(product)
                }
                this.updatePrice(product.price)
            },
            removeProductFromOrder(product){
                let index = _.findIndex(this.order_products, productItem => { return productItem.id == product.id })
                //Just one item of the product in the order... remove it
                if (this.order_products[index].quantity == 1) {
                  this.order_products = _.reject(this.order_products, product)
                }
                //More than one item of the product, reduce the quantity by one
                else {
                  this.order_products[index].quantity --
                }  
                this.updatePrice(product.price, false)
            },
            updatePrice(price, sum = true){
                this.total_price = sum == true ? this.total_price + price : this.total_price - price;
            },
            personalizeProduct(product){
                this.activeModal = true
                this.personalize_product = product
            },
            getProductPersonalized(product){
              this.order_products.push(product)
              this.updatePrice(product.price)
            },
            saveOrder(){
              if (this.order_products.length) {
                this.update ? this.updateOrder() : this.createOrder();
              }
              else {
               swal("Espera", "Agrega productos a la orden", "warning")
              }
            },
            createOrder(){
              axios.post('/orders', { name: this.name, total: this.total_price, order_products: this.order_products }).then(({data}) => {
                this.resetValues()
                this.$emit('orderCreated')
              });
            },
            updateOrder(){
              axios.put('/orders/' + this.active_order.id, { name: this.name, total: this.total_price, order_products: this.order_products }).then(({data}) => {
                this.resetValues()
                this.$emit('orderCreated')
              });
            },
            resetValues(){
                this.name = ''
                this.categories = '',
                this.subcategories = ''
                this.products = ''
                this.total_price = ''
                this.order_products = []
                this.tab_index = 999
            },
            closeModal(){
                this.activeModal = false
            }
        }
    }
</script>
