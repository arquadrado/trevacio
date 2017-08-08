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
		name: 'add',
		mixins: [Action],
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
			}
		},
		computed: {
			showFallbacks() {
				return this.finished
			},
			...mapGetters({})
		},
		methods: {
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
			...mapActions({
				addBook: 'addBook'
			})
		}
	}
</script>