<template>
    <LoadingComponent :props="loading" />

    <div class="db-card">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ $t("label.page") }}</h3>
        </div>
        <div class="db-card-body">
            <div class="row">
                <div class="col-12 sm:col-5" v-if="page.image">
                    <img class="db-image" alt="page" :src="page.image" />
                </div>
                <div class="col-12 md:pl-8" :class="page.image ? 'sm:col-7' : 'sm:col-12'">
                    <h3 class="text-lg font-medium capitalize mb-2 text-paragraph">
                        {{ page.title }}
                    </h3>
                    <label class="db-badge mb-3" :class="statusClass(page.status)">
                        {{ enums.statusEnumArray[page.status] }}
                    </label>
                    <p v-html="page.description" class="db-light-text">

                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent.vue";
import statusEnum from "../../../../enums/modules/statusEnum.js";
import alertService from "../../../../services/alertService.js";
import appService from "../../../../services/appService.js";

export default {
    name: "PageShowComponent",
    components: {
        LoadingComponent,
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },
        };
    },
    computed: {
        page: function () {
            return this.$store.getters["page/show"];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store
            .dispatch("page/show", this.$route.params.id)
            .then((res) => {
                this.loading.isActive = false;
            })
            .catch((error) => {
                this.loading.isActive = false;
            });
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        },
    },
};
</script>
