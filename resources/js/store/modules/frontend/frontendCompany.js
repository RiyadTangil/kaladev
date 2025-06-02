import axios from "axios";

export const frontendCompany = {
    namespaced: true,
    state: {
        lists: {
            company_name: "",
            company_email: "",
            company_phone: "",
            company_address: "",
            company_website: "",
            company_logo: "",
        }
    },
    getters: {
        lists: function (state) {
            return state.lists;
        }
    },
    actions: {
        lists: function (context) {
            return new Promise((resolve, reject) => {
                axios.get("frontend/setting").then((res) => {
                    if (res.data && res.data.data) {
                        context.commit("lists", res.data.data);
                    }
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        }
    },
    mutations: {
        lists: function (state, payload) {
            state.lists = payload;
        }
    }
}; 