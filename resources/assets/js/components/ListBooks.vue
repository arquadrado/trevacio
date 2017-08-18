<template>
    <div class="content-wrapper">
        <div class="modal-header">
            <h3 class="action">I will LIST these fuckers</h3>
            <button class="modal-default-button" @click="setContent('trevacio')">close</button>
        </div>
        <div class="modal-body" v-if="showList">
            <h3>{{ message }}</h3>
            <ul class="book-list" v-if="hasBooks">
                <li class="book" v-for="book in list" @click="openBook(book)">
                    <span>{{ `${book.title} - ${book.author.name}` }}</span>
                </li>
            </ul>
            <h4 v-else>No books to show</h4>
        </div>
        <div class="modal-body" v-else>
            <h3>{{ message }}</h3>
            <button v-for="(list, name) in lists" @click="setSelectedList(name)">{{ name }}</button>
        </div>
        <div class="modal-footer">
            <button class="modal-default-button" @click="setSelectedList(null)" v-if="showList">Back</button>
            <button class="modal-default-button" @click="searchBook">Search book</button>
            <button class="modal-default-button" @click="addBook">Add book</button>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
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

