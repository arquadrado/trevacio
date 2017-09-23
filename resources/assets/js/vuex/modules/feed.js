const state = {
    initialLoad: false,
    loadingFeed: false,
    userFeed: {
        entries: [],
        hasMoreEntries: false
    }
}

const getters = {
    initialLoadCompleted: state => state.initialLoad,
    isLoadingFeed: state => state.loadingFeed,
    getUserFeed: state => state.userFeed.entries,
    hasMoreEntries: state => state.userFeed.hasMoreEntries
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

        $.get(`get-activity?skip=${state.userFeed.entries.length}&take=10`, (response) => {
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
        feed.entries.forEach((item) => {
            state.userFeed.entries.push(item)
        })

        state.userFeed.hasMoreEntries = feed.hasMoreEntries 
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}