<template>
    <li class="list-item feed-item">
        <div class="item-info">
            <span class="">{{ item.id }} - {{ item.type }}</span>
            <br>
            <!-- <span class="comment-body">{{ item.body }}</span> -->
        </div>
    </li>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        props: [
            'index',
            'item'
        ],
        data() {
            return {
                
            }
        },
        computed: {
            ...mapGetters({
            })
        },
        methods: {
            ...mapActions({
                setContent: 'setContent',
            })
        }
    }
</script>

