
const state = {
    selectedBook: null,
    userCollection: typeof handover.userCollection !== 'undefined' ? handover.userCollection : [],
    library: typeof handover.library !== 'undefined' ? handover.library : [],
}

const getters = {
    getSelectedBook: state => state.selectedBook,
    getUserCollection: state => state.userCollection,
    getLibrary: state => state.library,
}

const actions = {
    addToLibrary({ commit, state }, args) {
         $.post('save-book', {
            _token: window.handover._token,
            title: args.book.title,
            author: args.book.author,
            force: args.force,
        }, args.successCallback)
         .fail(args.errorCallback)
         .done((response, status, responseContent) => {
            if (responseContent.status == 200) {
                commit('ADD_TO_LIBRARY', response.book)
                commit('ADD_TO_USER_COLLECTION', response.book)
            }
         })
    },
    addToUserCollection({ commit, state }, args) {
        $.post('add-to-user-collection', {
            _token: window.handover._token,
            book: args.book
        }, args.successCallback)
        .fail(args.errorCallback)
        .done((response, status, responseContent) => {
            if (responseContent.status == 200) {
                commit('ADD_TO_USER_COLLECTION', response.book)
            }
         })
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
    'ADD_TO_LIBRARY': (state, book) => {
        state.library[book.id] = book
    },
    'ADD_TO_USER_COLLECTION': (state, book) => {
        state.userCollection[book.id] = book
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