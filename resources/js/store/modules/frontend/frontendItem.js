import axios from "axios";
import appService from "../../../services/appService.js";

export const frontendItem = {
    namespaced: true,
    state: {
        lists: [],
        show: {},
        featured: [],
        popular: [],
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
        show: function (state) {
            return state.show;
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
                axios.get("frontend/item", {
                    params: payload
                }).then((res) => {
                    context.commit("lists", res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        show: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`frontend/item/${payload.slug}`).then((res) => {
                    context.commit("show", res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        featured: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get("frontend/item/featured-items", {
                    params: payload
                }).then((res) => {
                    context.commit("featured", res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        popular: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get("frontend/item/popular-items", {
                    params: payload
                }).then((res) => {
                    context.commit("popular", res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
    },
    mutations: {
        lists: function (state, payload) {
            state.lists = payload;
        },
        show: function (state, payload) {
            state.show = payload;
        },
        featured: function (state, payload) {
            state.featured = payload;
        },
        popular: function (state, payload) {
            state.popular = payload;
        },
    },
};
