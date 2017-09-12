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
    import diacritics from 'diacritics'
    import { mapGetters, mapActions } from 'vuex'
    import Navigation from './../mixins/Navigation.js'
    import Puppets from './../mixins/Puppets.js'

    export default {
        mixins: [Navigation, Puppets],
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
            __stop() {
                this.hasInfo = false
                this.loading = false
            },
            fetchBookInfoByTitle() {
                this.searchWikipedia(this.selectedBook.title, (response) => {
                    const page = this.processWikipediaResults(
                        this.selectedBook.title, 
                        response.query.search
                    )
                    if (!page) {
                        this.__stop()
                        return
                    }
                    this.fetchWikipediaPageByTitle(page.title)
                })
            },
            fetchBookInfo() {
                this.searchWikipedia(this.selectedBook.author.name, (response) => {
                    const authorPage = this.processWikipediaResults(
                        this.selectedBook.author.name,
                        response.query.search
                    )

                    if (!authorPage) {
                        this.__stop()
                        return
                    }
                    this.fetchWikipediaPageById(authorPage.pageid)
                })
            },
            searchWikipedia(searchString, success) {
                this.loading = true
                let url = `https://${this.searchLanguage}.wikipedia.org/w/api.php?action=query&list=search&srsearch=${searchString.replace(' ', '_')}&utf8=&origin=*&format=json`

                const self = this
                $.get({
                    url: url,
                    success: success,
                    error: (response) => {
                        console.log(response, 'error')
                        self.__stop()
                    }
                })
            },
            fetchWikipediaPageById(pageId) {
                if (!pageId) {
                    this.hasInfo = false
                    return
                }

                const self = this
                let url = `https://${this.searchLanguage}.wikipedia.org/w/api.php?format=json&action=query&prop=extracts|info&exintro=&explaintext=&pageids=${pageId}&origin=*&inprop=url`

                $.get({
                    url: url,
                    success: (response) => {
                        const page = response.query.pages[Object.keys(response.query.pages)[0]]
                        self.getWikipediaPageBooksSection(page)
                    },
                    error: (response) => {
                        console.log(response, 'error')
                        self.__stop()
                    }
                })
            },
            getWikipediaPageBooksSection(page) {
                let url = `https://${this.searchLanguage}.wikipedia.org/w/api.php?action=parse&format=json&page=${page.title}&prop=sections&origin=*`
                const self = this
                $.get({
                    url: url,
                    success: (response) => {
                        let sections = response.parse.sections

                        if (!sections) {
                            self.__stop()
                            return null
                        }

                        let booksSections = sections.filter((section) => {
                            return section.line.toLowerCase() == 'books' || section.line.toLowerCase() == 'bibliography' || section.line.toLowerCase() == 'popular science books' || section.line.toLowerCase() == 'list of works' || section.line.toLowerCase() == 'works'
                        })
                        if (booksSections.length > 0) {
                            self.getBooksSectionBookLink(page, booksSections[0],)
                            return
                        }

                        self.__stop()

                    },
                    error: (response) => {
                        console.log(response, 'error')
                        self.__stop()
                    }
                })


            },
            getBooksSectionBookLink(page, section) {
                if (section == null || page == null) {
                    return null
                }

                let url = `https://${this.searchLanguage}.wikipedia.org/w/api.php?action=parse&format=json&page=${page.title}&section=${section.index}&prop=links&origin=*`

                const self = this
                $.get({
                    url: url,
                    success: (response) => {
                        let match = self.getHighestMatch(self.selectedBook.title, response.parse.links, '*')

                        if (match.matchingIndex > 0.5) {

                            self.fetchWikipediaPageByTitle(match.content['*'])
                            return
                        }

                        self.__stop()

                    },
                    error: (response) => {
                        console.log(response, 'error')
                        self.__stop()
                    }
                })

            },
            fetchWikipediaPageByTitle(title) {
                this.loading = true
                if (!title) {
                    this.__stop()
                    return
                }

                const self = this
                let url = `https://${this.searchLanguage}.wikipedia.org/w/api.php?format=json&action=query&prop=extracts|info&exintro=&explaintext=&titles=${title}&inprop=url&origin=*`

                $.get({
                    url: url,
                    success: (response) => {
                        self.setBookInfo(response.query.pages[Object.keys(response.query.pages)[0]])
                        self.hasInfo = true
                        self.loading = false
                    },
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

                if (!match) {
                    this.__stop()
                    return null
                }

                if (match.matchingIndex > 0.6) {
                    return match.content
                }

                this.__stop()
                return null
            },
            ...mapActions({
                setContent: 'setContent',
                setBookInfo: 'setBookInfo'
            })
        },

        mounted() {
            if (!this.booksLoadedInfo.hasOwnProperty(this.selectedBook.id)) {
                this.fetchBookInfo()
                return
            }

            this.hasInfo = true
        }
    }
</script>