<template>
    <tr>
        <td>{{index + 1}}</td>
        <td v-if="edit == false">{{ingredient.name}}</td>
        <td v-else>

            <div class="field">
              <div class="control">
                <input class="input is-primary" type="text" placeholder="Nombre" v-model="name" required @keydown="errors.clear('name')">

                <span class="help is-danger" v-if="errors.get('name')" v-text="errors.get('name')"></span>

                <!-- Save or Cancel -->
                <div class="field is-grouped">
                  <p class="control">
                    <button class="button is-primary is-small" @click="updateIngredient" :disabled="errors.any()">
                      Actualizar
                    </button>
                  </p>
                  <p class="control">
                    <a class="button is-small"  @click="cancel">
                      Cancelar
                    </a>
                  </p>
                </div>

              </div>
            </div>
        </td>
        <td>
            <button class="button is-warning" @click="edit = true">
                <i class="fa fa-pencil" aria-hidden="true" style="color:white"></i>
            </button>
        </td>
        <td>
            <button class="button is-danger" @click="removeIngredient">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
            </button>
        </td>
    </tr>
</template>

<script>
    import Errors from '../helpers/Errors'

    export default {
        props: ['original_ingredient', 'index'],

        data(){
            return {
                errors: new Errors(),
                ingredient: this.original_ingredient,
                name: this.original_ingredient.name,
                edit: false
            }
        },

        methods: {
            updateIngredient(){
                axios.put('/ingredients/' + this.ingredient.id, { name: this.name }).then( data => {
                    this.edit = false
                    this.ingredient.name = this.name
                }, error => this.errors.record(error.response.data));
            },
            removeIngredient(){
                swal({
                  title: "¿Estás seguro?",
                  text: "No podrás recuperar los datos de " + this.ingredient.name,
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  cancelButtonText: 'Cancelar',
                  confirmButtonText: "Estoy seguro",
                  closeOnConfirm: true
                },
                () => axios.delete('/ingredients/' + this.ingredient.id).then(data => this.$emit('ingredientDeleted', this.index))
                )
            },
            cancel(){
                this.name = this.original_ingredient.name
                this.edit = false
            }
        }
    }
</script>