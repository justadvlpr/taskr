<template>
    <v-app-bar
        app
        color="primary"
        dense
        flat
    >
        <v-toolbar-title class="white--text hidden-xs-only mr-4">taskr.</v-toolbar-title>

        <v-toolbar-items v-if="isAuthenticated">
            <v-btn text to="/tasks/today">
                Today
            </v-btn>

            <v-btn text to="/tasks/all">
                All
            </v-btn>

            <v-btn text to="/tasks/create">
                <v-icon class="mr-1">mdi-plus</v-icon>
            </v-btn>
        </v-toolbar-items>

        <v-spacer/>

        <v-menu offset-y v-if="isAuthenticated">
            <template v-slot:activator="{ on }">
                <v-btn
                    color="white"
                    icon
                    text
                    v-on="on"
                >
                    <v-avatar>
                        <v-icon dark>mdi-account-circle</v-icon>
                    </v-avatar>
                </v-btn>
            </template>

            <v-list>
                <v-list-item to="/logout">
                    <v-list-item-title text>
                        Sign out
                    </v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>

        <v-menu offset-y>
            <template v-slot:activator="{ on }">
                <v-btn
                    color="white"
                    icon
                    text
                    v-on="on"
                >
                    <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
            </template>

            <v-list>
                <v-list-item-group>
                    <v-list-item>
                        <v-list-item-icon>
                            <v-icon>mdi-theme-light-dark</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>
                            <v-switch class="ml-5" v-model="darkMode"/>
                        </v-list-item-title>
                    </v-list-item>
                </v-list-item-group>
            </v-list>
        </v-menu>

    </v-app-bar>
</template>

<script lang="ts">
    import Vue from "vue";
    import Component from "vue-class-component";
    import {Watch} from "vue-property-decorator";

    @Component({
        name: "AppHeader",
    })
    export default class AppHeader extends Vue {

        private darkMode: boolean = this.$vuetify.theme.dark;

        get isAuthenticated() {
            return this.$store.state.auth.isAuthenticated;
        }

        get user() {
            return this.$store.state.auth.user;
        }

        @Watch('darkMode')
        onDarkModeSwitch() {
            if (this.darkMode) {
                localStorage.setItem('dark_mode', 'on');
                this.$vuetify.theme.dark = true;
            } else {
                localStorage.removeItem('dark_mode');
                this.$vuetify.theme.dark = false;
            }
        }

    }
</script>
