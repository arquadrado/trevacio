<template>
    <div class="content-wrapper">

        <div class="modal-header">
            <h3 class="action">{{ selectedBook.title }}</h3>
            <h4>reading sessions</h4>
            <br>
            <button class="" v-if="hasHistory" @click="back">Back</button>
            <button class="" @click="setContent('book')">Book</button>
        </div>
        <div class="modal-body">
            <div class="body-controls">
                <button class="" @click="addReadingSession">Add reading session</button>
                <button v-if="showSessions" @click="toggleListToShow">See all notes</button>
                <button v-if="showNotes" @click="toggleListToShow">See all sessions</button>
            </div>
            <list 
                :className="listClass" 
                :itemType="listItemType"
                :items="items"
            ></list>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import Navigation from './../mixins/Navigation.js'
    import List from './utilities/List.vue'

    export default {
        mixins: [Navigation],
        components: {
            'list': List
        },
        data() {
            return {
                itemsToShow: 'sessions'
            }
        },
        computed: {
            showNotes() {
                return this.itemsToShow == 'notes'
            },
            showSessions() {
                return this.itemsToShow == 'sessions'
            },
            listItemType() {
                return this.itemsToShow == 'sessions' ? 'session-item' : 'note-item'
            },
            listClass() {
                return this.itemsToShow == 'sessions' ? 'session-list' : 'note-list'
            },
            allNotes() {
                return this.selectedBook.reading_sessions.reduce((reduced, session) => {
                    session.notes.forEach((note) => {
                        reduced.push(note)
                    })
                    return reduced
                }, [])
            },
            items() {
                return this.itemsToShow == 'sessions' ? this.selectedBook.reading_sessions : this.allNotes
            },
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
            toggleListToShow() {
                this.itemsToShow = this.itemsToShow == 'sessions' ? 'notes' : 'sessions'
            },
            addReadingSession() {
                this.setSelectedReadingSession(null)
                this.setContent('reading-session')
            },
            selectSession(index) {
                this.setSelectedReadingSession(index)
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