<template>
	<div class="display">
		<div class="results">
			<div class="showcase" v-if="showResults">
				<div class="book" v-for="book in books">
					<button>{{ book.title }} - {{ book.author }}</button>
				</div>
				<div class="continue-interaction" @click="__setNextAction('default_action')">
					<button>Continue</button>
				</div>
			</div>
		</div>
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
		name: 'list',
		props: [
			'shouldProcess'
		],
		data() {
			return {
				prompts: [
					'How to you want them?',
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
						command: 'get_book',
						label: 'Get book'

					},
					{
						command: 'add',
						label: 'Add book'

					},
					{
						command: 'default_action',
						label: 'Let\'s try again?'

					}
				],

				finished: false
			}
		},
		computed: {
			showResults() {
				return this.finished && this.books.length
			},
			showFallbacks() {
				return this.finished && !this.books.length
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
				books: 'getBooks'
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

					this.getResults()
				}
			},
			getResults() {
				
				this.finished = true
				this.__respond(null, 'Here they are')
				return

				this.__respond(null, 'What...?')

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
				console.log(fallback)
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
</script>