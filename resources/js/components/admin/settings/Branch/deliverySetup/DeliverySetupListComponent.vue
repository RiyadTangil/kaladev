<template>
    <LoadingComponent :props="loading" />

    <div class="db-card db-tab-div active">
        <div class="db-card-header border-none">
            <h3 class="db-card-title">{{ $t("menu.delivery_setup") }}</h3>
            <div class="db-card-filter">
                <TableLimitComponent :method="list" :search="props.search" :page="paginationPage" />
                <DeliverySetupCreateComponent :props="props" />
            </div>
        </div>

        <div class="db-table-responsive">
            <table class="db-table stripe">
                <thead class="db-table-head">
                    <tr class="db-table-head-tr">
                        <th class="db-table-head-th">{{ $t("label.kilometer_range") }}</th>
                        <th class="db-table-head-th">
                            {{ $t("label.minimum_order_amount") }}
                        </th>
                        <th class="db-table-head-th">
                            {{ $t("label.delivery_charge") }}
                        </th>
                        <th class="db-table-head-th">
                            {{ $t("label.action") }}
                        </th>
                    </tr>
                </thead>
                <tbody class="db-table-body" v-if="deliverySetups.length > 0">
                    <tr class="db-table-body-tr" v-for="deliverySetup in deliverySetups" :key="deliverySetup">
                        <td class="db-table-body-td">
                            {{ deliverySetup.kilometer_range }}
                        </td>
                        <td class="db-table-body-td">
                            {{ deliverySetup.flat_minimum_order_amount }}
                        </td>
                        <td class="db-table-body-td">
                            {{ deliverySetup.flat_delivery_charge }}
                        </td>

                        <td class="db-table-body-td">
                            <div class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
                                <SmModalEditComponent @click="edit(deliverySetup)" />
                                <SmDeleteComponent @click="destroy(deliverySetup.id)" />
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
</template>

<script>
import LoadingComponent from "../../../../frontend/components/LoadingComponent.vue";
import DeliverySetupCreateComponent from "./DeliverySetupCreateComponent.vue";
import alertService from "../../../../../services/alertService.js";
import PaginationTextComponent from "../../../components/pagination/PaginationTextComponent.vue";
import PaginationBox from "../../../components/pagination/PaginationBox.vue";
import PaginationSMBox from "../../../components/pagination/PaginationSMBox.vue";
import appService from "../../../../../services/appService.js";
import TableLimitComponent from "../../../components/TableLimitComponent.vue";
import SmModalEditComponent from "../../../components/buttons/SmModalEditComponent.vue";
import SmDeleteComponent from "../../../components/buttons/SmDeleteComponent.vue";

export default {
    name: "DeliverySetupListComponent",
    components: {
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        DeliverySetupCreateComponent,
        LoadingComponent,
        SmDeleteComponent,
        SmModalEditComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            props: {
                branch_id: null,
                form: {
                    kilometer_range: "",
                    minimum_order_amount: "",
                    delivery_charge: "",
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: "id",
                    order_type: "desc",
                },
            },
        };
    },
    mounted() {
        this.props.branch_id = this.$route.params.id;
        this.props.form.branch_id = this.$route.params.id;
        this.list();
    },
    computed: {
        deliverySetups: function () {
            return this.$store.getters["deliverySetup/lists"];
        },
        pagination: function () {
            return this.$store.getters["deliverySetup/pagination"];
        },
        paginationPage: function () {
            return this.$store.getters["deliverySetup/page"];
        },
    },
    methods: {
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch("deliverySetup/lists", this.props).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        edit: function (deliverySetup) {
            appService.modalShow();
            this.loading.isActive = true;
            this.$store.dispatch("deliverySetup/edit", deliverySetup.id);
            this.props.form = {
                kilometer_range: deliverySetup.kilometer_range,
                minimum_order_amount: deliverySetup.flat_minimum_order_amount,
                delivery_charge: deliverySetup.flat_delivery_charge
            };
            this.loading.isActive = false;
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch("deliverySetup/destroy", {
                        branch_id: this.$route.params.id,
                        id: id,
                        search: this.props.search,
                    }).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(
                            null,
                            this.$t("menu.delivery_setup")
                        );
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.message);
                    });
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
    },
};
</script>