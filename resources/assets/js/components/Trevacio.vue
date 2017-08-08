<template>
    <div class="book-keeper">
        <div class="interaction">
            <span class="prompt">{{ trevacioLine }}</span>
            <br>
            <span class="fake-input" contenteditable="true" v-focus @keyup.enter="act" @input="processAnswer"></span>
        </div>
        <component
            :is="selectedAction"
            :shouldProcess="shouldProcess"
            @prompt="__setLine"
            @input-processed="__toggleShouldProcess"
        ></component>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import DefaultAction from '../trevacio/actions/DefaultAction.vue'
    import GetBook from '../trevacio/actions/GetBook.vue'
    import AddBook from '../trevacio/actions/AddBook.vue'
    import ListBooks from '../trevacio/actions/ListBooks.vue'

    export default {
        components: {
            'default_action': DefaultAction,
            'get_book': GetBook,
            'add': AddBook,
            'list': ListBooks,
        },
        props: [
        ],
        data() {
            return {
                actions: [
                    'book',
                    'books',
                    'joke'
                ],
                trevacioLine: '',
                results: false,
                userInput: '',
                shouldProcess: false
            }
        },
        computed: {
            ...mapGetters({
                selectedAction: 'getSelectedAction'
            })
        },
        methods: {
            __setLine(line) {
                this.trevacioLine = line
            },
            __toggleShouldProcess() {
                this.shouldProcess = false
            },
            act() {
                this.shouldProcess = true
                this.addUserInput(this.userInput)
                this.userInput = ''
                $('.fake-input').first().text('')
            },
            processAnswer(event) {
                this.userInput = event.target.innerText
            },
            ...mapActions({
                addUserInput: 'addUserInput'
            })

        },

        //for some reason this watch is breaking functionality
        /*watch: {
            selectedAction() {
                $('.fake-input').focus()
            }
        },*/

        directives: {
            focus: {
                inserted: function (el) {
                    el.focus()
                },
                updated: (el) => {
                    console.log('updated')
                    el.focus()
                }
            }
        },
        mounted() {
            console.log('What?.')
        }
    }
</script>
