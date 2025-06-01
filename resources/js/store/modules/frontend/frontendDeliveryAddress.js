

export const frontendDeliveryAddress = {
    namespaced: true,
    state: {
        address: {},
    },
    getters: {
        address: function (state) {
            return state.address;
        },
    },
    actions: {
        address: function (context, payload) {
            context.commit("address", payload);
        },
        reset: function (context) {
            context.commit('reset');
        },
    },
    mutations: {
        address: function (state, payload) {
            state.address = payload;
        },
        reset: function (state) {
            state.address = {};
        }
    },
};
