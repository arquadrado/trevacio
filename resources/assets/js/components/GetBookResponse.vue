<template>
    <div class="response">
        <div class="book-found" v-if="status == 201">
            <div class="modal-body">
                <h3>{{ response.message }}</h3>
                <ul class="book-list">
                    <li class="book" v-for="book in response.books" @click="openBook(book.id)">
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
                <button @click="searchAgain">Search again</button>
            </div>
        </div>
        <div class="book-owned" v-if="status == 404">
            <div class="modal-body">
                <h3>{{ response.message }}</h3>
                <button @click="setContent('add')">
                    Add book
                </button>
                <button @click="searchAgain">Search again</button>
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
            showBookComments(event, book) {
                event.stopPropagation()
                this.setSelectedBook(book.id)
                this.setCurrentCommentList('book')
                this.setContent('comment-list')
            },
            showBookStats(event, book) {
                event.stopPropagation()
                this.setSelectedBook(book.id)
                this.setContent('stats')
            },
            openBook(id) {
                this.setSelectedBook(id)
                this.setContent('book')
            },
            searchAgain() {
                this.$emit('searchAgain')
            },
            ...mapActions({
                toggleModal: 'toggleModal',
                setSelectedBook: 'setSelectedBook',
                setContent: 'setContent',
                setCurrentCommentList: 'setCurrentCommentList'
            })
        }
    }
</script>