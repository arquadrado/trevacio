<template>
	<div class="display">
		<div class="results">
			<div class="showcase" v-if="showResults">
				{{ results }}
				<div class="continue-interaction" @click="setAction('default_action')">
					<span>Continue</span>
				</div>
			</div>
		</div>
		<div class="fallbacks" v-if="showFallbacks">
			<div class="fallback" v-for="fallback in fallbacks" @click="setAction(fallback)">
				{{ fallback }}
			</div>
		</div>
	</div>
</template>

<script>
	import { mapGetters, mapActions } from 'vuex'

	export default {
		name: 'get_book',
		props: [
			'shouldProcess',
			'userInput'
		],
		data() {
			return {
				prompts: [
					'What book can I get you?',
					'What have you been reading?',
					'What did you read today?'
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
					'not_found',
					'default_action'
				],

				processed: false,
				results: null
			}
		},
		computed: {
			showResults() {
				return this.processed && this.results
			},
			showFallbacks() {
				return this.processed && !this.results
			},
			prompt() {
				if (!this.sessionInteractionsCount) {
					return `Hey ${this.user.friendly_name}, ${this.prompts[Math.floor(Math.random() * this.prompts.length)]}`
				}

				return this.prompts[Math.floor(Math.random() * this.prompts.length)]
			},
			...mapGetters({
				user: 'getUser',
				sessionInteractionsCount: 'getSessionInteractionsCount'
			})
		},
		methods: {
			processInput() {
				if (this.tryToBreak()) {
					return this.breakAction()
				}

				this.getResults()
			},
			getResults() {
				if (false) {
					this.results = 'Here is the fuking book you asked'
					this.$emit('prompt', 'There you go fuker')
					this.processed = true
					return
				}

				this.$emit('prompt', 'What...?')
				this.processed = true

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

				return breaker
			},
			breakAction() {
				this.setAction('default_action')
			},
			continueTo(action) {
				this.setAction(action)
			},
			...mapActions({
				setAction: 'setAction'
			})
		},

		watch: {
			shouldProcess() {
				this.processInput()
				this.$emit('processed')
			}
		},

		mounted() {
			this.$emit('prompt', this.prompt)
		}

	}
</script>