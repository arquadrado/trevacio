<template>
    <div class="content-wrapper">

        <div class="modal-header">
            <h3 class="action">{{ title }}</h3>
            <button v-if="hasHistory" @click="back"><span class="clickable-text">Back</span></button>
            <button @click="setContent('comment-list')"><span class="clickable-text">List</span></button>
        </div>
        <div class="modal-body">
            <div class="input-text" v-if="editing">
                <textarea :style="textareaStyle" v-model="comment" name="" id="" cols="30" rows="7"></textarea>
            </div>
            <div class="comment-body" v-else>
                <span>{{ comment }}</span>
            </div>
        </div>
        <div class="modal-footer" v-if="loading">
            <button>
                <loading-spinner></loading-spinner>
            </button>
        </div>
        <div class="modal-footer" v-else>

            <button v-if="editing" @click="edit"><span class="clickable-text">Cancel</span></button>
            <button :disabled="!canSubmit" @click="save" v-if="editing">
                <span class="clickable-text">Save</span>
            </button>
            <button v-if="!editing && canEdit" @click="edit"><span class="clickable-text">Edit</span></button>
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
                editing: false,
                comment: ''
            }
        },
        computed: {
            title() {
                if (this.currentCommentList == 'book') {
                    return `${this.selectedBook.title} comment`
                }

                return `${this.selectedBook.title} session of ${this.selectedReadingSession.date} note`
            },
            canEdit() {
                return this.selectedBook.in_library
            },
            canSubmit() {
                return this.comment !== '' && this.selectedBook != null
            },
            textareaStyle() {
                return {
                    'border': `2px solid ${this.colorScheme.details}`
                }
            },
            ...mapGetters({
                colorScheme: 'getColorScheme',
                selectedBook: 'getSelectedBook',
                selectedComment: 'getSelectedComment',
                selectedReadingSession: 'getSelectedReadingSession',
                currentCommentList: 'getCommentListToDisplay',
                loading: 'isLoading'
            })
        },
        methods: {
            edit() {
                this.editing = !this.editing
            },
            save() {
                this.updateComment({
                    selectedComment: this.selectedComment,
                    body: this.comment,
                    doneCallback: () => {
                        this.editing = false
                    }
                })
            },
            ...mapActions({
                setContent: 'setContent',
                updateComment: 'updateComment'
            })
        },
        mounted() {
            this.comment = this.selectedComment.body
        }
    }
</script>