<template>
    <div class="content-wrapper">
        <div class="book" v-if="selectedBook">
            <div class="modal-header">
                <h3 class="action">Book</h3>
                <button @click="searchBook"><span class="clickable-text">Search</span></button>
                <button @click="addBook"><span class="clickable-text">Add</span></button>
                <button v-if="selectedList" @click="setContent('list')"><span class="clickable-text">List</span></button>
                <button @click="close"><span class="clickable-text">close</span></button>
                <button v-if="hasHistory" @click="back"><span class="clickable-text">Back</span></button>
            </div>

            <div class="nav-arrows">
                <span class="prev" @click="previousBook"><i class="material-icons">arrow_back</i></span>
                <span class="next" @click="nextBook"><i class="material-icons">arrow_forward</i></span>
            </div>

            <div class="modal-body">
                <div class="book-info">
                    <h3><strong>{{ selectedBook.title }}</strong></h3>
                    <span 
                        class="author" 
                        @click="selectAuthor(selectedBook.author.id)">by
                        <strong>
                            <span class="clickable-text">{{ selectedBook.author.name }}</span>
                        </strong>
                    </span>
                    <br>
                    <br>
                    <div class="ratings">
                        <div class="overall">
                            <span :style="{'color': colorScheme.background}"><strong>{{ selectedBook.overall_rating }}</strong></span>
                            <i class="material-icons">star</i>
                        </div>
                        <span>overall rating</span>
                        <br>
                        <br>
                        <div class="user" v-if="selectedBook.in_library">
                            <i class="material-icons" @click="rateBook(n)" v-for="n in 10">{{selectedBook.user_rating.length &&selectedBook.user_rating[0].rating >= n ? 'star' : 'star_border' }}</i>
                        </div>
                        <span v-if="selectedBook.in_library">your rating</span>
                    </div>
                </div>
                <div class="book-actions">
                    <button @click="setContent('book-info')"><span class="clickable-text">Info</span></button>
                    <button v-if="selectedBook.in_library" @click="setContent('reading-session-list')"><span class="clickable-text">Reading Sessions</span></button>
                    <button @click="showComments"><span class="clickable-text">Comments</span></button>
                    <button @click="setContent('stats')"><span class="clickable-text">Stats</span></button>
                </div>
            </div>
            
            <div class="modal-footer">
                <button @click="removeBookFromUserCollection" v-if="selectedBook.in_library"><span class="clickable-text">Remove from collection</span></button>
                <button v-if="canDeleteBook" @click="deleteBook"><span class="clickable-text">Delete</span></button>
                <div class="book-not-owned" v-if="!selectedBook.in_library">
                    <h4>This book is not in your library. Add it to perform additional actions</h4>
                    <button @click="addBookToUserCollection">
                        <span class="clickable-text">Add book</span>
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
        data() {
            return {
                
            }
        },
        computed: {
            userRating() {
                if (this.selectedBook.user_rating && this.selectedBook.user_rating.length) {

                    return this.selectedBook.user_rating[0].rating
                }
                return 0
            },
            canDeleteBook() {
                return this.selectedBook.can_delete == 1
            },
            ...mapGetters({
                selectedBook: 'getSelectedBook',
                selectedList: 'getSelectedList',
                colorScheme: 'getColorScheme',
                loading: 'isLoading'
            })
        },
        methods: {
            selectAuthor(id) {
                this.setSelectedAuthor(id)
                this.setContent('author')
            },
            showComments() {
                this.setCurrentCommentList('book')
                this.setContent('comment-list')
            },
            deleteBook() {
                const self = this
                this.setModalContent({
                    message: 'Are you sure the want to delete this book?',
                    actions: [
                        {
                            label: 'Yes',
                            callback: () => {
                                self.deleteBookFromLibrary()
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
                this.setContent('home')
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
                setModalContent: 'setModalContent',
                rateBook: 'rateBook',
                nextBook: 'nextBook',
                previousBook: 'previousBook',
                setCurrentCommentList: 'setCurrentCommentList',
                setSelectedAuthor: 'setSelectedAuthor' 
            })
        }
    }
</script>