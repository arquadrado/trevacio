<template lang="html">
    <div class="recent-activity">
        <h3 class="action">Recent activity</h3>
        <loading-spinner v-if="isLoadingFeed && shouldUpdate"></loading-spinner>
        <div class="feed" v-if="hasRecentActivity">
            <list 
                :className="'activity-list'" 
                :itemType="'feed-item'"
                :items="userFeed"
            ></list>      
            <loading-spinner v-if="isLoadingFeed"></loading-spinner>
            <button v-if="!isLoadingFeed && hasMoreEntries" @click="updateUserFeed"><span class="clickable-text">Older entries</span></button>
        </div>
        <div class="feed" v-else>
            <h4>No activity to show</h4>
            <button @click="toggleGui" class="cta"><strong><span class="clickable-text">Get started</span></strong></button>
        </div> 
    </div>
</template>

<script lang="js">
import { mapActions, mapGetters } from 'vuex'
import List from '../utilities/List.vue'

  export default  {
    components: {
      'list': List
    },
    data() {
      return {

      }
    },
    computed: {
      hasRecentActivity() {
        return this.userFeed.length > 0
      },
      ...mapGetters({
        user: 'getUser',
        userFeed: 'getUserFeed',
        isLoadingFeed: 'isLoadingFeed',
        shouldUpdate: 'shouldUpdate',
        hasMoreEntries: 'hasMoreEntries'
      })
    },
    methods: {
        ...mapActions({
          setContent: 'setContent',
          updateUserFeed: 'updateUserFeed',
          toggleGui: 'toggleGui'
        })
    },
    mounted() {
      if (this.shouldUpdate) {
        this.updateUserFeed()
      }
    }
}
</script>

<style scoped lang="scss">

</style>
