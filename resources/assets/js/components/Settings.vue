<template>
    <div class="content-wrapper">
        <div class="user-settings">
            <div class="modal-header">
                <h3>Profile</h3>
                <br>
                <button class="" @click="setContent('list')">List</button>
                <button class="" @click="setContent('trevacio')">Close</button>
                <button class="" v-if="hasHistory" @click="back">Back</button>
            </div>
            <div class="modal-body">
                <h4><strong>{{ user.name }}</strong></h4>
                <span>member since <br><strong>{{ user.created_at }}</strong></span>
                <br>
            </div>
            <div class="modal-footer">
                <a class="logout-button" :href="logout_route"
                    onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                    Logout
                </a>

                <form id="logout-form" :action="logout_route" method="POST" style="display: none;">
                    <input type="hidden" name="_token" :value="csrf_token">
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import Navigation from './../mixins/Navigation.js'

    export default {
        mixins: [Navigation],
        data() {
            return {
                csrf_token: null,
                logout_route: null
            }
        },
        computed: {
            ...mapGetters({
                user: 'getUser'
            })
        },
        methods: {
            ...mapActions({
                setContent: 'setContent'
            })
        },
        mounted() {
            this.csrf_token = handover._token
            this.logout_route = handover.logout_route
        }
    }
</script>