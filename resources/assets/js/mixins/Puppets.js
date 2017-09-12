import diacritics from 'diacritics'

export default {
	computed: {},
	methods: {
		normalize(string, diacritics) {
			if (diacritics) {
				string = this.removeDiacritics(string)
			}

			return this.removeSpecialCharacters(string).toLowerCase()
		},
		hasSpecialCharacters(string) {
			let cleanString = string.replace(/[^a-zA-Z ]/g, "")

			return string > cleanString
		},
		removeDiacritics(string) {
			return diacritics.remove(string)
		},
		removeSpecialCharacters(string) {
			return string.replace(/[^a-zA-Z ]/g, "")			
		},
		getHighestMatch(needle, haystack, haystackKey, diacritics) {
			let cleanNeedle = this.normalize(needle, diacritics).split(' ')

			const self = this

			let index = 
				haystack
					.reduce((reduced, item) => {
						let matchingIndex = 
							cleanNeedle.reduce((reduced, word) => {
								let str = typeof haystackKey !== 'undefined' ? item[haystackKey] : item
								if (self.normalize(str, diacritics).includes(word)) {
									reduced += 1
								}
								return reduced
							}, 0) / cleanNeedle.length

						if (!reduced || reduced.matchingIndex < matchingIndex) {
							reduced = {
								content: item,
								matchingIndex: matchingIndex
							}
						}

						return reduced
				}, null)

			return index

		}
	} 
}