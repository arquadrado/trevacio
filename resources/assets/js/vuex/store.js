import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

import appearance from './modules/appearance.js'
import books from './modules/books.js'

const state = {
    user: handover.user,
    userInputs: [],
    sessionInteractions: [],

    actions: {
        add: {
            label: 'Add',
            name: 'add',
            icon: 'view_array',
            description: 'Add books to your personal library'
        },
        get: {
            label: 'Get',
            name: 'get',
            icon: 'view_carousel',
            description: 'Search for a specific book'
        },
        list: {
            label: 'List',
            name: 'list',
            icon: 'view_column',
            description: 'List books from the library or your personal collection'
        },
        settings: {
            label: 'Settings',
            name: 'settings',
            icon: 'settings',
            description: 'Personalize your experience and view your stats'
        },
        trevacio: {
            label: 'Trevacio',
            name: 'trevacio',
            icon: 'close',
            description: 'Close the menu and go back to home screen'
        },
        //joke: 'Wanna hear a joke?',
        //default: 'Ok, lets do this!',
    },
    selectedAction: 'get',

    showGui: false,

    content: 'trevacio'
}

const getters = {
    getUser: state => state.user,
    getUserInput: state => state.userInputs[0],
    getSessionInteractionsCount: state => state.userInputs.length,

    getActions: state => state.actions,
    getSelectedAction: state => state.selectedAction,

    getShowGui: state => state.showGui,

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
    setContent({ commit, state }, component) {
        commit('SET_CONTENT', component)
    },
}

const mutations = {
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
