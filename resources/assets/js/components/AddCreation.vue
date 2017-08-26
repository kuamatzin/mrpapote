<template>
    <div>
        {{base_product.name}}
        <button class="button is-primary" @click="modifyBase">Button</button>
    </div>
</template>

<script>
    export default {
        props: ['name'],
        watch: {
            name(name){
                this.base_product.name = name
            }
        },
        data(){
            return {
                base_product: {
                    name: '',
                }
            }
        },

        methods: {
            modifyBase(){
                this.base_product.name = "HOLA"
            }
        }
    }
</script>