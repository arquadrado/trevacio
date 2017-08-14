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
        add: 'Add book',
        get: 'Get book',
        list: 'List books',
        remove: 'Remove this little fucker?',
        joke: 'Wanna hear a joke?',
        default: 'Ok, lets do this!',
    },
    selectedAction: 'get',

    showGui: false,

    showModal: false,
    modalComponent: null
}

const getters = {
    getUser: state => state.user,
    getUserInput: state => state.userInputs[0],
    getSessionInteractionsCount: state => state.userInputs.length,

    getActions: state => state.actions,
    getSelectedAction: state => state.selectedAction,

    getShowGui: state => state.showGui,

    getShowModal: state => state.showModal,
    getModalComponent: state => state.modalComponent,
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
    setModalComponent({ commit, state }, component) {
        commit('SET_MODAL_COMPONENT', component)
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
    'SET_MODAL_COMPONENT': (state, component) => {
        state.modalComponent = component
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
