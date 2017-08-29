<template>
    <div class="content-wrapper">
        <div class="modal-header">
            <h3 class="action">Library</h3>
            <button class="" @click="searchBook">Search</button>
            <button class="" @click="addBook">Add</button>
            <button class="" @click="setContent('trevacio')">close</button>
            <button class="" v-if="hasHistory" @click="back">Back</button>
        </div>
        <div class="modal-body" v-if="showList">
            <div class="body-controls">
                <button v-for="(list, name) in lists" :disabled="listIsSelected(name)" @click="setSelectedList(name)">{{ name }}</button>
            </div>
            <ul class="book-list" v-if="hasBooks">
                <li class="book" v-for="book in list" @click="openBook(book)">
                    <span>{{ `${book.title} - ${book.author.name}` }}</span>
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
                setContent: 'setContent',
                setSelectedList: 'setSelectedList'
            })
        }
    }
</script>

