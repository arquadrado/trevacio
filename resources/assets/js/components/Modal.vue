<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container" :style="style">

                    <component :is="component"></component>

                </div>
            </div>
        </div>
  </transition>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import Add from './AddBook.vue'
    import Get from './GetBook.vue'
    import ReadingSession from './ReadingSession.vue'
    import ReadingSessionList from './ReadingSessionList.vue'
    import ListBooks from './ListBooks.vue'
    import Settings from './Settings.vue'

    export default {
        components: {
            'add': Add,
            'get': Get,
            'reading-session': ReadingSession,
            'reading-session-list': ReadingSessionList,
            'list': ListBooks,
            'settings': Settings
        },
        computed: {
            style() {
                return {
                    'background-color': this.colorScheme.background,
                    'color': this.colorScheme.details,
                    'border': `3px solid ${this.colorScheme.details}`
                }
            },
            ...mapGetters({
                colorScheme: 'getColorScheme',
                component: 'getModalComponent'
            })
        },
        methods: {
            ...mapActions({
                toggleModal: 'toggleModal'
            })
        }
    }
</script>