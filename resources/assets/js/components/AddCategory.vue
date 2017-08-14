<template>
        <div class="modal" :class="{ 'is-active': active }">
            <div class="modal-background"></div>
              <div class="modal-card">
                <header class="modal-card-head">
                  <p class="modal-card-title">Registrar Categoría</p>
                </header>
                <section class="modal-card-body">
                    <div class="field">
                      <div class="control">
                        <input class="input is-primary" type="text" placeholder="Nombre" v-model="name">
                      </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                  <a class="button is-success" @click="saveCategory">Guardar</a>
                  <a class="button" @click="closeModal">Cancelar</a>
                </footer>
              </div>
        </div>
</template>

<script>
    export default {
        props: ['activeModal'],
        watch: {
            activeModal() {
                this.active = !this.active;
            }
        },
        data() {
            return {
                name: '',
                active: this.activeModal,
            }
        },
        methods: {
            closeModal(){
                this.$emit('closeModal');
            },
            saveCategory(){
                if (this.name) {
                    axios.post('/categories', { name: this.name }).then(({data}) => {
                        console.log(data)
                        this.$emit('newCategory')
                        this.closeModal()
                    });
                }
                else {
                    swal('Ingresa un nombre para la categoría');
                }
            }
        }
    }
</script>