<template>
    <v-container
        class="text-center"
        fill-height
        style="height: calc(100vh - 58px);"
    >
        <v-row align="center">
            <v-col>
                <h1 class="display-4 primary--text">404</h1>

                <p>The page you were looking for does not exist</p>

                <v-btn
                    to="/"
                    color="primary"
                    outlined>
                    Go back
                </v-btn>
            </v-col>
        </v-row>
    </v-container>
</template>

<script lang="ts">
    import Vue from "vue";
    import Component from "vue-class-component";

    @Component({
        name: "PageNotFound",
    })
    export default class PageNotFound extends Vue {
        // logic
    }
</script>
