<template>
    <div class="content-wrapper">

        <div class="modal-header">
            <h3 class="action">I am a reading session</h3>
            <button @click="setModalComponent('get')">Back to book</button>
            <button @click="setModalComponent('reading-session-list')">Back to list</button>
        </div>
        <div class="modal-body">
            <div class="add-session" v-if="adding">
                <div class="input-text">
                    <label for="start">Starting page</label>
                    <input type="text" name="start" :style="inputStyle" v-model="session.start">
                </div>
                <div class="input-text">
                    <label for="end">Ending page</label>
                    <input type="text" name="end" :style="inputStyle" v-model="session.end">
                </div>
                <div class="input-text">
                    <label for="date">Date</label>
                    <input type="text" name="date" :style="inputStyle" v-model="session.date">
                </div>
            </div>
            <div class="session" v-else>
                <h3>Session of {{ selectedSession.date }}</h3>
                <span><strong>Starting page:</strong> {{ selectedSession.start }}</span><br>
                <span><strong>Ending page:</strong> {{ selectedSession.end }}</span><br>
                <span><strong>Pages read:</strong> {{ selectedSession.end - selectedSession.start }}</span><br>
            </div>
        </div>
        <div class="modal-footer">
            <button v-if="adding" class="modal-default-button" :disabled="!canSubmit" @click="saveSession">
                Save
            </button>
            <button v-else class="modal-default-button">Delete</button>

        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        data() {
            return {
                session: {
                    start: null,
                    end: null,
                    date: null
                }
            }
        },
        computed: {
            inputStyle() {
                return {
                    'border-bottom': `3px solid ${this.colorScheme.details}`
                }
            },
            adding() {
                return this.selectedSession === null
            },
            canSubmit() {
                return this.session.start !== null &&
                        this.session.end !== null &&
                        this.session.date !== null
            },
            ...mapGetters({
                selectedBook: 'getSelectedBook',
                selectedSession: 'getSelectedReadingSession',
                colorScheme: 'getColorScheme'
            })
        },
        methods: {
            saveSession() {
                console.log(this.selectedBook, 'selectedBook')
                this.saveReadingSession({
                    session: this.session,
                    bookId: this.selectedBook.id,
                    successCallback: (response, status, responseContent) => {
                        console.log('success')
                    },
                    errorCallback: (response, status, responseContent) => {
                        console.log('error')

                    } 
                })
            },
            ...mapActions({
                toggleModal: 'toggleModal',
                setModalComponent: 'setModalComponent',
                saveReadingSession: 'saveReadingSession'
            })
        }
    }
</script>