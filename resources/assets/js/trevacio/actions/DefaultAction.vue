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
		name: 'default_action',
		props: [
			'shouldProcess'
		],
		data() {
			return {
				prompts: [
					'What can I do for you today?',
					'Shall we begin?',
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
						command: 'list',
						label: 'List books'

					},
					{
						command: 'joke',
						label: 'Wanna hear a joke?'

					}
				],

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
				this.getResults()
			},
			getResults() {
				let toDo = ''
				this.fallbacks.some((fallback) => {
					if (this.userInput.includes(fallback.command)) {
						toDo = fallback.command
					}

					return this.userInput.includes(fallback.command)
				})

				if (toDo) {
					this.__respond(toDo, 'Alraite')
					return
				}

				this.__respond(null, 'Are you confused? This is what I am willing to do...')

				this.finished = true

			},
			...mapActions({
				setAction: 'setAction'
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