<template>
    <div v-if="edit_subcategory == false" class="img_wrp">
        <div class="hover">
            <!--
            <img src="https://cdn2.iconfinder.com/data/icons/flat-icons-19/128/Burger.png" @click="getProducts">
            -->
            <i class="fa fa-pencil edit" aria-hidden="true" @click="edit_subcategory = true"></i>
            <p @click="getProducts">{{subcategory.name}}</p>
        </div>
    </div>

    <!-- Edit subcategory -->
    <div v-else>
        <!--
        <img src="https://cdn2.iconfinder.com/data/icons/flat-icons-19/128/Burger.png">
        -->
        <div class="field">
          <div class="control">
            <input class="input" type="text" placeholder="Subcategoría" v-model="subcategory_name">
          </div>
        </div>
        <div class="field is-grouped">
          <p class="control">
            <a class="button is-primary" @click="updateSubcategory">Guardar</a>
          </p>
          <p class="control">
            <a class="button" @click="cancelUpdate">Cancelar</a>
          </p>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['subcategory', 'index'],

        watch: {
            subcategory(){
                this.original_data = this.subcategory
                this.subcategory_name = this.subcategory.name
            }
        },

        data(){
            return {
                edit_subcategory: false,
                subcategory_name: this.subcategory.name,
                original_data: this.subcategory
            }
        },

        methods: {
            cancelUpdate(){
                this.edit_subcategory = false
                this.subcategory_name = this.original_data.name
            },
            updateSubcategory(){
                axios.put('/subcategories/' + this.subcategory.id, { name: this.subcategory_name }).then(data => {
                    this.edit_subcategory = false
                    this.$emit('subcategoryUpdated', this.subcategory_name, this.index);
                });
            },
            getProducts(){
                this.$emit('getProducts', this.subcategory.id)
            }
        }
    }
</script>