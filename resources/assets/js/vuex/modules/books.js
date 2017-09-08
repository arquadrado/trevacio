
const state = {
    selectedBook: null,
    selectedReadingSession: null,
    selectedComment: null,
    selectedList: 'userCollection',
    lists: {
        userCollection: typeof handover.userCollection !== 'undefined' ? handover.userCollection : [],
        library: typeof handover.library !== 'undefined' ? handover.library : [],
    },
    commentListToDisplay: 'book'
}

const getters = {
    getSelectedBook: state => {
        if (state.selectedBook) {
            return state.lists.library[state.selectedBook]
        }
        return null
    },
    getSelectedReadingSession: state => state.selectedReadingSession,
    getSelectedComment: state => state.selectedComment,
    getUserCollection: state => state.lists.userCollection,
    getLibrary: state => state.lists.library,
    getSelectedList: state => state.lists[state.selectedList],
    getSelectedListName: state => state.selectedList,
    getLists: state => state.lists,
    getCommentListToDisplay: state => state.commentListToDisplay
}

const actions = {
    setCurrentCommentList({ commit }, listName) {
        commit('SET_CURRENT_COMMENT_LIST', listName)
    },
    setSelectedComment({ commit }, comment) {
        commit('SET_SELECTED_COMMENT', comment)
    },
    updateComment({ commit, dispatch, state }, data) {
        commit('UPDATE_COMMENT', data.body)
        
        $.post('update-comment', {
            _token: window.handover._token,
            commentId: data.selectedComment.id,
            comment: data.body
        }, () => {
            dispatch('updateLibrary', {
                successCallback: null,
                errorCallback: null,
            })
            console.log('comment updated')
        })
        .fail(() => {
            commit('UPDATE_COMMENT', data.body)
            
        })
    },
    addComment({ commit, dispatch, state }, data) {
        console.log(state.commentListToDisplay)
        switch (state.commentListToDisplay) {
            case 'book':
                commit('ADD_COMMENT_TO_BOOK', data)

            case 'session':
                commit('ADD_COMMENT_TO_SESSION', data)
        }


        $.post('save-comment', {
            _token: window.handover._token,
            commentableId: data.commentable_id,
            commentableType: data.commentable_type,
            comment: data.body,
        }, (response) => {
            console.log(response)
            dispatch('updateLibrary', {
                successCallback: null,
                errorCallback: null,
            })
            dispatch('setSelectedComment', response.comment)
            dispatch('setContent', 'comment')
            console.log('comment added')
        })
        .fail(() => {
            switch (state.commentListToDisplay) {
                case 'book':
                    commit('REMOVE_COMMENT_FROM_BOOK', null)

                case 'session':
                    commit('REMOVE_COMMENT_FROM_SESSION', null)
            }
            dispatch('setSelectedComment', null)
        })
    },

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
            dispatch('setSelectedBook', ids.length - 1 > currentIdIndex ? ids[currentIdIndex + 1] : ids[0])
        }

    },
    previousBook({ commit, dispatch, state }) {
        const ids = []
        for (let id in state.lists.userCollection) {
            ids.push(parseInt(id))
        }

        let currentIdIndex = ids.indexOf(state.selectedBook)

        if (currentIdIndex > -1) {
            dispatch('setSelectedBook', currentIdIndex > 0 ? ids[currentIdIndex - 1] : ids[ids.length - 1])
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
    'SET_CURRENT_COMMENT_LIST': (state, listName) => {
        state.commentListToDisplay = listName
    },
    'UPDATE_COMMENT': (state, body) => {
        state.selectedComment.body = body
    },
    'ADD_COMMENT_TO_BOOK': (state, data) => {
        state.lists.library[state.selectedBook].comments.unshift(data)
    },
    'ADD_COMMENT_TO_SESSION': (state, data) => {
        if (state.selectedReadingSession) {
            state.selectedReadingSession.notes.unshift(data)
        }
    },
    'REMOVE_COMMENT_FROM_BOOK': (state, comment) => {
        if (comment) {
            const index = state.lists.library[state.selectedBook].comments.indexOf(comment)

            if (index > -1) {
                state.lists.library[state.selectedBook].comments.splice(index, 1)    
            }
        }
        state.lists.library[state.selectedBook].comments.shift(data)
    },
    'RATE_BOOK': (state, rating) => {
        state.lists.library[state.selectedBook].user_rating[0].rating = rating
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
    'SET_SELECTED_COMMENT': (state, comment) => {
        state.selectedComment = comment
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