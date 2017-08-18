<template>
    <li class="action"
        :style="style"
        @mouseenter="hoverOn"
        @mouseleave="hoverOff"
        @click="execute"
    >
        <div class="zone">
            <div class="zone-content">
                <i class="material-icons">{{ action.icon }}</i>
                <p class="action-description">{{ action.description }}</p>
            </div>
        </div>
    </li>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        props: ['action'],
        data() {
            return {
                hover: false
            }
        },
        computed: {
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
            execute() {
                this.setContent(this.action.name)
                this.toggleGui()
            },
            hoverOn() {
                this.hover = true
            },
            hoverOff() {
                this.hover = false
            },
            ...mapActions({
                setContent: 'setContent',
                toggleGui: 'toggleGui'
            })
        }
    }
</script>