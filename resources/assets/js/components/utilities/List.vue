<template>
    <ul :class="className" v-if="hasItems">
        <component 
            :is="itemType" 
            :item="item" 
            :index="index"
            :key="item.id"
            v-for="(item, index) in items" 
        ></component>
    </ul>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import BookItem from './BookItem.vue'
    import CommentItem from './CommentItem.vue'
    import NoteItem from './NoteItem.vue'
    import SessionItem from './SessionItem.vue'
    import ColorSchemeItem from './ColorSchemeItem.vue'

    export default {
        components: {
            'book-item': BookItem,
            'comment-item': CommentItem,
            'note-item': NoteItem,
            'session-item': SessionItem,
            'color-scheme-item': ColorSchemeItem,
        },
        props: [
            'className',
            'itemType',
            'items'
        ],
        data() {
            return {}
        },
        computed: {
            hasItems() {
                return this.items && this.items.length
            },
            ...mapGetters({
            })
        },
        methods: {
            ...mapActions({
            })
        }
    }
</script>

