<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container" :style="style">

                    <h3>{{ message }}</h3>
                    <div class="actions" v-if="actions">
                        <button v-for="action in actions" @click="action.callback">{{ action.label }}</button>
                    </div>
                    <button v-else @click="toggleModal">close</button>
                </div>
            </div>
        </div>
  </transition>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    

    export default {
        components: {
    
        },
        computed: {
            message() {
                return this.content.message ? this.content.message : 'Something must be wrong.' 
            },
            actions() {
                return this.content.actions ? this.content.actions : null
            },
            style() {
                return {
                    'background-color': this.colorScheme.background,
                    'color': this.colorScheme.details,
                    'border': `3px solid ${this.colorScheme.details}`
                }
            },
            ...mapGetters({
                content: 'getModalContent',
                colorScheme: 'getColorScheme',
            })
        },
        methods: {
            ...mapActions({
                toggleModal: 'toggleModal'
            })
        }
    }
</script>