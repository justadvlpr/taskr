const namespaced = true;

export default {
    namespaced,
    state: {
        authVerified: false,
        snackbarProps: {},
    },
    actions: {},
    getters: {},
    mutations: {
        loadSnackbar(state: any, props: object = {}) {
            state.snackbarProps = props;
        },
    },
};
