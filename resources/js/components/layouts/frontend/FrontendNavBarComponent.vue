<template>
    <LoadingComponent :props="loading" />
    <header class="shadow-xs bg-white ff-header" ref="ffHeader">
        <div class="container flex flex-col lg:flex-row items-center justify-between">
            <div class="w-full flex items-center justify-between gap-5 xl:gap-8 lg:justify-start lg:w-fit">
                <router-link :to="{ name: 'frontend.home' }">
                    <img class="w-32" :src="setting.theme_frontend_logo" alt="frontend-logo">
                </router-link>

                <button @click="locationModalShow()"
                    class="bg-[#EFF0F6] flex-auto flex items-center gap-2 max-w-[150px] h-8 rounded-full px-3 bg-mate ">
                    <i class="lab lab-location text-sm text-primary"></i>
                    <span class="text-sm w-full mt-[1px] whitespace-nowrap overflow-hidden text-ellipsis !text-black">{{
                        branch.name }}</span>
                </button>


            </div>
            <nav class="items-center justify-center gap-6 hidden lg:flex">
                <router-link :to="{ name: 'frontend.home' }"
                    :class="checkIsPathAndRoutePathSame('/home') ? 'text-primary' : ''"
                    class="capitalize text-sm font-medium text-heading">
                    {{ $t('menu.home') }}
                </router-link>
                <router-link :to="{ name: 'frontend.menu', query: { s: categoryProps.slug } }"
                    :class="checkIsPathAndRoutePathSame('/menu') ? 'text-primary' : ''"
                    class="capitalize text-sm font-medium text-heading">
                    {{ $t('label.menu') }}
                </router-link>
                <router-link :to="{ name: 'frontend.offers' }"
                    :class="checkIsPathAndRoutePathSame('/offers') ? 'text-primary' : ''"
                    class="capitalize text-sm font-medium text-heading">
                    {{ $t('label.offers') }}
                </router-link>
            </nav>

            <div class="flex flex-col items-center justify-end gap-3 w-full mt-4 lg:flex-row lg:w-fit lg:mt-0">
                <form @submit.prevent="search"
                    class="header-search-group group flex items-center justify-center border border-solid gap-2 px-2 w-full lg:w-52 h-8 rounded-3xl transition border-[#EFF0F6] bg-[#EFF0F6] focus-within:bg-white focus-within:border-primary">
                    <button type="submit" class="header-search-submit">
                        <i class="lab lab-search-normal"></i>
                    </button>
                    <input type="search" v-model="searchItem" :placeholder="$t('button.search')"
                        class="header-search-field w-full h-full text-xs appearance-none placeholder:font-normal placeholder:text-paragraph text-heading">
                    <button type="button" @click.prevent="searchReset"
                        class="header-search-button transition invisible group-focus-within:visible">
                        <i class="lab lab-close-circle-line lab-font-size-16 lab-font-weight-600 text-red-500"></i>
                    </button>
                </form>
                <div v-if="setting.site_language_switch === enums.activityEnum.ENABLE"
                    class="hidden lg:block relative dropdown-group w-full sm:w-fit">
                    <button
                        class="flex items-center justify-center gap-1.5 w-fit rounded-3xl capitalize text-sm font-medium h-8 px-3 border transition text-heading bg-white border-gray-200 dropdown-btn">
                        <img :src="language.image" alt="flag" class="w-4 h-4 rounded-full">
                        <span class="whitespace-nowrap">{{ language.name }}</span>
                    </button>
                    <ul v-if="languages.length > 0"
                        class="p-2 min-w-[180px] rounded-lg shadow-xl absolute top-14 ltr:right-0 rtl:left-0 z-10 border border-gray-200 bg-white hidden dropdown-list">
                        <li @click="changeLanguage(language.id, language.code)" v-for="language in languages"
                            class="flex items-center gap-2 py-1.5 px-2.5 rounded-md cursor-pointer hover:bg-gray-100">
                            <img :src="language.image" alt="flag" class="w-4 h-4 rounded-full">
                            <span class="text-heading capitalize text-sm">{{ language.name }}</span>
                        </li>
                    </ul>
                </div>
                <button
                    class="webcart hidden lg:flex items-center justify-center gap-1.5 w-fit rounded-3xl capitalize text-sm font-medium h-8 px-3 transition text-white bg-heading">
                    <i class="lab lab-bag-2 lab-font-size-17"></i>
                    <span class="whitespace-nowrap">{{
                        currencyFormat(subtotal, setting.site_digit_after_decimal_point,
                            setting.site_default_currency_symbol, setting.site_currency_position)
                    }}</span>
                </button>
                <router-link v-if="!logged"
                    class="hidden lg:flex items-center justify-center gap-1 w-fit rounded-3xl capitalize text-sm font-medium h-8 px-3 transition text-white bg-primary"
                    :to="{ name: 'auth.login' }">
                    <i class="lab lab-profile-circle"></i>
                    <span class="whitespace-nowrap">{{ $t('label.login') }}</span>
                </router-link>

                <div v-else class="dropdown-group">
                    <button @click=""
                        class="dropdown-btn hidden lg:flex items-center justify-center gap-1 w-fit rounded-3xl capitalize text-sm font-medium h-8 px-3 transition text-white bg-primary">
                        <i class="lab lab-profile-circle"></i>
                        <span class="whitespace-nowrap">{{ $t('label.account') }}</span>
                        <i class="lab lab-arrow-down-2 text-xs ml-1.5 lab-font-size-15"></i>
                    </button>
                    <div
                        class="dropdown-list w-80 absolute top-12 ltr:right-0 rtl:left-0 z-[60] rounded-xl shadow-paper bg-white">
                        <div class="flex items-center gap-3 p-4 mb-2">
                            <figure
                                class="flex-shrink-0 relative z-10 w-[68px] h-[68px] rounded-full border-2 border-dashed border-white bg-gradient-to-t from-[#FF7A00] to-[#FF016C] before:absolute before:inset-0 before:-z-10 before:rounded-full before:scale-[1.03] before:bg-white">
                                <a
                                    class="relative w-full h-full scale-[0.98] overflow-hidden shadow-avatar rounded-full">
                                    <img class="w-full h-full rounded-full object-cover" :src="profile.image"
                                        alt="avatar">
                                    <label for="avatar"
                                        class="block absolute bottom-0 w-full flex items-center justify-center py-1 cursor-pointer bg-white/90">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M20.97 1H18.03C16.76 1 16 1.76 16 3.03V5.97C16 7.24 16.76 8 18.03 8H20.97C22.24 8 23 7.24 23 5.97V3.03C23 1.76 22.24 1 20.97 1ZM21.19 4.31C21.07 4.43 20.91 4.49 20.75 4.49C20.59 4.49 20.43 4.43 20.31 4.31L20.13 4.13V6.37C20.13 6.72 19.85 7 19.5 7C19.15 7 18.87 6.72 18.87 6.37V4.13L18.69 4.31C18.45 4.55 18.05 4.55 17.81 4.31C17.57 4.07 17.57 3.67 17.81 3.43L19.06 2.18C19.11 2.13 19.18 2.09 19.25 2.06C19.27 2.05 19.29 2.05 19.31 2.04C19.36 2.02 19.41 2.01 19.47 2.01C19.49 2.01 19.51 2.01 19.53 2.01C19.6 2.01 19.66 2.02 19.73 2.05C19.74 2.05 19.74 2.05 19.75 2.05C19.82 2.08 19.88 2.12 19.93 2.17C19.94 2.18 19.94 2.18 19.95 2.18L21.2 3.43C21.44 3.67 21.44 4.07 21.19 4.31Z"
                                                fill="#292D32" />
                                            <path
                                                d="M8.99914 10.38C10.3136 10.38 11.3791 9.31443 11.3791 8C11.3791 6.68556 10.3136 5.62 8.99914 5.62C7.6847 5.62 6.61914 6.68556 6.61914 8C6.61914 9.31443 7.6847 10.38 8.99914 10.38Z"
                                                fill="#292D32" />
                                            <path
                                                d="M20.97 8H20.5V12.61L20.37 12.5C19.59 11.83 18.33 11.83 17.55 12.5L13.39 16.07C12.61 16.74 11.35 16.74 10.57 16.07L10.23 15.79C9.52 15.17 8.39 15.11 7.59 15.65L3.85 18.16C3.63 17.6 3.5 16.95 3.5 16.19V7.81C3.5 4.99 4.99 3.5 7.81 3.5H16V3.03C16 2.63 16.07 2.29 16.23 2H7.81C4.17 2 2 4.17 2 7.81V16.19C2 17.28 2.19 18.23 2.56 19.03C3.42 20.93 5.26 22 7.81 22H16.19C19.83 22 22 19.83 22 16.19V7.77C21.71 7.93 21.37 8 20.97 8Z"
                                                fill="#292D32" />
                                        </svg>
                                        <input type="file" id="avatar" @change="saveImage" ref="imageProperty"
                                            accept="image/png, image/jpeg, image/jpg"
                                            class="opacity-0 cursor-pointer absolute inset-0 -z-10">
                                    </label>
                                </a>
                            </figure>
                            <figure>
                                <figcaption class="flex-auto">
                                    <h3 class="text-sm font-medium capitalize mb-0.5">{{ profile.name }}</h3>
                                    <h4 class="text-xs text-paragraph mb-1.5">{{ profile.email }}</h4>
                                    <h5 class="text-sm font-medium">{{ profile.currency_balance }} |
                                        {{ profile.points ?? 0 }} {{ $t('label.points') }}</h5>
                                </figcaption>
                            </figure>
                        </div>
                        <nav class="px-4">
                            <router-link
                                v-if="profile.role_id !== enums.roleEnum.CUSTOMER && Object.keys(authDefaultPermission).length > 0"
                                :to="{ name: 'admin.dashboard' }"
                                class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
                                <i class="lab lab-dashboard lab-font-size-17"></i>
                                <span class="text-sm leading-6 capitalize">{{ $t('menu.dashboard') }}</span>
                            </router-link>

                            <router-link :to="{ name: 'frontend.myOrder' }"
                                class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
                                <i class="lab lab-reserve-line lab-font-size-17"></i>
                                <span class="text-sm leading-6 capitalize">{{ $t('button.my_orders') }}</span>
                            </router-link>

                            <router-link :to="{ name: 'frontend.editProfile' }"
                                class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
                                <i class="lab lab-edit lab-font-size-17"></i>
                                <span class="text-sm leading-6 capitalize">{{ $t('button.edit_profile') }}</span>
                            </router-link>

                            <router-link v-if="hasChatAccess()" :to="{ name: 'frontend.chat' }" @click.native="refreshPermissions"
                                class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
                                <i class="lab lab-messages-line lab-font-size-17"></i>
                                <span class="text-sm leading-6 capitalize">{{ $t('button.chat') }}</span>
                            </router-link>

                            <router-link :to="{ name: 'frontend.address' }"
                                class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
                                <i class="lab lab-map lab-font-size-17"></i>
                                <span class="text-sm leading-6 capitalize">{{ $t('button.address') }}</span>
                            </router-link>

                            <router-link :to="{ name: 'frontend.changePassword' }"
                                class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
                                <i class="lab lab-key lab-font-size-17"></i>
                                <span class="text-sm leading-6 capitalize">{{ $t('button.change_password') }}</span>
                            </router-link>
                            <button @click="logout()"
                                class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
                                <i class="lab lab-logout lab-font-size-17"></i>
                                <span class="text-sm leading-6 capitalize">{{ $t('button.logout') }}</span>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div id="language" class="modal">
        <div class="modal-dialog max-w-xs">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t('menu.languages') }}</h3>
                <button class="modal-close fa-regular fa-circle-xmark" @click="hideLanguageModal()"></button>
            </div>
            <nav class="p-2">
                <button @click="changeLanguage(language.id, language.code)" v-for="language in languages" type="button"
                    class="w-full flex items-center gap-2 py-1.5 px-2.5 rounded-md cursor-pointer hover:bg-gray-100">
                    <img :src="language.image" alt="flag" class="w-4 h-4 rounded-full">
                    <span class="text-heading capitalize text-sm">{{ language.name }}</span>
                </button>
            </nav>
        </div>
    </div>

    <div id="order" v-if="orderNotificationStatus" ref="orderNotificationModal" class="modal active ff-modal">
        <div class="modal-dialog max-w-[360px] p-6 text-center relative">
            <button @click.prevent="closeOrderNotificationModal" class="modal-close absolute top-4 right-4">
                <i class="fa-regular fa-circle-xmark"></i>
            </button>
            <h3 class="text-[18px] font-semibold leading-8 mb-6">
                {{ orderNotificationMessage }}
                <span class="block">{{ $t('message.please_check_your_order_list') }}</span>
            </h3>
            <router-link :to="{ path: '/admin/' + orderNotification.url }"
                class="db-btn h-[38px] shadow-[0px_6px_10px_rgba(255,_0,_107,_0.24)] bg-primary text-white">
                {{ $t('button.let_me_check') }}
            </router-link>
        </div>
    </div>

    <div id="location-modal" ref="locationModal" class="modal ff-modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t('label.delivery_address') }}</h3>
                <div class="flex items-center justify-between">
                    <button class="modal-close fa-regular lab lab-close-circle-line lab-font-size-22 lab-font-bold"
                        @click="reset"></button>
                </div>

            </div>
            <div class="modal-body">
                <form @submit.prevent="saveLocation">
                    <MapComponent :key="mapKey" v-if="isMap"
                        :location="{ lat: localAddressProps.latitude, lng: localAddressProps.longitude }"
                        :position="location" />
                    <div class="flex items-center gap-2 mb-3" v-if="mapAddress">
                        <i class="lab lab-location text-xl text-primary"></i>
                        <span class="text-sm text-heading">{{ mapAddress }}</span>
                    </div>
                    <div class="mb-3">
                        <label for="apartment" class="text-xs leading-6 capitalize mb-1 text-heading">{{
                            $t('label.apartment_and_flat')
                            }}</label>
                        <textarea v-on:keypress="removeAddress" id="apartment" v-model="apartment"
                            class="h-12 w-full rounded-lg border py-1.5 px-2 placeholder:text-[10px] placeholder:text-[#6E7191] border-[#D9DBE9]"></textarea>
                    </div>
                    <div v-if="profile.id" class="mb-6">
                        <h3 class="capitalize font-medium mb-2">{{ $t('label.add_label') }}</h3>
                        <nav class="flex flex-wrap gap-3 active-group">
                            <button @click="changeSwitchLabel(enums.labelEnum.HOME)"
                                :class="switchLabel === enums.labelEnum.HOME ? 'active' : ''"
                                v-on:click="this.status = false; this.label = $t('label.home')"
                                :value="enums.labelEnum.HOME" type="button"
                                class="flex items-center gap-2 rounded-lg p-4 border bg-[#F7F7FC] border-[#F7F7FC]">
                                <i class="lab lab-home text-base leading-none"></i>
                                <span class="text-sm capitalize font-medium leading-none text-heading">{{
                                    $t('label.home')
                                }}</span>
                            </button>
                            <button @click="changeSwitchLabel(enums.labelEnum.WORK)"
                                :class="switchLabel === enums.labelEnum.WORK ? 'active' : ''"
                                v-on:click="this.status = false; this.label = $t('label.work')"
                                :value="enums.labelEnum.WORK" type="button"
                                class="flex items-center gap-2 rounded-lg p-4 border bg-[#F7F7FC] border-[#F7F7FC]">
                                <i class="lab lab-briefcase text-base leading-none"></i>
                                <span class="text-sm capitalize font-medium leading-none text-heading">
                                    {{ $t('label.work') }}
                                </span>
                            </button>
                            <button @click="changeSwitchLabel(enums.labelEnum.OTHER)"
                                :class="switchLabel === enums.labelEnum.OTHER ? 'active' : ''"
                                v-on:click="this.status = true; this.label = ''; this.errors.label = ''"
                                :value="enums.labelEnum.OTHER" type="button"
                                class="flex items-center gap-2 rounded-lg p-4 border bg-[#F7F7FC] border-[#F7F7FC]">
                                <i class="lab lab-more-square text-base leading-none"></i>
                                <span class="text-sm capitalize font-medium leading-none text-heading">{{
                                    $t('label.other')
                                }}</span>
                            </button>
                        </nav>
                        <small class="db-field-alert" v-if="errors.label && switchLabel !== enums.labelEnum.OTHER">{{
                            errors.label[0]
                        }}</small>
                        <div v-if="status" :class="!status ? 'h-0' : ''" class="overflow-hidden transition">
                            <input type="text" :placeholder="$t('label.type_label_name')" v-model="label"
                                v-bind:class="errors.label ? 'invalid' : ''"
                                class="h-10 w-full rounded-lg border mt-5 py-1.5 px-4 placeholder:text-xs border-[#D9DBE9]">
                            <small class="db-field-alert" v-if="errors.label">{{ errors.label[0] }}</small>
                        </div>
                    </div>
                    <div v-if="status && !profile.id" :class="!status ? 'h-0' : ''"
                        class="overflow-hidden transition mb-6">
                        <h3 class="capitalize font-medium mb-2">{{
                            $t('label.add_label') }}
                        </h3>
                        <input type="text" :placeholder="$t('label.type_label_name')" v-model="label"
                            v-bind:class="errors.label ? 'invalid' : ''"
                            class="h-12 mt-0 w-full rounded-lg border py-1.5 px-4 placeholder:text-xs border-[#D9DBE9]">
                        <small class="db-field-alert" v-if="errors.label">{{ errors.label[0] }}</small>
                    </div>
                    <div class="form-col-12 mb-6" v-if="addresses.length > 0">
                        <h3 class="font-medium capitalize">{{ $t('label.saved_address') }}</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 active-group pt-4">
                            <label @click="changeAddress(address)" v-for="address in addresses" :key="address"
                                :for="address.label" class="p-3 rounded-lg w-full border border-[#F7F7FC] bg-[#F7F7FC]"
                                :class="this.localAddressProps.id === address.id ? 'active' : ''">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2 text-xs text-[#008BBA]">
                                        <i class="icon-home"></i>
                                        <span class="font-medium">{{ address.label }}</span>
                                    </div>
                                    <div class="custom-radio sm">
                                        <input type="radio" :id="'address' + address.id" v-model="localAddressProps.id"
                                            :value="address.id" class="custom-radio-field">
                                        <span class="custom-radio-span"></span>
                                    </div>
                                </div>
                                <div class="text-xs flex gap-2 text-[#1F1F39]">
                                    <i class="icon-location1 mt-0.5"></i>
                                    <span v-if="address.apartment">{{ address.apartment }}, {{
                                        address.address
                                        }}</span>
                                    <span v-else>{{ address.address }}</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    <button type="submit"
                        class="rounded-3xl text-base py-3 px-3 font-medium w-full text-white bg-primary">
                        {{ $t('button.confirm_location') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import statusEnum from "../../../enums/modules/statusEnum.js";
import appService from "../../../services/appService.js";
import alertService from "../../../services/alertService.js";
import LoadingComponent from "../../frontend/components/LoadingComponent.vue";
import axios from 'axios'
import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";
import activityEnum from "../../../enums/modules/activityEnum.js";
import roleEnum from "../../../enums/modules/roleEnum.js";
import labelEnum from "../../../enums/modules/labelEnum.js";
import _ from "lodash";
import router from "../../../router";
import MapComponent from "../../frontend/components/MapComponent.vue";
import { Loader } from "google-maps";
import ENV from "../../../config/env";

const options = { libraries: ["places", "geometry", "drawing"] };
const loader = new Loader(ENV.GOOGLE_MAP_KEY, options);

export default {
    name: "FrontendNavbarComponent",
    components: { LoadingComponent, MapComponent },
    data() {
        return {
            lastPermissionRefresh: null,
            loading: {
                isActive: false,
            },
            mapKey: "create-update",
            orderNotificationStatus: false,
            orderNotificationMessage: "",
            currentRoute: "",
            defaultBranch: null,
            defaultLanguage: null,
            defaultCountryCode: null,
            enums: {
                activityEnum: activityEnum,
                roleEnum: roleEnum,
                labelEnum: labelEnum
            },
            branchProps: {
                paginate: 0,
                order_column: "id",
                order_type: "asc",
                status: statusEnum.ACTIVE
            },
            languageProps: {
                paginate: 0,
                order_column: "id",
                order_type: "asc",
                status: statusEnum.ACTIVE
            },
            categoryProps: {
                search: {
                    paginate: 0,
                    order_column: 'sort',
                    order_type: 'asc',
                    status: statusEnum.ACTIVE
                },
                slug: '',
            },
            orderNotification: {
                permission: false,
                url: ""
            },
            searchItem: "",
            addressProps: {
                search: {
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'asc'
                },
            },
            branchProps: {
                paginate: 0,
                order_column: "id",
                order_type: "asc",
                status: statusEnum.ACTIVE
            },
            localAddressProps: {
                latitude: "",
                longitude: "",
                apartment: "",
                address: "",
                id: null,
                label: "",
            },
            localAddress: {},
            mapAddress: "",
            status: false,
            switchLabel: null,
            addresses: {},
            apartment: "",
            label: "",
            isMap: false,
            errors: {},
            toggleValue: false,
            inputButton: this.$t('label.select_branch')

        }
    },
    computed: {
        logged: function () {
            return this.$store.getters.authStatus;
        },
        authDefaultPermission: function () {
            return this.$store.getters.authDefaultPermission;
        },
        profile: function () {
            return this.$store.getters.authInfo;
        },
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        branch: function () {
            return this.$store.getters['frontendBranch/show'];
        },
        language: function () {
            return this.$store.getters['frontendLanguage/show'];
        },
        languages: function () {
            return this.$store.getters['frontendLanguage/lists'];
        },
        categories: function () {
            return this.$store.getters['frontendItemCategory/lists'];
        },
        subtotal: function () {
            return this.$store.getters['frontendCart/subtotal'];
        },
        carts: function () {
            return this.$store.getters['frontendCart/lists'];
        }
    },
    mounted() {
        // Refresh permissions once when component is first mounted
        this.refreshPermissions();
        
        window.addEventListener('scroll', () => {
            const resetRoutes = [
                'frontend.myOrder',
                'frontend.editProfile',
                'frontend.chat',
                'frontend.address',
                'frontend.changePassword',
                'auth.login',
                'auth.signupPhone',
                'auth.signupRegister',
                'auth.forgetPassword',
                'auth.verifyEmail',
                'frontend.page',
                'frontend.offers'
            ];
            if (this.$refs.ffHeader) {
                if (!resetRoutes.includes(this.$route.name)) {
                    if (window.scrollY > 0) {
                        this.$refs.ffHeader.classList.add('active');
                    } else {
                        this.$refs.ffHeader.classList.remove('active');
                    }
                } else {
                    this.$refs.ffHeader.classList.remove('active');
                }
            }
        });

        this.currentRoute = this.$route.path;
        this.loading.isActive = true;
        this.orderPermissionCheck();
        this.$store.dispatch('frontendSetting/lists').then(res => {
            // this.defaultBranch = res.data.data.site_default_branch;
            this.defaultLanguage = res.data.data.site_default_language;
            this.defaultCountryCode = res.data.data.company_country_code;

            const globalState = this.$store.getters['globalState/lists'];
            if (globalState.branch_id == null || globalState.branch_id === 0) {
                this.locationModalShow();
            } else {
                this.defaultBranch = globalState.branch_id;
                this.branchSet();
            }

            if (globalState.language_id > 0) {
                this.defaultLanguage = globalState.language_id;
            }
            // this.$store.dispatch('frontendBranch/show', this.defaultBranch).then().catch();
            this.$store.dispatch('frontendLanguage/lists', this.languageProps).then().catch();
            this.$store.dispatch('frontendLanguage/show', this.defaultLanguage).then(res => {
                this.$i18n.locale = res.data.data.code;
                this.$store.dispatch("globalState/init", {
                    language_code: res.data.data.code
                });
            }).catch();
            this.$store.dispatch('frontendItemCategory/lists', this.categoryProps.search).then().catch();
            this.loading.isActive = false;

            window.setTimeout(() => {
                this.$store.dispatch('frontendCountryCode/show', this.defaultCountryCode).then().catch();
                this.$store.dispatch('frontendCart/initOrderType', {
                    order_setup_delivery: res.data.data.order_setup_delivery,
                    order_setup_takeaway: res.data.data.order_setup_takeaway
                });

                if (this.$store.getters.authStatus && res.data.data.notification_fcm_api_key && res.data.data.notification_fcm_auth_domain && res.data.data.notification_fcm_project_id && res.data.data.notification_fcm_storage_bucket && res.data.data.notification_fcm_messaging_sender_id && res.data.data.notification_fcm_app_id && res.data.data.notification_fcm_measurement_id) {
                    initializeApp({
                        apiKey: res.data.data.notification_fcm_api_key,
                        authDomain: res.data.data.notification_fcm_auth_domain,
                        projectId: res.data.data.notification_fcm_project_id,
                        storageBucket: res.data.data.notification_fcm_storage_bucket,
                        messagingSenderId: res.data.data.notification_fcm_messaging_sender_id,
                        appId: res.data.data.notification_fcm_app_id,
                        measurementId: res.data.data.notification_fcm_measurement_id
                    });
                    const messaging = getMessaging();

                    Notification.requestPermission().then((permission) => {
                        if (permission === 'granted') {
                            getToken(messaging, { vapidKey: res.data.data.notification_fcm_public_vapid_key }).then((currentToken) => {
                                if (currentToken) {
                                    axios.post('/frontend/device-token/web', { token: currentToken }).then().catch((error) => {
                                        if (error.response.data.message === 'Unauthenticated.') {
                                            this.$store.dispatch('loginDataReset');
                                        }
                                    });
                                }
                            }).catch();
                        }
                    });

                    onMessage(messaging, (payload) => {
                        const notificationTitle = payload.notification.title;
                        const notificationOptions = {
                            body: payload.notification.body,
                            icon: '/images/default/firebase-logo.png'
                        };
                        new Notification(notificationTitle, notificationOptions);

                        if (payload.data.topicName === 'new-order-found' && this.orderNotification.permission) {
                            this.orderNotificationStatus = true;
                            this.orderNotificationMessage = payload.notification.body;
                            const audio = new Audio(res.data.data.notification_audio);
                            audio.play();
                        }
                    });
                }
            }, 3000);
        }).catch((err) => {
            this.loading.isActive = false;
        });

        if (typeof this.$store.getters.authInfo.id !== 'undefined') {
            this.$store.dispatch("frontendEditProfile/profile").then((res) => {
                this.$store.dispatch('updateAuthInfo', res.data.data).then(res => {
                    this.loading.isActive = false;
                }).catch((err) => {
                    this.loading.isActive = false;
                });
            });
        }
    },

    methods: {
        branchSet: function () {
            this.$store.dispatch('frontendBranch/show', this.defaultBranch).then().catch();
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        checkIsPathAndRoutePathSame(path) {
            if (this.currentRoute === path) {
                return true;
            }
        },
        changeSwitchLabel: function (id) {
            if (id === this.enums.labelEnum.OTHER) {
                this.label = "";
            }
            this.switchLabel = id;
            this.localAddressProps = this.localAddress;
        },
        removeAddress: function () {
            this.localAddressProps = this.localAddress;
        },
        location: function (e) {
            this.mapAddress = e.address;
            this.localAddressProps = {
                latitude: e.location.lat,
                longitude: e.location.lng,
                address: e.address,
                apartment: "",
                id: null,
            }
            this.localAddress = {
                latitude: e.location.lat,
                longitude: e.location.lng,
                address: e.address,
                apartment: "",
                id: null,
            }

        },
        reset: function () {
            this.locationModalHide();
            this.status = false;
            this.switchLabel = null;
            this.label = "";
            this.apartment = "";
            this.mapAddress = "";
            this.isMap = false;
            this.addresses = {};
            this.errors = {};
        },
        changeLanguage: function (id, code) {
            this.hideLanguageModal();
            this.defaultLanguage = id;
            this.$store.dispatch("globalState/set", { language_id: id, language_code: code }).then(res => {
                this.$store.dispatch('frontendLanguage/show', id).then(res => {
                    this.$i18n.locale = res.data.data.code;
                }).catch();
            }).catch();
        },
        hideLanguageModal: function () {
            appService.modalHide('#language');
        },
        logout: function () {
            this.$store.dispatch("logout").then(res => {
                this.$store.dispatch('frontendDeliveryAddress/reset');
                this.$router.push({ name: "frontend.home" });
            }).catch();
        },
        currencyFormat(amount, decimal, currency, position) {
            return appService.currencyFormat(amount, decimal, currency, position);
        },
        search: function () {
            if (typeof this.searchItem !== "undefined" && this.searchItem !== "") {
                this.$router.push({ name: "frontend.search", query: { s: this.searchItem } });
                this.searchItem = "";
            }
        },
        searchReset: function () {
            this.searchItem = "";
        },
        saveImage: function () {
            if (this.$refs.imageProperty.files[0]) {
                try {
                    this.loading.isActive = true;
                    const formData = new FormData();
                    formData.append("image", this.$refs.imageProperty.files[0]);
                    this.$store.dispatch("frontendEditProfile/changeImage", { form: formData }).then((res) => {
                        this.$store.dispatch('updateAuthInfo', res.data.data).then(res => {
                            this.loading.isActive = false;
                            alertService.success(this.$t("message.photo_update"));
                            this.$refs.imageProperty.value = null;
                        }).catch((err) => {
                            this.loading.isActive = false;
                            alertService.error(err);
                        });
                    }).catch((err) => {
                        this.loading.isActive = false;
                        this.imageErrors = err.response.data.errors;
                    });
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }
        },
        orderPermissionCheck: function () {
            const permissions = this.$store.getters.authPermission;
            if (permissions.length > 0) {
                _.forEach(permissions, (permission) => {
                    if (permission.name === 'online-orders') {
                        if (permission.access === true) {
                            this.orderNotification.permission = true;
                            this.orderNotification.url = permission.url;
                        }
                    }
                });
            }
        },
        closeOrderNotificationModal: function () {
            const modalTarget = this.$refs.orderNotificationModal;
            modalTarget?.classList?.remove("active");
            document.body.style.overflowY = "auto";
            this.loading.isActive = false;
            this.orderNotificationStatus = false;
        },
        locationModalShow: function () {
            if (typeof this.$store.getters.authInfo.id !== 'undefined') {
                this.status = false;
                this.loading.isActive = true;
                this.$store.dispatch("frontendAddress/lists", this.addressProps).then(res => {
                    this.addresses = res.data.data;
                    this.loading.isActive = false;
                }).catch((err) => {
                    this.loading.isActive = false;
                });
            } else {
                this.status = true;
            }
            const modalTarget = this.$refs.locationModal;
            modalTarget?.classList?.add("active");
            document.body.style.overflowY = "hidden";
            this.isMap = true;
        },
        locationModalHide: function () {
            const modalTarget = this.$refs.locationModal;
            modalTarget?.classList?.remove("active");
            document.body.style.overflowY = "auto";
        },
        changeAddress: function (address) {
            this.switchLabel = null;
            this.label = "";
            this.apartment = "";
            this.errors = {};
            this.localAddressProps = {
                latitude: address.latitude,
                longitude: address.longitude,
                address: address.address,
                apartment: address.apartment,
                id: address.id,
                label: address.label,
            }
        },
        saveLocation: function () {
            if (this.apartment) {
                this.localAddressProps.apartment = this.apartment;
            }

            if (this.localAddressProps.latitude && this.localAddressProps.longitude) {
                this.$store.dispatch("frontendBranch/showByLatLong", {
                    latitude: this.localAddressProps.latitude,
                    longitude: this.localAddressProps.longitude,
                }).then((branchRes) => {
                    if (typeof this.$store.getters.authInfo.id !== 'undefined' && this.localAddressProps.id === null) {
                        this.localAddressProps.label = this.label;
                        this.loading.isActive = true;
                        this.$store.dispatch("frontendAddress/save", {
                            form: this.localAddressProps,
                            search: this.addressProps.search
                        }).then((addressRes) => {
                            this.localAddressProps.id = addressRes.data.data.id;
                            this.$store.dispatch("frontendDeliveryAddress/address", this.localAddressProps);
                            this.loading.isActive = false;
                            this.status = false;
                            this.switchLabel = null;
                            this.errors = {};
                            this.confirmDeliveryAddress(branchRes);
                        }).catch((err) => {
                            this.loading.isActive = false;
                            this.errors = err.response.data.errors;
                        });

                    } else {
                        if (this.localAddressProps.latitude) {
                            if (!this.label && typeof this.$store.getters.authInfo.id === 'undefined') {
                                this.errors = {
                                    label: [this.$t('message.label_required')],
                                };
                            } else {
                                if (this.label && typeof this.$store.getters.authInfo.id === 'undefined') {
                                    this.localAddressProps.label = this.label;
                                }
                                this.$store.dispatch("frontendDeliveryAddress/address", this.localAddressProps);
                                this.errors = {};
                                this.confirmDeliveryAddress(branchRes);
                            }

                        }

                    }

                }).catch((err) => {
                    this.errors = {};
                    alertService.error(err.response.data.message);
                });
            }
        },
        confirmDeliveryAddress: function (branchRes) {
            if (this.carts.length > 0 && this.$store.getters['globalState/lists'].branch_id !== branchRes.data.data.id) {
                appService.changeBranchConfirmation().then((res) => {
                    this.$store.dispatch('frontendCart/resetCart').then(res => {
                    }).catch();
                    this.$store.dispatch("globalState/set", {
                        branch_id: branchRes.data.data.id,
                    });

                    this.defaultBranch = branchRes.data.data.id;
                    this.branchSet();
                    this.locationModalHide();
                    router.push({ name: "frontend.home" });
                    location.reload();
                }).catch((err) => {
                    this.loading.isActive = false;
                })
            } else {
                this.$store.dispatch("globalState/set", {
                    branch_id: branchRes.data.data.id,
                });
                this.defaultBranch = branchRes.data.data.id;
                this.branchSet();
                this.locationModalHide();
                location.reload();
            }
        },
        refreshPermissions: function() {
            // Only refresh if we haven't refreshed recently (within last 10 seconds)
            const now = Date.now();
            if (!this.lastPermissionRefresh || (now - this.lastPermissionRefresh > 10000)) {
                this.lastPermissionRefresh = now;
                this.$store.dispatch("refreshPermissions")
                    .then(() => {
                        // Permissions updated successfully, no need for console logs in production
                    })
                    .catch(error => {
                        console.error("Error refreshing permissions:", error);
                    });
            }
        },
        hasChatAccess: function() {
            const permissions = this.$store.getters.authPermission;
            if (permissions && Array.isArray(permissions)) {
                const messagePermission = permissions.find(p => p.name === 'messages' || p.url === 'messages');
                return messagePermission && messagePermission.access === true;
            }
            return true; // Default to showing if permissions aren't properly loaded
        },
    },

    watch: {
        $route(to, from) {
            this.currentRoute = to.path;
            
            // Only refresh permissions when navigating to the chat page
            // or when coming from admin pages that might change permissions
            if (to.name === 'frontend.chat' || (from.name && from.name.startsWith('admin.'))) {
                this.$store.dispatch("refreshPermissions").catch(error => {
                    console.error("Error refreshing permissions:", error);
                });
            }
        },
        categories: {
            deep: true,
            handler(category) {
                if (category.length > 0) {
                    if (category[0].slug !== "undefined") {
                        this.categoryProps.slug = category[0].slug;
                    }
                }
            }
        }
    },
}
</script>