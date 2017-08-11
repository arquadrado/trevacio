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
	import Action from '../../mixins/Action.js'

	export default {
		name: 'default_action',
		mixins: [Action],
		data() {
			return {
				prompts: [
					'What can I do for you today?',
					'Shall we begin?',
				],
				actions: [
					'get',
					'add',
					'list',
					'joke'
				]
			}
		},
		computed: {
			...mapGetters({
			})
		},
		methods: {
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
				this.__respond(null, 'Okay, this is what I can do for you...')
				this.finished = true
			},
			...mapActions({})
		}
	}
</script>