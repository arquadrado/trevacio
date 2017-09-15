<template>
    <li class="color-scheme" @click="selectColorScheme(item)">
        <div class="color-scheme-info" v-if="!editing">
            <span class="color-scheme-user">{{ item.name }}</span>
            <br>
            <span class="color-scheme-body">{{ item.details }}</span>
            <br>
            <span class="color-scheme-body">{{ item.background }}</span>
            <br>
            <span v-if="item.font" class="color-scheme-body">{{ item.font }}</span>
        </div>
        <div class="quick-actions">
            <i class="material-icons" @click="toggleEdit">edit</i>
            <i class="material-icons" v-if="item.id !== null" @click="update()">save</i>
            <i class="material-icons" v-else @click="save()">save</i>
        </div>
        <div class="color-scheme-info" v-if="editing">
            <label for="details">Details</label>
            <input type="color" name="details" v-model="details">
            <br>
            <label for="background">Background</label>
            <input type="color" name="background" v-model="background">
            <br>
            <label for="font">Font</label>
            <input type="text" name="font" v-model="font">
        </div>
    </li>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'

    export default {
        props: [
            'item',
            'index'
        ],
        data() {
            return {
                editing: false,
                details: null,
                background: null,
                font: null
            }
        },
        computed: {
            ...mapGetters({
            })
        },
        methods: {
            update() {
                this.updateColorScheme({
                    id: this.item.id,
                    index: this.index,
                    details: this.details,
                    background: this.background,
                    font: this.font,
                })
            },
            save() {
                this.saveColorScheme({
                    index: this.index,
                    details: this.details,
                    background: this.background,
                    font: this.font,
                })
            },
            toggleEdit() {
                this.editing = !this.editing
            },
            updateColorScheme() {
                console.log(this.item)
            },
            selectColorScheme(scheme) {
                this.setSelectedColorScheme(scheme)
            },
            ...mapActions({
                setSelectedColorScheme: 'setSelectedColorScheme',
                saveColorScheme: 'saveColorScheme',
                updateColorScheme: 'updateColorScheme'
            })
        },
        mounted() {
            this.details = this.item.details
            this.background = this.item.background
            this.font = this.item.font
        }
    }
</script>

