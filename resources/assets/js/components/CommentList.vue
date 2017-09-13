<template>
    <div class="content-wrapper">
        <div class="modal-header">
            <h3 class="action">{{ listTitle }}</h3>
            <h4>{{ subtitle }}</h4>
            <br>
            <button class="" v-if="hasHistory" @click="back">Back</button>
            <button class="" @click="setContent('book')">Book</button>
            <button class="" @click="setContent('reading-session-list')">Sessions</button>
        </div>
        <div class="modal-body">
            <div class="body-controls">
                <button class="" @click="addComment" v-if="selectedBook.in_library">{{ addText }}</button>
                <h4 v-else>This book is not in your library. Add it to add comments</h4>
            </div>
            <ul class="comment-list">
                <li class="comment" @click="selectComment(comment)" v-for="comment in commentList">
                    <div class="comment-info">
                        <span class="comment-user">{{ comment.user.name }} - {{ typeof comment.updated_at !== 'undefined' ? comment.updated_at : 'just now' }}</span>
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
                return this.selectedBook.title

            },
            subtitle() {
                if (this.currentCommentList == 'book') {
                    return 'comments'
                }                
                return `session notes of ${this.selectedReadingSession.date}`
            },
            addText() {
                return this.currentCommentList == 'book' ? 'Add comment' : 'Add note'
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