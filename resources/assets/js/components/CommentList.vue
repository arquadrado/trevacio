<template>
    <div class="content-wrapper">

        <div class="modal-header">
            <h3 class="action">{{ listTitle }}</h3>
            <button class="" v-if="hasHistory" @click="back">Back</button>
            <button class="" @click="setContent('book')">Book</button>
            <button class="" @click="setContent('reading-session-list')">Sessions</button>
        </div>
        <div class="modal-body">
            <div class="body-controls">
                <button class="" @click="addComment" v-if="selectedBook.in_library">Add comment</button>
                <h4 v-else>This book is not in your library. Add it to add comments</h4>
            </div>
            <ul class="comment-list">
                <li class="comment" @click="selectComment(comment)" v-for="comment in commentList">
                    <div class="comment-info">
                        <span class="comment-user">{{ comment.user.name }} - {{ comment.updated_at }}</span>
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
            listTitle() {
                if (this.currentCommentList == 'book') {
                    return `${this.selectedBook.title} comments`
                }

                return `${this.selectedBook.title} session of ${this.selectedReadingSession.date} notes`
            },
            commentList() {
                return this.currentCommentList == 'book' ? this.selectedBook.comments : this.selectedReadingSession.notes
            },
            ...mapGetters({
                selectedBook: 'getSelectedBook',
                selectedReadingSession: 'getSelectedReadingSession',
                currentCommentList: 'getCommentListToDisplay',
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