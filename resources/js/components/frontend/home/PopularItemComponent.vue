<template>
    <LoadingComponent :props="loading" />
    <section class="mb-16">
        <div class="container" v-if="popularItems?.length > 0">
            <div class="flex items-center justify-between gap-2 mb-6">
                <h2 class="text-2xl font-semibold capitalize">{{ $t('label.most_popular_items') }}</h2>
            </div>
            <ItemComponent :items="popularItems" :design="itemProps.design" />
        </div>
    </section>
</template>
<script>

import alertService from "../../../services/alertService.js";
import itemDesignEnum from "../../../enums/modules/itemDesignEnum.js";
import ItemComponent from "../components/ItemComponent.vue";
import LoadingComponent from "../components/LoadingComponent.vue";

export default {
    name: "PopularItemComponent",
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
                isActive: false
            },
            itemProps: {
                design: itemDesignEnum.LIST,
            },
        };
    },
    mounted() {
        try {
            if (this.$store.getters['globalState/lists'].branch_id) {
                this.loading.isActive = true;
                this.$store.dispatch("frontendItem/popular", {
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
        popularItems: function () {
            return this.$store.getters["frontendItem/popular"];
        }
    }
};
</script>