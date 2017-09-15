<template>
    <div class="content-wrapper">
        <h2 class="greeting">Welcome to the library, {{ user.friendly_name }}</h2>
        <h3 class="cta" @click="toggleGui"><span class="clickable-text">Get started</span></h3>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        computed: {
            ...mapGetters({
                user: 'getUser'
            })
        },
        methods: {
            ...mapActions({
                setContent: 'setContent',
                toggleGui: 'toggleGui'
            })
        }
    }
</script>