<template>
    <div class="content-wrapper">
        <h1 class="greeting">Welcome to the library, {{ user.friendly_name }}</h1>
        <h3 class="cta" @click="toggleGui">Get started</h3>
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