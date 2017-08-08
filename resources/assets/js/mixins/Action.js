import { mapGetters, mapActions } from 'vuex'

export default {
    props: {
        shouldProcess: {
          type: Boolean,
          required: true
        }
    },
    data() {
        return {
            prompts: [],

            breakers: [],

            fallbacks: [],
            finished: false
        }
    },
    computed: {
        showFallbacks() {
            return this.finished
        },
        prompt() {
            if (!this.sessionInteractionsCount) {
                return `Hey ${this.user.friendly_name}, ${this.prompts[Math.floor(Math.random() * this.prompts.length)]}`
            }

            return this.prompts[Math.floor(Math.random() * this.prompts.length)]
        },
        ...mapGetters({
            user: 'getUser',
            userInput: 'getUserInput',
            sessionInteractionsCount: 'getSessionInteractionsCount',
        })
    },
    methods: {
        __respond(action, prompt) {
            this.$emit('input-processed')
            if (action) {
                this.__setNextAction(action)
                return
            }
            this.$emit('prompt', prompt)
        },
        __setNextAction(action) {
            this.finished = true
            this.$emit('input-processed')
            this.setAction(action)
        },
        __getBook(title) {
            let book = null
            const self = this
            if (this.books.length) {
                this.books.some((b) => {
                    if (self.userInput.includes(b.title)) {
                        book = b
                    }
                    return self.userInput.includes(b.title)
                })
            }

            return book
        },

        processInput() {

        },

        getResults() {

        },

        tryToBreak() {
            let breaker = null
            const self = this

            this.breakers.some(option => {
                if (self.userInput.includes(option)) {
                    breaker = option
                }
                return self.userInput.includes(option)
            })
            if (breaker) {
                return breaker
            }

            return this.checkFallbacks()
        },

        breakAction(action) {
            if (this.breakers.indexOf(action) > -1 || !action) {
                this.__setNextAction('default_action')
                return
            }

            this.__setNextAction(action)
        },

        checkFallbacks() {
            let fallback = null
            const self = this

            this.fallbacks.some(option => {
                if (self.userInput.includes(option.command)) {
                    fallback = option.command
                }
                return self.userInput.includes(option.command)
            })
            return fallback
        },
        ...mapActions({
            setAction: 'setAction',
            addUserInput: 'addUserInput',
        })
    },

    watch: {
        shouldProcess() {
            if (this.shouldProcess) {
                this.processInput()
            }
        }
    },

    mounted() {
        this.$emit('prompt', this.prompt)
    }
}