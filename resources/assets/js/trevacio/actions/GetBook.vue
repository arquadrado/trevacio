<template>
	<div class="display">
		<div class="results">
			<div class="showcase" v-if="showResults">
				<button>{{ book.title }} - {{ book.author }}</button>
				<div class="continue-interaction" @click="__setNextAction('default_action')">
					<button>Continue</button>
				</div>
			</div>
		</div>
		<div class="fallbacks" v-if="showFallbacks">
			<div class="fallback" v-for="fallback in fallbacks" @click="__setNextAction(fallback.command)">
				<button>
					{{ fallback.label }}
				</button>
			</div>
		</div>
	</div>
</template>

<script>
	import { mapGetters, mapActions } from 'vuex'
	import Action from '../../mixins/Action.js'

	export default {
		name: 'get',
		mixins: [Action],
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
					'exit',
					'help'
				],
				actions: [
					'add',
					'list',
					'default'
				],
				book: null,
			}
		},
		computed: {
			showResults() {
				return this.finished && this.book
			},
			showFallbacks() {
				return this.finished && !this.book
			},
			...mapGetters({
				books: 'getBooks'
			})
		},
		methods: {
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
				this.finished = false
				if (this.userInput) {
					if (this.tryToBreak()) {
						return this.breakAction(this.tryToBreak())
					}

					this.getResults()
				}
			},
			getResults() {
				let book = this.__getBook(this.userInput)
				if (book) {
					this.book = book
					this.__respond(null, 'There you have it fucker')
					this.finished = true
					return
				}

				if (this.checkFallbacks()) {
					this.__respond(this.checkFallbacks())
					this.finished = true
					return
				}
				this.__respond(null, `What is ${this.userInput}? It's not a book in my list...`)
			}
		}
	}
</script>