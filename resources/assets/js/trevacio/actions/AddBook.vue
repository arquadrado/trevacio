<template>
	<div class="display">
		<div class="fallbacks" v-if="showFallbacks">
			<div class="fallback" v-for="fallback in fallbacks" @click="__setNextAction(fallback.command)">
				<button>{{ fallback.label }}</button>
			</div>
		</div>
	</div>
</template>

<script>
	import { mapGetters, mapActions } from 'vuex'

	export default {
		name: 'add',
		props: [
			'shouldProcess'
		],
		data() {
			return {
				prompts: [
					'What is the title of the book?',
				],

				breakers: [
					'nothing',
					'rien',
					'nada',
					'no',
					'nao',
					'exit'
				],

				fallbacks: [
					{
						command: 'list',
						label: 'List books'

					},
					{
						command: 'get_book',
						label: 'Get book'

					},
					{
						command: 'default_action',
						label: 'Let\'s try again?'

					}
				],
				book: null,
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
				sessionInteractionsCount: 'getSessionInteractionsCount'
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
			processInput() {
				this.finished = false
				if (this.userInput) {
					if (this.tryToBreak()) {
						return this.breakAction(this.tryToBreak())
					}
					if (this.book && this.book.hasOwnProperty('title')) {
						this.book.author = this.userInput
						this.addBook(this.book)
						this.book = null
						this.__respond(null, 'Done. What else?')
						this.finished = true
						return
					}
					this.book = {
						title: this.userInput
					}

					this.__respond(null, 'Who wrote it?')
				}
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
				addBook: 'addBook'
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
</script>