
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')

window.Vue = require('vue')

Vue.debug = true
Vue.devtools = true

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import store from './vuex/store'

import { mapGetters, mapActions } from 'vuex'
import ContentWrapper from './components/ContentWrapper.vue'
import Gui from './components/Gui.vue'

const app = new Vue({
    el: '#app',
    components: {
        ContentWrapper,
        Gui
    },
    data: {
    },

    computed: {
    	selectedSuit() {
            console.log(this.colorScheme)
    		return {
                'background-color': this.colorScheme.background,
                'color': this.colorScheme.details
            }
    	},
        ...mapGetters({
            showGui: 'getShowGui',
            colorScheme: 'getColorScheme'
        })
    },

    methods: {
        ...mapActions({
            suitEyes: 'changeColorScheme',
            toggleGui: 'toggleGui'
        })
    },
    store
});
