<template>
    <li class="comment" @click="selectComment(item)">
        <div class="comment-info">
            <span class="comment-user">{{ item.user.name }} - {{ typeof item.updated_at !== 'undefined' ? item.updated_at : 'just now' }}</span>
            <br>
            <span class="comment-body">{{ item.body }}</span>
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
            selectComment(comment) {
                this.setSelectedComment(comment)
                this.setContent('comment')
            },
            ...mapActions({
                setContent: 'setContent',
                setSelectedComment: 'setSelectedComment'
            })
        }
    }
</script>

