<template>
    <LoadingComponent :props="loading" />
    <SmModalCreateComponent :props="addButton" />

    <div id="modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("menu.pages") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label for="title" class="db-field-title required">{{ $t("label.title") }}</label>
                            <input v-model="props.form.title" v-bind:class="errors.title ? 'invalid' : ''" type="text"
                                id="title" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.title">{{
                                errors.title[0]
                            }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="active">{{ $t("label.status") }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.ACTIVE" v-model="props.form.status" id="active"
                                            type="radio" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="active" class="db-field-label">{{ $t("label.active") }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.INACTIVE" v-model="props.form.status" type="radio"
                                            id="inactive" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="inactive" class="db-field-label">{{ $t("label.inactive") }}</label>
                                </div>
                            </div>
                            <small class="db-field-alert" v-if="errors.status">{{ errors.status[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="template_id" class="db-field-title">{{
                                $t("label.template_id")
                            }}</label>

                            <vue-select class="db-field-control f-b-custom-select" id="template_id" v-bind:class="errors.template_id ? 'invalid' : ''
                                " v-model="props.form.template_id" :options="menuTemplates" label-by="name"
                                value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true" placeholder="--"
                                search-placeholder="--" />
                            <small class="db-field-alert" v-if="errors.template_id">{{ errors.template_id[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="image" class="db-field-title">{{
                                $t("label.image")
                            }}</label>
                            <input @change="changeImage" v-bind:class="errors.image ? 'invalid' : ''" id="image" type="file"
                                class="db-field-control" ref="imageProperty" accept="image/png, image/jpeg, image/jpg" />
                            <small class="db-field-alert" v-if="errors.image">{{
                                errors.image[0]
                            }}</small>
                        </div>

                        <div class="form-col-12">
                            <label for="description" class="db-field-title required">{{
                                $t("label.description")
                            }}</label>
                            <quill-editor v-model:value="props.form.description" id="description"
                                class="!h-40 textarea-border-radius ql-container ql-snow" />
                            <small class="db-field-alert" v-if="errors.description">{{ errors.description[0] }}</small>
                        </div>

                        <div class="form-col-12">
                            <div class="modal-btns">
                                <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                    <i class="lab lab-close"></i>
                                    <span>{{ $t("button.close") }}</span>
                                </button>

                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-save"></i>
                                    <span>{{ $t("button.save") }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import SmModalCreateComponent from "../../components/buttons/SmModalCreateComponent.vue";
import LoadingComponent from "../../components/LoadingComponent.vue";
import statusEnum from "../../../../enums/modules/statusEnum.js";
import alertService from "../../../../services/alertService.js";
import appService from "../../../../services/appService.js";
import { quillEditor } from 'vue3-quill'

export default {
    name: "PageCreateComponent",
    components: { SmModalCreateComponent, LoadingComponent, quillEditor },
    props: ["props"],
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
            image: "",
            errors: {},
        };
    },
    mounted() {
        this.$store.dispatch("menuTemplate/lists");
    },
    computed: {
        addButton: function () {
            return { title: this.$t('button.add_page') };
        },
        menuTemplates: function () {
            return this.$store.getters["menuTemplate/lists"];
        },
    },
    methods: {
        changeImage: function (e) {
            this.image = e.target.files[0];
        },
        reset: function () {
            appService.modalHide();
            this.$store.dispatch("page/reset").then().catch();
            this.errors = {};
            this.$props.props.form = {
                title: "",
                description: "",
                menu_section_id: null,
                template_id: null,
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
                fd.append("title", this.props.form.title);
                fd.append("status", this.props.form.status);
                fd.append("description", this.props.form.description);
                fd.append(
                    "menu_section_id",
                    this.props.form.menu_section_id === null
                        ? ""
                        : this.props.form.menu_section_id
                );
                fd.append(
                    "template_id",
                    this.props.form.template_id === null
                        ? 0
                        : this.props.form.template_id
                );
                if (this.image) {
                    fd.append("image", this.image);
                }

                const tempId = this.$store.getters["page/temp"].temp_id;
                this.loading.isActive = true;
                this.$store
                    .dispatch("page/save", {
                        form: fd,
                        search: this.props.search,
                    })
                    .then((res) => {
                        appService.modalHide();
                        this.loading.isActive = false;
                        alertService.successFlip(
                            tempId === null ? 0 : 1,
                            this.$t("menu.pages")
                        );
                        this.props.form = {
                            title: "",
                            description: "",
                            menu_section_id: null,
                            template_id: null,
                            status: statusEnum.ACTIVE,
                        };
                        this.image = "";
                        this.errors = {};
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
