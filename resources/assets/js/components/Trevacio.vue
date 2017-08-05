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
            :userInput="userInput"
            @prompt="__setLine"
            @processed="__toggleShouldProcess"
        ></component>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import DefaultAction from '../trevacio/actions/DefaultAction.vue'
    import GetBook from '../trevacio/actions/GetBook.vue'

    export default {
        components: {
            'default_action': DefaultAction,
            'get_book': GetBook
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
                console.log(line)
                this.trevacioLine = line
            },
            __toggleShouldProcess() {
                console.log('sje')
                this.shouldProcess = false
            },
            act() {
                this.shouldProcess = true
                /*switch(this.guess()) {
                    case 'book':
                        this.getBook()
                        break

                    case 'books':
                        this.getBooks()
                        break

                    case 'joke':
                        this.getJoke()
                        break

                    default:
                        this.getDefaultAnswer()
                        break
                }*/

                this.userInput = ''
                $('.fake-input').first().text('')
            },
            processAnswer(event) {
                this.userInput = event.target.innerText
            },

            guess() {
                /*const self = this
                this.actions.some((action) => {
                    console.log(self.userInput.includes(action), action, self.userInput)
                    if (self.userInput.includes(action)) {
                        //self.selectedAction = action
                    }

                    return self.userInput.includes(action)
                })

                return this.selectedAction*/
            },

            getBook() {
                console.log('Fetching a new book')
                this.trevacioLine = 'Is that a new book?'
            },

            getBooks() {
                console.log('Fetching all the books')
                this.trevacioLine = 'I ain\'t no time for that!'
            },

            getJoke() {
                console.log('Are we here to play?')
                this.trevacioLine = 'You are made to the steak!'
            },

            getDefaultAnswer() {
                console.log('Ooops')
                this.trevacioLine = 'I can\'t deal with this right now'
            },

        },

        directives: {
            focus: {
                inserted: function (el) {
                    el.focus()
                }
            }
        },
        mounted() {
            console.log('What?.')
        }
    }
</script>
