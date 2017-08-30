<template>
    <div class="content-wrapper">
        <div class="book" v-if="selectedBook">
            <div class="modal-header">
                <h3 class="action">Book - {{ selectedBook.title }}</h3>
                <button class="" v-if="canDeleteBook" @click="deleteBook">Delete</button>
                <button class="" @click="searchBook">Search</button>
                <button class="" @click="addBook">Add</button>
                <button class="" v-if="selectedList" @click="setContent('list')">List</button>
                <button class="" @click="close">close</button>
                <button class="" v-if="hasHistory" @click="back">Back</button>
            </div>
            <div class="modal-body">
                <div class="book-info">
                    <span><strong>Title:</strong> {{ selectedBook.title }} </span><br>
                    <span><strong>Author:</strong> {{ selectedBook.author.name }} </span><br>
                </div>
                <div class="book-actions" v-if="selectedBook.in_library">
                    <button @click="setContent('reading-session-list')">Reading Sessions</button>
                    <button @click="setContent('stats')">Stats</button>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-default-button" @click="removeBookFromUserCollection" v-if="selectedBook.in_library">Remove from collection</button>
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
    import Navigation from './../mixins/Navigation.js'

    export default {
        mixins: [Navigation],
        computed: {
            canDeleteBook() {
                return this.selectedBook.can_delete == 1
            },
            ...mapGetters({
                selectedBook: 'getSelectedBook',
                selectedList: 'getSelectedList',
            })
        },
        methods: {
            deleteBook() {
                const self = this
                this.setModalContent({
                    message: 'Are you sure the want to delete this book?',
                    actions: [
                        {
                            label: 'Yes',
                            callback: () => {
                                self.deleteBookFromLibrary()
                                self.toggleModal()
                            }
                        },
                        {
                            label: 'no',
                            callback: () => {
                                self.toggleModal()
                            }
                        }
                    ]
                })
                this.toggleModal()
            },
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
            removeBookFromUserCollection(book) {
                const self = this
                self.removeFromUserCollection({
                    bookId: self.selectedBook.id,
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
                removeFromUserCollection: 'removeFromUserCollection',
                setSelectedBook: 'setSelectedBook',
                toggleModal: 'toggleModal',
                deleteBookFromLibrary: 'deleteBook',
                setModalContent: 'setModalContent'
            })
        }
    }
</script>