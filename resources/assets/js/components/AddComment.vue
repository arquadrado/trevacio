<template>
    <div class="content-wrapper">

        <div class="modal-header">
            <h3 class="action">{{ title }}</h3>
            <button class="" v-if="hasHistory" @click="back">Back</button>
            <button class="" @click="setContent('comment-list')">List</button>
        </div>
        <div class="modal-body">
            <div class="input-text">
                <label>Comment</label>
                <textarea :style="textareaStyle" v-model="comment" name="" id="" cols="30" rows="7"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button v-if="!loading" class="" :disabled="!canSubmit" @click="add">
                Save
            </button>
            <button v-else>
                <loading-spinner></loading-spinner>
            </button>
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
                comment: ''
            }
        },
        computed: {
            title() {
                if (this.currentCommentList == 'book') {
                    return `Add comment to ${this.selectedBook.title}`
                }

                return `${this.selectedBook.title} session of ${this.selectedReadingSession.date} note`
            },
            canSubmit() {
                return this.comment !== '' && this.selectedBook != null
            },
            textareaStyle() {
                return {
                    'border': `1px solid ${this.colorScheme.details}`
                }
            },
            commentableId() {
                switch (this.currentCommentList) {
                    case 'book':
                        return this.selectedBook.id

                    case 'session':
                        return this.selectedReadingSession.id

                }
            },
            ...mapGetters({
                loading: 'isLoading',
                colorScheme: 'getColorScheme',
                selectedBook: 'getSelectedBook',
                currentCommentList: 'getCommentListToDisplay',
                selectedReadingSession: 'getSelectedReadingSession',
                user: 'getUser'
            })
        },
        methods: {
            add() {
                this.addComment({
                    body: this.comment,
                    commentable_id: this.commentableId,
                    commentable_type: this.currentCommentList,
                    user: this.user
                })
            },
            ...mapActions({
                setContent: 'setContent',
                addComment: 'addComment'
            })
        }
    }
</script>