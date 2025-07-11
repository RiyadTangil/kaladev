<template>
    <LoadingComponent :props="loading" />
    <SmModalCreateComponent :props="addButton" />

    <div id="modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("menu.taxes") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12">
                            <label for="name" class="db-field-title required">
                                {{ $t("label.name") }}
                            </label>
                            <input v-model="props.form.name" v-bind:class="errors.name ? 'invalid' : ''" type="text"
                                id="name" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.name">
                                {{ errors.name[0] }}
                            </small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="code" class="db-field-title required">{{
                                $t("label.code")
                            }}</label>
                            <input v-model="props.form.code" v-bind:class="errors.code ? 'invalid' : ''" type="text"
                                id="code" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.code">{{
                                errors.code[0]
                            }}</small>
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label for="tax_rate" class="db-field-title required">
                                {{ $t("label.tax_rate") }}
                            </label>
                            <input v-model="props.form.tax_rate" v-bind:class="errors.tax_rate ? 'invalid' : ''" type="text"
                                id="tax_rate" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.tax_rate">{{ errors.tax_rate[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="active">{{ $t('label.status') }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.ACTIVE" v-model="props.form.status" id="active"
                                            type="radio" class="custom-radio-field">
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="active" class="db-field-label">{{ $t('label.active') }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.INACTIVE" v-model="props.form.status" type="radio"
                                            id="inactive" class="custom-radio-field">
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="inactive" class="db-field-label">{{ $t('label.inactive') }}</label>
                                </div>
                            </div>
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
import statusEnum from "../../../../enums/modules/statusEnum.js";
import alertService from "../../../../services/alertService.js";
import appService from "../../../../services/appService.js";

export default {
    name: "TaxCreateComponent",
    components: { SmModalCreateComponent, LoadingComponent },
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive")
                }
            },
            errors: {},
        };
    },
    computed: {
        addButton: function () {
            return { title: this.$t('button.add_tax') };
        }
    },
    methods: {
        reset: function () {
            appService.modalHide();
            this.$store.dispatch("tax/reset").then().catch();
            this.errors = {};
            this.$props.props.form = {
                name: "",
                code: "",
                tax_rate: "",
                status: statusEnum.ACTIVE,
            };
        },

        save: function () {
            try {
                const tempId = this.$store.getters["tax/temp"].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch("tax/save", this.props).then((res) => {
                    appService.modalHide();
                    this.loading.isActive = false;
                    alertService.successFlip(
                        tempId === null ? 0 : 1,
                        this.$t("menu.taxes")
                    );
                    this.props.form = {
                        name: "",
                        code: "",
                        tax_rate: "",
                        status: statusEnum.ACTIVE,
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
