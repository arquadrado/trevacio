<template>
    <li class="book" @click="openBook(item)">
        <div class="book-info">
            <span class="book-title">{{ item.title }}</span>
            <br>
            <span class="book-author" v-if="item.author">by {{ item.author.name }}</span>
        </div>
        <div class="quick-actions">
            <i class="material-icons" @click="showBookComments($event, item)">comment</i>
            <i class="material-icons" @click="showBookStats($event, item)">timeline</i>
        </div>
    </li>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        props: [
            'item'
        ],
        data() {
            return {}
        },
        computed: {
            ...mapGetters({
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
            openBook(book) {
                this.setSelectedBook(book.id)
                this.setContent('book')
            },
            ...mapActions({
                setContent: 'setContent',
                setSelectedBook: 'setSelectedBook',
            })
        }
    }
</script>

