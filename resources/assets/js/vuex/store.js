import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

const state = {
    colorSchemes: [
        {
            details: '#ffffff',
            background: '#222222'
        },
        {
            details: '#ffffff',
            background: '#000000'
        },
        {
            details: '#000000',
            background: '#ffffff'
        },
        {
            details: '#777777',
            background: '#ffffff'
        },
        {
            details: '#cccccc',
            background: '#777777'
        },
        {
            details: '#EB934A',
            background: '#2D8D8D'
        },
        {
            details: '#EBE44A',
            background: '#703A9E'
        },
    ],
    selectedColorSchemeIndex: 0,
    user: handover.user,
    userInputs: [],
    actions: {
        add: 'Add book',
        get: 'Get book',
        list: 'List books',
        remove: 'Remove this little fucker?',
        joke: 'Wanna hear a joke?',
        default: 'Ok, lets do this!',
    },
    selectedAction: 'get',
    sessionInteractions: [],
    books: [],
    showGui: false,
    showModal: false
}

const getters = {
    getUser: state => state.user,
    getUserInput: state => state.userInputs[0],
    getActions: state => state.actions,
    getSelectedAction: state => state.selectedAction,
    getSessionInteractionsCount: state => state.userInputs.length,
    getBooks: state => state.books,
    getShowGui: state => state.showGui,
    getShowModal: state => state.showModal,
    getColorScheme: state => state.colorSchemes[state.selectedColorSchemeIndex]
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
    },
    toggleGui({ commit, state }) {
        commit('TOGGLE_GUI')
    },
    changeColorScheme({ commit, state }) {
        commit('CHANGE_COLOR_SCHEME')
    },
    toggleModal({ commit, state }) {
        commit('TOGGLE_MODAL')
    }
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
    },
    'TOGGLE_GUI': state => {
        state.showGui = !state.showGui
    },
    'CHANGE_COLOR_SCHEME': state => {
        let currentIndex = state.selectedColorSchemeIndex
        currentIndex++
        state.selectedColorSchemeIndex = currentIndex % state.colorSchemes.length
    },
    'TOGGLE_MODAL': state => {
        state.showModal = !state.showModal
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
