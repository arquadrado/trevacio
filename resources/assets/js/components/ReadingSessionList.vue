<template>
    <div class="content-wrapper">

        <div class="modal-header">
            <h3 class="action">I am a reading session list</h3>
            <button @click="setModalComponent('get')">Back</button>
        </div>
        <div class="modal-body">
            <ul class="session-list">
                <li class="session" v-for="session in selectedBook.reading_sessions">
                    <span @click="selectSession(session)">{{ session.date }}</span>
                </li>
            </ul>
        </div>
        <div class="modal-footer">
            

        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
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
            selectSession(session) {
                this.setSelectedReadingSession(session)
                this.setModalComponent('reading-session')
            },
            ...mapActions({
                toggleModal: 'toggleModal',
                setModalComponent: 'setModalComponent',
                setSelectedReadingSession: 'setSelectedReadingSession'
            })
        }
    }
</script>