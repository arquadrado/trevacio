<template>
    <div class="content-wrapper">

        <div class="modal-header">
            <h3 class="action">I will ADD a fucking book!</h3>
            <button @click="toggleModal">close</button>
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
                <button class="modal-default-button" :disabled="!canSubmit" @click="saveBook">
                    Save
                </button>
            </div>
        </div>
        <response 
            :response="response.responseJSON"
            :status="response.status"
            :books="books" 
            v-if="response"
            @addToLibrary="addToLibrary"
            @newBook="saveBook($event, true)"
        ></response>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import Response from './AddBookResponse.vue'

    export default {
        components: {
            'response': Response
        },
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
                colorScheme: 'getColorScheme'
            })
        },
        methods: {
            addToLibrary(book) {
                const self = this 
                $.post('add-to-library', {
                    _token: window.handover._token,
                    book: book
                }, function(response, status, responseContent) {
                    console.log(response, status, responseContent, 'Esta')
                    self.response = responseContent
                    self.submitted = true
                })
            },
            saveBook(event, force = null) {
                console.log('xis', force)
                const self = this
                $.post('save-book', {
                    _token: window.handover._token,
                    title: this.book.title,
                    author: this.book.author,
                    force: force,
                }, function(response, status, responseContent) {
                    console.log(response, status, responseContent)
                    self.response = responseContent
                    if (response.books) {
                        self.books = response.books
                    }
                    self.submitted = true
                })
            },
            ...mapActions({
                toggleModal: 'toggleModal'
            })
        }
    }
</script>