<template>
    <div class="response">
        <div class="success" v-if="status == 200">
            <div class="modal-body">
                <h3>{{ response.book.title }} added</h3>
                <button @click="openBook(response.book)">Open book</button>
                <button @click="addAnother">Add another</button>
            </div>
        </div>
        <div class="book-found" v-if="status == 201">
            <div class="modal-body">
                <h3>{{ response.message }}</h3>
                <ul class="book-list">
                    <li class="book" v-for="book in books" @click="openBook(book)">
                        <div class="book-info">
                            <span class="book-title">{{ book.title }}</span>
                            <br>
                            <span class="book-author">by {{ book.author.name }}</span>
                        </div>
                        <div class="quick-actions">
                            <i class="material-icons" @click="showBookComments($event, book)">comment</i>
                            <i class="material-icons" @click="showBookStats($event, book)">timeline</i>
                        </div>
                    </li>
                </ul>
                <br>
                <button @click="newBook">This is a different book</button>
            </div>
        </div>
        <div class="book-owned" v-if="status == 202">
            <div class="modal-body">
                <h3>{{ response.message }}</h3>
                <button @click="openBook(response.book)">
                    Open {{ response.book.title }}
                </button>
                <button @click="addAnother">Add another</button>
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
            }
        },
        computed: {
            ...mapGetters({
            })
        },
        methods: {
            addAnother() {
                this.$emit('addAnother')
            },
            openBook(book) {
                this.setSelectedBook(book.id)
                this.setContent('book')
            },
            addToLibrary() {
                this.$emit('inLibrary', this.book)
            },
            newBook() {
                this.$emit('newBook')
            },
            ...mapActions({
                toggleModal: 'toggleModal',
                setSelectedBook: 'setSelectedBook',
                setContent: 'setContent'
            })
        }
    }
</script>