<template>
<div>
    <section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title animated bounceInDown">
                Gastos
                </h1>
            </div>
        </div>
    </section>
    <div class="card card-2">
        <div style="padding: 20px">
            <div class="columns">
                <div class="column is-4">
                    <label style="padding-left: 5px">Mes</label>
                    <vue-monthly-picker v-model="expensesMonth" placeHolder="Mes Inicio" :monthLabels="labels" inputClass="input"></vue-monthly-picker>
                </div>
                <div class="column is-4 is-offset-4">
                    <br>
                    <button class="button is-primary" @click="register_expense = true">
                    Registrar Gasto
                    </button>
                </div>
            </div>
            <hr>
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Descripción</th>
                        <th>Archivo</th>
                        <th>Total</th>
                        <th>Fecha de compra</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>

                    <tr v-if="register_expense" is="new-expense" @newExpense="newExpense" @cancelExpense="cancelExpense"></tr>

                    <tr is="expense" v-for="(expense, index) in expenses" :expense="expense" :index="index" @expenseDeleted="expenseDeleted">

                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</template>

<script>
    import VueMonthlyPicker from 'vue-monthly-picker'
    import NewExpense from './NewExpense.vue'
    import Expense from './Expense.vue'
    import ExpenseApi from '../helpers/Expense'
    import DateFormater from '../helpers/DateFormater'
    import moment from 'moment'

    export default {
        components: { VueMonthlyPicker, NewExpense, Expense },

        mounted(){
            this.getExpenses()
        },

        watch: {
            expensesMonth(){
                this.getExpenses()
            }
        },

        data(){
            return {
                dateTrs: new DateFormater(),
                expense_api: new ExpenseApi(),
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                expensesMonth: moment().format("YYYY-MM"),
                expenses: '',
                register_expense: false
            }
        },

        methods: {
            getExpenses(){
                this.expense_api.getByMonth(this.dateTrs.month(this.expensesMonth)).then(({data}) => this.expenses = data)
            },
            newExpense(expense){
                this.register_expense = false
                this.expenses.push(expense)
            },
            cancelExpense(){
                this.register_expense = false
            },
            expenseDeleted(index){
                this.expenses.splice(index, 1)
            }
        }
    }
</script>