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
                <button class="modal-default-button" :disabled="!canSubmit" @click="addBookToLibrary">
                    Save
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
                colorScheme: 'getColorScheme',
                userCollection: 'getUserCollection',
                library: 'getLibrary'
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
                const books = []
                for (let id in this.userCollection) {
                    if (this.userCollection[id].title.includes(this.book.title)) {
                        books.push(this.userCollection[id])
                        break
                    }

                    let a = this.book.title.split(' ').filter((word) => {
                        return word.length > 2
                    })

                    let b = this.userCollection[id].title.split(' ')

                    let matches = a.reduce((reduced, word) => {
                        if (b.indexOf(word) > -1) {
                            reduced++
                        }
                        return reduced
                    }, 0)

                    const matchPercentage = matches * 100 / a.length

                    if (matches > 50) {
                        books.push(this.userCollection[id])
                    }

                }

                console.log(books, 'hey books')
                if (books.length > 0) {
                    console.log('entered')
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

                console.log('I will try')
                const self = this
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
            ...mapActions({
                toggleModal: 'toggleModal',
                addToLibrary: 'addToLibrary',
                addToUserCollection: 'addToUserCollection'
            })
        }
    }
</script>