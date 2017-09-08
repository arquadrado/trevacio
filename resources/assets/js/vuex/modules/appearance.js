const state = {
    colorSchemes: [
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
    selectedColorSchemeIndex: 0,
    loading: false
}

const getters = {
    getColorScheme: state => state.colorSchemes[state.selectedColorSchemeIndex],
    isLoading: state => state.loading
}

const actions = {
    toggleLoading({ commit }) {
        commit('TOGGLE_LOADING')
    },
    changeColorScheme({ commit, state }) {
        commit('CHANGE_COLOR_SCHEME')
    }
}

const mutations = {
    'CHANGE_COLOR_SCHEME': state => {
        let currentIndex = state.selectedColorSchemeIndex
        currentIndex++
        state.selectedColorSchemeIndex = currentIndex % state.colorSchemes.length
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