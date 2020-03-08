import {STORAGE_KEY_USER_PRIVATE_TOKEN} from "@/constants/storage.type";
import axios from "axios";

const state = {
    user: {},
    isAuthenticated: !!localStorage.getItem(STORAGE_KEY_USER_PRIVATE_TOKEN),
};

const getters = {

    user(state: any) {
        return state.user;
    },

    isAuthenticated(state: any) {
        return state.isAuthenticated;
    },

};

const actions = {

    loginOrRegister({commit}: any, payload: any) {
        commit('destroyAuthState');

        let url = '';

        switch (payload.type) {
            case 'login':
                url = 'auth/login';
                break;

            case 'register':
                url = 'auth/register';
                break;
        }

        let formData = new FormData();

        for (let [key, value] of Object.entries(payload.formData)) {
            formData.append(key, '' + value);
        }

        return axios
            .post(url, formData, {
                timeout: 10000,
            })
            .then(response => {

                if (!response.data) {
                    throw 'Error, no response data!';
                }

                if (response.data.success === true) {
                    return response.data;
                }

                throw 'Error processing request!';

            })
            .catch((error) => {

                if (!error.response.data) {
                    throw 'Error, no response data!';
                }

                if (error.response.data.success === false) {

                    if (error.response.data.errors) {

                        let errors = "";

                        Object.values(error.response.data.errors).forEach((value: any) => {
                            Object.values(value).forEach((error: any) => {
                                errors += "* " + error + "\n";
                            });
                        });

                        throw errors;

                    }

                }

                throw 'Error processing request!';

            });
    },

    logout({commit}: any) {
        commit('destroyAuthState');
    },

    verify({commit, state}: any) {
        if (state.isAuthenticated) {
            return axios
                .get('auth/verify')
                .then(response => {
                    if (
                        response &&
                        response.data &&
                        response.data.user &&
                        response.status === 200
                    ) {
                        commit('setAuthState', response.data);
                    } else {
                        commit('destroyAuthState');
                    }
                })
                .catch(() => {
                    commit('destroyAuthState');
                });
        }
    }

};

const mutations = {

    setAuthState(state: any, data: any) {
        state.isAuthenticated = true;
        state.user = data.user;

        if (data.user.token) {
            localStorage.setItem(STORAGE_KEY_USER_PRIVATE_TOKEN, data.user.token);
        }
    },

    destroyAuthState(state: any) {
        state.isAuthenticated = false;
        state.user = {};
        localStorage.removeItem(STORAGE_KEY_USER_PRIVATE_TOKEN);
    },

};

export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
};
