<template>
    <LoadingComponent :props="loading" />
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">{{ $t('menu.items') }}</h3>
                <div class="db-card-filter">
                    <TableLimitComponent :method="list" :search="props.search" :page="paginationPage" />
                    <FilterComponent />

                    <button class="db-btn h-[37px] text-white bg-primary" @click="copyNow">
                        <i class="lab lab-copy-items"></i>
                        <span>{{ $t('button.copy_now') }}</span>
                    </button>
                </div>
            </div>

            <div class="table-filter-div">
                <form class="p-4 sm:p-5 mb-5" @submit.prevent="search">
                    <div class="row">

                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchIsFeatured" class="db-field-title required ">{{
                                $t("label.branch")
                            }}</label>
                            <vue-select class="db-field-control f-b-custom-select" id="searchIsFeatured"
                                v-model="props.search.branch_id" :options="branches" label-by="name" value-by="id"
                                :closeOnSelect="true" :searchable="true" :clearOnClose="true" placeholder="--"
                                search-placeholder="--" />
                        </div>

                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="name" class="db-field-title after:hidden">{{
                                $t("label.name")
                            }}</label>
                            <input id="name" v-model="props.search.name" type="text" class="db-field-control" />
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="price" class="db-field-title after:hidden">{{
                                $t("label.price")
                            }}</label>
                            <input id="price" v-on:keypress="numberOnly($event)" v-model="props.search.price" type="text"
                                class="db-field-control" />
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="item_category_id" class="db-field-title">{{
                                $t("label.category")
                            }}</label>

                            <vue-select class="db-field-control f-b-custom-select" id="item_category_id"
                                v-model="props.search.item_category_id" :options="itemCategories" label-by="name"
                                value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true" placeholder="--"
                                search-placeholder="--" />
                        </div>

                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="tax_id" class="db-field-title">{{
                                $t("label.tax")
                            }}</label>

                            <vue-select class="db-field-control f-b-custom-select" id="tax_id" v-model="props.search.tax_id"
                                :options="taxes" label-by="name" value-by="id" :closeOnSelect="true" :searchable="true"
                                :clearOnClose="true" placeholder="--" search-placeholder="--" />
                        </div>

                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchIsFeatured" class="db-field-title after:hidden">{{
                                $t("label.is_featured")
                            }}</label>
                            <vue-select class="db-field-control f-b-custom-select" id="searchIsFeatured"
                                v-model="props.search.is_featured" :options="[
                                    { id: enums.askEnum.YES, name: $t('label.yes') },
                                    { id: enums.askEnum.NO, name: $t('label.no') },
                                ]" label-by="name" value-by="id" :closeOnSelect="true" :searchable="true"
                                :clearOnClose="true" placeholder="--" search-placeholder="--" />
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
                                <button type="button" class="db-btn py-2 text-white bg-gray-600" @click="clear">
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
                            <th class="db-table-head-th">
                                <div class="custom-checkbox float-left mr-2">
                                    <input v-model="isCheckedAll" type="checkbox" class="custom-checkbox-field"
                                        @change="checkAllItems()" :checked="isCheckedAll" id="checkAll">
                                    <i class="fa-solid fa-check custom-checkbox-icon"></i>
                                </div>
                                <label for="checkAll"> {{ $t('label.all') }} </label>
                            </th>
                            <th class="db-table-head-th">
                                {{ $t('label.name') }}
                            </th>
                            <th class="db-table-head-th">
                                {{ $t('label.category') }}
                            </th>
                            <th class="db-table-head-th">
                                {{ $t('label.price') }}
                            </th>
                            <th class="db-table-head-th">
                                {{ $t('label.status') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="db-table-body" v-if="items.length > 0">
                        <tr class="db-table-body-tr" v-for="item in items" :key="item">
                            <td class="db-table-body-td">
                                <div class="custom-checkbox">
                                    <input type="checkbox" class="custom-checkbox-field" @change="addItems(item.id)"
                                        :checked="this.selectedItems.indexOf(item.id) !== -1">
                                    <i class="fa-solid fa-check custom-checkbox-icon"></i>
                                </div>

                            </td>
                            <td class="db-table-body-td">
                                {{ textShortener(item.name, 40) }}
                            </td>
                            <td class="db-table-body-td">{{ item.category_name }}</td>
                            <td class="db-table-body-td">{{ item.flat_price }}</td>
                            <td class="db-table-body-td">
                                <span :class="statusClass(item.status)">
                                    {{ enums.statusEnumArray[item.status] }}
                                </span>
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
import statusEnum from "../../../enums/modules/statusEnum.js";
import askEnum from "../../../enums/modules/askEnum.js";
import itemTypeEnum from "../../../enums/modules/itemTypeEnum.js";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent.vue";
import PaginationBox from "../components/pagination/PaginationBox.vue";
import PaginationSMBox from "../components/pagination/PaginationSMBox.vue";
import appService from "../../../services/appService.js";
import TableLimitComponent from "../components/TableLimitComponent.vue";
import FilterComponent from "../components/buttons/collapse/FilterComponent.vue";
import displayModeEnum from "../../../enums/modules/displayModeEnum.js";
import alertService from "../../../services/alertService.js";

export default {
    name: "CopyItemListComponent",
    components: {
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        LoadingComponent,
        FilterComponent,
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            enums: {
                statusEnum: statusEnum,
                itemTypeEnum: itemTypeEnum,
                askEnum: askEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive")
                }
            },
            taxProps: {
                search: {
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'asc'
                }
            },
            categoryProps: {
                search: {
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'asc'
                }
            },
            props: {
                form: {
                    name: "",
                    price: "",
                    description: "",
                    caution: "",
                    is_featured: askEnum.YES,
                    item_category_id: null,
                    tax_id: null,
                    status: statusEnum.ACTIVE,
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'id',
                    order_type: 'desc',
                    name: "",
                    price: "",
                    item_category_id: null,
                    status: null,
                    tax_id: null,
                    is_featured: null,
                    branch_id: null
                }
            },
            selectedItems: [],
            deSelectedItems: [],
            isCheckedAll: false
        }
    },
    mounted() {
        this.clear();
        this.loadBranches();

        this.loading.isActive = true;
        this.props.search.page = 1;
        this.$store.dispatch('itemCategory/lists', this.categoryProps.search).then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
        this.$store.dispatch('tax/lists', this.taxProps.search).then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });

    },
    computed: {
        items: function () {
            return this.$store.getters['item/lists'];
        },
        pagination: function () {
            return this.$store.getters['item/pagination'];
        },
        paginationPage: function () {
            return this.$store.getters['item/page'];
        },
        itemCategories: function () {
            return this.$store.getters["itemCategory/lists"];
        },
        taxes: function () {
            return this.$store.getters['tax/lists'];
        },
        direction: function () {
            return this.$store.getters['frontendLanguage/show'].display_mode === displayModeEnum.RTL ? 'rtl' : 'ltr';
        },
        branches: function () {
            return this.$store.getters["branch/lists"];
        },
        branch: function () {
            return this.$store.getters['backendGlobalState/branchShow'];
        },

    },
    beforeUnmount: function () {
        this.clear();
    },
    methods: {
        permissionChecker(e) {
            return appService.permissionChecker(e);
        },
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        numberOnly: function (e) {
            return appService.floatNumber(e);
        },
        search: function () {
            this.list();
        },
        clear: function () {
            this.props.search.paginate = 1;
            this.props.search.page = 1;
            this.props.search.name = "";
            this.props.search.price = "";
            this.props.search.item_category_id = null;
            this.props.search.status = null;
            this.props.search.tax_id = null;
            this.props.search.is_featured = null;
            this.props.search.branch_id = null;
            this.selectedItems = [];
            this.deSelectedItems = [];

            this.$store.dispatch('item/resetItemList');
        },
        list: function (page = 1) {

            if (!this.props.search.branch_id) {
                alertService.warning(this.$t('message.branch_field_is_required'));
                return false;
            }
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch('item/copyLists', this.props.search).then(res => {
                this.loading.isActive = false;

                if (this.isCheckedAll) {
                    this.items.map((item) => {
                        if (this.deSelectedItems.indexOf(item.id) == -1 && this.selectedItems.indexOf(item.id) == -1) {
                            this.selectedItems.push(item.id);
                        } else {
                            return 0;
                        }
                    })
                }

            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        addItems: function (id) {
            const i = this.selectedItems.indexOf(id);
            if (i !== -1) {
                this.selectedItems.splice(i, 1);
                if (this.isCheckedAll && this.deSelectedItems.indexOf(id) == -1) {
                    this.deSelectedItems.push(id);
                }
            } else {
                this.selectedItems.push(id);
                if (this.isCheckedAll && this.deSelectedItems.indexOf(id) !== -1) {
                    this.deSelectedItems.splice(i, 1);
                }
            }
        },
        loadBranches: function () {
            this.$store.dispatch("branch/lists", { paginate: 0, except: this.branch.id });
        },
        copyNow: function () {
            if (this.selectedItems.length < 1) {
                alertService.warning(this.$t('message.please_select_items'));
                return false;
            }
            const info = {
                branch_from: this.props.search.branch_id,
                branch_to: this.branch.id,
                items: this.selectedItems,
                is_checked_all: this.isCheckedAll,
                excepts: this.deSelectedItems
            }

            this.loading.isActive = true;
            this.$store.dispatch('item/copyItems', info)
                .then((res) => {
                    this.loading.isActive = false;
                    alertService.success(this.$t("message.items_copied_successfully"));
                    this.clear();
                })
                .catch((err) => {
                    this.loading.isActive = false;
                    if (err.response && err.response.data && err.response.data.message) {
                        alertService.error(err.response.data.message);
                    }
                })

        },
        checkAllItems: function () {
            if (this.isCheckedAll) {
                this.items.map((item) => {
                    if (this.deSelectedItems.indexOf(item.id) == -1 && this.selectedItems.indexOf(item.id) == -1) {
                        this.selectedItems.push(item.id);
                    } else {
                        return 0;
                    }
                })
            } else {
                this.selectedItems = [];
                this.deSelectedItems = [];
            }
        }
    },
    watch: {
        branch(newVal, oldVal) {
            if (newVal) this.loadBranches();
        }
    }
}
</script>
