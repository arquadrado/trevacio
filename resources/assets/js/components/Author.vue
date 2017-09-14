<template>
    <div class="content-wrapper">
        <div class="author">
            <div class="modal-header">
                <h3>{{ selectedAuthor.name }}</h3>
                <h4>Author</h4>
                <br>
                <button class="" @click="setContent('list')">List</button>
                <button class="" @click="setContent('trevacio')">Close</button>
                <button class="" v-if="hasHistory" @click="back">Back</button>
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

                <button @click="toggleShowWorks" v-if="!showWorks">Show author works</button>
                <button @click="toggleShowWorks" v-else>hide works</button>

                <list 
                    v-if="showWorks"
                    :className="'book-list'" 
                    :itemType="'book-item'"
                    :items="selectedAuthor.books"
                ></list>
            </div>
            <div class="modal-footer">
                <button @click="toggleShowWorks" v-if="showWorks">hide works</button>
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