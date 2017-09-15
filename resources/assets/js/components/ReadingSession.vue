<template>
    <div class="content-wrapper">
        <div class="reading-session">
            <div class="modal-header">
                <h3 class="action">{{ title }}</h3>
                <h4 v-if="!adding">reading session</h4>
                <br>
                <button @click="setContent('book')"><span class="clickable-text">Book</span></button>
                <button class="" v-if="hasHistory" @click="back"><span class="clickable-text">Back</span></button>
                <button v-if="!adding" class="" @click="deleteSession"><span class="clickable-text">Delete</span></button>
            </div>
            <div class="modal-body">
                <div class="body-controls"></div>
                <div class="add-session" v-if="adding">
                    <div class="input-text">
                        <label for="start">Starting page</label>
                        <input type="number" min="1" name="start" :style="inputStyle" v-model="session.start">
                    </div>
                    <div class="input-text">
                        <label for="end">Ending page</label>
                        <input type="number" name="end" :style="inputStyle" v-model="session.end">
                    </div>
                    <div class="input-text">
                        <label for="date">Date</label>
                        <input type="date" name="date" :style="inputStyle" v-model="session.date">
                    </div>
                </div>
                <div class="session" v-else>
                    <span><strong>Starting page:</strong> {{ selectedSession.start }}</span><br>
                    <span><strong>Ending page:</strong> {{ selectedSession.end }}</span><br>
                    <span><strong>Pages read:</strong> {{ selectedSession.end - selectedSession.start }}</span><br>
                    <div class="book-actions">
                        <button @click="showSessionNotes">Notes</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button v-if="!adding" class="" @click="setSelectedReadingSession(null)"><span class="clickable-text">Add another</span></button>
                <button v-if="adding && !loading" class="" :disabled="!canSubmit" @click="saveSession">
                    <span class="clickable-text">Save</span>
                </button>
                <button v-if="adding && loading">
                    <loading-spinner></loading-spinner>
                </button>
            </div>
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
                session: {
                    start: null,
                    end: null,
                    date: null
                }
            }
        },
        computed: {
            title() {
                if (this.adding) {
                    return 'Add reading session'
                }

                return this.selectedSession.date
            },
            inputStyle() {
                return {
                    'border-bottom': `1px solid ${this.colorScheme.details}`
                }
            },
            adding() {
                return this.selectedSession === null || typeof this.selectedSession == 'undefined' 
            },
            canSubmit() {
                return this.session.start !== null &&
                        this.session.end !== null &&
                        this.session.date !== null
            },
            ...mapGetters({
                selectedBook: 'getSelectedBook',
                selectedSession: 'getSelectedReadingSession',
                colorScheme: 'getColorScheme',
                loading: 'isLoading'
            })
        },
        methods: {
            showSessionNotes() {
                this.setCurrentCommentList('session')
                this.setContent('comment-list')
            },
            deleteSession() {
                const self = this
                this.setModalContent({
                    message: 'Are you sure the want to delete this reading session?',
                    actions: [
                        {
                            label: 'Yes',
                            callback: () => {
                                self.deleteReadingSession()
                            }
                        },
                        {
                            label: 'no',
                            callback: () => {
                                self.toggleModal()
                            }
                        }
                    ]
                })
                this.toggleModal()
            },
            saveSession() {
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
                setContent: 'setContent',
                saveReadingSession: 'saveReadingSession',
                setSelectedReadingSession: 'setSelectedReadingSession',
                setModalContent: 'setModalContent',
                deleteReadingSession: 'deleteReadingSession',
                setCurrentCommentList: 'setCurrentCommentList'
            })
        }
    }
</script>