<template>
    <div class="item-info">
        <span class="date">{{ item.updated_at }}</span>
        <br>
        <span class="item-body">{{ `You have taken a note on the session of ${item.session.date} on the book ${item.book.title} written by ${item.book.author}` }}</span>
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
            })
        },
        methods: {
            ...mapActions({
                setContent: 'setContent',
            })
        }
    }
</script>

