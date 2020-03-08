<template>
    <v-container>
        <v-alert
            border="left"
            prominent
            type="error"
        >
            Something went wrong while loading this page, please try again later. <v-btn to="/">Retry</v-btn>
        </v-alert>
    </v-container>
</template>

<script lang="ts">
    import Vue from "vue";
    import Component from "vue-class-component";

    @Component({
        name: "AppError",
    })
    export default class AppError extends Vue {
    }
</script>
