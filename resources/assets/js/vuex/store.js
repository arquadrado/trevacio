import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

import appearance from './modules/appearance.js'
import books from './modules/books.js'

const state = {
    user: handover.user,
    userInputs: [],
    sessionInteractions: [],

    navigationHistory: [],

    actions: {
        
        add: {
            label: 'Add',
            name: 'add',
            icon: 'playlist_add',
            description: 'Add books to your personal library'
        },
        get: {
            label: 'Get',
            name: 'get',
            icon: 'search',
            description: 'Search for a specific book'
        },
        list: {
            label: 'List',
            name: 'list',
            icon: 'list',
            description: 'List books from the library or your personal collection'
        },
        settings: {
            label: 'Settings',
            name: 'settings',
            icon: 'people',
            description: 'Personalize your experience and view your stats'
        },
        home: {
            label: 'Home',
            name: 'home',
            icon: 'close',
            description: 'Close the menu and go back to home screen'
        },
        /*trevacio: {
            label: 'Trevacio',
            name: 'trevacio',
            icon: 'close',
            description: 'Close the menu and go back to home screen'
        },*/
        //joke: 'Wanna hear a joke?',
        //default: 'Ok, lets do this!',
    },
    selectedAction: 'get',

    showGui: false,

    showModal: false,

    modalContent: {
        message: 'Are you sure?',
        actions: [{
                    label: 'Default',
                    callback: null
                }]
    },

    content: 'home'
}

const getters = {
    getUser: state => state.user,
    getUserInput: state => state.userInputs[0],
    getSessionInteractionsCount: state => state.userInputs.length,

    hasNavigationHistory: state => state.navigationHistory.length > 1,

    getActions: state => state.actions,
    getSelectedAction: state => state.selectedAction,

    getShowGui: state => state.showGui,

    getShowModal: state => state.showModal,
    getModalContent: state => state.modalContent,

    getContent: state => state.content,
}

const actions = {
    addUserInput({ commit, state }, input) {
        commit('ADD_USER_INPUT', input.trim())
    },

    setAction({ commit, state }, action) {
        commit('SET_ACTION', action)
    },

    toggleGui({ commit, state }) {
        commit('TOGGLE_GUI')
    },

    toggleModal({ commit, state }) {
        commit('TOGGLE_MODAL')
    },
    setModalContent({ commit, state }, content) {
        commit('SET_MODAL_CONTENT', content)
    },

    setContent({ commit, state }, component) {
        commit('SET_CONTENT', component)
        commit('ADD_NAVIGATION_ENTRY', component)
    },

    back({ commit, state }) {
        commit('SHIFT_NAVIGATION_HISTORY')
        commit('SET_CONTENT', state.navigationHistory[0])
    }
}

const mutations = {
    'UPDATE_USER_INFO': (state, user) => {
        state.user = user
    },
    'SHIFT_NAVIGATION_HISTORY': (state) => {
        state.navigationHistory.shift()
    },
    'ADD_NAVIGATION_ENTRY': (state, content) => {
        state.navigationHistory.unshift(content)
    },

    'ADD_USER_INPUT': (state, input) => {
        state.userInputs.unshift(input)
    },

    'SET_ACTION': (state, action) => {
        state.selectedAction = action
    },

    'TOGGLE_GUI': state => {
        state.showGui = !state.showGui
    },

    'TOGGLE_MODAL': state => {
        state.showModal = !state.showModal
    },
    'SET_MODAL_CONTENT': (state, content) => {
        state.modalContent = content
    },

    'SET_CONTENT': (state, component) => {
        state.content = component
    },
}

export default new Vuex.Store({
    state,
    actions,
    getters,
    mutations,
    modules: {
        appearance,
        books
    },
    strict: true
})
