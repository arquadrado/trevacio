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
	import Action from '../../mixins/Action.js'

	export default {
		name: 'list',
		mixins: [Action],
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
				actions: [
					'add',
					'get',
					'default'
				]
			}
		},
		computed: {
			showResults() {
				return this.finished && this.books.length
			},
			showFallbacks() {
				return this.finished && !this.books.length
			},
			...mapGetters({
				books: 'getBooks'
			})
		},
		methods: {
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
			...mapActions({})
		}
	}
</script>