<template>
    <LoadingComponent :props="loading" />
    <section class="mb-12">
        <div class="container" v-if="featuredItems?.length > 0">
            <h2 class="text-2xl font-semibold capitalize mb-6">{{ $t('label.featured_items') }}</h2>
            <ItemComponent :items="featuredItems" :design="itemProps.design" />
        </div>
    </section>
</template>
<script>

import itemDesignEnum from "../../../enums/modules/itemDesignEnum.js";
import ItemComponent from "../components/ItemComponent.vue";
import LoadingComponent from "../components/LoadingComponent.vue";

export default {
    name: "FeaturedItemComponent",
    components: {
        ItemComponent,
        LoadingComponent
    },
    props: {
        items: Object,
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            itemProps: {
                design: itemDesignEnum.GRID,
            },
        };
    },
    mounted() {
        try {
            if (this.$store.getters['globalState/lists'].branch_id) {
                this.loading.isActive = true;
                this.$store.dispatch("frontendItem/featured", {
                    order_column: "id",
                    order_type: "desc",
                    branch_id: this.$store.getters['globalState/lists'].branch_id
                }).then(res => {
                    this.loading.isActive = false;
                }).catch((err) => {
                    this.loading.isActive = false;
                });
            }
            
        } catch (err) {
            this.loading.isActive = false;
        }
    },
    computed: {
        featuredItems: function () {
            return this.$store.getters["frontendItem/featured"];
        },
    },
    methods: {},
};
</script>