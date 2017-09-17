<template>
    <div class="item-info" @click="openBook">
        <span class="date">{{ item.updated_at }}</span>
        <br>
        <span class="item-body">You added a book titled <strong>{{ item.title }}</strong> written by <strong class="clickable-text" @click="openAuthor($event)">{{ item.author }}</strong></span>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        props: [
            'index',
            'item'
        ],
        data() {
            return {
                
            }
        },
        computed: {
            ...mapGetters({
                selectedBook: 'getSelectedBook'
            })
        },
        methods: {
            openAuthor(event) {
                event.stopPropagation()
                this.setSelectedBook(this.item.id)
                this.setSelectedAuthor(this.selectedBook.author.id)
                this.setContent('author')
            },
            openBook() {
                this.setSelectedBook(this.item.id)
                this.setContent('book')
            },
            ...mapActions({
                setContent: 'setContent',
                setSelectedBook: 'setSelectedBook',
                setSelectedAuthor: 'setSelectedAuthor'
            })
        }
    }
</script>

