<template>
    <div class="response">
        <div class="book-found" v-if="status == 201">
            <div class="modal-body">
                <h3>{{ response.message }}</h3>
                <button @click="selectBook(book.id)" v-for="book in books">
                    {{ book.title }} - {{ book.author.name }}
                </button>
                <br>
                <button @click="searchAgain">Search again</button>
            </div>
        </div>
        <div class="book-owned" v-if="status == 404">
            <div class="modal-body">
                <h3>{{ response.message }}</h3>
                <button @click="setModalComponent('add')">
                    Add book
                </button>
                <button @click="searchAgain">Search again</button>
                <button @click="toggleModal">Close</button>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        props: ['response', 'status', 'books'],
        data() {
            return {
                book: null
            }
        },
        computed: {},
        methods: {
            selectBook(id) {
                this.setSelectedBook(id)
                this.setModalComponent('book')
            },
            searchAgain() {
                console.log('gonna search')
                this.$emit('searchAgain')
            },
            ...mapActions({
                toggleModal: 'toggleModal',
                setSelectedBook: 'setSelectedBook',
                setModalComponent: 'setModalComponent',
            })
        }
    }
</script>