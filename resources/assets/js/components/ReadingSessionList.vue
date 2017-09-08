<template>
    <div class="content-wrapper">

        <div class="modal-header">
            <h3 class="action">{{ selectedBook.title }} session list</h3>
            <button class="" v-if="hasHistory" @click="back">Back</button>
            <button class="" @click="setContent('book')">Book</button>
        </div>
        <div class="modal-body">
            <div class="body-controls">
                <button class="" @click="addReadingSession">Add reading session</button>
            </div>
            <ul class="session-list">
                <li class="session" 
                    v-for="session in selectedBook.reading_sessions" 
                    @click="selectSession(session)"
                >
                    <div class="session-info">
                        <span class="session-date">{{ session.date }}</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import Navigation from './../mixins/Navigation.js'

    export default {
        mixins: [Navigation],
        data() {
            return {

            }
        },
        computed: {
            inputStyle() {
                return {
                    'border-bottom': `3px solid ${this.colorScheme.details}`
                }
            },

            ...mapGetters({
                selectedBook: 'getSelectedBook',
                colorScheme: 'getColorScheme'
            })
        },
        methods: {
            addReadingSession() {
                this.setSelectedReadingSession(null)
                this.setContent('reading-session')
            },
            selectSession(session) {
                this.setSelectedReadingSession(session)
                this.setContent('reading-session')
            },
            ...mapActions({
                toggleModal: 'toggleModal',
                setContent: 'setContent',
                setSelectedReadingSession: 'setSelectedReadingSession'
            })
        }
    }
</script>