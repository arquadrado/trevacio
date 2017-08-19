<template>
    <div class="content-wrapper">
        <div class="book" v-if="selectedBook">
            <div class="modal-header">
                <h3 class="action">Book - {{ selectedBook.title }}</h3>
                <button class="modal-default-button" @click="close">close</button>
            </div>
            <div class="modal-body">
                <span><strong>Title:</strong> {{ selectedBook.title }} </span><br>
                <span><strong>Author:</strong> {{ selectedBook.author.name }} </span><br><br>
                <div class="book-actions" v-if="selectedBook.in_library">
                    <button @click="setContent('reading-session-list')">Reading Sessions</button>
                    <button @click="setContent('stats')">Stats</button>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-default-button" @click="searchBook">Search book</button>
                <button class="modal-default-button" @click="addBook">Add a new book</button>
                <button class="modal-default-button" v-if="selectedList" @click="setContent('list')">Back to list</button>
                <div class="book-not-owned" v-if="!selectedBook.in_library">
                    <h4>This book is not in your library. Add it to perform actions</h4>
                    <button class="modal-default-button" @click="addBookToUserCollection">
                        Add book
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        computed: {
            ...mapGetters({
                selectedBook: 'getSelectedBook',
                selectedList: 'getSelectedList'
            })
        },
        methods: {
            close() {
                this.setSelectedBook(null)
                this.setContent('trevacio')
            },
            addBook() {
                this.setSelectedBook(null)
                this.setContent('add')
            },
            searchBook() {
                this.setSelectedBook(null)
                this.setContent('get')
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
            ...mapActions({
                setContent: 'setContent',
                setSelectedReadingSession: 'setSelectedReadingSession',
                addToUserCollection: 'addToUserCollection',
                setSelectedBook: 'setSelectedBook',
                toggleModal: 'toggleModal'
            })
        }
    }
</script>