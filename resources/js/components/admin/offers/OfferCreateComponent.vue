<template>
    <LoadingComponent :props="loading" />
    <SmSidebarModalCreateComponent :props="addButton" />

    <div id="sidebar" class="drawer">
        <div class="drawer-header">
            <h3 class="drawer-title">{{ $t('menu.offers') }}</h3>
            <button class="fa-solid fa-xmark close-btn" @click="reset"></button>
        </div>
        <div class="drawer-body">
            <form @submit.prevent="save">
                <div class="form-row">
                    <div class="form-col-12 sm:form-col-6">
                        <label for="name" class="db-field-title required">{{ $t("label.name") }}</label>
                        <input v-model="props.form.name" v-bind:class="errors.name ? 'invalid' : ''" type="text"
                            id="name" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.name">{{
                            errors.name[0]
                            }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="amount" class="db-field-title required">
                            {{ $t("label.discount_percentage") }}
                        </label>
                        <input v-model="props.form.amount" v-on:keypress="floatNumber($event)"
                            v-bind:class="errors.amount ? 'invalid' : ''" type="text" id="amount"
                            class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.amount">{{ errors.amount[0] }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="day" class="db-field-title required">{{
                            $t("label.day")
                            }}</label>
                        <vue-select class="db-field-control f-b-custom-select" id="day" v-model="props.form.day"
                            :options="enums.dayEnums" label-by="name" value-by="id" :closeOnSelect="true"
                            :searchable="true" :clearOnClose="true" placeholder="--" search-placeholder="--" />
                        <small class="db-field-alert" v-if="errors.day">{{ errors.day[0] }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required">{{ $t("label.image") }} (548px,140px)</label>
                        <input @change="changeImage" v-bind:class="errors.image ? 'invalid' : ''" id="image" type="file"
                            class="db-field-control" ref="imageProperty" accept="image/png, image/jpeg, image/jpg" />
                        <small class="db-field-alert" v-if="errors.image">{{ errors.image[0] }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="start_time" class="db-field-title required">{{ $t("label.start_time") }}</label>
                        <Datepicker @update:modelValue="handleTimeStart" hideInputIcon v-model="props.startTime"
                            :time-picker="true" :time-picker-only="true" :is24="false" utc="false"
                            :input-class-name="errors.start_time ? 'invalid' : ''">
                        </Datepicker>
                        <small class="db-field-alert" v-if="errors.start_time">{{ errors.start_time[0] }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="end_time" class="db-field-title required">{{ $t("label.end_time") }}</label>
                        <Datepicker @update:modelValue="handleTimeEnd" hideInputIcon v-model="props.endTime"
                            :time-picker="true" :time-picker-only="true" :is24="false" utc="false"
                            :input-class-name="errors.end_time ? 'invalid' : ''">
                        </Datepicker>
                        <small class="db-field-alert" v-if="errors.end_time">{{
                            errors.end_time[0]
                            }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title">{{ $t("label.status") }}</label>
                        <div class="db-field-radio-group">
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input type="radio" v-model="props.form.status" id="active"
                                        :value="enums.statusEnum.ACTIVE" class="custom-radio-field" checked />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="active" class="db-field-label">{{
                                    $t("label.active")
                                    }}</label>
                            </div>
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input type="radio" class="custom-radio-field" v-model="props.form.status"
                                        id="inactive" :value="enums.statusEnum.INACTIVE" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="inactive" class="db-field-label">{{
                                    $t("label.inactive")
                                    }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-col-12">
                        <div class="flex flex-wrap gap-3 mt-4">
                            <button type="submit" class="db-btn py-2 text-white bg-primary">
                                <i class="lab lab-save"></i>
                                <span>{{ $t("label.save") }}</span>
                            </button>

                            <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                <i class="lab lab-close"></i>
                                <span>{{ $t("button.close") }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
import SmSidebarModalCreateComponent from "../components/buttons/SmSidebarModalCreateComponent.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import LoadingComponent from "../components/LoadingComponent.vue";
import statusEnum from "../../../enums/modules/statusEnum.js";
import dayEnum from "../../../enums/modules/dayEnum.js";
import alertService from "../../../services/alertService.js";
import appService from "../../../services/appService.js";

export default {
    name: "OfferCreateComponent",
    components: { SmSidebarModalCreateComponent, LoadingComponent, Datepicker },
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                statusEnum: statusEnum,
                dayEnums: dayEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },
            image: "",
            errors: {},
        };
    },
    computed: {
        addButton: function () {
            return { title: this.$t('button.add_offer') };
        }
    },
    methods: {
        floatNumber(e) {
            return appService.floatNumber(e);
        },
        changeImage: function (e) {
            this.image = e.target.files[0];
        },
        handleTimeStart: function (e) {
            if (e) {
                this.props.form.start_time = e.hours + ':' + e.minutes + ':' + e.seconds;
            } else {
                this.props.form.start_time = null;

            }
        },
        handleTimeEnd: function (e) {
            if (e) {
                this.props.form.end_time = e.hours + ':' + e.minutes + ':' + e.seconds;
            } else {
                this.props.form.end_time = null;

            }
        },
        reset: function () {
            appService.sideDrawerHide();
            this.$store.dispatch("offer/reset").then().catch();
            this.errors = {};
            this.$props.props.startTime = '';
            this.$props.props.endTime = '';
            this.$props.props.form = {
                name: "",
                amount: "",
                day: null,
                start_time: "",
                end_time: "",
                status: statusEnum.ACTIVE,
            };
            if (this.image) {
                this.image = "";
                this.$refs.imageProperty.value = null;
            }
        },

        save: function () {
            try {
                const fd = new FormData();
                fd.append("name", this.props.form.name);
                fd.append("amount", this.props.form.amount);
                fd.append("day", this.props.form.day);
                fd.append("start_time", this.props.form.start_time);
                fd.append("end_time", this.props.form.end_time);
                fd.append("status", this.props.form.status);
                if (this.image) {
                    fd.append("image", this.image);
                }
                const tempId = this.$store.getters["offer/temp"].temp_id;
                this.loading.isActive = true;
                this.$store
                    .dispatch("offer/save", {
                        form: fd,
                        search: this.props.search,
                    })
                    .then((res) => {
                        appService.sideDrawerHide();
                        this.loading.isActive = false;
                        alertService.successFlip(
                            tempId === null ? 0 : 1,
                            this.$t("menu.offers")
                        );
                        this.props.form = {
                            name: "",
                            amount: "",
                            day: null,
                            start_time: "",
                            end_time: "",
                            status: statusEnum.ACTIVE,
                        };
                        this.image = "";
                        this.errors = {};
                        this.$props.props.startTime = '';
                        this.$props.props.endTime = '';
                        this.$refs.imageProperty.value = null;
                    })
                    .catch((err) => {
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