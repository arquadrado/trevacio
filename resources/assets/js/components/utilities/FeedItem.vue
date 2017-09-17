<template>
    <li class="list-item feed-item">
        <component :is="item.type" :item="item" :index="index"></component>
    </li>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import BookFeedItem from './BookFeedItem.vue'
    import SessionFeedItem from './SessionFeedItem.vue'
    import RatingFeedItem from './RatingFeedItem.vue'
    import CommentFeedItem from './CommentFeedItem.vue'
    import NoteFeedItem from './NoteFeedItem.vue'

    export default {
        components: {
            'book': BookFeedItem,
            'session': SessionFeedItem,
            'rating': RatingFeedItem,
            'comment': CommentFeedItem,
            'note': NoteFeedItem,
        },
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
            })
        },
        methods: {
            ...mapActions({
                setContent: 'setContent',
            })
        }
    }
</script>

