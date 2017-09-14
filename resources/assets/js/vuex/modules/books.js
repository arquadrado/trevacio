
const state = {
    selectedBook: null,
    selectedReadingSession: null,
    selectedComment: null,
    selectedList: 'userCollection',
    lists: {
        userCollection: typeof handover.userCollection !== 'undefined' ? handover.userCollection : [],
        library: typeof handover.library !== 'undefined' ? handover.library : [],
    },
    commentListToDisplay: 'book',
    booksLoadedInfo: {},
    authors: typeof handover.authors !== 'undefined' ? handover.authors : {},
    selectedAuthorId: null,
    authorsLoadedInfo: {}
}

const getters = {
    getSelectedBook: state => {
        if (state.selectedBook) {
            return state.lists.library[state.selectedBook]
        }
        return null
    },
    getSelectedReadingSession: state => {
        if (state.selectedBook) {
            return state.lists.library[state.selectedBook].reading_sessions[state.selectedReadingSession]
        }
        return null 
    }, 
    getSelectedComment: state => state.selectedComment,
    getUserCollection: state => state.lists.userCollection,
    getLibrary: state => state.lists.library,
    getSelectedList: state => state.lists[state.selectedList],
    getSelectedListName: state => state.selectedList,
    getLists: state => state.lists,
    getCommentListToDisplay: state => state.commentListToDisplay,
    getBooksLoadedInfo: state => state.booksLoadedInfo,
    getSelectedAuthor: state => state.authors[state.selectedAuthorId],
    getAuthorsLoadedInfo: state => state.authorsLoadedInfo
}

const actions = {
    setAuthorInfo({ commit }, info) {
        commit('SET_AUTHOR_INFO', info)
    },
    setSelectedAuthor({ commit }, authorId) {
        commit('SET_SELECTED_AUTHOR', authorId)
    },
    setBookInfo({ commit }, info) {
        commit('SET_BOOK_INFO', info)
    },
    setCurrentCommentList({ commit }, listName) {
        commit('SET_CURRENT_COMMENT_LIST', listName)
    },
    setSelectedComment({ commit }, comment) {
        commit('SET_SELECTED_COMMENT', comment)
    },
    updateComment({ commit, dispatch, state }, data) {
        dispatch('toggleLoading')
        commit('UPDATE_COMMENT', data.body)
        
        $.post('update-comment', {
            _token: window.handover._token,
            commentId: data.selectedComment.id,
            comment: data.body
        }, () => {
            dispatch('toggleLoading')
            dispatch('updateLibrary', {
                successCallback: data.doneCallback,
                errorCallback: null,
            })
            console.log('comment updated')
        })
        .fail(() => {
            commit('UPDATE_COMMENT', data.body)
            
        })
    },
    addComment({ commit, dispatch, state }, data) {
        dispatch('toggleLoading')
        $.post('save-comment', {
            _token: window.handover._token,
            commentableId: data.commentable_id,
            commentableType: data.commentable_type,
            comment: data.body,
        }, (response) => {
            dispatch('toggleLoading')
            dispatch('updateLibrary', {
                successCallback: null,
                errorCallback: null,
            })
            dispatch('setSelectedComment', response.comment)
            dispatch('setContent', 'comment')
            console.log('comment added')
        })
        .fail(() => {
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
        dispatch('toggleLoading')
        let bookId = state.lists.library[state.selectedBook].id 
        
        $.post('delete-book', {
            _token: window.handover._token,
            bookId: bookId,
        }, () => {
            dispatch('toggleLoading')
            dispatch('updateLibrary', {
                successCallback: () => {
                    dispatch('setSelectedBook', null)
                    dispatch('setContent', 'list')
                    dispatch('toggleModal')
                },
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
        dispatch('toggleLoading')
        $.post('delete-session', {
            _token: window.handover._token,
            sessionId: state.lists.library[state.selectedBook].reading_sessions[state.selectedReadingSession].id,
        }, () => {
            commit('DELETE_READING_SESSION')
            dispatch('setContent', 'reading-session-list')
            dispatch('toggleLoading')
            dispatch('setSelectedReadingSession', null)
            dispatch('updateUserInfo')
            dispatch('toggleModal')
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
        const currentList = state.lists[state.selectedList]
        for (let id in currentList) {
            ids.push(parseInt(id))
        }

        let currentIdIndex = ids.indexOf(state.selectedBook)

        if (currentIdIndex > -1) {
            dispatch('setSelectedBook', ids.length - 1 > currentIdIndex ? ids[currentIdIndex + 1] : ids[0])
        }

    },
    previousBook({ commit, dispatch, state }) {
        const ids = []
        const currentList = state.lists[state.selectedList]
        for (let id in currentList) {
            ids.push(parseInt(id))
        }

        let currentIdIndex = ids.indexOf(state.selectedBook)

        if (currentIdIndex > -1) {
            dispatch('setSelectedBook', currentIdIndex > 0 ? ids[currentIdIndex - 1] : ids[ids.length - 1])
        }

    },

    addToLibrary({ commit, dispatch, state }, args) {

        dispatch('toggleLoading')
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
            dispatch('toggleLoading')

         })
    },
    addToUserCollection({ commit, dispatch, state }, args) {
        dispatch('toggleLoading')
        $.post('add-to-user-collection', {
            _token: window.handover._token,
            book: args.book
        }, args.successCallback)
        .fail(args.errorCallback)
        .done((response, status, responseContent) => {
            if (responseContent.status == 200) {
                commit('ADD_TO_USER_COLLECTION', response.book)
            }
            dispatch('toggleLoading')
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
        dispatch('toggleLoading')
        $.post('get-book', {
            _token: window.handover._token,
            title: args.book,
        }, args.successCallback)
        .done((response, status, responseContent) => {
            if (responseContent.status == 200) {
                dispatch('setSelectedBook', response.book.id)
            }
            dispatch('toggleLoading')
        })
        .fail(args.errorCallback)
    },
    setSelectedBook({ commit, state }, id) {
        commit('SET_SELECTED_BOOK', id)
    },
    setSelectedReadingSession({ commit }, index) {
        commit('SET_SELECTED_READING_SESSION', index)
    },
    updateLibrary({ commit, dispatch }, args) {
        dispatch('toggleLoading')
        $.get('update-library', args.successCallback)
        .done((response) => {
            console.log(response)
            dispatch('toggleLoading')
            commit('UPDATE_LIBRARY', {
                userCollection: response.userCollection,
                library: response.library
            })
        })
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
        dispatch('toggleLoading')
        $.post('save-reading-session', {
            _token: window.handover._token,
            session: args.session,
            bookId: args.bookId
        }, (response, status, responseContent) => {
            if (responseContent.status == 200) {
                dispatch('toggleLoading')
                args.successCallback = () => {
                    dispatch('setContent', 'reading-session-list')
                }
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
    'SET_AUTHOR_INFO': (state, info) => {
        state.authorsLoadedInfo[state.selectedAuthorId] = info
    },
    'SET_SELECTED_AUTHOR': (state, authorId) => {
        state.selectedAuthorId = authorId
    },
    'SET_BOOK_INFO': (state, info) => {
        state.booksLoadedInfo[state.selectedBook] = info
    },
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
        if (state.lists.library[state.selectedBook].user_rating.length) {

            state.lists.library[state.selectedBook].user_rating[0].rating = rating
        } else {
            state.lists.library[state.selectedBook].user_rating[0] = {
                rating: rating
            }
        }
    },
    'DELETE_READING_SESSION': (state) => {
        if (state.selectedBook) {
            let session = state.lists.library[state.selectedBook].reading_sessions[state.selectedReadingSession]
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
    'SET_SELECTED_READING_SESSION': (state, index) => {
        state.selectedReadingSession = index
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