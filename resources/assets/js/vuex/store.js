import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

const state = {
    user: handover.user,
    userInputs: [],
    selectedAction: 'get_book',
    sessionInteractions: [],
    books: []
}

const actions = {
    setAction({ commit, state }, action) {
        commit('SET_ACTION', action)
    },
    addUserInput({ commit, state }, input) {
        commit('ADD_USER_INPUT', input.trim())
    },
    addBook({ commit, state }, book) {
        commit('ADD_BOOK', book)
    }
}

const getters = {
    getUser: state => state.user,
    getUserInput: state => state.userInputs[0],
    getSelectedAction: state => state.selectedAction,
    getSessionInteractionsCount: state => state.userInputs.length,
    getBooks: state => state.books
}

const mutations = {
    'SET_ACTION': (state, action) => {
        state.selectedAction = action
    },
    'ADD_USER_INPUT': (state, input) => {
        state.userInputs.unshift(input)
    },
    'ADD_BOOK': (state, book) => {
        state.books.unshift(book)
    }
}

export default new Vuex.Store({
    state,
    actions,
    getters,
    mutations,
    modules: {},
    strict: true
})
