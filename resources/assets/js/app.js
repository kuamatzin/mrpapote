
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


/**
 * Our Vuejs event handler which we will be using for flash messaging
 * @type {Vue}
 */
window.events = new Vue();

/**
 * Our Flash function which will be used to add new flash events to our event handler
 * 
 * @param  String message Our alert message
 * @param  String type    The type of alert we want to show
 */
window.flash = function(message, type) {
    window.events.$emit('flash', message, type);
};

//AnimateCSS
$.fn.extend({
    animateCss: function (animationName) {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        this.addClass('animated ' + animationName).one(animationEnd, function() {
            $(this).removeClass('animated ' + animationName);
        });
        return this;
    }
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('create-order', require('./components/CreateOrder.vue'));
Vue.component('orders', require('./components/Orders.vue'));
Vue.component('order-item', require('./components/OrderItem.vue'));
Vue.component('categories', require('./components/Catalog.vue'));
Vue.component('add-category', require('./components/AddCategory.vue'));
Vue.component('subcategory', require('./components/Subcategory.vue'));
Vue.component('product', require('./components/Product.vue'));
Vue.component('add-product', require('./components/AddProduct.vue'));
Vue.component('ingredients', require('./components/Ingredients.vue'));
Vue.component('flash', require('vue-flash'));

const app = new Vue({
    el: '#app'
});
