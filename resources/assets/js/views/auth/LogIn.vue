<template>
    <v-container>
        <v-card :loading="isProcessing" class="mx-auto my-12" elevation="0" max-width="400">
            <v-card-title class="justify-center">
                Login into your account
            </v-card-title>

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
                <!--<v-btn small text to="/forgot-password">
                    Forgot Password?
                </v-btn>-->
                <v-spacer/>

                <v-btn :disabled="!formValid || isProcessing" @click.prevent="submit" color="primary" text>
                    Sign in
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-container>
</template>

<script lang="ts">
    import Vue from "vue";
    import Component from "vue-class-component";
    import {MODULE_AUTH} from "@/store/modules.type";
    import {SET_AUTH} from "@/store/modules/auth/mutations.type";

    @Component({
        name: "LogIn",
    })
    export default class LogIn extends Vue {

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

            this.$store.dispatch(
                'auth/loginOrRegister',
                {
                    type: 'login',
                    formData: this.formModel,
                })
                .then(data => {
                    if (data) {
                        this.$store.commit('auth/setAuthState', data);
                        this.$router.push('/tasks/today');
                    }
                })
                .catch(() => {
                })
                .finally(() => {
                    this.isProcessing = false;
                });
        }

    }
</script>
