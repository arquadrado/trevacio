<template>
  <div class="content-wrapper">
    <div class="home" v-if="selectedSectionIndex !== null">
      <h2 class="greeting">Welcome, {{ user.friendly_name }}</h2>
      <button :disabled="isSelected(index)" v-for="(section, index) in sections" :key="index" @click="selectSection(index)">
        <span class="clickable-text">{{ section.label }}</span>
      </button>

      <component :is="selectedSection"></component>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

import Help from './Home/Help'
import RecentActivity from './Home/RecentActivity'
import LastBook from './Home/LastBook'

export default {
  components: {
    'help': Help,
    'recent-activity': RecentActivity,
    //'last-book': LastBook
  },
  data() {
    return {
      sections: [
        {
          label: 'Recent activity',
          value: 'recent-activity'
        },
        {
          label: 'Help',
          value: 'help'
        },
        // {
        //   label: 'Last book',
        //   value: 'last-book'
        // }
      ],
      selectedSectionIndex: null
    }
  },
  computed: {
    selectedSection() {
      if (this.selectedSectionIndexi !== null) {
        return this.sections[this.selectedSectionIndex].value
      }

      return null
    },

    ...mapGetters({
      user: 'getUser',
    })
  },
  methods: {
    isSelected(index) {
      return index === this.selectedSectionIndex
    },
    selectSection(index) {
      this.selectedSectionIndex = index
    },
    ...mapActions({
      setContent: 'setContent',
      toggleGui: 'toggleGui'
    })
  },
  mounted() {
    this.selectedSectionIndex = 0
  }
}
</script>