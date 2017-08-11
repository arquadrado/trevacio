<template>
    <li class="action"
        :style="style"
        @mouseenter="hoverOn"
        @mouseleave="hoverOff"
        @click="toggleModal"
    >
        <i class="material-icons">{{ icon }}</i>
    </li>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        props: ['action'],
        data() {
            return {
                icons: {
                    add: 'playlist_add',
                    get: 'arrow_downward',
                    list: 'list',
                    remove: 'remove'
                },
                hover: false
            }
        },
        computed: {
            icon() {
                if (this.icons.hasOwnProperty(this.action)) {

                    return this.icons[this.action]
                }

                return 'bug_report'
            },
            style() {
                if (this.hover) {
                    return {
                        'background-color': this.colorScheme.details,
                        'color': this.colorScheme.background,
                        'border': `3px solid ${this.colorScheme.background}`
                    }
                }
                return {
                    'background-color': this.colorScheme.background,
                    'color': this.colorScheme.details,
                    'border': `3px solid ${this.colorScheme.details}`
                }
            },
            ...mapGetters({
                colorScheme: 'getColorScheme'
            })
        },
        methods: {

            hoverOn() {
                this.hover = true
            },
            hoverOff() {
                this.hover = false
            },
            ...mapActions({
                toggleModal: 'toggleModal'
            })
        }
    }
</script>