<template>
    <div>
        <AppHeader/>

        <v-content>
            <router-view :key="$route.path"/>
        </v-content>
    </div>
</template>

<script lang="ts">
    import Vue from "vue";
    import Component from "vue-class-component";
    import AppHeader from "@/components/AppHeader.vue";
    import AppFooter from "@/components/AppFooter.vue";
    import store from "@/store";

    @Component({
        name: "BaseLayout",
        components: {
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
