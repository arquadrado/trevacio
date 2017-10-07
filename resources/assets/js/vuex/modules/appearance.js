console.log(typeof handover.user.color_schemes !== 'undefined')
console.log(handover.user.color_schemes.length > 0)
console.log(!handover.user.default_color_scheme)


const state = {
  colorSchemeSet: typeof handover.user.color_schemes !== 'undefined' &&
    handover.user.color_schemes.length > 0 &&
    !handover.user.default_color_scheme ? 'user' : 'default',
  userColorSchemes: typeof handover.user.color_schemes != 'undefined' ? handover.user.color_schemes : [],
  defaultColorSchemes: [
    {
      details: '#000000',
      background: '#fff7e6',
      font: 'Times New Roman'
    },
    {
      details: '#ADDCDC',
      background: '#163434'
    },
    {
      details: '#AECFD5',
      background: '#0C515E'
    },
    {
      details: '#ffffff',
      background: '#10317C'
    },
    {
      details: '#269A9A',
      background: '#10317C'
    },
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
  defaultColorSchemeSelectedIndex: 0,
  userColorSchemeSelectedIndex: 0,
  loading: false
}

const getters = {
  getColorSchemeSet: state => state.colorSchemeSet,
  getColorScheme: state => {
    if (state.colorSchemeSet == 'user') {
      return state.userColorSchemes[state.userColorSchemeSelectedIndex]
    }
    return state.defaultColorSchemes[state.defaultColorSchemeSelectedIndex]
  },
  isLoading: state => state.loading,
  getUserColorSchemes: state => state.userColorSchemes
}

const actions = {
  toggleColorSchemeSet({ commit, state }) {
    commit('TOGGLE_COLOR_SCHEME_SET')
    $.post('set-color-scheme-default', {
      _token: window.handover._token,
      value: state.colorSchemeSet === 'user' ? 0 : 1
    }, (response) => {
    })
      .fail(() => {
        commit('TOGGLE_COLOR_SCHEME_SET')
      })
  },
  updateColorScheme({ commit, state }, data) {
    $.post('update-color-scheme', {
      _token: window.handover._token,
      schemeId: data.id,
      details: data.details,
      background: data.background,
      font: data.font,
    }, (response) => {
      commit('UPDATE_COLOR_SCHEME', {
        index: data.index,
        details: data.details,
        background: data.background,
        font: data.font,
      })
    })
      .fail(() => {
      })
  },
  saveColorScheme({ commit, state }, data) {
    $.post('save-color-scheme', {
      _token: window.handover._token,
      details: data.details,
      background: data.background,
      font: data.font,
    }, (response) => {
      commit('SAVE_COLOR_SCHEME', {
        index: data.index,
        details: data.details,
        background: data.background,
        font: data.font,
        id: response.scheme.id
      })
    })
      .fail(() => {

      })
  },
  addColorScheme({ commit, state }) {
    commit('ADD_COLOR_SCHEME')
  },
  setSelectedColorScheme({ commit, state }, scheme) {
    commit('SET_SELECTED_COLOR_SCHEME', scheme)
  },
  toggleLoading({ commit, state }) {
    commit('TOGGLE_LOADING')
  },
  changeColorScheme({ commit, state }) {
    commit('CHANGE_COLOR_SCHEME')
  }
}

const mutations = {
  'TOGGLE_COLOR_SCHEME_SET': (state) => {
    if (state.colorSchemeSet === 'default' && state.userColorSchemes.length > 0) {
      state.colorSchemeSet = 'user'
    } else {
      state.colorSchemeSet = 'default'
    }
  },
  'UPDATE_COLOR_SCHEME': (state, data) => {
    state.userColorSchemes[data.index].details = data.details
    state.userColorSchemes[data.index].background = data.background
    state.userColorSchemes[data.index].font = data.font
  },
  'SAVE_COLOR_SCHEME': (state, data) => {
    state.userColorSchemes[data.index].id = data.id
    state.userColorSchemes[data.index].details = data.details
    state.userColorSchemes[data.index].background = data.background
    state.userColorSchemes[data.index].font = data.font
  },
  'ADD_COLOR_SCHEME': state => {
    state.userColorSchemes.push({
      id: null,
      details: '#000000',
      background: '#ffffff',
      order: 0
    })
  },
  'SET_SELECTED_COLOR_SCHEME': state => {

  },
  'CHANGE_COLOR_SCHEME': state => {
    console.log('pato bravo')
    if (state.colorSchemeSet == 'user') {
      let currentIndex = state.userColorSchemeSelectedIndex
      let colorSchemes = state.userColorSchemes
      currentIndex++
      state.userColorSchemeSelectedIndex = currentIndex % colorSchemes.length
      return

    }
    let currentIndex = state.defaultColorSchemeSelectedIndex
    let colorSchemes = state.defaultColorSchemes
    currentIndex++
    state.defaultColorSchemeSelectedIndex = currentIndex % colorSchemes.length
  },
  'TOGGLE_LOADING': state => {
    state.loading = !state.loading
  }
}

export default {
  state,
  getters,
  actions,
  mutations
}