import axios, {AxiosRequestConfig} from "axios"
import {STORAGE_KEY_USER_PRIVATE_TOKEN} from "@/constants/storage.type";
import router from "@/router";

window.axios = axios;

window.axios.interceptors.request.use(
    function (config: AxiosRequestConfig) {

        if (typeof window.location.origin !== 'undefined') {
            config.baseURL = window.location.origin + '/api/';
        } else {
            throw Error('App base URL missing!');
        }

        config.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        config.headers.common["Request-Origin"] = navigator.userAgent;

        if (localStorage.getItem(STORAGE_KEY_USER_PRIVATE_TOKEN)) {
            config.headers.common["Authorization"] = `Bearer ${localStorage.getItem(STORAGE_KEY_USER_PRIVATE_TOKEN)}`;
        }

        return config;

    },
    function (error: any) {
        return Promise.reject(error)
    }
);

window.axios.interceptors.response.use(function(resp: AxiosRequestConfig) {
        return new Promise((resolve) => {
            resolve(resp);
        });
    },
    function (err: any) {
    return new Promise(() => {
        /**
         * If any request throws am Unauthorized error, we logout the user.
         */
        if (
            err.response.data &&
            (err.response.statusText === 'Unauthorized' || err.response.data.name === 'Unauthorized')
        ) {
            router.push('/logout').catch(() => {});
        } else {
            throw err;
        }
    });
});
