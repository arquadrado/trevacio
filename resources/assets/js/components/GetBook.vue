<template>
    <div class="content-wrapper">

        <div class="modal-header">
            <h3 class="action">I will GET a fucking book!</h3>
            <button @click="close">close</button>
        </div>

        <div class="book" v-if="selectedBook">
            <div class="modal-body">
                <h3>Here is the fuking book</h3>
                <br>
                <span><strong>Title:</strong> {{ selectedBook.title }} </span>
                <span><strong>Author:</strong> {{ selectedBook.author.name }} </span>
                <div class="book-actions" v-if="selectedBook.in_library">
                    <h4>I can do stuff</h4>
                </div>
            </div>
            <div class="modal-footer" v-if="!selectedBook.in_library">
                <h4>This book is not in your library. Add it to perform actions</h4>
                <button class="modal-default-button" @click="setModalComponent('add')">
                    Add book
                </button>
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
            </div>
        </div>
        <response 
            :response="response.responseJSON"
            :status="response.status"
            :books="books" 
            v-if="response && !selectedBook"
        ></response>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import Response from './GetBookResponse.vue'

    export default {
        components: {
            'response': Response
        },
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
                selectedBook: 'getSelectedBook'
            })
        },
        methods: {
            close() {
                this.setSelectedBook(null)
                this.toggleModal()
            },
            getBook() {
                const self = this
                $.post('get-book', {
                    _token: window.handover._token,
                    title: this.bookToGet,
                }, function(response, status, responseContent) {
                    
                    if (responseContent.status == 200) {
                        self.setSelectedBook(response.book)
                        return
                    }

                    self.response = responseContent
                    if (response.books) {
                        self.books = response.books
                    }
                    self.submitted = true
                })
            },
            ...mapActions({
                toggleModal: 'toggleModal',
                setSelectedBook: 'setSelectedBook',
                setModalComponent: 'setModalComponent'
            })
        }
    }
</script>