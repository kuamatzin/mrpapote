<template>
    <tr>
        <td v-if="adding == false">
            <button class="button is-primary" @click="adding = true">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </button>
        </td>
        <td v-if="adding"></td>
        <td v-if="adding">
            <div class="field">
              <div class="control">
                <input class="input is-primary" type="text" placeholder="Nombre" v-model="name" required @keydown="errors.clear('name')">

                <span class="help is-danger" v-if="errors.get('name')" v-text="errors.get('name')"></span>
              </div>
            </div>

            <!-- Save or Cancel -->
            <button class="button is-primary is-small" @click="saveIngredient" :disabled="errors.any()">
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
        props: [],

        data(){
            return {
                errors: new Errors(),
                adding: false,
                name: '',
            }
        },

        methods: {
            saveIngredient(){
                axios.post('/ingredients', { name: this.name }).then( ({data}) => {
                    this.adding = false
                    this.name = '',
                    this.$emit('newIngredientAdded', data)
                }, error => this.errors.record(error.response.data));
            },
            cancel(){
                this.adding = false
                this.name = ''
            }
        }
    }
</script>