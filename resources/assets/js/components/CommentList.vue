<template>
    <div class="content-wrapper">

        <div class="modal-header">
            <h3 class="action">Comments</h3>
            <button class="" v-if="hasHistory" @click="back">Back</button>
        </div>
        <div class="modal-body">
            <div class="body-controls">
                <button class="" @click="addComment" v-if="selectedBook.in_library">Add comment</button>
                <h4 v-else>This book is not in your library. Add it to add comments</h4>
            </div>
            <ul class="comment-list">
                <li class="comment" @click="selectComment(comment)" v-for="comment in selectedBook.comments">
                    <div class="comment-info">
                        <span class="comment-user">{{ comment.user.name }}</span>
                        <br>
                        <span class="comment-body">{{ comment.body }}</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import Navigation from './../mixins/Navigation.js'

    export default {
        mixins: [Navigation],
        data() {
            return {

            }
        },
        computed: {
            ...mapGetters({
                selectedBook: 'getSelectedBook',
            })
        },
        methods: {
            addComment() {
                this.setContent('add-comment')
            },
            selectComment(comment) {
                this.setSelectedComment(comment)
                this.setContent('comment')
            },
            ...mapActions({
                setContent: 'setContent',
                setSelectedComment: 'setSelectedComment'
            })
        }
    }
</script>