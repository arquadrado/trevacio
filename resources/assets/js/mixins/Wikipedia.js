import { mapGetters, mapActions } from 'vuex'
import Puppets from './Puppets.js'

export default {
	mixins: [Puppets],
	data() {
        return {
            hasInfo: false,
            searchLanguage: 'en',
            loading: false
        }
    },
	computed: {
		...mapGetters({
		})
	},
	methods: {
		__stop() {
            this.hasInfo = false
            this.loading = false
        },
		searchWikipedia(searchString, success) {
            this.loading = true
            let url = `https://${this.searchLanguage}.wikipedia.org/w/api.php?action=query&list=search&srsearch=${searchString.replace(' ', '_')}&utf8=&origin=*&format=json`

            const self = this
            $.get({
                url: url,
                success: success,
                error: (response) => {
                    self.__stop()
                }
            })
        },
        fetchWikipediaPageById(pageId, success) {
            if (!pageId) {
                this.hasInfo = false
                return
            }

            const self = this
            let url = `https://${this.searchLanguage}.wikipedia.org/w/api.php?format=json&action=query&prop=extracts|info&exintro=&explaintext=&pageids=${pageId}&origin=*&inprop=url`

            $.get({
                url: url,
                success: success,
                error: (response) => {
                    console.log(response, 'error')
                    self.__stop()
                }
            })
        },
        fetchWikipediaPageByTitle(title, success) {
            this.loading = true
            if (!title) {
                this.__stop()
                return
            }

            const self = this
            let url = `https://${this.searchLanguage}.wikipedia.org/w/api.php?format=json&action=query&prop=extracts|info&exintro=&explaintext=&titles=${title}&inprop=url&origin=*`

            $.get({
                url: url,
                success: success,
                error: (response) => {
                    console.log(response, 'error')
                    self.__stop()
                }
            })
        },
        processWikipediaResults(searchString, results) {
            const match = this.getHighestMatch(
                searchString, 
                results,
                'title',
                true
            ) 

            if (match && match.matchingIndex > 0.6) {
                return match.content
            }

            this.__stop()
            return null
        },
		...mapActions({
		})
	} 
}