<template>
    <div class="response">
        <div class="success" v-if="status == 200">
            <div class="modal-body">
                <h3>Book Added</h3>
                <button @click="toggleModal">Close</button>
            </div>    
        </div>
        <div class="book-found" v-if="status == 201">
            <div class="modal-body">
                <h3>{{ response.message }}</h3>
                <button @click="setBook(book)" v-for="book in books">
                    {{ book.title }} - {{ book.author.name }}
                </button>
                <br>
                <button :disabled="!book" @click="addToLibrary">Add this book to my library</button>
                <button @click="newBook">This is a different book</button>
            </div>
        </div>
        <div class="book-owned" v-if="status == 202">
            <div class="modal-body">
                <h3>{{ response.message }}</h3>
                <button @click="openBook(book)">
                    Open {{ response.book.title }}
                </button>
                <button @click="toggleModal">Close</button>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        props: ['response', 'status', 'books'],
        data() {
            return {
                book: null
            } 
        },
        computed: {},
        methods: {
            openBook(book) {
                console.log(book, 'gonna open')
            },
            setBook(book) {
                this.book = book
            },
            addToLibrary() {
                this.$emit('addToLibrary', this.book)
            },
            newBook() {
                this.$emit('newBook')
            },
            ...mapActions({
                toggleModal: 'toggleModal'
            })
        }
    }
</script>