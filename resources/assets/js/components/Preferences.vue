<template>
    <div class="content-wrapper">
        <div class="modal-header">
            <h3 class="action">{{ user.name }}</h3>
            <h4>preferences</h4>
            <br>
            <button @click="back" v-if="hasHistory"><span class="clickable-text">Back</span></button>
        </div>

        <div class="modal-body">
            <div class="preferences">
                <div class="color-scheme">
                    <h4>Color scheme</h4>
                    <label>
                        <input type="checkbox" :checked="useDefault" @change="toggleColorSchemeSet"> Use default
                    </label>
                    <br>
                    <button @click="addColorScheme">
                        <span class="clickable-text">
                            Add color scheme
                        </span>
                    </button>
                    <list 
                        :className="'color-scheme-list'" 
                        :itemType="'color-scheme-item'"
                        :items="userColorSchemes"
                    ></list>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import Navigation from './../mixins/Navigation.js'
    import List from './utilities/List.vue'

    export default {
        mixins: [Navigation],
        components: {
            'list': List
        },
        data() {
            return {
            }
        },
        computed: {
            useDefault() {
                return this.getColorSchemeSet === 'default'
            },
            ...mapGetters({
                colorScheme: 'getColorScheme',
                user: 'getUser',
                userColorSchemes: 'getUserColorSchemes',
                getColorSchemeSet: 'getColorSchemeSet',
            })
        },
        methods: {
            ...mapActions({
                setContent: 'setContent',
                addColorScheme: 'addColorScheme',
                toggleColorSchemeSet: 'toggleColorSchemeSet'
            })
        },
        mounted() {}
    }
</script>