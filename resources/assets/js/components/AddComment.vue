<template>
    <div class="content-wrapper">

        <div class="modal-header">
            <h3 class="action">Add a comment</h3>
            <button class="" v-if="hasHistory" @click="back">Back</button>
        </div>
        <div class="modal-body">
            <div class="input-text">
                <label>Comment</label>
                <textarea :style="textareaStyle" v-model="comment" name="" id="" cols="30" rows="7"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="" :disabled="!canSubmit" @click="add">
                Save
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
            })
        },
        methods: {
            add() {
                this.addComment({
                    comment: this.comment,
                    commentable_id: this.selectedBook.id,
                    commentable_type: 'book'
                })
                this.setContent('comment')
            },
            ...mapActions({
                setContent: 'setContent',
                addComment: 'addComment'
            })
        }
    }
</script>