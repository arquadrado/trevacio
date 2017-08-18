<template>
    <div class="content-wrapper" v-if="hasStatsToShow">
        <div class="modal-header">
            <h3 class="action">Stats - {{ selectedBook.title }}</h3>
            <button class="modal-default-button" @click="close">close</button>
            <button class="modal-default-button" @click="setContent('book')">back</button>
        </div>
        <div class="modal-body">
            <div class="stats book-user-stats" v-if="statsToShow === 'user'">
                <span>You read <strong>{{ selectedBook.book_user_stats.page_average }}</strong> of this book</span><br>
                <span>You read an average of <strong>{{ selectedBook.book_user_stats.page_per_day_average }}</strong> pages per day</span><br>
                <span><strong>Distribution:</strong></span><br>
                <ul>
                    <li v-for="(count, day) in selectedBook.book_user_stats.distribution">
                        <span>{{ day }} </span><span :style="getDistributionRepresentation(count)"></span><span> {{ count }}</span>
                    </li>
                </ul>
                <br>
            </div>
            <div class="stats book-stats" v-else>
                <span><strong>General page average:</strong> {{ selectedBook.book_stats.page_average }}</span><br>
                <span><strong>General page per day average:</strong> {{ selectedBook.book_stats.page_per_day_average }}</span><br>
                <br>
            </div>
            <button @click="toggleStatsToShow">{{ statsToShow === 'all' ? 'User stats' : 'General Book stats' }}</button>
        </div>
        <div class="modal-footer">

        </div>
    </div>
    <div class="content-wrapper" v-else>
        <div class="modal-header">
            <h3 class="action">Stats</h3>
            <button class="modal-default-button" @click="close">close</button>
            <button class="modal-default-button" @click="setContent('book')">back</button>
        </div>
        <div class="modal-body">
            <h3>No stats to show</h3>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        data() {
            return {
                statsToShow: 'user'
            }
        },
        computed: {
            hasStatsToShow() {
                return this.selectedBook !== null && this.selectedBook.book_stats
            },
            ...mapGetters({
                selectedBook: 'getSelectedBook',
                colorScheme: 'getColorScheme'
            })
        },
        methods: {
            getDistributionRepresentation(value) {
                return {
                    'border': `5px solid ${this.colorScheme.details}`,
                    'width': `${value * 2}px`,
                    'display': 'inline-block'
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
                toggleModal: 'toggleModal'
            })
        }
    }
</script>