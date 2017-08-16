<template>
    <div class="content-wrapper">
        <div class="modal-header">
            <h3 class="action">Soon to be SETTINGS</h3>
            <button class="modal-default-button" @click="toggleModal">Close</button>
        </div>
        <div class="modal-body">
            <h4>Ye ye ye</h4>
        </div>
        <div class="modal-footer">
            <button class="modal-default-button" @click="toggleModal">Close</button>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        computed: {
            ...mapGetters({})
        },
        methods: {
            ...mapActions({
                toggleModal: 'toggleModal'
            })
        }
    }
</script>