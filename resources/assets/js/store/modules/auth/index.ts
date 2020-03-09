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

    login({commit}: any, payload: any) {
        const form = new FormData();
        form.append('login', payload.login);
        form.append('password', payload.password);

        return axios
            .post('auth/login', form, {
                timeout: 10000,
            })
            .then(response => {
                if (
                    response.data &&
                    response.data.success
                ) {
                    commit('setAuthState', response.data);

                    return response.data;
                }

                throw 'Error processing request!';
            })
            .catch((error) => {
                if (
                    error.response &&
                    error.response.data &&
                    error.response.data.success === false &&
                    error.response.data.error
                ) {
                    throw error.response.data.error;
                }

                throw 'Error processing request!';
            });
    },

    register({commit}: any, payload: any) {
        const form = new FormData();
        form.append('login', payload.login);
        form.append('password', payload.password);

        return axios
            .post('auth/register', form, {
                timeout: 10000,
            })
            .then(response => {
                if (
                    response.data &&
                    response.data.success
                ) {
                    return response.data;
                }

                throw 'Error processing request!';
            })
            .catch((error) => {
                if (
                    error.response &&
                    error.response.data &&
                    error.response.data.success === false &&
                    error.response.data.error
                ) {
                    throw error.response.data.error;
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
