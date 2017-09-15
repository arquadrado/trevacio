<template>
    <div class="content-wrapper">
        <div class="modal-header">
            <h3 class="action">{{ user.name }}</h3>
            <h4>stats</h4>
            <br>
            <button @click="back" v-if="hasHistory"><span class="clickable-text">Back</span></button>
        </div>

        <div class="modal-body" v-if="hasStatsToShow">
            <div class="stats book-user-stats">
                <span>You have read <strong>{{ user.stats.page_average }}</strong> in <strong>{{user.stats.timespan}}</strong> days in <strong>{{user.stats.session_count}}</strong> sessions</span><br>
                <span>You read an average of <strong>{{ user.stats.page_per_day_average }}</strong> pages per day</span><br><br>
                <span><strong>Distribution:</strong></span><br><br>
                <ul class="distribution">
                    <li v-for="(sessionData, day) in user.stats.distribution">
                        <div class="day">{{ day }} </div>
                        <div class="bar" v-bar:data="getDistributionRepresentation(sessionData.pages)">
                            <span :style="barStyle"></span>
                        </div>
                        <span>{{ sessionData.pages }}</span>
                    </li>
                </ul>
                <br>
            </div>
        </div>
        <div class="modal-body" v-else>
            <h3>No stats to show</h3>
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