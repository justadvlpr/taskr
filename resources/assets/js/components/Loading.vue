<template>
    <v-skeleton-loader
        :boilerplate="false"
        class="mx-auto"
        ref="skeleton"
        title="aa"
        type="table"
    />
</template>

<script lang="ts">
    import Vue from "vue";
    import {Component} from "vue-property-decorator";

    @Component({
        name: "Loading",
    })
    export default class Loading extends Vue {

    }
</script>
