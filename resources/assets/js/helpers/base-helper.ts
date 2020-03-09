import Loading from "@/components/Loading.vue";
import PageError from "@/components/AppError.vue";
import store from "@/store";

export const formatDate = (date: any) => {
    let d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) {
        month = '0' + month;
    }

    if (day.length < 2) {
        day = '0' + day;
    }

    return [year, month, day].join('-');
};

export const lazyLoadRoute: any = (AsyncView: any) => {
    const AsyncHandler = () => ({
        component: AsyncView,
        loading: Loading,
        error: PageError,
        timeout: 10000,
        delay: 100,
    });

    return Promise.resolve({
        functional: true,
        render(h: any, {data, children}: any) {
            return h(AsyncHandler, data, children);
        }
    });
};

export const displaySnackbar = (message: string, color: string = 'green') => {
    store.commit('base/loadSnackbar', {
        msg: message,
        color: color
    });
};
