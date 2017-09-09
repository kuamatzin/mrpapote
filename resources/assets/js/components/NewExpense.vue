<template>
<tr>
    <td></td>
    <td>
        <input class="input is-primary" placeholder="Descripción" type="text" v-model="description" @keydown="errors.clear('description')">
        <span class="help is-danger" v-if="errors.get('description')" v-text="errors.get('description')"></span>
    </td>
    <td>
        <div class="file" style="padding: 0px">
            <label class="file-label">
                <input class="file-input" type="file" name="resume">
                <span class="file-cta">
                    <span class="file-icon">
                        <i class="fa fa-upload"></i>
                    </span>
                    <span class="file-label">
                        Archivo
                    </span>
                </span>
            </label>
        </div>
    </td>
    <td>
        <input class="input is-primary" type="text" placeholder="Total" v-model="total" @keydown="errors.clear('total')">
        <span class="help is-danger" v-if="errors.get('total')" v-text="errors.get('total')"></span>
    </td>
    <td>
        <input class="input is-primary" type="date" @click="errors.clear('date')" v-model="date">
        <span class="help is-danger" v-if="errors.get('date')" v-text="errors.get('date')"></span>
        <div style="padding-top:10px">
            <!-- Save or Cancel -->
            <button class="button is-primary is-small" @click="saveExpense" :disabled="errors.any()">
              Guardar
            </button>

            <button class="button is-small" @click="cancel">
              Cancelar
            </button>
        </div>
    </td>
</tr>
</template>

<script>
    import Errors from '../helpers/Errors'
    
    export default {
        data(){
            return {
                errors: new Errors(),
                description: '',
                file: '',
                total: '',
                date: ''
            }
        },

        methods: {
            saveExpense(){
                axios.post('/expenses', { description: this.description, total: this.total, date: this.date }).then( ({data}) => {
                    this.adding = false
                    this.resetValues()
                    this.$emit('newExpense', data)
                }, error => this.errors.record(error.response.data));
            },
            resetValues(){
                this.description = ''
                this.total = ''
                this.date = null
            },
            cancel(){
                this.resetValues()
                this.$emit('cancelExpense')
            }
        }
    }
</script>