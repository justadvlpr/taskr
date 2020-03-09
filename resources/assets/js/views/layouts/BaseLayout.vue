<template>
    <div>
        <AppHeader/>

        <Snackbar/>

        <v-content>
            <router-view :key="$route.path"/>
        </v-content>
    </div>
</template>

<script lang="ts">
    import Vue from "vue";
    import Component from "vue-class-component";
    import AppHeader from "@/components/AppHeader.vue";
    import store from "@/store";
    import Snackbar from "@/components/Snackbar.vue";

    @Component({
        name: "BaseLayout",
        components: {
            Snackbar,
            AppHeader,
        },
        beforeRouteEnter: async function (to, from, next) {
            const storeClone: any = store;

            try {
                if (!storeClone.state.base.authVerified) {
                    await store.dispatch('auth/verify');
                }
                next();
            } catch {
                next('/error');
            } finally {
                storeClone.state.base.authVerified = true;
            }
        },
    })
    export default class BaseLayout extends Vue {
    }
</script>
