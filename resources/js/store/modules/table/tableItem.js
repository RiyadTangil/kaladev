import axios from "axios";
import appService from "../../../services/appService.js";

export const tableItem = {
    namespaced: true,
    state: {
        lists: [],
        featured: [],
        popular: {},
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
        featured: function (state) {
            return state.featured;
        },
        popular: function (state) {
            return state.popular;
        },
    },
    actions: {
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = `table/item/${payload.table_slug}`;
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if(typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit('lists', res.data.data);
                    }
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
    },
    mutations: {
        lists: function (state, payload) {
            state.lists = payload
        },
        featured: function (state, payload) {
            state.featured = payload;
        },
        popular: function (state, payload) {
            state.popular = payload;
        }
    },
};
