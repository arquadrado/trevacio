<template>
    <div class="content-wrapper">
        <div class="modal-header">
            <h3 class="action">{{ user.name }}</h3>
            <h4>preferences</h4>
            <br>
            <button @click="back" v-if="hasHistory"><span class="clickable-text">Back</span></button>
        </div>

        <div class="modal-body">
            <div class="preferences">
                <h3>Preferences</h3>
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
            }
        },
        computed: {
            hasStatsToShow() {
                return this.user.stats
            },
            barStyle() {
                return {
                    'background-color': this.colorScheme.details
                }
            },
            ...mapGetters({
                selectedBook: 'getSelectedBook',
                colorScheme: 'getColorScheme',
                user: 'getUser'
            })
        },
        methods: {
            getDistributionRepresentation(value) {
                return {
                    'longest': this.user.longest_session,
                    'count': value
                }
            },
            ...mapActions({
                setContent: 'setContent',
            })
        },
        directives: {
            bar: {
                inserted: function (el, binding, vnode) {
                    let barWidth = ($(el).width() * binding.value.count) / binding.value.longest 
                    let $span = $(el).find('span')
                    $span.width(barWidth)
                },
                updated: (el) => {
                    console.log('updated', 'xis')
                },
                componentUpdated: (el, binding) => {
                    let barWidth = ($(el).width() * binding.value.count) / binding.value.longest 
                    let $span = $(el).find('span')
                    $span.width(barWidth)
                }
            }
        },
        mounted() {}
    }
</script>