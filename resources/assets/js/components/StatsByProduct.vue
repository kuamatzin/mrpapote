<template>
    <div>
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
            <div style="padding: 20px">
                <div class="columns">
                    <div class="column field">
                        <label class="label">Categoría</label>
                        <div class="control">
                            <div class="select">
                                <select v-model="category_selected" @change="getSubcategories">
                                    <option v-for="category in categories" :value="category.id" >{{category.name}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="column field">
                        <label class="label">Subcategoría</label>
                        <div class="control">
                            <div class="select">
                                <select v-model="subcategory_selected" @change="getProducts">
                                    <option>Sin Seleccionar</option>
                                    <option v-for="subcategory in subcategories" :value="subcategory.id">{{subcategory.name}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="column field">
                        <label class="label">Producto</label>
                        <div class="control">
                            <div class="select">
                                <select v-model="product_selected">
                                    <option value="0">Sin Seleccionar</option>
                                    <option v-for="product in products" :value="product.id">{{product.name}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
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
                        <button class="button is-primary" @click="getStats">Generar Reporte</button>
                    </div>
                </div>
                <hr>
                <stats-sales :chart-data="chart_data"></stats-sales>
                <hr>

                <div class="columns">
                        <div class="column is-4 is-offset-4">
                            <label class="checkbox" style="padding: 10px">
                                <input type="checkbox" v-model="showOrders">
                                Órdenes
                            </label>
                            <label class="checkbox" style="padding: 10px">
                                <input type="checkbox" v-model="showUtilities">
                                Utilidades
                            </label>
                        </div>
                    </div>

                    <hr>
                <table class="table animated bounce" id="stats-table">
                    <thead>
                    <tr>
                        <th>
                            Mes
                        </th>
                        <th v-if="showOrders">
                            Ordenes
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
                        <td v-if="showOrders">
                            {{ orders[index] }}
                        </td>
                        <td v-if="showUtilities">
                            ${{ moneyFormat(revenue[index]) }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import StatsSales from './LineChart.js'
    import VueMonthlyPicker from 'vue-monthly-picker'
    import moment from 'moment'

    export default {
        components: { StatsSales, VueMonthlyPicker },

        props: [],

        mounted(){
            this.getCategories()
        },

        data(){
            return {
                chart_data: {
                    labels: [],
                    datasets: [
                        {
                            label: '#Ordenes',
                            backgroundColor: '#f87979',
                            data: []
                        }, {
                            label: 'Utilidades',
                            backgroundColor: '#22D0B2',
                            data: []
                        }
                    ]
                },
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                showOrders: true,
                showUtilities: true,
                months: '',
                orders: '',
                revenue: '',
                startMonth: moment().format("YYYY-MM"),
                endMonth: moment().format("YYYY-MM"),
                category_selected: '',
                subcategory_selected: '0',
                product_selected: '0',
                categories: '',
                subcategories: '',
                products: '',
                data: ''
            }
        },

        methods: {
            getCategories(){
                axios.get('/categories').then(({data}) => {
                    this.categories = data
                    this.category_selected = this.categories[0].id
                    this.getSubcategories()
                })
            },

            getSubcategories(){
                axios.get('/subcategories/findByCategory/' + this.category_selected).then(({data}) => {
                    this.subcategories = data
                    this.subcategory_selected = this.subcategories[0].id
                    this.getProducts()
                })
            },

            getProducts(){
                axios.get('/products/findBySubcategory/' + this.subcategory_selected).then(({data}) => {
                    this.products = data
                    this.product_selected = this.products[0].id
                    this.getProductStats()
                })
            },

            getStats(){
                if (this.product_selected != '0') {
                    this.getProductStats()
                }
                else {
                    this.getSubcategoryStats()
                }
            },

            getProductStats(){
                axios.get('/statistics/product/' + this.product_selected + '?start_date=' + moment(this.startMonth._i).format('M') + '&end_date=' + moment(this.endMonth._i).format('M')).then(({data}) => this.setData(data))
            },

            getSubcategoryStats(){
                axios.get('/statistics/subcategory/' + this.subcategory_selected + '?start_date=' + moment(this.startMonth._i).format('M') + '&end_date=' + moment(this.endMonth._i).format('M')).then(({data}) => this.setData(data))
            },

            getSubcategoryProductsStats(){
                axios.get('/statistics/subcategoryProducts/' + this.subcategory_selected + '?month=' + moment(this.startMonth._i).format('M')).then(({data}) => this.setData2(data))
            },

            setData(data){
                this.months = data.months
                this.orders = data.orders
                this.revenue = data.revenue

                this.chart_data = {
                    labels: data.months,
                    datasets: [
                        {
                            label: '#Ordenes',
                            backgroundColor: '#f87979',
                            data: data.orders
                        }, {
                            label: 'Utilidades',
                            backgroundColor: '#22D0B2',
                            data: data.revenue
                        }
                    ]
                }
            },

            setData2(data){
                this.months = ''
                this.orders = ''
                this.revenue = ''

                this.chart_data = {
                    labels: data.products,
                    datasets: [
                        {
                            label: '#Ordenes',
                            backgroundColor: '#f87979',
                            data: data.orders
                        }, {
                            label: 'Utilidades',
                            backgroundColor: '#22D0B2',
                            data: data.revenue
                        }
                    ]
                }
            },

            moneyFormat(number){
                return number.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
        }
    }
</script>