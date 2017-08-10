<template>
    <div class="silent-book-keeper">
        <ul class="actions-list" v-dimension>
            <li class="action" @mouseover="test()" :style="actionStyle"><i class="material-icons">playlist_add</i></li>
            <li class="action" :style="actionStyle"><i class="material-icons">arrow_downward</i></li>
            <li class="action" :style="actionStyle"><i class="material-icons">list</i></li>
            <li class="action" :style="actionStyle"><i class="material-icons">remove</i></li>
        </ul>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        components: {},
        props: [
        ],
        data() {
            return {}
        },
        computed: {
            listStyle() {
                return {
                    'left': 0
                }
            },
            actionStyle() {
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
                    $(el).find('.action').each((index, elem) => {
                        console.log($(elem))
                        $(elem).width(($(el).height() / 4) - 6)
                        $(elem).height(($(el).height() / 4) - 6)
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
