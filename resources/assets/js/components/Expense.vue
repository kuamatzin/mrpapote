<template>
<tr>
    <td>{{ index+1 }}</td>
    <td v-if="edit">
        <input class="input is-primary" type="text" placeholder="Descripción" v-model="description" required @keydown="errors.clear('description')">

        <span class="help is-danger" v-if="errors.get('description')" v-text="errors.get('description')"></span>
    </td>
    <td v-else>{{ expense.description }}</td>
    <td v-if="edit">
        <div class="file" style="padding: 0px">
            <label class="file-label">
                <input class="file-input" type="file" name="resume" @change="fileChanged">
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
    <td v-else>
        <div v-if="file">
            <a download :href="file" class="button is-primary">Descargar</a>
        </div>
        <div v-else>
            Sin Archivo
        </div>
    </td>
    <td v-if="edit">
        <input class="input is-primary" type="text" placeholder="Total" v-model="total" required @keydown="errors.clear('total')">

        <span class="help is-danger" v-if="errors.get('total')" v-text="errors.get('total')"></span>
    </td>
    <td v-else>${{ moneyFormat }}</td>
    <td v-if="edit">
        <input class="input is-primary" type="date" @click="errors.clear('buy_date')" v-model="buy_date">
        <span class="help is-danger" v-if="errors.get('buy_date')" v-text="errors.get('buy_date')"></span>
        <div style="padding-top:10px">
            <!-- Save or Cancel -->
            <button class="button is-primary is-small" @click="createForm" :disabled="errors.any()">
              Guardar
            </button>

            <button class="button is-small" @click="cancel">
              Cancelar
            </button>
        </div>
    </td>
    <td v-else>{{ dateFormated }}</td>
    <td>
        <button class="button is-warning" @click="edit = true">
            <i class="fa fa-pencil" aria-hidden="true" style="color:white"></i>
        </button>
    </td>
    <td>
        <button class="button is-danger" @click="deleteExpense">
            <i class="fa fa-trash-o" aria-hidden="true"></i>
        </button>
    </td>
</tr>
</template>

<script>
    import moment from 'moment'
    import Errors from '../helpers/Errors'

    export default {
        props: ['expense', 'index'],

        computed: {
            moneyFormat(){
                return parseInt(this.total).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            dateFormated(){
                return moment(this.date, 'YYYY-MM-DD h:mm:ss').format('D MMMM YYYY')
            },
        },

        data(){
            return {
                errors: new Errors(),
                edit: false,
                description: this.expense.description,
                file: this.expense.file,
                total: this.expense.total,
                date: this.expense.buy_date,
                buy_date: moment(this.expense.buy_date, 'YYYY-MM-DD h:mm:ss').format('YYYY-MM-DD')
            }
        },

        methods: {

            cancel(){
                this.edit = false
            },

            createForm(){
                let form = new FormData()

                form.append('description', this.description)
                form.append('total', this.total)
                form.append('buy_date', this.buy_date)
                form.append('file', this.file)
                form.append('_method', 'PUT') //This line is important
                this.updateExpense(form)
            },

            updateExpense(form){
                //Defined as a POST method but acts like a PUT method cuz we append the 'method' => PUT value in the form (FormData) object in the creaeForm method
                axios.post('/expenses/' + this.expense.id, form).then( ({data}) => {
                    this.edit = false
                    this.description = data.description
                    this.total = data.total
                    this.date = data.buy_date
                    this.buy_date = moment(data.buy_date, 'YYYY-MM-DD h:mm:ss').format('YYYY-MM-DD')
                }, error => this.errors.record(error.response.data));
            },

            deleteExpense(){
                swal({
                  title: "¿Estás seguro?",
                  text: "No podrás recuperar los datos",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  cancelButtonText: 'Cancelar',
                  confirmButtonText: "Estoy seguro",
                  closeOnConfirm: true
                },
                () => axios.delete('/expenses/' + this.expense.id).then(data => this.$emit('expenseDeleted', this.index))
                )
            },

            fileChanged(e){
                if (! e.target.files.length) return;

                this.file = e.target.files[0]
            }
        }
    }
</script>