const state = {
  loadingFeed: false,
  userFeed: {
    entries: [],
    hasMoreEntries: false
  },
  needsUpdate: true
}

const getters = {
  shouldUpdate: state => state.needsUpdate,
  isLoadingFeed: state => state.loadingFeed,
  getUserFeed: state => state.userFeed.entries,
  hasMoreEntries: state => state.userFeed.hasMoreEntries
}

const actions = {
  setNeedsUpdate({ commit }, value) {
    commit('SET_NEEDS_UPDATE', value)
  },
  toggleFeedLoading({ commit }) {
    commit('TOGGLE_FEED_LOADING')
  },
  updateUserFeed({ commit, dispatch, state }) {
    dispatch('toggleFeedLoading')
    const skip = state.needsUpdate ? 0 : state.userFeed.entries.length
    $.get(`get-activity?skip=${skip}&take=10`, (response) => {
      commit('UPDATE_USER_FEED', response.feed)
      dispatch('toggleFeedLoading')
      dispatch('setNeedsUpdate', false)

    })
  }
}

const mutations = {
  'SET_NEEDS_UPDATE': (state, value) => {
    state.needsUpdate = value
  },
  'TOGGLE_FEED_LOADING': (state) => {
    state.loadingFeed = !state.loadingFeed
  },
  'UPDATE_USER_FEED': (state, feed) => {
    if (state.needsUpdate) {
      state.userFeed.entries = feed.entries
    } else {
      feed.entries.forEach((item) => {
        state.userFeed.entries.push(item)
      })
    }

    state.userFeed.hasMoreEntries = feed.hasMoreEntries
  }
}

export default {
  state,
  getters,
  actions,
  mutations
}