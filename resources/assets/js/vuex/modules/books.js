
const state = {
    selectedBook: null,
    selectedReadingSession: null,
    selectedList: 'userCollection',
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
    rateBook({ commit, dispatch, state }, rating) {
        const previousRating = state.lists.library[state.selectedBook].user_rating
        const bookId = state.lists.library[state.selectedBook].id 
        commit('RATE_BOOK', rating)
        $.post('rate-book', {
            _token: window.handover._token,
            bookId: bookId,
            rating: rating,
        }, () => {
            dispatch('updateLibrary', {
                successCallback: null,
                errorCallback: null,
            })
            console.log('book rated')
        })
         .fail(() => {
            commit('RATE_BOOK', previousRating)
         })
    },
    deleteBook({ commit, dispatch, state }) {
        let bookId = state.lists.library[state.selectedBook].id 
        dispatch('setSelectedBook', null)
        dispatch('setContent', 'list')
        $.post('delete-book', {
            _token: window.handover._token,
            bookId: bookId,
        }, () => {
            dispatch('updateLibrary', {
                successCallback: null,
                errorCallback: null,
            })
            console.log('book deleted')
        })
         .fail(() => {
            dispatch('updateLibrary', {
                successCallback: null,
                errorCallback: null,
            })
         })
    },
    deleteReadingSession({ commit, dispatch, state }) {
        dispatch('setContent', 'reading-session-list')
        commit('DELETE_READING_SESSION', state.selectedReadingSession)
        $.post('delete-session', {
            _token: window.handover._token,
            sessionId: state.selectedReadingSession.id,
        }, () => {
            dispatch('setSelectedReadingSession', null)
            dispatch('updateUserInfo')
            console.log('session deleted')
        })
         .fail(() => {
            dispatch('updateLibrary', {
                successCallback: null,
                errorCallback: null,
            })
         })
    },
    nextBook({ commit, dispatch, state }) {
        const ids = []
        for (let id in state.lists.userCollection) {
            ids.push(parseInt(id))
        }

        let currentIdIndex = ids.indexOf(state.selectedBook)

        if (currentIdIndex > -1) {
            let indexToSelect = ids.indexOf(state.selectedBook + 1)
            dispatch('setSelectedBook', indexToSelect > -1 ? ids[indexToSelect] : ids[0])
        }

    },
    previousBook({ commit, dispatch, state }) {
        const ids = []
        for (let id in state.lists.userCollection) {
            ids.push(parseInt(id))
        }

        let currentIdIndex = ids.indexOf(state.selectedBook)

        if (currentIdIndex > -1) {
            let indexToSelect = ids.indexOf(state.selectedBook - 1)
            dispatch('setSelectedBook', indexToSelect > -1 ? ids[indexToSelect] : ids[ids.length - 1])
        }

    },

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
    removeFromUserCollection({ commit, dispatch, state }, args) {
        $.post('remove-from-user-collection', {
            _token: window.handover._token,
            bookId: args.bookId
        }, args.successCallback)
        .fail(args.errorCallback)
        .done((response, status, responseContent) => {
            if (responseContent.status == 200) {
                commit('REMOVE_FROM_USER_COLLECTION', response.book)
                dispatch('updateLibrary', {
                    successCallback: null,
                    errorCallback: null,
                })
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
    updateUserInfo({ commit }) {
        $.get('update-user-info', (response) => {
            console.log(response)
            commit('UPDATE_USER_INFO', response.user)
        })
        .fail(() => {console.log('failed to update user info')})
    },
    saveReadingSession({ commit, dispatch, rootState, state }, args) {
        $.post('save-reading-session', {
            _token: window.handover._token,
            session: args.session,
            bookId: args.bookId
        }, args.successCallback)
        .done((response, status, responseContent) => {
            if (responseContent.status == 200) {
                dispatch('setSelectedReadingSession', response.session)
                dispatch('updateLibrary', args)
                dispatch('updateUserInfo')
            }
        })
        .fail(args.errorCallback)
    },
    setSelectedList({ commit }, list) {
        commit('SET_SELECTED_LIST', list)
    }
}

const mutations = {
    'RATE_BOOK': (state, rating) => {
        state.lists.library[state.selectedBook].user_rating = rating
    },
    'DELETE_READING_SESSION': (state, session) => {
        if (state.selectedBook) {
            let index = state.lists.library[state.selectedBook].reading_sessions.indexOf(session)
            if (index > -1) {
                state.lists.library[state.selectedBook].reading_sessions.splice(index, 1)
            }
        }
    },
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
    'REMOVE_FROM_USER_COLLECTION': (state, book) => {
        delete state.lists.userCollection[book.id]
        state.lists.library[book.id].in_library = 0
        state.lists.library[book.id].can_delete = 0
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