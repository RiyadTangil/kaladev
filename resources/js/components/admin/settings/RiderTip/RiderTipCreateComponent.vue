<template>
    <LoadingComponent :props="loading" />
    <SmModalCreateComponent :props="addButton" />

    <div id="modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("menu.rider_tips") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label for="label" class="db-field-title required">{{
                                $t("label.label")
                                }}</label>
                            <input v-model="props.form.label" v-bind:class="errors.label ? 'invalid' : ''" type="text"
                                id="label" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.label">{{
                                errors.label[0]
                                }}</small>
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label for="amount" class="db-field-title required">{{
                                $t("label.amount")
                                }}</label>
                            <input v-on:keypress="floatNumber($event)" v-model="props.form.amount"
                                v-bind:class="errors.amount ? 'invalid' : ''" type="text" id="amount"
                                class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.amount">{{ errors.amount[0] }}</small>
                        </div>

                        <div class="form-col-12">
                            <div class="modal-btns">
                                <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                    <i class="lab lab-close"></i>
                                    <span>{{ $t("button.close") }}</span>
                                </button>

                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-save"></i>
                                    <span>{{ $t("button.save") }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>

import SmModalCreateComponent from "../../components/buttons/SmModalCreateComponent.vue";
import LoadingComponent from "../../components/LoadingComponent.vue";
import alertService from "../../../../services/alertService.js";
import appService from "../../../../services/appService.js";
import askEnum from "../../../../enums/modules/askEnum.js";

export default {
    name: "RiderTipCreateComponent",
    components: { SmModalCreateComponent, LoadingComponent },
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                askEnum: askEnum,
                askEnumArray: {
                    [askEnum.YES]: this.$t("label.yes"),
                    [askEnum.NO]: this.$t("label.no"),
                },
            },
            errors: {},
        };
    },
    computed: {
        addButton: function () {
            return { title: this.$t('button.add_rider_tip') };
        }
    },
    methods: {
        floatNumber(e) {
            return appService.floatNumber(e);
        },
        reset: function () {
            appService.modalHide();
            this.$store.dispatch("riderTip/reset").then().catch();
            this.errors = {};
            this.$props.props.form = {
                label: "",
                amount: "",
            };
        },

        save: function () {
            try {
                const tempId = this.$store.getters["riderTip/temp"].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch("riderTip/save", this.props).then((res) => {
                    appService.modalHide();
                    this.loading.isActive = false;
                    alertService.successFlip(
                        tempId === null ? 0 : 1,
                        this.$t("menu.rider_tips")
                    );
                    this.props.form = {
                        label: "",
                        amount: "",
                    };
                    this.errors = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    },
};
</script>