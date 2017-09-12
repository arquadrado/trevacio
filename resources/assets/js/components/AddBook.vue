<template>
    <div class="content-wrapper">

        <div class="modal-header">
            <h3 class="action">Add a book</h3>
            <button class="" @click="setContent('list')">List</button>
            <button class="" v-if="hasHistory" @click="back">Back</button>
            <button class="" @click="setContent('trevacio')">close</button>
        </div>
        <div class="new-book" v-if="!submitted">

            <div class="modal-body">
                <div class="input-text">
                    <label for="book">What is the name of the book?</label>
                    <input type="text" name="book" :style="inputStyle" v-model="book.title">
                </div>
                <br>
                <div class="input-text">
                    <label>Who wrote it?</label>
                    <input :style="inputStyle" v-model="book.author"></input>
                </div>
                    <!-- <div class="input-text">
                        <label>Note</label>
                        <textarea :style="textareaStyle" name="" id="" cols="30" rows="7"></textarea>
                    </div> -->
            </div>

            <div class="modal-footer">
                <button class="" v-if="!loading" :disabled="!canSubmit" @click="addBookToLibrary">
                    Save
                </button>
                <button v-if="loading">
                    <loading-spinner></loading-spinner>
                </button>
            </div>
        </div>
        <response
            :response="response.responseJSON"
            :status="response.status"
            :books="books"
            v-if="response"
            @inLibrary="addBookToUserCollection"
            @newBook="addBookToLibrary($event, true)"
        ></response>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import CollectionHandler from './../mixins/CollectionHandler.js'
    import Navigation from './../mixins/Navigation.js'
    import Response from './AddBookResponse.vue'

    export default {
        components: {
            'response': Response
        },
        mixins: [CollectionHandler, Navigation],
        data() {
            return {
                book: {
                    title: '',
                    author: ''
                },
                books: null,
                submitted: false,
                response: false
            }
        },
        computed: {
            canSubmit() {
                return this.book.title !== '' && this.book.author !== ''
            },
            textareaStyle() {
                return {
                    'border': `2px solid ${this.colorScheme.details}`
                }
            },
            inputStyle() {
                return {
                    'border-bottom': `2px solid ${this.colorScheme.details}`
                }
            },
            style() {
                return {
                    'background-color': this.colorScheme.background,
                    'color': this.colorScheme.details,
                    'border': `2px solid ${this.colorScheme.details}`
                }
            },
            ...mapGetters({
                colorScheme: 'getColorScheme',
                userCollection: 'getUserCollection',
                library: 'getLibrary',
                loading: 'isLoading'
            })
        },
        methods: {
            addBookToUserCollection(book) {
                const self = this
                self.addToUserCollection({
                    book: book,
                    successCallback: (response, status, responseContent) => {
                        self.response = responseContent
                        self.submitted = true
                    },
                    errorCallback: (response, status, responseContent) => {
                        console.log('total failure')
                    }
                })
            },
            addBookToLibrary(event, force = null) {
                const self = this
                if (force) {
                    self.addToLibrary({
                        book: {
                            title: self.book.title,
                            author: self.book.author
                        },
                        force: force,
                        successCallback: (response, status, responseContent) => {
                            self.response = responseContent
                            if (response.books) {
                                self.books = response.books
                            }
                            self.submitted = true
                        },
                        errorCallback: (response, status, responseContent) => {
                            console.log('total failure')
                        }
                    })

                    return
                }

                this.updateLibrary({
                    successCallback: (response, status, responseContent) => {

                        const books = this.findMatches(this.book.title, this.library)

                        if (books.length > 0) {
                            this.response = {
                                responseJSON: {
                                    message: 'Found these fukers'
                                },
                                status: 201
                            }
                            this.books = books
                            this.submitted = true
                            return
                        }

                        self.addToLibrary({
                            book: {
                                title: self.book.title,
                                author: self.book.author
                            },
                            force: force,
                            successCallback: (response, status, responseContent) => {
                                self.response = responseContent
                                if (response.books) {
                                    self.books = response.books
                                }
                                self.submitted = true
                            },
                            errorCallback: (response, status, responseContent) => {
                                console.log('total failure')
                            }
                        })
                    },
                    errorCallback: (response, status, responseContent) => {
                        console.log('total failure')
                    }
                })
            },
            ...mapActions({
                setContent: 'setContent',
                addToLibrary: 'addToLibrary',
                addToUserCollection: 'addToUserCollection',
                updateLibrary: 'updateLibrary'
            })
        }
    }
</script>