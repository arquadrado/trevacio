<template>
    <div class="content">
        <transition name="slide">
            <component :is="content"></component>
        </transition>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import Trevacio from './Trevacio.vue'
    import Add from './AddBook.vue'
    import Get from './GetBook.vue'
    import ReadingSession from './ReadingSession.vue'
    import ReadingSessionList from './ReadingSessionList.vue'
    import ListBooks from './ListBooks.vue'
    import Settings from './Settings.vue'
    import Book from './Book.vue'
    import Stats from './Stats.vue'
    import CommentList from './CommentList.vue'
    import AddComment from './AddComment.vue'
    import Comment from './Comment.vue'
    import BookInfo from './BookInfo.vue'

    export default {
        components: {
            'trevacio': Trevacio,
            'add': Add,
            'get': Get,
            'reading-session': ReadingSession,
            'reading-session-list': ReadingSessionList,
            'list': ListBooks,
            'settings': Settings,
            'book': Book,
            'stats': Stats,
            'comment-list': CommentList,
            'add-comment': AddComment,
            'comment': Comment,
            'book-info': BookInfo,
        },
        computed: {
            style() {
                return {
                    'background-color': this.colorScheme.background,
                    'color': this.colorScheme.details,
                    'border': `3px solid ${this.colorScheme.details}`
                }
            },
            ...mapGetters({
                colorScheme: 'getColorScheme',
                content: 'getContent'
            })
        },
        methods: {
            ...mapActions({
                toggleModal: 'toggleModal'
            })
        }
    }
</script>