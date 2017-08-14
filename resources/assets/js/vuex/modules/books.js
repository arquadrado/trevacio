const state = {
    selectedBook: null,
    books: [],
}

const getters = {
    getSelectedBook: state => state.selectedBook,
    getBooks: state => state.books,
}

const actions = {
    addBook({ commit, state }, book) {
        commit('ADD_BOOK', book)
    },
    fetchBook({ commit, dispatch, state }, args) {
        $.post('get-book', {
            _token: window.handover._token,
            title: args.book,
        }, args.successCallback)
        .done((response, status, responseContent) => {
            if (responseContent.status == 200) {
                dispatch('setSelectedBook', response.book)
            }
        })
        .fail(args.errorCallback)
    },
    setSelectedBook({ commit, state }, book) {
        commit('SET_SELECTED_BOOK', book)
    },
}

const mutations = {
    'ADD_BOOK': (state, book) => {
        state.books.unshift(book)
    },
    'SET_SELECTED_BOOK': (state, book) => {
        state.selectedBook = book
    },
}

export default {
    state,
    getters,
    actions,
    mutations
}