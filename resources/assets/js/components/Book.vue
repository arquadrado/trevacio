<template>
    <div class="content-wrapper">
        <div class="book" v-if="selectedBook">

            <div class="modal-header">
                <h3 class="action">Book</h3>
                <button class="" v-if="canDeleteBook" @click="deleteBook">Delete</button>
                <button class="" @click="searchBook">Search</button>
                <button class="" @click="addBook">Add</button>
                <button class="" v-if="selectedList" @click="setContent('list')">List</button>
                <button class="" @click="close">close</button>
                <button class="" v-if="hasHistory" @click="back">Back</button>
            </div>

            <div class="nav-arrows">
                <span class="prev" @click="previousBook"><i class="material-icons">arrow_back</i></span>
                <span class="next" @click="nextBook"><i class="material-icons">arrow_forward</i></span>
            </div>

            <div class="modal-body">
                <div class="book-info">
                    <h3><strong>{{ selectedBook.title }}</strong></h3>
                    <span>by<strong> {{ selectedBook.author.name }} </strong></span><br><br>
                    <div class="ratings">
                        <span>overall rating</span>
                        <div class="overall">
                            <span :style="{'color': colorScheme.background}"><strong>{{ selectedBook.overall_rating }}</strong></span>
                            <i class="material-icons">star</i>
                        </div>
                        <span v-if="selectedBook.in_library">your rating</span>
                        <div class="user" v-if="selectedBook.in_library">
                            <i class="material-icons" @click="rateBook(n)" v-for="n in 10">{{selectedBook.user_rating.length &&selectedBook.user_rating[0].rating >= n ? 'star' : 'star_border' }}</i>
                        </div>
                    </div>
                </div>
                <div class="book-actions">
                    <button @click="setContent('book-info')">Info</button>
                    <button v-if="selectedBook.in_library" @click="setContent('reading-session-list')">Reading Sessions</button>
                    <button @click="showComments">Comments</button>
                    <button @click="setContent('stats')">Stats</button>
                </div>
            </div>
            
            <div class="modal-footer">
                <button class="modal-default-button" @click="removeBookFromUserCollection" v-if="selectedBook.in_library">Remove from collection</button>
                <div class="book-not-owned" v-if="!selectedBook.in_library">
                    <h4>This book is not in your library. Add it to perform additional actions</h4>
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
        data() {
            return {
                
            }
        },
        computed: {
            userRating() {
                return this.selectedBook.user_rating[0].rating
            },
            canDeleteBook() {
                return this.selectedBook.can_delete == 1
            },
            ...mapGetters({
                selectedBook: 'getSelectedBook',
                selectedList: 'getSelectedList',
                colorScheme: 'getColorScheme'
            })
        },
        methods: {
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
                setModalContent: 'setModalContent',
                rateBook: 'rateBook',
                nextBook: 'nextBook',
                previousBook: 'previousBook',
                setCurrentCommentList: 'setCurrentCommentList'
            })
        }
    }
</script>