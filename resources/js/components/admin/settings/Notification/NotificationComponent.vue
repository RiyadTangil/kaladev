<template>
    <LoadingComponent :props="loading" />

    <div id="company" class="db-card db-tab-div active">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ $t("menu.notification") }}</h3>
        </div>
        <div class="db-card-body">
            <form @submit.prevent="save">
                <div class="form-row">
                    <div class="form-col-12 sm:form-col-6">
                        <label for="notification_fcm_topic" class="db-field-title required">
                            {{ $t("label.notification_fcm_public_vapid_key") }}
                        </label>
                        <input v-model="form.notification_fcm_public_vapid_key"
                            v-bind:class="errors.notification_fcm_public_vapid_key ? 'invalid' : ''" type="text"
                            id="notification_fcm_topic" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.notification_fcm_public_vapid_key">{{
                            errors.notification_fcm_public_vapid_key[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="notification_fcm_api_key" class="db-field-title required">
                            {{ $t("label.notification_fcm_api_key") }}
                        </label>
                        <input v-model="form.notification_fcm_api_key"
                            v-bind:class="errors.notification_fcm_api_key ? 'invalid' : ''" type="text"
                            id="notification_fcm_api_key" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.notification_fcm_api_key">{{
                            errors.notification_fcm_api_key[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="notification_fcm_auth_domain" class="db-field-title required">
                            {{ $t("label.notification_fcm_auth_domain") }}
                        </label>
                        <input v-model="form.notification_fcm_auth_domain"
                            v-bind:class="errors.notification_fcm_auth_domain ? 'invalid' : ''" type="text"
                            id="notification_fcm_auth_domain" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.notification_fcm_auth_domain">{{
                            errors.notification_fcm_auth_domain[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="notification_fcm_project_id" class="db-field-title required">
                            {{ $t("label.notification_fcm_project_id") }}
                        </label>
                        <input v-model="form.notification_fcm_project_id"
                            v-bind:class="errors.notification_fcm_project_id ? 'invalid' : ''" type="text"
                            id="notification_fcm_project_id" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.notification_fcm_project_id">{{
                            errors.notification_fcm_project_id[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="notification_fcm_storage_bucket" class="db-field-title required">
                            {{ $t("label.notification_fcm_storage_bucket") }}
                        </label>
                        <input v-model="form.notification_fcm_storage_bucket"
                            v-bind:class="errors.notification_fcm_storage_bucket ? 'invalid' : ''" type="text"
                            id="notification_fcm_storage_bucket" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.notification_fcm_storage_bucket">{{
                            errors.notification_fcm_storage_bucket[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="notification_fcm_messaging_sender_id" class="db-field-title required">
                            {{ $t("label.notification_fcm_messaging_sender_id") }}
                        </label>
                        <input v-model="form.notification_fcm_messaging_sender_id"
                            v-bind:class="errors.notification_fcm_messaging_sender_id ? 'invalid' : ''" type="text"
                            id="notification_fcm_messaging_sender_id" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.notification_fcm_messaging_sender_id">{{
                            errors.notification_fcm_messaging_sender_id[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="notification_fcm_app_id" class="db-field-title required">
                            {{ $t("label.notification_fcm_app_id") }}
                        </label>
                        <input v-model="form.notification_fcm_app_id"
                            v-bind:class="errors.notification_fcm_app_id ? 'invalid' : ''" type="text"
                            id="notification_fcm_app_id" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.notification_fcm_app_id">{{
                            errors.notification_fcm_app_id[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="notification_fcm_measurement_id" class="db-field-title required">
                            {{ $t("label.notification_fcm_measurement_id") }}
                        </label>
                        <input v-model="form.notification_fcm_measurement_id"
                            v-bind:class="errors.notification_fcm_measurement_id ? 'invalid' : ''" type="text"
                            id="notification_fcm_measurement_id" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.notification_fcm_measurement_id">{{
                            errors.notification_fcm_measurement_id[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="notification_fcm_json_file" class="db-field-title required">
                            {{ $t("label.file") }} ({{ $t("label.json") }})
                        </label>
                        <input @change="changeFile" v-bind:class="errors.notification_fcm_json_file ? 'invalid' : ''"
                            id="notification_fcm_json_file" type="file" class="db-field-control" ref="fileProperty"
                            accept="application/json" />
                        <small class="db-field-alert" v-if="errors.notification_fcm_json_file">{{
                            errors.notification_fcm_json_file[0]
                        }}</small>
                    </div>

                    <div class="form-col-12">
                        <button type="submit" class="db-btn text-white bg-primary">
                            <i class="lab lab-save"></i>
                            <span>{{ $t("button.save") }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent.vue";
import alertService from "../../../../services/alertService.js";

export default {
    name: "NotificationComponent",
    components: { LoadingComponent, alertService },
    data() {
        return {
            loading: {
                isActive: false,
            },
            form: {
                notification_fcm_api_key: "",
                notification_fcm_public_vapid_key: "",
                notification_fcm_auth_domain: "",
                notification_fcm_project_id: "",
                notification_fcm_storage_bucket: "",
                notification_fcm_messaging_sender_id: "",
                notification_fcm_app_id: "",
                notification_fcm_measurement_id: "",
            },
            errors: {},
            notification_fcm_json_file: ""
        };
    },
    mounted() {
        try {
            this.loading.isActive = true;
            this.$store.dispatch("notification/lists").then((res) => {
                this.form = {
                    notification_fcm_api_key: res.data.data.notification_fcm_api_key,
                    notification_fcm_public_vapid_key: res.data.data.notification_fcm_public_vapid_key,
                    notification_fcm_auth_domain: res.data.data.notification_fcm_auth_domain,
                    notification_fcm_project_id: res.data.data.notification_fcm_project_id,
                    notification_fcm_storage_bucket: res.data.data.notification_fcm_storage_bucket,
                    notification_fcm_messaging_sender_id: res.data.data.notification_fcm_messaging_sender_id,
                    notification_fcm_app_id: res.data.data.notification_fcm_app_id,
                    notification_fcm_measurement_id: res.data.data.notification_fcm_measurement_id,
                };
                this.loading.isActive = false;
            })
                .catch((err) => {
                    this.loading.isActive = false;
                });
        } catch (err) {
            this.loading.isActive = false;
            alertService.error(err);
        }
    },
    methods: {
        changeFile: function (e) {
            this.notification_fcm_json_file = e.target.files[0];
        },
        save: function () {
            try {
                const fd = new FormData();
                fd.append("notification_fcm_api_key", this.form.notification_fcm_api_key);
                fd.append("notification_fcm_public_vapid_key", this.form.notification_fcm_public_vapid_key);
                fd.append("notification_fcm_auth_domain", this.form.notification_fcm_auth_domain);
                fd.append("notification_fcm_project_id", this.form.notification_fcm_project_id);
                fd.append("notification_fcm_storage_bucket", this.form.notification_fcm_storage_bucket);
                fd.append("notification_fcm_messaging_sender_id", this.form.notification_fcm_messaging_sender_id);
                fd.append("notification_fcm_app_id", this.form.notification_fcm_app_id);
                fd.append("notification_fcm_measurement_id", this.form.notification_fcm_measurement_id);
                if (this.notification_fcm_json_file) {
                    fd.append("notification_fcm_json_file", this.notification_fcm_json_file);
                }
                this.loading.isActive = true;
                this.$store
                    .dispatch("notification/save", { form: fd })
                    .then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(
                            res.config.method === "put" ?? 0,
                            this.$t("menu.notification")
                        );
                        navigator.serviceWorker.getRegistrations().then(function (registrations) {
                            for (let registration of registrations) {
                                registration.unregister();
                            }
                        });
                        this.errors = {};
                        this.notification_fcm_json_file = "";
                        this.$refs.fileProperty.value = null;
                    }).catch((err) => {
                        this.loading.isActive = false;
                        if (err.response.data.status !== "undefined" && err.response.data.status === false) {
                            alertService.error(err.response.data.message)
                        } else {
                            this.errors = err.response.data.errors;
                        }
                    });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    },
};
</script>