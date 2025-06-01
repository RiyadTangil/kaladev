<template>
    <LoadingComponent :props="loading" />
    <section class="mb-16 mt-8">
        <div class="container">
            <div v-if="categories.length > 0" class="swiper mb-12 menu-swiper">
                <CategoryComponent :categories="categories" :design="categoryProps.design" />
            </div>

            <div v-if="Object.keys(category).length > 0"
                class="flex gap-4 flex-col sm:flex-row items-center justify-between mb-6">
                <h2 class="capitalize text-[26px] leading-[40px] font-semibold text-center sm:text-left text-primary">{{
                    category.name
                }}</h2>
                <div class="flex items-center gap-3">
                    <button type="button" class="lab lab-row-vertical lab-font-size-20 text-xl"
                        v-on:click="itemProps.design = itemDesignEnum.LIST"
                        :class="itemProps.design === itemDesignEnum.LIST ? 'text-heading' : 'text-[#A0A3BD]'"></button>
                    <button type="button" class="lab lab-element-3 lab-font-size-20 text-xl"
                        v-on:click="itemProps.design = itemDesignEnum.GRID"
                        :class="itemProps.design === itemDesignEnum.GRID ? 'text-heading' : 'text-[#A0A3BD]'"></button>
                </div>
            </div>

            <ItemComponent :items="items.items" :design="itemProps.design" />
        </div>
    </section>
</template>

<script>

import statusEnum from "../../../enums/modules/statusEnum.js";
import categoryDesignEnum from "../../../enums/modules/categoryDesignEnum.js";
import CategoryComponent from "../components/CategoryComponent.vue";
import ItemComponent from "../components/ItemComponent.vue";
import itemDesignEnum from "../../../enums/modules/itemDesignEnum.js";
import itemTypeEnum from "../../../enums/modules/itemTypeEnum.js";
import LoadingComponent from "../components/LoadingComponent.vue";

export default {
    name: "MenuComponent",
    components: { CategoryComponent, ItemComponent, LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false
            },
            itemTypeEnum: itemTypeEnum,
            itemDesignEnum: itemDesignEnum,
            category: {},
            items: {},
            categoryProps: {
                search: {
                    paginate: 0,
                    order_column: 'sort',
                    order_type: 'asc',
                    status: statusEnum.ACTIVE
                },
                design: categoryDesignEnum.SECOND
            },
            itemProps: {
                design: itemDesignEnum.LIST,
            }
        }
    },
    computed: {
        categories: function () {
            return this.$store.getters['frontendItemCategory/lists'];
        },
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendItemCategory/lists", this.categoryProps.search).then((res) => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
        this.categoryShow();
    },
    methods: {
        itemTypeSet: function (e) {
            this.itemProps.type = e;
        },
        itemTypeReset: function () {
            this.itemProps.type = null;
        },
        categoryShow: function () {
            if (typeof this.$route.query.s !== "undefined" && this.$route.query.s !== "") {
                this.loading.isActive = true;
                this.$store.dispatch("frontendItemCategory/show", {
                    slug: this.$route.query.s,
                    branch_id: this.$store.getters['globalState/lists'].branch_id,
                    vuex: false
                }).then((res) => {
                    this.category = res.data.data;
                    this.items = res.data.data;
                    this.loading.isActive = false;
                }).catch((err) => {
                    this.loading.isActive = false;
                });
            }
        }
    },
    watch: {
        $route() {
            this.categoryShow();
        }
    }
}
</script>