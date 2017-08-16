<template>
    <div class="content-wrapper">

        <div class="modal-header">
            <h3 class="action">I will GET a fucking book!</h3>
            <button class="modal-default-button" @click="close">close</button>
        </div>

        <div class="book" v-if="selectedBook">
            <div class="modal-body">
                <h3>Here is the fuking book</h3>
                <br>
                <span><strong>Title:</strong> {{ selectedBook.title }} </span>
                <span><strong>Author:</strong> {{ selectedBook.author.name }} </span>
                <div class="book-actions" v-if="selectedBook.in_library">
                    <h4 @click="showReadingSessionList">See reading sessions</h4>
                    <h4 @click="addReadingSession">Add reading session</h4>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-default-button" @click="searchBook">Search book</button>
                <button class="modal-default-button" @click="addBook">Add book</button>
                <div class="book-not-owned" v-if="!selectedBook.in_library">
                    <h4>This book is not in your library. Add it to perform actions</h4>
                    <button class="modal-default-button" @click="addBookToUserCollection">
                        Add book
                    </button>
                </div>
            </div>
        </div>

        <div class="getbook" v-if="!selectedBook && !response">

            <div class="modal-body">
                    <div class="input-text">
                        <label for="book">What is the name of the book?</label>
                        <input type="text" name="book" :style="inputStyle" v-model="bookToGet">
                    </div>
            </div>

            <div class="modal-footer">
                <button class="modal-default-button" :disabled="!canSubmit" @click="getBook">
                    Get book
                </button>
                <button class="modal-default-button" @click="listBooks">List books</button>
            </div>
        </div>
        <response
            :response="response.responseJSON"
            :status="response.status"
            :books="books"
            v-if="response && !selectedBook"
            @searchAgain="searchAgain"
        ></response>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import CollectionHandler from './../mixins/CollectionHandler.js'
    import Response from './GetBookResponse.vue'

    export default {
        components: {
            'response': Response
        },
        mixins: [CollectionHandler],
        data() {
            return {
                bookToGet: '',
                books: null,
                submitted: false,
                response: false
            }
        },
        computed: {
            canSubmit() {
                return this.bookToGet !== ''
            },
            textareaStyle() {
                return {
                    'border': `3px solid ${this.colorScheme.details}`
                }
            },
            inputStyle() {
                return {
                    'border-bottom': `3px solid ${this.colorScheme.details}`
                }
            },
            style() {
                return {
                    'background-color': this.colorScheme.background,
                    'color': this.colorScheme.details,
                    'border': `3px solid ${this.colorScheme.details}`
                }
            },
            ...mapGetters({
                colorScheme: 'getColorScheme',
                selectedBook: 'getSelectedBook',
                userCollection: 'getUserCollection',
                library: 'getLibrary'
            })
        },
        methods: {
            listBooks() {
                this.setSelectedList(null)
                this.setModalComponent('list')
            },
            addBook() {
                this.setSelectedBook(null)
                this.setModalComponent('add')
            },
            searchBook() {
                this.setSelectedBook(null)
                this.setModalComponent('get')
            },
            showReadingSessionList() {
                this.setModalComponent('reading-session-list')
            },
            addReadingSession() {
                this.setSelectedReadingSession(null)
                this.setModalComponent('reading-session')
            },
            addBookToUserCollection(book) {
                const self = this
                self.addToUserCollection({
                    book: self.selectedBook,
                    successCallback: (response, status, responseContent) => {
                        console.log('success')
                    },
                    errorCallback: (response, status, responseContent) => {
                        console.log('total failure')
                    }
                })
            },
            searchAgain() {
                this.bookToGet = ''
                this.response = false
            },
            close() {
                this.setSelectedBook(null)
                this.toggleModal()
            },
            getBook() {
                this.updateLibrary({
                    successCallback: (response, status, responseContent) => {

                        const books = this.findMatches(this.bookToGet, this.library)

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

                        const self = this
                        self.fetchBook({
                            book: self.bookToGet,
                            successCallback: (response, status, responseContent) => {

                                if (responseContent.status == 200) {
                                    self.setSelectedBook(response.book.id)
                                    return
                                }

                                self.response = responseContent
                                if (response.books) {
                                    self.books = response.books
                                }
                                self.submitted = true
                            },
                            errorCallback: (response) => {
                                self.response = response
                                self.submitted = true
                            }
                        })
                    },
                    errorCallback: (response, status, responseContent) => {
                        console.log('total failure')
                    }
                })

            },
            ...mapActions({
                toggleModal: 'toggleModal',
                setSelectedBook: 'setSelectedBook',
                setSelectedReadingSession: 'setSelectedReadingSession',
                setModalComponent: 'setModalComponent',
                fetchBook: 'fetchBook',
                updateLibrary: 'updateLibrary',
                addToUserCollection: 'addToUserCollection',
                setSelectedList: 'setSelectedList'
            })
        }
    }
</script>