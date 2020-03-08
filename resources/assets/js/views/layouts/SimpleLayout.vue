<template>
    <div>
        <v-content>
            <router-view/>
        </v-content>
    </div>
</template>

<script lang="ts">
    import Vue from "vue";
    import Component from "vue-class-component";

    @Component({
        name: "SimpleLayout",
    })
    export default class SimpleLayout extends Vue {
    }
</script>
