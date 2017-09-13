<template>
    <div class="content-wrapper">
        <div class="modal-header">
            <h3 class="action">{{ selectedBook.title }}</h3>
            <h4>stats</h4>
            <br>
            <button class="" @click="setContent('book')">Book</button>
            <button class="" @click="back" v-if="hasHistory">Back</button>
            <button class="" @click="close">close</button>
        </div>
        <div class="nav-arrows">
            <span class="prev" @click="previousBook"><i class="material-icons">arrow_back</i></span>
            <span class="next" @click="nextBook"><i class="material-icons">arrow_forward</i></span>
        </div>
        <div class="modal-body" v-if="hasStatsToShow">
            <div class="body-controls">
                <button class="second-order-button" @click="toggleStatsToShow" :disabled="statsToShow !== 'all'" v-if="selectedBook.in_library">User Stats</button>
                <button class="second-order-button" @click="toggleStatsToShow" :disabled="statsToShow === 'all'">Book stats</button>
                
            </div>
            <div class="stats book-user-stats" v-if="hasUserStats">
                <span>You read <strong>{{ selectedBook.book_user_stats.page_average }}</strong> of this book in <strong>{{selectedBook.book_user_stats.timespan}}</strong> days in <strong>{{selectedBook.book_user_stats.session_count}}</strong> sessions</span><br>
                <span>You read an average of <strong>{{ selectedBook.book_user_stats.page_per_day_average }}</strong> pages per day</span><br><br>
                <span><strong>Distribution:</strong></span><br><br>
                <ul class="distribution">
                    <li v-for="(count, day) in selectedBook.book_user_stats.distribution">
                        <div class="day">{{ day }} </div>
                        <div class="bar" v-bar:data="getDistributionRepresentation(count)">
                            <span :style="barStyle"></span>
                        </div>
                        <span>{{ count }}</span>
                    </li>
                </ul>
                <br>
            </div>
            <div class="stats book-stats" v-else>
                <span>This book was read by <strong>{{ selectedBook.book_stats.users }}</strong> persons with an average of <strong>{{ selectedBook.book_stats.page_average }}</strong> pages per person and <strong>    {{ selectedBook.book_stats.page_per_day_average }}</strong> pages per day</span><br>
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
                statsToShow: 'user'
            }
        },
        computed: {
            hasUserStats() {
                return this.statsToShow === 'user' && this.selectedBook.book_user_stats
            },
            hasStatsToShow() {
                return this.selectedBook !== null && this.selectedBook.book_stats
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
            prev() {
                this.previousBook()
                this.$forceUpdate()
            },
            next() {
                this.nextBook()
                this.$forceUpdate()
            },
            getDistributionRepresentation(value) {
                return {
                    'longest': this.user.longest_session,
                    'count': value
                }
            },
            toggleStatsToShow() {
                this.statsToShow = this.statsToShow === 'all' ? 'user' : 'all'
            },
            close() {
                this.setSelectedBook(null)
                this.setContent('trevacio')
            },
            ...mapActions({
                setContent: 'setContent',
                setSelectedBook: 'setSelectedBook',
                toggleModal: 'toggleModal',
                nextBook: 'nextBook',
                previousBook: 'previousBook',
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
        mounted() {
            if (!this.selectedBook.in_library) {
                this.statsToShow = 'all'
            }
            const self = this
            window.addEventListener('keyup', (event) => {
                switch (event.keyCode) {
                    case 37:
                        self.prev()
                        break
                    case 39:
                        self.next()
                        break
                }
            })
        }
    }
</script>