<template>
    <div class="silent-book-keeper">
        <ul class="actions-list" v-dimension:actionsCount="actionsCount">
            <gui-action
                :action="action"
                :key="action"
                v-for="(label, action) in availableActions"
            ></gui-action>
        </ul>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import GuiAction from './GuiAction.vue'
    export default {
        components: {
            'gui-action': GuiAction
        },
        props: [
        ],
        data() {
            return {}
        },
        computed: {
            actionsCount() {
                return Object.keys(this.availableActions).length
            },
            ...mapGetters({
                availableActions: 'getActions'
            })
        },
        methods: {
            test() {
                console.log('pato')
            },
            ...mapActions({
                addUserInput: 'addUserInput'
            })
        },

        directives: {
            dimension: {
                inserted: function (el, binding, vnode) {
                    console.log(binding)
                    $(el).find('.action').each((index, elem) => {
                        $(elem).width(($(el).height() / binding.value) - 6)
                        $(elem).height(($(el).height() / binding.value) - 6)
                        //$(elem).css('border-radius', ($(el).height() / 8))
                    })
                },
                updated: (el) => {
                    console.log('updated')
                }
            }
        },

        mounted() {
            console.log('Hello! I\'m Gui... Guilhotina!')
        }
    }
</script>
