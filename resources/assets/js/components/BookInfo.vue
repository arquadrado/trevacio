<template>
    <div class="content-wrapper">

        <div class="modal-header">
            <h3 class="action">{{ title }}</h3>
            <button class="" v-if="hasHistory" @click="back">Back</button>
            <button class="" @click="setContent('book')">Book</button>
        </div>
        <div class="modal-body">
            <loading-spinner v-if="loading"></loading-spinner>
            <div class="show-info" v-else>
                <div class="info" v-if="hasInfo">
                    <p>{{ info.extract }}</p>
                </div>
                <div class="info-not-found" v-if="!hasInfo">
                    <h4>
                        Couldn't find any information about this book. You are sure that it exists?
                    </h4>
                    <span>Wanna try in a different language?</span>
                    <br>
                    <div class="input-text">
                        <label>Enter the language you would like to search</label>
                        <input :style="inputStyle" v-model="searchLanguage"></input>
                    </div>
                    <br>
                    <button @click="fetchBookInfo">search in author books</button>
                    <br>
                    <button @click="fetchBookInfoByTitle">search by title</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import Navigation from './../mixins/Navigation.js'

    export default {
        mixins: [Navigation],
        data() {
            return {
                hasInfo: false,
                searchLanguage: 'en',
                loading: false
            }
        },
        computed: {
            title() {
                return `${this.selectedBook.title} info`
            },
            inputStyle() {
                return {
                    'border-bottom': `2px solid ${this.colorScheme.details}`
                }
            },
            
            info() {
                return this.booksLoadedInfo[this.selectedBook.id]
            },
            ...mapGetters({
                colorScheme: 'getColorScheme',
                selectedBook: 'getSelectedBook',
                booksLoadedInfo: 'getBooksLoadedInfo'
            })
        },
        methods: {
            findMatch(needle, haystack, needleTarget, haystackKey) {
                let explodedName = needle.split(' ')
                let processed = haystack.filter((item) => {
                    return explodedName.every((word) => {
                        return item[haystackKey].toLowerCase().includes(word.toLowerCase())
                    })
                })
                if (processed.length > 0) {
                    return processed[0][needleTarget]
                }
                self.hasInfo = false
                self.loading = false
                return null
            },
            fetchBookInfoByTitle() {

            },
            fetchBookInfo() {
                const self = this
                const errorCallback = (response) => {
                    console.log(response, 'error')
                    self.hasInfo = false
                    self.loading = false
                }

                this.searchWikipedia((response) => {
                    const authorPageId = self.processWikipediaResults(response.query.search)
                    console.log(response, 'get author pageid')
                    self.fetchWikipediaPageById(authorPageId, (response) => {
                        console.log(response, 'get author page')
                        const page = response.query.pages[Object.keys(response.query.pages)[0]]
                        self.getWikipediaPageBooksSection(page, (response) => {
                            let sections = response.parse.sections
                            console.log(response, 'get book sections')

                            if (!sections) {
                                self.hasInfo = false
                                self.loading = false
                                return null
                            }

                            let booksSections = sections.filter((section) => {
                                console.log(section.line.toLowerCase())
                                return section.line.toLowerCase() == 'books' || section.line.toLowerCase() == 'bibliography' || section.line.toLowerCase() == 'popular science books' || section.line.toLowerCase() == 'list of works'
                            })
                            console.log(booksSections, 'puta que pariu')
                            if (booksSections.length > 0) {
                                self.getBooksSectionBookLink(page, booksSections[0], (response) => {
                                    console.log(response, 'get section links')
                                    let match = self.findMatch(self.selectedBook.title, response.parse.links, '*', '*')
                                    self.fetchWikipediaPageByTitle(match)

                                }, errorCallback)
                                return
                            }

                            this.loading = false
                            this.hasInfo = false


                        }, errorCallback)
                    }, errorCallback)
                }, errorCallback)
            },
            searchWikipedia(successCallback, errorCallback) {
                this.loading = true
                let url = `https://${this.searchLanguage}.wikipedia.org/w/api.php?action=query&list=search&srsearch=${this.selectedBook.author.name.replace(' ', '_')}&utf8=&origin=*&format=json`
                $.get({
                    url: url,
                    success: successCallback,
                    error: errorCallback
                })
            },
            getWikipediaPageBooksSection(page, successCallback, errorCallback) {
                let url = `https://${this.searchLanguage}.wikipedia.org/w/api.php?action=parse&format=json&page=${page.title}&prop=sections&origin=*`

                $.get({
                    url: url,
                    success: successCallback,
                    error: errorCallback
                })


            },
            getBooksSectionBookLink(page, section, successCallback, errorCallback) {
                if (section == null || page == null) {
                    return null
                }

                let url = `https://${this.searchLanguage}.wikipedia.org/w/api.php?action=parse&format=json&page=${page.title}&section=${section.index}&prop=links&origin=*`

                $.get({
                    url: url,
                    success: successCallback,
                    error: errorCallback
                })

            },
            fetchWikipediaPageByTitle(title) {
                if (!title) {
                    console.log('y would?')
                    this.hasInfo = false
                    this.loading = false
                    return
                }

                const self = this
                let url = `https://${this.searchLanguage}.wikipedia.org/w/api.php?format=json&action=query&prop=extracts|info&exintro=&explaintext=&titles=${title}&inprop=url&origin=*`

                //let url = `https://en.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles=${this.selectedBook.title.replace(' ', '_')}`

                $.get({
                    url: url,
                    success: (response) => {
                        console.log(response, 'get page by title')
                        self.setBookInfo(response.query.pages[Object.keys(response.query.pages)[0]])
                        self.hasInfo = true
                        self.loading = false
                    },
                    error: (response) => {
                        console.log(response, 'error')
                        self.hasInfo = false
                        self.loading = false
                    }
                })
            },
            fetchWikipediaPageById(pageId, successCallback, errorCallback) {
                if (!pageId) {
                    console.log('y would?')
                    this.hasInfo = false
                    return
                }

                const self = this
                let url = `https://${this.searchLanguage}.wikipedia.org/w/api.php?format=json&action=query&prop=extracts|info&exintro=&explaintext=&pageids=${pageId}&origin=*&inprop=url`

                $.get({
                    url: url,
                    success: successCallback,
                    error: errorCallback
                })
            },
            processWikipediaResults(results) {
                const self = this
                let explodedName = self.selectedBook.author.name.replace(/[^a-zA-Z ]/g, "").split(' ')
                console.log(explodedName)
                let processed = results.filter((result) => {
                    return explodedName.every((word) => {
                        return result.title.toLowerCase().includes(word.toLowerCase())
                    })
                })

                if (processed.length > 0) {
                    return processed[0].pageid
                }

                self.hasInfo = false
                self.loading = false
                return null
            },
            ...mapActions({
                setContent: 'setContent',
                setBookInfo: 'setBookInfo'
            })
        },

        mounted() {
            if (!this.bookInfo) {
                this.fetchBookInfo()
            }
        }
    }
</script>