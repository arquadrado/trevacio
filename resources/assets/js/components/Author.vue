<template>
    <div class="content-wrapper">
        <div class="author">
            <div class="modal-header">
                <h3>{{ selectedAuthor.name }}</h3>
                <h4>Author</h4>
                <br>
                <button @click="setContent('list')"><span class="clickable-text">List</span></button>
                <button @click="setContent('home')"><span class="clickable-text">Close</span></button>
                <button v-if="hasHistory" @click="back"><span class="clickable-text">Back</span></button>
            </div>
            <div class="modal-body">
                <div class="show-info" v-if="loading">
                    <loading-spinner></loading-spinner>
                </div>
                <div class="show-info" v-else>
                    <div class="author-info" v-if="hasInfo">
                        <p class="info-text">{{ info.extract }}</p>
                        <a :href="info.fullurl" target=_blank>source</a>
                    </div>
                    <div class="author-info" v-else>
                        <p>There is no info available about this author.</p>
                    </div>
                </div>

                <button @click="toggleShowWorks" v-if="!showWorks"><span class="clickable-text">Show author works</span></button>
                <button @click="toggleShowWorks" v-else><span class="clickable-text">Hide works</span></button>

                <list 
                    v-if="showWorks"
                    :className="'book-list'" 
                    :itemType="'book-item'"
                    :items="selectedAuthor.books"
                ></list>
            </div>
            <div class="modal-footer">
                <button @click="toggleShowWorks" v-if="showWorks"><span class="clickable-text">Hide works</span></button>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import Navigation from './../mixins/Navigation.js'
    import Wikipedia from './../mixins/Wikipedia.js'
    import List from './utilities/List.vue'

    export default {
        mixins: [Navigation, Wikipedia],
        components: {
            'list': List
        },
        data() {
            return {
                showWorks: false
            }
        },
        computed: {
            info() {
                return this.authorsLoadedInfo[this.selectedAuthor.id]
            },
            ...mapGetters({
                selectedAuthor: 'getSelectedAuthor',
                authorsLoadedInfo: 'getAuthorsLoadedInfo'
            })
        },
        methods: {
            fetchAuthorInfo() {
                this.searchWikipedia(this.selectedAuthor.name, (response) => {
                    const authorPage = this.processWikipediaResults(
                        this.selectedAuthor.name,
                        response.query.search
                    )

                    if (!authorPage) {
                        this.__stop()
                        return
                    }
                    this.fetchWikipediaPageById(authorPage.pageid, (response) => {
                        console.log(response, 'pahe')
                        this.setAuthorInfo(response.query.pages[Object.keys(response.query.pages)[0]])
                        this.hasInfo = true
                        this.loading = false
                    })
                })
            },
            toggleShowWorks() {
                this.showWorks = !this.showWorks
            },
            ...mapActions({
                setContent: 'setContent',
                setAuthorInfo: 'setAuthorInfo'
            })
        },
        mounted() {
            if (!this.authorsLoadedInfo.hasOwnProperty(this.selectedAuthor.id)) {
                this.fetchAuthorInfo()
                return
            }

            this.hasInfo = true
        }
    }
</script>