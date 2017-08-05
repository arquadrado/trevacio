import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

const state = {
    user: handover.user,
    selectedAction: 'get_book',
    sessionInteractions: []
}

const actions = {
    setAction({ commit, state }, action) {
        commit('SET_ACTION', action)
    }
}

const getters = {
    getUser: state => state.user,
    getSelectedAction: state => state.selectedAction,
    getSessionInteractions: state => state.sessionInteractions,
    getSessionInteractionsCount: state => state.sessionInteractions.length
}

const mutations = {
    'SET_ACTION': (state, action) => {
        state.selectedAction = action
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
