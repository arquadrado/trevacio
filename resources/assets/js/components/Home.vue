<template>
    <div class="content-wrapper">
        <h2 class="greeting">Welcome, {{ user.friendly_name }}</h2>
        <button @click="toggleHelp"><span class="clickable-text">{{ helpMessage }}</span></button>
        <div class="help" v-if="showHelp">
            <p><strong>Bkooper</strong>'s purpose is to track your personal reading habits and give you concrete date based on them. How many days have you spent reading the last book? How many pages per day you read? Was this book faster/easier to read than the previous one? Are you reading as much as you would want? Will you remember the conclusions, insights you got from a book one year from now? Will they be the same two years from now? Bkooper tries to give you some clues on what the answers to these questions might be.</p>

            <p>At the same time you can give your opinion on books and see what other people has been reading and what they say about their and your books.</p>

            <p>With <strong>Bkooper</strong> you can <button @click="setContent('add')"><span class="clickable-text">add</span></button> new books to the library, <button @click="setContent('get')"><span class="clickable-text">search</span></button> for a specific book or author or check the books currently in the <button @click="setContent('list')"><span class="clickable-text">library</span></button>.</p>

            <p>Within the <strong>book</strong> area you have multiple actions available. You can check the book's <strong>rating</strong>, <strong>info</strong>, <strong>reading sessions</strong>, <strong>comments</strong> and, if you have the book in your collection, its <strong>stats</strong>. You can also go to the <strong>author</strong> area.</p>

            <p>The book's <strong>rating</strong> is the average of all the ratings the book has. You can rate the book from 1 to 10 if you have the book in your collection.</p>

            <p>Bkooper gets(or tries to get) the book <strong>info</strong> from Wikipedia's api. By default it will try to find the author page in the English version of Wikipedia, search for the book among her\his works and displays the main excerpt of the book page. If it cannot find any information or if the information happens to be wrong you have the chance to specify the language to search in (by country code, pt, en, fr, etc) or to search by the book's title directly.</p>

            <p>A book's <strong>reading session</strong> is simply anytime you grab the book to read. For registering it you simply specify the page where you started, the page where finished and date. You can cheat this but what's the point? You can also add <strong>notes</strong> to every sessions. Session notes are whatever you want, they can be personal conclusions, critics, insights or eureka moments taken out of that particular session. Reading sessions and hence, session notes, are private. Nobody but you can access them.</p>

            <p><strong>Comments</strong> doesn't need much explaining, I think. It's simple where you and other people can give their thoughts and opinions about the book. These are public and everyone can see them even if they haven't the book in their library.</p>

            <p><strong>General book stats</strong> display how many persons have read (or are reading) this book, the number of pages read per person and the number of pages read per day. If you have the book in your collection and have reading sessions for this book you will have your <strong>personal stats</strong> for the book and a graphic display of the distribution of the reading session by date and number of pages. The timespan of a book reading is calculated by the difference between it last and first reading session date.</p>

            <p><strong>Author</strong>'s area gives you basic information about the author and has list of all the author's works in the library. The information about the author is also fetched from Wikipedia's api.</p>

            <p>Since some persons might have high eye sensitivity there is a feature that allows you to choose the colors of the page. By clicking the <i class="material-icons">palette</i> icon on the top of the page you will iterate through a set of color schemes. The default set has a lot of options but you can create your own in your preferences section.</p>

            <p>You can open a general <strong>meny</strong> that allows you to navigate directly to any of the app's sections by clicking on the <i class="material-icons">local_library</i> icon on the top of the page.</p>

            <p><strong>User section</strong> is where you can see your profile information, see your personal reading stats and ajust your preferences.</p>
    
            <button @click="toggleGui" class="cta"><strong><span class="clickable-text">Get started</span></strong></button>

        </div>
        <button @click="toggleHelp" v-if="showHelp"><span class="clickable-text">{{ helpMessage }}</span></button>
        <br>
        <br>
        <h3 class="action">Recent activity</h3>
        <div class="recent-activity">
            <h4>No activity to show</h4>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        data() {
            return {
            }
        },
        computed: {
            helpMessage() {
                if (this.showHelp) {
                    return 'Hide help'
                }

                return 'See what you can do'
            },
            ...mapGetters({
                user: 'getUser',
                showHelp: 'getShowHelp'
            })
        },
        methods: {
            ...mapActions({
                setContent: 'setContent',
                toggleGui: 'toggleGui',
                toggleHelp: 'toggleShowHelp'
            })
        }
    }
</script>