<template>
    <div>
        <div class="Chart__list">
            <section class="hero is-primary">
                <div class="hero-body">
                    <div class="container">
                        <h1 class="title animated bounceInDown">
                            Estadísticas
                        </h1>
                    </div>
                </div>
            </section>
            <div class="card card-2">
                <div class="Chart" style="padding: 20px">
                    <div class="columns">
                        <div class="column is-4">
                            <label>Seleccionar mes inicial</label>
                            <vue-monthly-picker v-model="startMonth" placeHolder="Mes Inicio" :monthLabels="labels" inputClass="input"></vue-monthly-picker>
                        </div>
                        <div class="column is-4">
                            <label>Seleccionar mes final</label>
                            <vue-monthly-picker v-model="endMonth" placeHolder="Mes Inicio" :monthLabels="labels" inputClass="input"></vue-monthly-picker>
                        </div>
                        <div class="column">
                            <br>
                            <button class="button is-primary" @click="getStatsData">Generar Reporte</button>
                        </div>
                    </div>
                    <hr>
                    <stats-sales :chart-data="chart_data"></stats-sales>
                    <hr>
                    <div class="columns">
                        <div class="column is-4 is-offset-4">
                            <label class="checkbox" style="padding: 10px">
                                <input type="checkbox" v-model="showRevenue">
                                Ingresos
                            </label>
                            <label class="checkbox" style="padding: 10px">
                                <input type="checkbox" v-model="showExpenses">
                                Gastos
                            </label>
                            <label class="checkbox" style="padding: 10px">
                                <input type="checkbox" v-model="showUtilities">
                                Utilidades
                            </label>
                        </div>
                    </div>
                    <table class="table animated bounce" id="stats-table">
                        <thead>
                        <tr>
                            <th>
                                Mes
                            </th>
                            <th v-if="showExpenses">
                                Ingresos
                            </th>
                            <th v-if="showRevenue">
                                Gastos
                            </th>
                            <th v-if="showUtilities">
                                Utilidades
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(month, index) in months">
                            <td>
                                {{month}}
                            </td>
                            <td v-if="showRevenue">
                                ${{ moneyFormat(revenue[index]) }}
                            </td>
                            <td v-if="showExpenses">
                                ${{ moneyFormat(expenses[index]) }}
                            </td>
                            <td v-if="showUtilities">
                                ${{ moneyFormat(utilities[index]) }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import StatsSales from './LineChart.js'
    import VueMonthlyPicker from 'vue-monthly-picker'
    import moment from 'moment'
    import Stat from '../helpers/Stat'
    import DateFormater from '../helpers/DateFormater'

    export default {
        components: { StatsSales, VueMonthlyPicker },

        mounted(){
           this.getStatsData()
        },
        data(){
            return {
                dateTrs: new DateFormater(),
                stat_api: new Stat(),
                chart_data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'Ganancias',
                            backgroundColor: '#f87979',
                            data: []
                        }, {
                            label: 'Gastos',
                            backgroundColor: '#22D0B2',
                            data: []
                        }, {
                            label: 'Utilidades',
                            backgroundColor: '#2FC460',
                            data: []
                        }
                    ]
                },
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                startMonth: moment().format("YYYY-MM"),
                endMonth: moment().format("YYYY-MM"),
                months: '',
                revenue: '',
                expenses: '',
                utilities: '',
                showUtilities: true,
                showRevenue: true,
                showExpenses: true
            }
        },
        methods: {
            getStatsData(){
                this.stat_api.getGeneral(this.dateTrs.month(this.startMonth), this.dateTrs.month(this.endMonth)).then(({data}) => this.setData(data))
            },
            setData(data){

                this.months = data.months
                this.revenue = data.revenue
                this.expenses = data.expenses
                this.utilities = data.utilities

                this.chart_data = {
                    labels: data.months,
                    datasets: [
                        {
                            label: 'Ganancias',
                            backgroundColor: '#f87979',
                            data: data.revenue
                        }, {
                            label: 'Gastos',
                            backgroundColor: '#22D0B2',
                            data: data.expenses
                        }, {
                            label: 'Utilidades',
                            backgroundColor: '#2FC460',
                            data: data.utilities
                        }
                    ]
                }

                this.resetAnimation('stats-table', 'bounce')
            },
            moneyFormat(number){
                return number.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
            resetAnimation(div_id, animation){
                $('#' + div_id).removeClass("animated " + animation)
                setTimeout(() => $('#' + div_id).addClass("animated " + animation), 10)
            },
        }
    }
</script>