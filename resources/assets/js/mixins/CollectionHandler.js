export default {
	methods: {
		findMatches(needle, haystack) {
			const books = []
			const selectedBookRawTitle = needle.toLowerCase()
                                               .replace(/[^a-zA-Z ]/g, "") 
			for (let id in haystack) {
                let collectionBookTitle = haystack[id].title.toLowerCase().replace(/[^a-zA-Z ]/g, "")

                if (collectionBookTitle.includes(selectedBookRawTitle)) {
                    books.push(haystack[id])
                    continue
                }

                let a = selectedBookRawTitle.split(' ').filter((word) => {
                    return word.length > 2
                })

                let b = collectionBookTitle.split(' ')

                let matches = a.reduce((reduced, word) => {
                    if (b.indexOf(word) > -1) {
                        reduced++
                    }
                    return reduced
                }, 0)

                const matchPercentage = matches * 100 / a.length

                if (matchPercentage >= 50) {
                    books.push(haystack[id])
                }

            }

            return books
		}
	}
}