<template>
    <div class="content-wrapper">

        <div class="modal-header">
            <h3 class="action">Comment</h3>
            <button class="" v-if="hasHistory" @click="back">Back</button>
        </div>
        <div class="modal-body">
            <div class="input-text" v-if="editing">
                <textarea :style="textareaStyle" v-model="comment" name="" id="" cols="30" rows="7"></textarea>
            </div>
            <div class="comment-body" v-else>
                <span>{{ comment }}</span>
            </div>
        </div>
        <div class="modal-footer">

            <button v-if="editing" @click="edit">Cancel</button>
            <button class="" :disabled="!canSubmit" @click="save" v-if="editing">
                Save
            </button>
            <button v-if="!editing && canEdit" @click="edit">Edit</button>
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
                selectedComment: 'getSelectedComment'
            })
        },
        methods: {
            edit() {
                this.editing = !this.editing
            },
            save() {
                this.editing = false
                this.updateComment({
                    selectedComment: this.selectedComment,
                    comment: this.comment
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