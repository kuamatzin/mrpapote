<template>
    <tr>
        <td v-if="edit == false">{{product.name}}</td>
        <td v-else>
            <div class="field">
              <div class="control">
                <input class="input is-primary" type="text" placeholder="Nombre" v-model="name" required @keydown="errors.clear('name')">

                <span class="help is-danger" v-if="errors.get('name')" v-text="errors.get('name')"></span>
              </div>
            </div>
        </td>
        <td v-if="edit == false">{{product.description}}</td>
        <td v-else>
            <div class="field">
              <div class="control">
                <input class="input is-primary" type="text" placeholder="Descripción" v-model="description" @keydown="errors.clear('description')">

                <span class="help is-danger" v-if="errors.get('description')" v-text="errors.get('description')"></span>
              </div>
            </div>
        </td>
        <td v-if="edit == false">${{product.price}}</td>
        <td v-else>
            <div class="field">
              <div class="control">
                <input class="input is-primary" type="text" placeholder="Precio" v-model="price" required @keydown="errors.clear('price')">

                <span class="help is-danger" v-if="errors.get('price')" v-text="errors.get('price')"></span>
              </div>
            </div>
            <!-- Save or Cancel -->
            <div class="field is-grouped">
              <p class="control">
                <button class="button is-primary is-small" @click="updateProduct" :disabled="errors.any()">
                  Actualizar
                </button>
              </p>
              <p class="control">
                <a class="button is-small"  @click="cancel">
                  Cancelar
                </a>
              </p>
            </div>
        </td>
        <td>
            <button class="button is-primary" @click="personalizeProduct">
                Ingredientes
            </button>
        </td>
        <td>
            <button class="button is-warning" @click="edit = true">
                <i class="fa fa-pencil" aria-hidden="true" style="color:white"></i>
            </button>
        </td>
        <td>
            <button class="button is-danger" @click="deleteProduct">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
            </button>
        </td>
    </tr>
</template>

<script>
    import sweetalert from 'sweetalert'
    import Errors from '../helpers/Errors'

    export default {
        props: ['original_data', 'index'],
        
        watch: {
            original_data(){
                this.product = this.original_data
                this.name = this.original_data.name
                this.description = this.original_data.description
                this.price = this.original_data.price
            }
        },

        data(){
            return {
                edit: false,
                errors: new Errors(),
                product: this.original_data,
                name: this.original_data.name,
                description: this.original_data.description,
                price: this.original_data.price
            }
        },

        methods: {
            updateProduct(){
                axios.put('/products/' + this.product.id, { name: this.name, description: this.description, price: this.price }).then(({data}) => {
                    this.product.name = this.name
                    this.product.description = this.description
                    this.product.price = this.price
                    this.edit = false
                }, error => this.errors.record(error.response.data))
            },

            deleteProduct(){
                swal({
                  title: "¿Estás seguro?",
                  text: "No podrás recuperar los datos de " + this.product.name,
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  cancelButtonText: 'Cancelar',
                  confirmButtonText: "Estoy seguro",
                  closeOnConfirm: true
                },
                () => axios.delete('/products/' + this.product.id).then(data => this.$emit('productDeleted', this.index))
                )
            },
            cancel(){
                this.edit = false
                this.name = this.original_data.name
                this.description = this.original_data.description
                this.price = this.original_data.price
                this.errors.clearAll()
            },
            personalizeProduct(){
                this.$emit('personalizeProduct', this.product)
            }
        }
    }
</script>