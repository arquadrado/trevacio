<template>
    <div class="item-info" @click="openSession">
        <span class="date">{{ item.updated_at }}</span>
        <br>
        <span class="item-body">You have had a reading session of {{item.pages}} pages in the book <strong>{{item.book.title}}</strong> written by <strong class="clickable-text">{{item.book.author}}</strong></span>
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
            getSessionIndex() {
                for (let i = 0; i < this.selectedBook.reading_sessions.length; i++) {
                    if (this.item.id == this.selectedBook.reading_sessions[i].id) {
                        return i
                    }
                }
            },
            openSession() {
                this.setSelectedBook(this.item.book.id)
                console.log(this.getSessionIndex())
                this.setSelectedReadingSession(this.getSessionIndex())
                this.setContent('reading-session')
            },
            ...mapActions({
                setContent: 'setContent',
                setSelectedBook: 'setSelectedBook',
                setSelectedReadingSession: 'setSelectedReadingSession' 
            })
        }
    }
</script>

