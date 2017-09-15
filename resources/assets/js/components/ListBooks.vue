<template>
    <div class="content-wrapper">
        <div class="modal-header">
            <h3 class="action">Library</h3>
            <button @click="searchBook"><span class="clickable-text">Search</span></button>
            <button @click="addBook"><span class="clickable-text">Add</span></button>
            <button @click="setContent('home')"><span class="clickable-text">Close</span></button>
            <button v-if="hasHistory" @click="back"><span class="clickable-text">Back</span></button>
        </div>
        <div class="modal-body" v-if="showList">
            <div class="body-controls">
                <button v-for="(list, name) in lists" :disabled="listIsSelected(name)" @click="setSelectedList(name)"><span class="clickable-text">{{ name }}</span></button>
            </div>
            <ul class="book-list" v-if="hasBooks">
                <li class="book" v-for="book in list" @click="openBook(book)">
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
            <h4 v-else>No books to show</h4>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import Navigation from './../mixins/Navigation.js'

    export default {
        mixins: [Navigation],
        data() {
            return {}
        },
        computed: {
            message() {
                if (this.showList) {
                    switch(this.listName) {
                        case 'userCollection':
                            return 'Here are YOUR books'
                        case 'library':
                            return 'Here are ALL books'
                        default:
                            return 'Here are SOME books'
                    }
                }

                return 'Choose a list'
            },
            hasBooks() {
                return this.showList ? Object.keys(this.list).length : false
            },
            showList() {
                return this.list !== null && typeof this.list !== 'undefined'
            },
            ...mapGetters({
                list: 'getSelectedList',
                listName: 'getSelectedListName',
                lists: 'getLists'
            })
        },
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
            listIsSelected(name) {
                return this.listName === name
            },
            addBook() {
                this.setSelectedBook(null)
                this.setContent('add')
            },
            searchBook() {
                this.setSelectedBook(null)
                this.setContent('get')
            },
            openBook(book) {
                this.setSelectedBook(book.id)
                this.setContent('book')
            },
            ...mapActions({
                setContent: 'setContent',
                setSelectedBook: 'setSelectedBook',
                setSelectedList: 'setSelectedList',
                setCurrentCommentList: 'setCurrentCommentList'
            })
        }
    }
</script>

