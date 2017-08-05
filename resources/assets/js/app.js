
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

import Trevacio from './components/Trevacio.vue'

const app = new Vue({
    el: '#app',
    components: {
        Trevacio
    },
    data: {
    	suitIndex: 3,
    	suits: [
    		{
    			name: 'lightest',
    			style: {
	    			'background-color': '#ffffff',
	    			'color': '#000000'
	    		}
    		},
            {
                name: 'lighter',
                style: {
                    'background-color': '#ffffff',
                    'color': '#777777'
                }
            },
    		{
    			name: 'darkest',
    			style: {
	    			'background-color': '#000000',
	    			'color': '#ffffff'
	    		}
    		},
    		{
    			name: 'dark',
    			style: {
	    			'background-color': '#222222',
	    			'color': '#ffffff'
	    		}
    		},
    		{
    			name: 'light',
    			style: {
	    			'background-color': '#cccccc',
	    			'color': '#777777'
	    		}
    		}
    	]
    },

    computed: {
    	selectedSuit() {
    		return this.suits[this.suitIndex]
    	}
    },

    methods: {
    	suitEyes() {
            let currentIndex = this.suitIndex
            currentIndex++
    		this.suitIndex = currentIndex % this.suits.length
    	}
    },
    store
});
