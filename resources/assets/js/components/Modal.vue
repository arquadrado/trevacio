<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container" :style="style">

                    <div class="modal-header">
                        <slot name="header">
                          default header
                        </slot>
                    </div>

                    <div class="modal-body">
                        <slot name="body">
                         default body
                        </slot>
                    </div>

                    <div class="modal-footer">
                        <slot name="footer">
                        default footer
                            <button class="modal-default-button" @click="toggleModal">
                             OK
                            </button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
  </transition>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        computed: {
            style() {
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
            ...mapActions({
                toggleModal: 'toggleModal'
            })
        }
    }
</script>