<template>
    <LoadingComponent :props="loading" />
    <div class="col-12">
        <div class="db-card db-tab-div active">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">{{ $t("menu.offers") }}</h3>
                <div class="db-card-filter">
                    <TableLimitComponent :method="list" :search="props.search" :page="paginationPage" />
                    <FilterComponent />
                    <div class="dropdown-group">
                        <ExportComponent />
                        <div class="dropdown-list db-card-filter-dropdown-list">
                            <PrintComponent :props="printObj" />
                            <ExcelComponent :method="xls" />
                        </div>
                    </div>
                    <OfferCreateComponent :props="props" v-if="permissionChecker('offers_create')" />
                </div>
            </div>
            <div class="table-filter-div">
                <form class="p-4 sm:p-5 mb-5" @submit.prevent="search">
                    <div class="row">
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchName" class="db-field-title after:hidden">{{
                                $t("label.name")
                                }}</label>
                            <input id="searchName" v-model="props.search.name" type="text" class="db-field-control" />
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchAmount" class="db-field-title after:hidden">{{
                                $t("label.amount")
                                }}</label>
                            <input id="searchAmount" v-model="props.search.amount" v-on:keypress="floatNumber($event)"
                                type="text" class="db-field-control" />
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchStartDate" class="db-field-title after:hidden">{{
                                $t("label.start_time")
                                }}</label>
                            <Datepicker @update:modelValue="handleStartTime" hideInputIcon v-model="startTimeValue"
                                :time-picker="true" :time-picker-only="true" :is24="false" utc="false">
                            </Datepicker>
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchEndDate" class="db-field-title after:hidden">{{
                                $t("label.end_time")
                                }}</label>
                            <Datepicker @update:modelValue="handleEndTime" hideInputIcon v-model="endTimeValue"
                                :time-picker="true" :time-picker-only="true" :is24="false" utc="false">
                            </Datepicker>
                        </div>

                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchDay" class="db-field-title after:hidden">{{
                                $t("label.day")
                            }}</label>
                            <vue-select class="db-field-control f-b-custom-select" id="searchDay"
                                v-model="props.search.day" :options="enums.dayEnums" label-by="name" value-by="id"
                                :closeOnSelect="true" :searchable="true" :clearOnClose="true" placeholder="--"
                                search-placeholder="--" />
                        </div>

                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchStatus" class="db-field-title after:hidden">{{
                                $t("label.status")
                                }}</label>
                            <vue-select class="db-field-control f-b-custom-select" id="searchStatus"
                                v-model="props.search.status" :options="[
                                    { id: enums.statusEnum.ACTIVE, name: $t('label.active') },
                                    { id: enums.statusEnum.INACTIVE, name: $t('label.inactive') },
                                ]" label-by="name" value-by="id" :closeOnSelect="true" :searchable="true"
                                :clearOnClose="true" placeholder="--" search-placeholder="--" />
                        </div>
                        <div class="col-12">
                            <div class="flex flex-wrap gap-3 mt-4">
                                <button class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-search-line lab-font-size-16"></i>
                                    <span>{{ $t("button.search") }}</span>
                                </button>
                                <button class="db-btn py-2 text-white bg-gray-600" @click="clear">
                                    <i class="lab lab-cross-line-2 lab-font-size-22"></i>
                                    <span>{{ $t("button.clear") }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="db-table-responsive">
                <table class="db-table stripe" id="print" :dir="direction">
                    <thead class="db-table-head">
                        <tr class="db-table-head-tr">
                            <th class="db-table-head-th">{{ $t("label.name") }}</th>
                            <th class="db-table-head-th">{{ $t("label.amount") }}</th>
                            <th class="db-table-head-th">{{ $t("label.day") }}</th>
                            <th class="db-table-head-th">{{ $t("label.time") }}</th>
                            <th class="db-table-head-th">{{ $t("label.status") }}</th>
                            <th class="db-table-head-th hidden-print"
                                v-if="permissionChecker('offers_show') || permissionChecker('offers_edit') || permissionChecker('offers_delete')">
                                {{ $t("label.action") }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="db-table-body" v-if="offers.length > 0">
                        <tr class="db-table-body-tr" v-for="offer in offers" :key="offer">
                            <td class="db-table-body-td">
                                <div v-if="offer.name.length < 40">{{ offer.name }}</div>
                                <div v-else>{{ offer.name.substring(0, 40) + ".." }}</div>
                            </td>
                            <td class="db-table-body-td">{{ offer.flat_amount }}</td>
                            <td class="db-table-body-td">{{ offer.day_name }}</td>
                            <td class="db-table-body-td">{{ offer.convert_time }}</td>
                            <td class="db-table-body-td">
                                <span :class="statusClass(offer.status)">
                                    {{ enums.statusEnumArray[offer.status] }}
                                </span>
                            </td>
                            <td class="db-table-body-td hidden-print"
                                v-if="permissionChecker('offers_show') || permissionChecker('offers_edit') || permissionChecker('offers_delete')">
                                <div class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
                                    <SmIconViewComponent :link="'admin.offer.show'" :id="offer.id"
                                        v-if="permissionChecker('offers_show')" />
                                    <SmIconSidebarModalEditComponent @click="edit(offer)"
                                        v-if="permissionChecker('offers_edit')" />
                                    <SmIconDeleteComponent @click="destroy(offer.id)"
                                        v-if="permissionChecker('offers_delete')" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-6">
                <PaginationSMBox :pagination="pagination" :method="list" />
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <PaginationTextComponent :props="{ page: paginationPage }" />
                    <PaginationBox :pagination="pagination" :method="list" />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import LoadingComponent from "../components/LoadingComponent.vue";
import OfferCreateComponent from "./OfferCreateComponent.vue";
import alertService from "../../../services/alertService.js";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent.vue";
import PaginationBox from "../components/pagination/PaginationBox.vue";
import PaginationSMBox from "../components/pagination/PaginationSMBox.vue";
import appService from "../../../services/appService.js";
import statusEnum from "../../../enums/modules/statusEnum.js";
import dayEnum from "../../../enums/modules/dayEnum.js";
import TableLimitComponent from "../components/TableLimitComponent.vue";
import SmIconDeleteComponent from "../components/buttons/SmIconDeleteComponent.vue";
import SmIconSidebarModalEditComponent from "../components/buttons/SmIconSidebarModalEditComponent.vue";
import SmViewComponent from "../components/buttons/SmViewComponent.vue";
import SmSidebarModalEditComponent from "../components/buttons/SmSidebarModalEditComponent.vue";
import FilterComponent from "../components/buttons/collapse/FilterComponent.vue";
import ExportComponent from "../components/buttons/export/ExportComponent.vue";
import print from "vue3-print-nb";
import PrintComponent from "../components/buttons/export/PrintComponent.vue";
import ExcelComponent from "../components/buttons/export/ExcelComponent.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import SmIconViewComponent from "../components/buttons/SmIconViewComponent.vue";
import displayModeEnum from "../../../enums/modules/displayModeEnum.js";

export default {
    name: "OfferListComponent",
    components: {
        SmSidebarModalEditComponent,
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        OfferCreateComponent,
        LoadingComponent,
        SmIconDeleteComponent,
        SmViewComponent,
        SmIconSidebarModalEditComponent,
        ExportComponent,
        FilterComponent,
        PrintComponent,
        ExcelComponent,
        Datepicker,
        SmIconViewComponent,
    },
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
            printLoading: true,
            printObj: {
                id: "print",
                popTitle: this.$t("menu.offers"),
            },
            props: {
                form: {
                    name: "",
                    amount: "",
                    start_time: "",
                    day: null,
                    end_time: "",
                    status: statusEnum.ACTIVE,
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: "id",
                    order_type: "desc",
                    name: "",
                    amount: "",
                    start_time: "",
                    end_time: "",
                    day: null,
                    status: null,
                },
                startTime: '',
                endTime: '',
            },
            startTimeValue: null,
            endTimeValue: null
        };
    },
    mounted() {
        this.list();
    },
    computed: {
        offers: function () {
            return this.$store.getters["offer/lists"];
        },
        pagination: function () {
            return this.$store.getters["offer/pagination"];
        },
        paginationPage: function () {
            return this.$store.getters["offer/page"];
        },
        direction: function () {
            return this.$store.getters['frontendLanguage/show'].display_mode === displayModeEnum.RTL ? 'rtl' : 'ltr';
        },
    },
    methods: {
        permissionChecker(e) {
            return appService.permissionChecker(e);
        },
        floatNumber(e) {
            return appService.floatNumber(e);
        },
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        search: function () {
            this.list();
        },
        handleStartTime: function (e) {
            if (e) {
                this.props.search.start_time = e.hours + ':' + e.minutes + ':' + e.seconds;
            } else {
                this.props.search.start_time = '';
                this.startTimeValue = null;
            }
        },
        handleEndTime: function (e) {
            if (e) {
                this.props.search.end_time = e.hours + ':' + e.minutes + ':' + e.seconds;
            } else {
                this.props.search.end_time = '';
                this.endTimeValue = null;
            }
        },
        clear: function () {
            this.props.search.paginate = 1;
            this.props.search.page = 1;
            this.props.search.name = "";
            this.props.search.amount = "";
            this.props.search.status = null;
            this.props.search.day = null;
            this.props.search.start_time = "";
            this.props.search.end_time = "";
            this.startTimeValue = null
            this.endTimeValue = null
            this.list();
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch("offer/lists", this.props.search).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        edit: function (offer) {
            appService.sideDrawerShow();
            this.loading.isActive = true;

            this.$store
                .dispatch("offer/edit", offer.id)
                .then((res) => {
                    this.loading.isActive = false;
                    this.props.errors = {};
                    this.props.startTime = this.parseTime(offer.start_time);
                    this.props.endTime = this.parseTime(offer.end_time);
                    this.props.form = {
                        name: offer.name,
                        amount: offer.flat_amount,
                        day: offer.day,
                        start_time: offer.start_time,
                        end_time: offer.end_time,
                        status: offer.status,
                    };
                })
                .catch((err) => {
                    alertService.error(err.response.data.message);
                });
        },
        parseTime(time) {
            if (time === "") {
                return "";
            } else {
                const [hours, minutes, seconds] = time.split(':').map(Number);
                return { hours, minutes, seconds };
            }
        },
        destroy: function (id) {
            appService
                .destroyConfirmation()
                .then((res) => {
                    try {
                        this.loading.isActive = true;
                        this.$store
                            .dispatch("offer/destroy", { id: id, search: this.props.search })
                            .then((res) => {
                                this.loading.isActive = false;
                                alertService.successFlip(null, this.$t("menu.offers"));
                            })
                            .catch((err) => {
                                this.loading.isActive = false;
                                alertService.error(err.response.data.message);
                            });
                    } catch (err) {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.message);
                    }
                })
                .catch((err) => {
                    this.loading.isActive = false;
                });
        },

        xls: function () {
            this.loading.isActive = true;
            this.$store
                .dispatch("offer/export", this.props.search)
                .then((res) => {
                    this.loading.isActive = false;
                    const blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                    });
                    const link = document.createElement("a");
                    link.href = URL.createObjectURL(blob);
                    link.download = this.$t("menu.offers");
                    link.click();
                    URL.revokeObjectURL(link.href);
                })
                .catch((err) => {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                });
        },
    },
};
</script>
<style scoped>
@media print {
    .hidden-print {
        display: none !important;
    }
}
</style>