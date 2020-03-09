<template>
    <v-snackbar :color="color"
                :multi-line="true"
                :right="true"
                :timeout="timeout"
                :top="true"
                style="white-space: pre-line;"
                v-model="show">
        {{message}}

        <v-btn @click.native="show = false" dark text>Close</v-btn>
    </v-snackbar>
</template>

<script lang="ts">
    import Vue from "vue";
    import Component from "vue-class-component";

    @Component({
        name: "Snackbar",
    })
    export default class Snackbar extends Vue {
        public show: boolean = false;
        public message: string = '';
        public color: string = 'success';
        public timeout: number = 10000;

        public created() {

            this.$store.watch(state => state.base.snackbarProps, () => {
                const props = this.$store.state.base.snackbarProps;

                if (props && props.msg !== '') {
                    this.show = true;
                    this.message = props.msg;

                    if (props.color !== '') {
                        this.color = props.color;
                    }

                    if (props.timeout !== '') {
                        this.timeout = props.timeout;
                    }
                }
            });

        }
    }
</script>
