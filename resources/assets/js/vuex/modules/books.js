
const state = {
    selectedBook: null,
    selectedReadingSession: null,
    selectedList: null,
    lists: {
        userCollection: typeof handover.userCollection !== 'undefined' ? handover.userCollection : [],
        library: typeof handover.library !== 'undefined' ? handover.library : [],
    }
}

const getters = {
    getSelectedBook: state => {
        if (state.selectedBook) {
            return state.lists.library[state.selectedBook]
        }
        return null
    },
    getSelectedReadingSession: state => state.selectedReadingSession,
    getUserCollection: state => state.lists.userCollection,
    getLibrary: state => state.lists.library,
    getSelectedList: state => state.lists[state.selectedList],
    getSelectedListName: state => state.selectedList,
    getLists: state => state.lists
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
                dispatch('setSelectedBook', response.book.id)
            }
        })
        .fail(args.errorCallback)
    },
    setSelectedBook({ commit, state }, id) {
        commit('SET_SELECTED_BOOK', id)
    },
    setSelectedReadingSession({ commit }, session) {
        commit('SET_SELECTED_READING_SESSION', session)
    },
    updateLibrary({ commit }, args) {
        $.get('update-library', (response) => {
            console.log(response)
            commit('UPDATE_LIBRARY', {
                userCollection: response.userCollection,
                library: response.library
            })
        }).done(args.successCallback)
        .fail(args.errorCallback)
    },
    saveReadingSession({ commit, dispatch, state }, args) {
        $.post('save-reading-session', {
            _token: window.handover._token,
            session: args.session,
            bookId: args.bookId
        }, args.successCallback)
        .done((response, status, responseContent) => {
            if (responseContent.status == 200) {
                dispatch('setSelectedReadingSession', response.session)
                dispatch('updateLibrary', args)
            }
        })
        .fail(args.errorCallback)
    },
    setSelectedList({ commit }, list) {
        commit('SET_SELECTED_LIST', list)
    }
}

const mutations = {
    'ADD_TO_LIBRARY': (state, book) => {
        state.lists.library[book.id] = book
    },
    'ADD_TO_USER_COLLECTION': (state, book) => {
        state.lists.userCollection[book.id] = book
        if (state.selectedBook) {
            state.lists.userCollection[book.id].in_library = 1
            state.lists.library[book.id].in_library = 1
        }
    },
    'SET_SELECTED_BOOK': (state, book) => {
        state.selectedBook = book
    },
    'SET_SELECTED_READING_SESSION': (state, session) => {
        state.selectedReadingSession = session
    },
    'UPDATE_LIBRARY': (state, data) => {
        state.lists.userCollection = data.userCollection
        state.lists.library = data.library
    },
    'SET_SELECTED_LIST': (state, list) => {
        state.selectedList = list
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}