<template>
    <tr>
        <td v-if="adding == false">
            <button class="button is-primary" @click="adding = true">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </button>
        </td>
        <td v-if="adding">
            <div class="field">
              <div class="control">
                <input class="input is-primary" type="text" placeholder="Nombre" v-model="name" required @keydown="errors.clear('name')">

                <span class="help is-danger" v-if="errors.get('name')" v-text="errors.get('name')"></span>
              </div>
            </div>
        </td>
        <td v-if="adding">
            <div class="field">
              <div class="control">
                <input class="input is-primary" type="text" placeholder="Descripción" v-model="description" @keydown="errors.clear('description')">

                <span class="help is-danger" v-if="errors.get('description')" v-text="errors.get('description')"></span>
              </div>
            </div>
        </td>
        <td v-if="adding">
            <div class="field">
              <div class="control">
                <input class="input is-primary" type="text" placeholder="Precio" v-model="price" required @keydown="errors.clear('price')">

                <span class="help is-danger" v-if="errors.get('price')" v-text="errors.get('price')"></span>
              </div>
            </div>

            <!-- Save or Cancel -->
            <button class="button is-primary is-small" @click="saveProduct" :disabled="errors.any()">
              Guardar
            </button>

            <button class="button is-small" @click="cancel">
              Cancelar
            </button>
        </td>
    </tr>
</template>

<script>
    import Errors from '../helpers/Errors'

    export default {
        props: ['subcategory_id'],

        data(){
            return {
                errors: new Errors(),
                name: '',
                description: '',
                price: '',
                adding: false
            }
        },

        methods: {
            saveProduct(){
                axios.post('products', { subcategory_id: this.subcategory_id, name: this.name, description: this.description, price: this.price}).then(({data}) => {
                    this.clearData()
                    this.adding = false
                    this.$emit('productAdded', data)
                }, error => this.errors.record(error.response.data))
            },
            clearData(){
                this.name = ''
                this.description = ''
                this.price = ''
                this.errors.clearAll()
            },
            cancel(){
                this.clearData()
                this.adding = false
            }
        }
    }
</script>