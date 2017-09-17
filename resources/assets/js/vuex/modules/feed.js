const state = {
    initialLoad: false,
    loadingFeed: false,
    userFeed: []
}

const getters = {
    initialLoadCompleted: state => state.initialLoad,
    isLoadingFeed: state => state.loadingFeed,
    getUserFeed: state => state.userFeed
}

const actions = {
    setInitialLoad({ commit }, value) {
        commit('SET_INITIAL_LOAD', value)
    },
    toggleFeedLoading({ commit }) {
        commit('TOGGLE_FEED_LOADING')
    },
    updateUserFeed({ commit, dispatch, state }) {
        dispatch('toggleFeedLoading')

        $.get(`get-activity?skip=${state.userFeed.length}&take=10`, (response) => {
            commit('UPDATE_USER_FEED', response.feed)
            dispatch('toggleFeedLoading')
            dispatch('setInitialLoad', true)

        })
    }
}

const mutations = {
    'SET_INITIAL_LOAD': (state, value) => {
        state.initialLoad = value
    },
    'TOGGLE_FEED_LOADING': (state) => {
        state.loadingFeed = !state.loadingFeed 
    },
    'UPDATE_USER_FEED': (state, feed) => {
        feed.forEach((item) => {
            state.userFeed.push(item)
        })
        console.log('qwuer cartlaho sdfsdf')
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}