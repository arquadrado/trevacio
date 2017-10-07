<template>
  <div class="item-info" @click="openBook">
    <span class="date">{{ item.updated_at }}</span>
    <br>
    <span class="item-body">You have rated the book
      <strong>{{item.book.title }}</strong> written by
      <strong class="clickable-text" @click="openAuthor($event)">{{ item.book.author }}</strong> with {{ item.rating }} stars</span>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  props: [
    'index',
    'item'
  ],
  data() {
    return {

    }
  },
  computed: {
    ...mapGetters({
      selectedBook: 'getSelectedBook'
    })
  },
  methods: {
    openAuthor(event) {
      event.stopPropagation()
      this.setSelectedBook(this.item.book.id)
      this.setSelectedAuthor(this.selectedBook.author.id)
      this.setContent('author')
    },
    openBook() {
      console.log(this.item)
      this.setSelectedBook(this.item.book.id)
      this.setContent('book')
    },
    ...mapActions({
      setContent: 'setContent',
      setSelectedBook: 'setSelectedBook',
      setSelectedAuthor: 'setSelectedAuthor'
    })
  }
}
</script>

