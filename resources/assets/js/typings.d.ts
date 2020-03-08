declare module '*.vue' {
    import Vue from 'vue'
    export default Vue
}

interface Window {
    axios: any;
    stripe: any;
    elements: any;
    appGlobalVars: {
        baseUrl: string,
        name: string,
        version: string,
    };
}
