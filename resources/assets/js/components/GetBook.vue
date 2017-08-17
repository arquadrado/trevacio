<template>
    <div class="content-wrapper">

        <div class="modal-header">
            <h3 class="action">I will GET a fucking book!</h3>
            <button class="modal-default-button" @click="close">close</button>
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
                selectedList: 'getSelectedList',
                userCollection: 'getUserCollection',
                library: 'getLibrary'
            })
        },
        methods: {
            listBooks() {
                this.setSelectedList(null)
                this.setModalComponent('list')
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
                setModalComponent: 'setModalComponent',
                fetchBook: 'fetchBook',
                updateLibrary: 'updateLibrary',
                setSelectedList: 'setSelectedList'
            })
        }
    }
</script>