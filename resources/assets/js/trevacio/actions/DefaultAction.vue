<template>
	<div class="display">
		<div class="actions" v-if="showActions">
			<div class="fallback" v-for="action in actions" @click="setAction(action)">
				{{ action }}
			</div>
		</div>
	</div>
</template>

<script>
	import { mapGetters, mapActions } from 'vuex'

	export default {
		name: 'default_action',
		props: [
			'shouldProcess',
			'userInput'
		],
		data() {
			return {
				prompts: [
					'What can I do for you today?',
					'Shall we begin?',
				],

				actions: [
					'get_book',
					'add_book',
					'list_books',
					'joke'
				],

				processed: false,
				results: null
			}
		},
		computed: {
			showActions() {
				return this.processed
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

				this.getResults()
			},
			getResults() {
				let toDo = ''
				this.actions.some((action) => {
					if (this.userInput.includes(action)) {
						toDo = action
					}

					return this.userInput.includes(action)
				})

				if (toDo) {
					this.setAction(toDo)
					this.$emit('prompt', 'Alraite!')
					return
				}

				this.$emit('prompt', 'Are you confused? This is what I am willing to do...')
				this.processed = true

			},
			...mapActions({
				setAction: 'setAction'
			})
		},

		watch: {
			shouldProcess() {
				console.log('gonna process default')
				this.processInput()
				this.$emit('processed')
			}
		},

		mounted() {
			this.$emit('prompt', this.prompt)
		}

	}
</script>