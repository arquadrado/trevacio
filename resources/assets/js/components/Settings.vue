<template>
    <div class="content-wrapper">
        <div class="modal-header">
            <h3 class="action">Soon to be SETTINGS</h3>
            <button class="modal-default-button" @click="setContent('trevacio')">Close</button>
            <button class="modal-default-button" v-if="hasHistory" @click="back">Back</button>
        </div>
        <div class="modal-body">
            <h4>Ye ye ye</h4>
        </div>
        <div class="modal-footer">
            <button class="modal-default-button" @click="setContent('trevacio')">Close</button>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import Navigation from './../mixins/Navigation.js'

    export default {
        mixins: [Navigation],
        computed: {
            ...mapGetters({})
        },
        methods: {
            ...mapActions({
                setContent: 'setContent'
            })
        }
    }
</script>