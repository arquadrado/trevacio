<template>
    <div class="content-wrapper">
        <div class="modal-header">
            <h3 class="action">{{ listTitle }}</h3>
            <h4>{{ subtitle }}</h4>
            <br>
            <button v-if="hasHistory" @click="back"><span class="clickable-text">Back</span></button>
            <button @click="setContent('book')"><span class="clickable-text">Book</span></button>
            <button @click="setContent('reading-session-list')"><span class="clickable-text">Sessions</span></button>
        </div>
        <div class="modal-body">
            <div class="body-controls">
                <button @click="addComment" v-if="selectedBook.in_library"><span class="clickable-text">{{ addText }}</span></button>
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