<template>
    <div class="content-wrapper">
        <div class="user-settings">
            <div class="modal-header">
                <h3>Profile</h3>
                <br>
                <button @click="setContent('list')"><span class="clickable-text">List</span></button>
                <button @click="setContent('home')"><span class="clickable-text">Close</span></button>
                <button v-if="hasHistory" @click="back"><span class="clickable-text">Back</span></button>
            </div>
            <div class="modal-body">
                <div class="user-info">
                    <h4><strong>{{ user.name }}</strong></h4>
                    <span>member since <br><strong>{{ user.created_at }}</strong></span>
                </div>
                <br>
                <div class="user-actions">
                    <button @click="setContent('user-stats')"><span class="clickable-text">User stats</span></button>
                </div>
            </div>
            <div class="modal-footer">
                <a class="logout-button" :href="logout_route"
                    onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                    <span class="clickable-text">Logout</span>
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