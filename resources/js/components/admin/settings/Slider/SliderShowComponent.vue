<template>
    <LoadingComponent :props="loading" />

    <div class="db-card">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ $t("label.slider") }}</h3>
        </div>
        <div class="db-card-body">
            <div class="row">
                <div class="col-12 sm:col-5">
                    <img class="db-image" alt="slider" :src="slider.image" />
                </div>
                <div class="col-12 sm:col-7 md:pl-8">
                    <h3 class="text-lg font-medium capitalize mb-2 text-paragraph">
                        {{ slider.title }}
                    </h3>
                    <label class="db-badge mb-3" :class="statusClass(slider.status)">
                        {{ enums.statusEnumArray[slider.status] }}
                    </label>
                    <p class="db-light-text">
                        {{ slider.description }}
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
    name: "SliderShowComponent",
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
        slider: function () {
            return this.$store.getters["slider/show"];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store
            .dispatch("slider/show", this.$route.params.id)
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
