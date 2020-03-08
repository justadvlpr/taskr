import Vue from "vue";
import Vuetify from "vuetify/lib";
import colors from "vuetify/lib/util/colors";

const LRU = require("lru-cache");

Vue.use(Vuetify);

let themeCache = null;

if (process.env.NODE_ENV === 'production') {
    themeCache = new LRU({max: 10, maxAge: 1000 * 60 * 60});
}

export default new Vuetify({
    icons: {
        iconfont: 'mdi',
    },
    theme: {
        options: {
            themeCache,
            minifyTheme: function (css) {
                return process.env.NODE_ENV === 'production'
                    ? css.replace(/[\r\n|\r|\n]/g, '')
                    : css
            },
        },
        dark: !!localStorage.getItem('dark_mode'),
        themes: {
            light: {
                primary: "#9e9d24",
                secondary: "#a0af22",
                grey: colors.grey.lighten5,
            },
            dark: {
                primary: "#9e9d24",
                secondary: "#a0af22",
                grey: colors.grey.darken4,
            },
        },
    },
});
