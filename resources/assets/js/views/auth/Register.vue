<template>
    <v-container>
        <v-card :loading="isProcessing" class="mx-auto my-12" elevation="0" max-width="400">
            <v-card-title class="justify-center">
                Create an account
            </v-card-title>

            <v-card-text class="text-center">
                Already have an account?
                <router-link to="/login">Login</router-link>
            </v-card-text>

            <v-card-text>
                <v-form v-model="formValid">
                    <v-text-field
                        :rules="rules.login"
                        label="Username"
                        solo
                        type="text"
                        v-model="formModel.login"
                    />

                    <v-text-field
                        :append-icon="togglePasswordStatus ? 'mdi-eye' : 'mdi-eye-off-outline'"
                        :rules="rules.password"
                        :type="togglePasswordStatus ? 'password' : 'text'"
                        @click:append="togglePasswordStatus = !togglePasswordStatus"
                        class="mt-3"
                        label="Password"
                        solo
                        v-model="formModel.password"
                    />
                </v-form>
            </v-card-text>

            <v-card-actions class="pa-4 pt-0">
                <v-btn :disabled="!formValid || isProcessing" @click.prevent="submit" color="primary" text>
                    Register
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-container>
</template>

<script lang="ts">
    import Vue from "vue";
    import Component from "vue-class-component";
    import {displaySnackbar} from "@/helpers/base-helper";

    @Component({
        name: "Register",
    })
    export default class Register extends Vue {

        public formValid: boolean = true;

        public formModel: { login: string, password: string } = {
            login: '',
            password: '',
        };

        public rules: any = {
            login: [
                (v: string) => !!v || 'Username is required',
            ],
            password: [
                (v: string) => !!v || 'Password is required',
                (v: string) => (v && v.length >= 3) || 'Password must have 3+ characters',
            ],
        };

        public togglePasswordStatus: boolean = true;

        public isProcessing: boolean = false;

        submit() {
            this.isProcessing = true;

            this.$store.dispatch('auth/register', this.formModel)
                .then(() => {
                    this.$router.push('/login');
                })
                .catch((error) => {
                    displaySnackbar(error, 'red');
                })
                .finally(() => {
                    this.isProcessing = false;
                });
        }

    }
</script>
