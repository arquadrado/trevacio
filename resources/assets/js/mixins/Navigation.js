import { mapGetters, mapActions } from 'vuex'

export default {
	computed: {
		...mapGetters({
			hasHistory: 'hasNavigationHistory'
		})
	},
	methods: {
		...mapActions({
			back: 'back'
		})
	} 
}