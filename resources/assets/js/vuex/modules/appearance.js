const state = {
    colorSchemes: [
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
}

const getters = {
    getColorScheme: state => state.colorSchemes[state.selectedColorSchemeIndex],
}

const actions = {
    changeColorScheme({ commit, state }) {
        commit('CHANGE_COLOR_SCHEME')
    }
}

const mutations = {
    'CHANGE_COLOR_SCHEME': state => {
        let currentIndex = state.selectedColorSchemeIndex
        currentIndex++
        state.selectedColorSchemeIndex = currentIndex % state.colorSchemes.length
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}