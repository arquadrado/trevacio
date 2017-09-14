<template>
    <li class="session" 
        @click="selectSession(index)"
    >
        <div class="session-info">
            <span class="session-date">{{ item.date }}</span>
        </div>
    </li>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        props: [
            'item',
            'index'
        ],
        data() {
            return {}
        },
        computed: {
            ...mapGetters({
                selectedBook: 'getSelectedBook'
            })
        },
        methods: {
            selectSession(index) {
                console.log(index)
                this.setSelectedReadingSession(index)
                this.setContent('reading-session')
            },
            ...mapActions({
                setContent: 'setContent',
                setSelectedReadingSession: 'setSelectedReadingSession'
            })
        }
    }
</script>

