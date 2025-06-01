<template>
    <LoadingComponent :props="loading" />
    <section class="pt-8 pb-16">
        <div class="container max-w-[965px]">
            <router-link :to="{ name: 'frontend.home' }"
                class="text-xs font-medium inline-flex mb-3 items-center gap-2 text-primary">
                <i class="lab lab-undo lab-font-size-16"></i>
                <span>{{ $t('label.back_to_home') }}</span>
            </router-link>
            <div class="row">
                <div class="col-12 md:col-7">
                    <div class="p-4 mb-6 rounded-2xl shadow-xs bg-white">
                        <div v-if="checkoutProps.form.order_type === orderTypeEnum.DELIVERY"
                            class="items-center mb-3 mt-3">
                            <h3 class="font-medium mb-2">{{ branchName }}</h3>
                            <div class="flex items-center gap-2">
                                <i class="lab lab-location text-xl text-primary"></i>
                                <p class="text-sm text-heading">{{ branchAddress }}</p>
                            </div>
                        </div>
                        <MapComponent :key="mapKey" v-if="mapShow" :location="location" :position="branchPosition"
                            :setting="{ autocomplete: false, mouseEvent: false, currentLocation: false }" />

                        <div v-if="checkoutProps.form.order_type === orderTypeEnum.TAKEAWAY"
                            class="flex items-center gap-2 mb-3 mt-6">
                            <i class="lab lab-location text-xl text-primary"></i>
                            <span class="text-sm text-heading">{{ branchAddress }}</span>
                        </div>

                        <div v-if="checkoutProps.form.order_type === orderTypeEnum.DELIVERY" class="mb-5">
                            <div class="flex flex-wrap justify-between gap-5 mb-2.5">
                                <h4 class="capitalize font-medium"> {{ $t('label.delivery_address') }} </h4>
                                <div class="flex gap-3">
                                    <button v-if="Object.keys(localAddress).length !== 0" @click="editAddress"
                                        type="button"
                                        class="group text-xs capitalize font-medium flex items-center rounded-3xl py-1.5 px-3 gap-1 text-[#00749B] bg-[#D6F5FF] transition hover:text-white hover:bg-[#00749B]">
                                        <i class="lab lab-edit-2 lab-font-size-13"></i>
                                        <span>{{ $t('button.edit_address') }}</span>
                                    </button>
                                    <AddressComponent :getLocation="updateAddress" :props="addressProps" />
                                </div>
                            </div>
                            <div v-if="addresses.length > 0" class="grid grid-cols-1 sm:grid-cols-3 gap-3 active-group">
                                <label @click="changeAddress($event, address)"
                                    :class="checkoutProps.form.address_id === address.id ? 'active' : ''"
                                    v-for="address in addresses" :key="address" :for="address.label"
                                    class="p-3 rounded-lg w-full border border-[#F7F7FC] bg-[#F7F7FC]">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2 text-xs text-[#008BBA]">
                                            <i class="icon-home"></i>
                                            <span class="font-medium">{{ address.label }}</span>
                                        </div>
                                        <div class="custom-radio sm">
                                            <input type="radio" :id="address.label"
                                                v-model="checkoutProps.form.address_id" :value="address.id"
                                                class="custom-radio-field">
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

                        <div>
                            <h4 class="font-medium mb-2.5">{{ $t('label.preferred_time') }}</h4>

                            <Swiper :speed="1000" slidesPerView="auto" :spaceBetween="16" class="mb-3">
                                <SwiperSlide class="active-group !w-fit !relative">
                                    <label @click="changeDayTake(dayTakeEnum.TODAY)" for="today"
                                        :class="dayTake === dayTakeEnum.TODAY ? 'active' : ''"
                                        class="w-full db-field-radio px-2.5 py-2 rounded-lg border border-[#F7F7FC] bg-[#F7F7FC]">
                                        <div class="custom-radio sm">
                                            <input type="radio" v-model="dayTake" :value="dayTakeEnum.TODAY" id="today"
                                                class="custom-radio-field">
                                            <span class="custom-radio-span"></span>
                                        </div>
                                        <label for="today" class="db-field-label text-sm text-heading">
                                            {{ $t('label.today') }}
                                        </label>
                                    </label>
                                </SwiperSlide>
                                <SwiperSlide class="active-group !w-fit !relative">
                                    <label @click="changeDayTake(dayTakeEnum.TOMORROW)" for="tomorrow"
                                        :class="dayTake === dayTakeEnum.TOMORROW ? 'active' : ''"
                                        class="db-field-radio px-2.5 py-2 rounded-lg border border-[#F7F7FC] bg-[#F7F7FC]">
                                        <div class="custom-radio sm">
                                            <input type="radio" v-model="dayTake" :value="dayTakeEnum.TOMORROW"
                                                id="tomorrow" class="custom-radio-field">
                                            <span class="custom-radio-span"></span>
                                        </div>
                                        <label for="tomorrow" class="db-field-label text-sm text-heading">
                                            {{ $t('label.tomorrow') }}
                                        </label>
                                    </label>
                                </SwiperSlide>
                            </Swiper>


                            <Swiper v-if="dayTake === dayTakeEnum.TODAY" :speed="1000" slidesPerView="auto"
                                :spaceBetween="16">
                                <SwiperSlide v-for="todayTimeSlot in todayTimeSlots" :key="todayTimeSlot"
                                    class="active-group !w-fit !relative">
                                    <label
                                        :class="todayTimeSlot.time === checkoutProps.form.delivery_time ? 'active' : ''"
                                        :for="todayTimeSlot.label"
                                        class="db-field-radio px-2.5 py-2 rounded-lg border border-[#F7F7FC] bg-[#F7F7FC]">
                                        <div class="custom-radio sm">
                                            <input v-model="checkoutProps.form.delivery_time" type="radio"
                                                :id="todayTimeSlot.label" :value="todayTimeSlot.time"
                                                class="custom-radio-field">
                                            <span class="custom-radio-span"></span>
                                        </div>
                                        <label :for="todayTimeSlot.label" class="db-field-label text-sm text-heading">
                                            {{ todayTimeSlot.label }}
                                        </label>
                                    </label>
                                </SwiperSlide>
                            </Swiper>


                            <Swiper v-if="dayTake === dayTakeEnum.TOMORROW" :speed="1000" slidesPerView="auto"
                                :spaceBetween="16">
                                <SwiperSlide v-for="tomorrowTimeSlot in tomorrowTimeSlots" :key="tomorrowTimeSlot"
                                    class="active-group !w-fit !relative">
                                    <label
                                        :class="tomorrowTimeSlot.time === checkoutProps.form.delivery_time ? 'active' : ''"
                                        :for="tomorrowTimeSlot.label"
                                        class="w-full db-field-radio px-2.5 py-2 rounded-lg border border-[#F7F7FC] bg-[#F7F7FC]">
                                        <div class="custom-radio sm">
                                            <input v-model="checkoutProps.form.delivery_time" type="radio"
                                                :id="tomorrowTimeSlot.label" :value="tomorrowTimeSlot.time"
                                                class="custom-radio-field">
                                            <span class="custom-radio-span"></span>
                                        </div>
                                        <label :for="tomorrowTimeSlot.label"
                                            class="db-field-label text-sm text-heading">
                                            {{ tomorrowTimeSlot.label }}
                                        </label>
                                    </label>
                                </SwiperSlide>
                            </Swiper>

                        </div>
                    </div>

                    <div class="mb-6 p-4 rounded-2xl shadow-xs bg-white"
                        v-if="gatewayId !== paymentTypeEnum.CASH_ON_DELIVERY && checkoutProps.form.order_type !== orderTypeEnum.TAKEAWAY">
                        <h3 class="mb-1 text-lg font-medium capitalize">{{ $t('label.tip_your_rider') }}
                        </h3>
                        <p class="text-xs font-heading mb-5">{{ $t('message.rider_tip_message') }}</p>
                        <Swiper :speed="1000" slidesPerView="auto" :spaceBetween="16">
                            <SwiperSlide v-for="riderTip in riderTips" :key="riderTip"
                                class="active-group !w-fit !relative">
                                <label :class="calculatePercentageTip(riderTip.amount) === checkoutProps.form.rider_tip ? 'active' : ''"
                                    :for="riderTip.label"
                                    class="db-field-radio px-2.5 py-2 rounded-lg border border-[#F7F7FC] bg-[#F7F7FC]">
                                    <div class="custom-radio sm">
                                        <input v-model="checkoutProps.form.rider_tip" type="radio" :id="riderTip.label"
                                            :value="calculatePercentageTip(riderTip.amount)" 
                                            class="custom-radio-field"
                                            @change="updateRiderTipPercentage(riderTip.amount)">
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label v-if="riderTip.amount > 0" :for="riderTip.label"
                                        class="db-field-label text-sm text-heading">
                                        {{ riderTip.flat_amount }}%
                                    </label>
                                    <label v-else :for="riderTip.label" class="db-field-label text-sm text-heading">
                                        {{ riderTip.label }}
                                    </label>
                                </label>
                            </SwiperSlide>
                        </Swiper>
                    </div>

                    <div class="mb-6 rounded-2xl shadow-card">
                        <h4 class="font-bold capitalize p-4 border-b border-gray-100">
                            {{ $t('label.select_payment_method') }}
                        </h4>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4">
                            <div v-if="+profile.balance >= +getTotal()" @click.prevent="selectPaymentMethod(credit)"
                                :class="Object.keys(paymentMethod).length > 0 && credit.id === paymentMethod.id ? 'border-primary/50 bg-[#F7F7FC]' : 'border-white bg-white'"
                                class="flex flex-col items-center justify-center gap-2.5 py-4 rounded-lg shadow-xs cursor-pointer border">
                                <img class="h-6" :src="credit.image" alt="payment" />
                                <span class="text-xs text-center font-medium">{{ credit.name }} ({{ profile.balance
                                    }})</span>
                            </div>

                            <div v-if="setting.site_online_payment_gateway === activityEnum.ENABLE"
                                v-for="paymentGateway in paymentGateways"
                                @click.prevent="selectPaymentMethod(paymentGateway)"
                                :class="Object.keys(paymentMethod).length > 0 && paymentGateway.id === paymentMethod.id ? 'border-primary/50 bg-[#F7F7FC]' : 'border-white bg-white'"
                                class="flex flex-col items-center justify-center gap-2.5 py-4 rounded-lg shadow-xs cursor-pointer border">
                                <img class="h-6" :src="paymentGateway.image" alt="payment" />
                                <span class="text-xs text-center font-medium">{{ paymentGateway.name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 md:col-5">
                    <div class="rounded-2xl shadow-xs bg-white">
                        <div class="p-4 border-b">
                            <h3 class="capitalize font-medium mb-3 text-center">{{
                                $t('label.cart_summary')
                            }}</h3>
                            <div class="flex items-center rounded-2xl w-fit mx-auto mb-6 text-[#008BBA] bg-[#BDEFFF]">
                                <div v-if="setting.order_setup_delivery === activityEnum.ENABLE"
                                    class="relative cursor-pointer">
                                    <input @change="changeOrderType(orderTypeEnum.DELIVERY)" id="checkout-delivery"
                                        :checked="orderType === orderTypeEnum.DELIVERY" :value="orderTypeEnum.DELIVERY"
                                        class="cart-switch w-full h-full absolute top-0 left-0 opacity-0 cursor-pointer"
                                        type="radio">
                                    <label
                                        class="py-1.5 px-3.5 rounded-2xl text-xs font-medium capitalize transition cursor-pointer"
                                        for="checkout-delivery">{{ $t('label.delivery') }}</label>
                                </div>
                                <div v-if="setting.order_setup_takeaway === activityEnum.ENABLE"
                                    class="relative cursor-pointer">
                                    <input @change="changeOrderType(orderTypeEnum.TAKEAWAY)" id="checkout-takeaway"
                                        :checked="orderType === orderTypeEnum.TAKEAWAY" :value="orderTypeEnum.TAKEAWAY"
                                        class="cart-switch w-full h-full absolute top-0 left-0 opacity-0 cursor-pointer"
                                        type="radio">
                                    <label
                                        class="py-1.5 px-3.5 rounded-2xl text-xs font-medium capitalize transition cursor-pointer"
                                        for="checkout-takeaway">{{ $t('label.takeaway') }}</label>
                                </div>
                            </div>
                            <div class="pl-3">
                                <div v-for="cart in carts"
                                    class="mb-3 pb-3 border-b last:mb-0 last:pb-0 last:border-b-0 border-gray-2">
                                    <div class="flex items-center gap-3 relative">
                                        <h3
                                            class="absolute top-5 ltr:-left-3 rtl:-right-3 text-sm w-[26px] h-[26px] leading-[26px] text-center rounded-full text-white bg-heading">
                                            {{ cart.quantity }}</h3>
                                        <img :src="cart.image" alt="thumbnail"
                                            class="w-16 h-16 rounded-lg flex-shrink-0">
                                        <div class="w-full">
                                            <span class="text-sm font-medium capitalize transition text-heading">
                                                {{ cart.name }}
                                            </span>
                                            <p v-if="Object.keys(cart.item_variations.variations).length !== 0"
                                                class="capitalize text-xs mb-1.5">
                                                <span v-for="(variation, variationName) in cart.item_variations.names">
                                                    {{ variationName }}: {{ variation }}, &nbsp;
                                                </span>
                                            </p>
                                            <h4 class="text-xs font-semibold">
                                                {{
                                                    currencyFormat(cart.total, setting.site_digit_after_decimal_point,
                                                        setting.site_default_currency_symbol, setting.site_currency_position)
                                                }}
                                            </h4>
                                        </div>
                                    </div>

                                    <ul v-if="cart.item_extras.extras.length > 0 || cart.instruction !== ''"
                                        class="flex flex-col gap-1.5 mt-2">
                                        <li v-if="cart.item_extras.extras.length > 0" class="flex gap-1">
                                            <h3 class="capitalize text-xs w-fit whitespace-nowrap">
                                                {{ $t('label.extras') }}:
                                            </h3>
                                            <p class="text-xs">
                                                <span v-for="extra in cart.item_extras.names">
                                                    {{ extra }}, &nbsp;
                                                </span>
                                            </p>
                                        </li>

                                        <li v-if="cart.instruction !== ''" class="flex gap-1">
                                            <h3 class="capitalize text-xs w-fit whitespace-nowrap">
                                                {{ $t('label.instruction') }}:
                                            </h3>
                                            <p class="text-xs">{{ cart.instruction }}</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <CouponComponent :props="{ total: parseFloat(subtotal) }" :coupon="coupon" />
                            <PointComponent v-on:applyPoints="point" :subtotal="subtotal" />

                            <div class="rounded-xl mb-6 border border-[#EFF0F6]">
                                <ul class="flex flex-col gap-2 p-3 border-b border-dashed border-[#EFF0F6]">
                                    <li class="flex items-center justify-between text-heading">
                                        <span class="text-sm leading-6 capitalize">
                                            {{ $t('label.subtotal') }}
                                        </span>
                                        <span class="text-sm leading-6 capitalize">
                                            {{
                                                currencyFormat(subtotal, setting.site_digit_after_decimal_point,
                                                    setting.site_default_currency_symbol, setting.site_currency_position)
                                            }}
                                        </span>
                                    </li>
                                    <li class="flex items-center justify-between text-heading">
                                        <span class="text-sm leading-6 capitalize">
                                            {{ $t('label.discount') }}
                                        </span>
                                        <span class="text-sm leading-6 capitalize">
                                            {{
                                                currencyFormat(checkoutProps.form.discount,
                                                    setting.site_digit_after_decimal_point,
                                                    setting.site_default_currency_symbol,
                                                    setting.site_currency_position)
                                            }}
                                        </span>
                                    </li>
                                    <li v-if="checkoutProps.form.point_discount_amount > 0"
                                        class="flex items-center justify-between text-heading ">
                                        <span class="text-sm leading-6 capitalize ">
                                            {{ $t('label.points') }} {{ $t('label.discount') }}
                                        </span>
                                        <span class="text-sm leading-6 capitalize text-red-600">
                                            {{
                                                currencyFormat(checkoutProps.form.point_discount_amount,
                                                    setting.site_digit_after_decimal_point,
                                                    setting.site_default_currency_symbol,
                                                    setting.site_currency_position)
                                            }}
                                        </span>
                                    </li>
                                    <li v-if="checkoutProps.form.rider_tip > 0"
                                        class="flex items-center justify-between text-heading ">
                                        <span class="text-sm leading-6 capitalize">
                                            {{ $t('label.rider_tips') }}
                                            <span>({{ checkoutProps.form.rider_tip_percentage }}%)</span>
                                        </span>
                                        <span class="text-sm leading-6 capitalize text-green-600">
                                            {{
                                                currencyFormat(checkoutProps.form.rider_tip,
                                                    setting.site_digit_after_decimal_point,
                                                    setting.site_default_currency_symbol,
                                                    setting.site_currency_position)
                                            }}
                                        </span>
                                    </li>
                                    <li v-if="checkoutProps.form.order_type === orderTypeEnum.DELIVERY"
                                        class="flex items-center justify-between text-heading">
                                        <span class="text-sm leading-6 capitalize">
                                            {{ $t('label.delivery_charge') }}
                                        </span>
                                        <span class="text-sm leading-6 capitalize font-medium text-[#1AB759]">
                                            {{
                                                currencyFormat(checkoutProps.form.delivery_charge,
                                                    setting.site_digit_after_decimal_point,
                                                    setting.site_default_currency_symbol,
                                                    setting.site_currency_position)
                                            }}
                                        </span>
                                    </li>
                                </ul>
                                <div class="flex items-center justify-between p-3">
                                    <h4 class="text-sm leading-6 font-semibold capitalize">
                                        {{ $t('label.total') }}
                                    </h4>
                                    <h5 class="text-sm leading-6 font-semibold capitalize">
                                        {{
                                            currencyFormat(subtotal + parseFloat(checkoutProps.form.rider_tip) +
                                                checkoutProps.form.delivery_charge - checkoutProps.form.discount -
                                                checkoutProps.form.point_discount_amount,
                                                setting.site_digit_after_decimal_point, setting.site_default_currency_symbol,
                                                setting.site_currency_position)
                                        }}
                                    </h5>
                                </div>
                            </div>
                            <button v-if="placeOrderShow" type="button"
                                class="w-full rounded-3xl capitalize font-medium leading-6 py-3 text-white bg-primary"
                                @click="orderSubmit">
                                {{ $t('button.confirm') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
import appService from "../../../services/appService.js";
import alertService from "../../../services/alertService.js";
import MapComponent from "../components/MapComponent.vue";
import dayTakeEnum from "../../../enums/modules/dayTakeEnum.js";
import isAdvanceOrderEnum from "../../../enums/modules/isAdvanceOrderEnum.js";
import sourceEnum from "../../../enums/modules/sourceEnum.js";
import AddressComponent from "./AddressComponent.vue";
import LoadingComponent from "../components/LoadingComponent.vue";
import labelEnum from "../../../enums/modules/labelEnum.js";
import paymentTypeEnum from "../../../enums/modules/paymentTypeEnum.js";
import activityEnum from "../../../enums/modules/activityEnum.js";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum.js";
import CouponComponent from "./CouponComponent.vue";
import PointComponent from "./PointComponent.vue";
import router from "../../../router";
import env from "../../../config/env";
import _ from "lodash";
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import askEnum from "../../../enums/modules/askEnum.js";
import statusEnum from "../../../enums/modules/statusEnum.js";
import { riderTip } from "../../../store/modules/riderTip.js";


export default {
    name: "CheckoutComponent",
    components: {
        LoadingComponent,
        AddressComponent,
        CouponComponent,
        MapComponent,
        Swiper,
        SwiperSlide,
        PointComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            mapShow: false,
            placeOrderShow: false,
            mapKey: "branch",
            location: {
                lat: null,
                lng: null
            },
            branchName: null,
            branchAddress: null,
            localAddress: {},
            dayTakeEnum: dayTakeEnum,
            activityEnum: activityEnum,
            statusEnum: statusEnum,
            askEnum: askEnum,
            isAdvanceOrderEnum: isAdvanceOrderEnum,
            labelEnum: labelEnum,
            paymentTypeEnum: paymentTypeEnum,
            dayTake: dayTakeEnum.TODAY,
            orderTypeEnum: orderTypeEnum,
            paymentGateways: [],
            gatewayId: null,
            credit: {},
            cashOnDelivery: {},
            checkoutProps: {
                form: {
                    branch_id: null,
                    subtotal: 0,
                    discount: 0,
                    delivery_charge: 0,
                    delivery_time: null,
                    total: 0,
                    order_type: null,
                    is_advance_order: null,
                    source: sourceEnum.WEB,
                    address_id: null,
                    coupon_id: null,
                    items: [],
                    point_discount_amount: 0,
                    applied_points: 0,
                    rider_tip: 0,
                    rider_tip_percentage: 0,
                    is_percentage_tip: false,
                }
            },
            addressProps: {
                form: {
                    address: "",
                    apartment: "",
                    latitude: "",
                    longitude: "",
                    label: "",
                },
                search: {
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'asc'
                },
                status: false,
                switchLabel: "",
                isMap: false,
            },
            riderTipProps: {
                paginate: 0,
                order_column: 'id',
                order_type: 'asc'
            },
            branchSettings: {
                itemsToShow: 2.5,
                wrapAround: false,
                snapAlign: "start"
            },
            branchBreakpoints: {
                200: {
                    itemsToShow: 1.1,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                250: {
                    itemsToShow: 1.3,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                300: {
                    itemsToShow: 1.4,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                375: {
                    itemsToShow: 1.7,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                540: {
                    itemsToShow: 2.5,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                700: {
                    itemsToShow: 2.5,
                    wrapAround: false,
                    snapAlign: 'start',
                }
            },
            dayTakeSettings: {
                itemsToShow: 2,
                wrapAround: false,
                snapAlign: "start"
            },
            dayTakeBreakpoints: {
                200: {
                    itemsToShow: 1.1,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                250: {
                    itemsToShow: 1.3,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                300: {
                    itemsToShow: 1.4,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                375: {
                    itemsToShow: 1.7,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                540: {
                    itemsToShow: 2.5,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                700: {
                    itemsToShow: 3.2,
                    wrapAround: false,
                    snapAlign: 'start',
                }
            },
            timeSettings: {
                itemsToShow: 3.2,
                wrapAround: false,
                snapAlign: "start"
            },
            timeBreakpoints: {
                200: {
                    itemsToShow: 1.1,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                250: {
                    itemsToShow: 1.3,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                300: {
                    itemsToShow: 1.4,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                375: {
                    itemsToShow: 1.7,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                540: {
                    itemsToShow: 2.5,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                700: {
                    itemsToShow: 3.2,
                    wrapAround: false,
                    snapAlign: 'start',
                }
            },
        }
    },
    computed: {
        globalState: function () {
            return this.$store.getters['globalState/lists'];
        },
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        branches: function () {
            return this.$store.getters['frontendBranch/lists'];
        },
        riderTips: function () {
            return this.$store.getters['frontendRiderTip/lists'];
        },
        branch: function () {
            return this.$store.getters['frontendBranch/show'];
        },
        carts: function () {
            return this.$store.getters['frontendCart/lists'];
        },
        subtotal: function () {
            return this.$store.getters['frontendCart/subtotal'];
        },
        todayTimeSlots: function () {
            return this.$store.getters['frontendTimeSlot/today'];
        },
        tomorrowTimeSlots: function () {
            return this.$store.getters['frontendTimeSlot/tomorrow'];
        },
        addresses: function () {
            return this.$store.getters['frontendAddress/lists'];
        },
        orderType: function () {
            return this.$store.getters['frontendCart/orderType'];
        },
        profile: function () {
            return this.$store.getters.authInfo;
        },
        paymentMethod: function () {
            return this.$store.getters['frontendCart/paymentMethod'];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendRiderTip/lists", this.riderTipProps).then(res => {
            this.checkoutProps.form.rider_tip = res.data.data[0].flat_amount
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });

        if (typeof this.$store.getters['frontendCart/paymentMethod'].id !== "undefined") {
            this.gatewayId = this.$store.getters['frontendCart/paymentMethod'].id;
        } else {
            this.gatewayId = paymentTypeEnum.CASH_ON_DELIVERY;

        }

        this.$store.dispatch("frontendSetting/lists").then(res => {
            if ((res.data.data.order_setup_delivery === activityEnum.DISABLE && res.data.data.order_setup_takeaway === activityEnum.DISABLE) || this.$store.getters['frontendCart/lists'].length === 0) {
                this.$router.push({ name: 'frontend.home' });
            }
        }).catch();

        this.loading.isActive = true;
        this.$store.dispatch("frontendAddress/lists", this.addressProps).then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });

        this.checkoutProps.form.branch_id = this.$store.getters['globalState/lists'].branch_id;
        if (this.checkoutProps.form.branch_id > 0) {
            this.timeslot(this.checkoutProps.form.branch_id);
            this.loading.isActive = true;
            this.$store.dispatch("frontendBranch/show", this.checkoutProps.form.branch_id).then(res => {
                this.loading.isActive = false;
                this.location = {
                    lat: res.data.data.latitude,
                    lng: res.data.data.longitude
                };
                this.branchName = res.data.data.name;
                this.branchAddress = res.data.data.address;
            }).catch((err) => {
                this.loading.isActive = false;
            });

            window.setTimeout(() => {
                this.mapShow = true;
                this.placeOrderShow = true;
            }, 3000);
        }

        this.$store.dispatch('frontendPaymentGateway/lists', { status: this.statusEnum.ACTIVE }).then(res => {
            if (res.data.data.length > 0) {
                _.forEach(res.data.data, (gateway) => {
                    if (gateway.slug === "credit") {
                        this.credit = gateway;
                    } else {
                        this.paymentGateways.push(gateway);
                    }
                });
            }
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });

        this.checkoutProps.form.order_type = this.orderType;
    },
    methods: {
        branchPosition: function (e) {
            window.setTimeout(() => {
                if (this.$store.getters['frontendDeliveryAddress/address'].id) {
                    this.deliveryChargeCalculation(this.$store.getters['frontendDeliveryAddress/address']);
                }
            }, 300);
        },
        currencyFormat: function (amount, decimal, currency, position) {
            return appService.currencyFormat(amount, decimal, currency, position);
        },
        editAddress: function () {
            if (typeof this.localAddress === "object" && this.checkoutProps.form.address_id !== null) {
                this.loading.isActive = true;
                this.$store.dispatch("frontendAddress/edit", this.checkoutProps.form.address_id).then((res) => {
                    this.loading.isActive = false;

                    this.addressProps.form.address = this.localAddress.address;
                    this.addressProps.form.apartment = this.localAddress.apartment;
                    this.addressProps.form.latitude = this.localAddress.latitude;
                    this.addressProps.form.longitude = this.localAddress.longitude;
                    this.addressProps.form.label = this.localAddress.label;

                    if (this.addressProps.form.label !== labelEnum.HOME && this.addressProps.form.label !== labelEnum.WORK) {
                        this.addressProps.status = true;
                        this.addressProps.switchLabel = labelEnum.OTHER;
                    } else {
                        this.addressProps.switchLabel = this.localAddress.label;
                    }

                    this.addressProps.isMap = true;
                    appService.modalShow('.address-modal');
                }).catch((err) => {
                    alertService.error(err.response.data.message);
                });
            }
        },
        timeslot: function (branchId) {
            this.$store.dispatch("frontendTimeSlot/today", branchId).then(res => {
                this.checkoutProps.form.delivery_time = null;
                this.checkoutProps.form.is_advance_order = null;

                this.loading.isActive = false;
                if (res.data.data.length > 0) {
                    if (typeof res.data.data[0] !== "undefined") {
                        this.checkoutProps.form.delivery_time = res.data.data[0].time;
                        this.checkoutProps.form.is_advance_order = isAdvanceOrderEnum.NO
                    }
                }
            }).catch((err) => {
                this.loading.isActive = false;
            });

            this.loading.isActive = true;
            this.$store.dispatch("frontendTimeSlot/tomorrow", branchId).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        updateAddress: function (address) {
            this.deliveryChargeCalculation(address);
        },
        changeDayTake: function (id) {
            if (id === dayTakeEnum.TODAY) {
                if (typeof this.todayTimeSlots[0] !== "undefined") {
                    this.checkoutProps.form.delivery_time = this.todayTimeSlots[0].time;
                    this.checkoutProps.form.is_advance_order = isAdvanceOrderEnum.NO;
                } else {
                    this.checkoutProps.form.delivery_time = null;
                    this.checkoutProps.form.is_advance_order = isAdvanceOrderEnum.NO;
                }
            } else if (id === dayTakeEnum.TOMORROW) {
                if (typeof this.tomorrowTimeSlots[0] !== "undefined") {
                    this.checkoutProps.form.delivery_time = this.tomorrowTimeSlots[0].time;
                    this.checkoutProps.form.is_advance_order = isAdvanceOrderEnum.YES;
                } else {
                    this.checkoutProps.form.delivery_time = null;
                    this.checkoutProps.form.is_advance_order = isAdvanceOrderEnum.YES;
                }
            }
        },
        changeAddress: function (e, address) {
            e.preventDefault();
            this.deliveryChargeCalculation(address);
        },
        deliveryChargeCalculation: function (address) {
            if (this.checkoutProps.form.order_type === orderTypeEnum.DELIVERY) {
                this.localAddress = address;
                if ((typeof this.localAddress.latitude !== 'undefined' && this.localAddress.latitude !== '') && (typeof this.localAddress.longitude !== 'undefined' && this.localAddress.longitude !== '') && (typeof this.location.lat !== 'undefined' && this.location.lat !== '') && (typeof this.location.lng !== 'undefined' && this.location.lng !== '')) {
                    const distance = appService.distance(parseFloat(this.localAddress.latitude), parseFloat(this.localAddress.longitude), parseFloat(this.location.lat), parseFloat(this.location.lng));

                    this.$store.dispatch("frontendDeliverySetup/distanceCheck", {
                        branch_id: this.$store.getters['globalState/lists'].branch_id,
                        distance: distance,
                        latitude: this.localAddress.latitude,
                        longitude: this.localAddress.longitude
                    }).then((res) => {
                        if (res.data.data.branch_id) {
                            if (this.subtotal < parseFloat(res.data.data.flat_minimum_order_amount)) {
                                this.localAddress = {};
                                this.checkoutProps.form.address_id = null;
                                this.checkoutProps.form.delivery_charge = 0;
                                alertService.error(this.$t('message.minimum_order', { amount: res.data.data.currency_minimum_order_amount }));
                            } else {
                                this.localAddress = address;
                                this.checkoutProps.form.address_id = address.id;
                                this.checkoutProps.form.delivery_charge = parseFloat(res.data.data.flat_delivery_charge);
                            }
                        } else {
                            this.checkoutProps.form.address_id = address.id;
                            if (distance > this.setting.order_setup_free_delivery_kilometer) {
                                let extraDistance = distance - parseFloat(this.setting.order_setup_free_delivery_kilometer);
                                this.checkoutProps.form.delivery_charge = (extraDistance * parseFloat(this.setting.order_setup_charge_per_kilo) + parseFloat(this.setting.order_setup_basic_delivery_charge));
                            } else {
                                this.checkoutProps.form.delivery_charge = parseFloat(this.setting.order_setup_basic_delivery_charge);
                            }
                        }
                    }).catch((err) => {
                        this.loading.isActive = false;
                        this.localAddress = {};
                        this.checkoutProps.form.address_id = null;
                        this.checkoutProps.form.delivery_charge = 0;
                        alertService.error(err.response.data.message);

                    });

                }
            }
        },
        coupon: function (e) {
            if (Object.keys(e).length !== 0) {
                this.checkoutProps.form.discount = e.convert_discount;
                this.checkoutProps.form.coupon_id = e.id;
            } else {
                this.checkoutProps.form.discount = 0;
                this.checkoutProps.form.coupon_id = null;
            }
        },
        point: function (e) {
            if (Object.keys(e).length !== 0) {
                this.checkoutProps.form.point_discount_amount = e.point_discount_amount;
                this.checkoutProps.form.applied_points = e.applicable_points;
            } else {
                this.checkoutProps.form.point_discount_amount = 0;
                this.checkoutProps.form.applied_points = null;
            }
        },
        getTotal: function () {
            return parseFloat(this.subtotal + +this.checkoutProps.form.rider_tip + this.checkoutProps.form.delivery_charge - this.checkoutProps.form.discount - this.checkoutProps.form.point_discount_amount).toFixed(this.setting.site_digit_after_decimal_point);
        },
        orderSubmit: function () {
            this.loading.isActive = true;
            this.checkoutProps.form.subtotal = this.subtotal;
            this.checkoutProps.form.total = this.getTotal();
            this.checkoutProps.form.items = [];
            _.forEach(this.carts, (item, index) => {
                let item_variations = [];
                if (Object.keys(item.item_variations.variations).length > 0) {
                    _.forEach(item.item_variations.variations, (value, index) => {
                        item_variations.push({
                            "id": value,
                            "item_id": item.item_id,
                            "item_attribute_id": index,
                        });
                    });
                }

                if (Object.keys(item.item_variations.names).length > 0) {
                    let i = 0;
                    _.forEach(item.item_variations.names, (value, index) => {
                        item_variations[i].variation_name = index;
                        item_variations[i].name = value;
                        i++;
                    });
                }

                let item_extras = [];
                if (item.item_extras.extras.length) {
                    _.forEach(item.item_extras.extras, (value) => {
                        item_extras.push({
                            id: value,
                            item_id: item.item_id,
                        });
                    });
                }

                if (item.item_extras.names.length) {
                    let i = 0;
                    _.forEach(item.item_extras.names, (value) => {
                        item_extras[i].name = value;
                        i++;
                    });
                }

                this.checkoutProps.form.items.push({
                    item_id: item.item_id,
                    item_price: item.convert_price,
                    branch_id: this.checkoutProps.form.branch_id,
                    instruction: item.instruction,
                    quantity: item.quantity,
                    discount: item.discount,
                    total_price: item.total,
                    item_variation_total: item.item_variation_total,
                    item_extra_total: item.item_extra_total,
                    item_variations: item_variations,
                    item_extras: item_extras
                });
            });

            // Check if isset point discount . 


            this.checkoutProps.form.items = JSON.stringify(this.checkoutProps.form.items);
            this.$store.dispatch('frontendOrder/save', this.checkoutProps.form).then(orderResponse => {



                this.loading.isActive = false;
                let paymentSlug = Object.keys(this.paymentMethod).length > 0 ? this.paymentMethod.slug : '';
                if (paymentSlug) {
                    window.location.href = env.API_URL + "/payment/" + paymentSlug + "/" + orderResponse.data.data.id + "/pay";
                } else {
                    alertService.error(this.$t('message.payment_method_required'));
                    return false;
                }

                this.mapShow = false;
                this.location.lat = null;
                this.location.lng = null;
                this.branchAddress = null;
                this.localAddress = {};

                this.checkoutProps.form.branch_id = null;
                this.checkoutProps.form.subtotal = null;
                this.checkoutProps.form.discount = 0;
                this.checkoutProps.form.delivery_charge = 0;
                this.checkoutProps.form.delivery_time = null;
                this.checkoutProps.form.total = 0;
                this.checkoutProps.form.order_type = null;
                this.checkoutProps.form.is_advance_order = null;
                this.checkoutProps.form.address_id = null;
                this.checkoutProps.form.coupon_id = null;
                this.checkoutProps.form.items = [];
            }).catch((err) => {
                this.loading.isActive = false;
                if (typeof err.response.data.errors === 'object') {
                    _.forEach(err.response.data.errors, (error) => {
                        alertService.error(error[0]);
                    });
                }
            });
        },
        changeOrderType: function (e) {
            this.checkoutProps.form.order_type = e;
            this.$store.dispatch('frontendCart/updateOrderType', this.checkoutProps.form.order_type).then().catch();
            if (this.checkoutProps.form.order_type === orderTypeEnum.TAKEAWAY) {
                this.checkoutProps.form.delivery_charge = 0;
                this.checkoutProps.form.address_id = null;
                this.localAddress = {};
            }
        },
        selectPaymentMethod: function (paymentMethod) {
            this.gatewayId = paymentMethod.id;
            if (paymentMethod.id === paymentTypeEnum.CASH_ON_DELIVERY) {
                this.checkoutProps.form.rider_tip = 0;
            }
            this.$store.dispatch("frontendCart/paymentMethod", paymentMethod);
        },
        updateRiderTipPercentage(percentage) {
            this.checkoutProps.form.rider_tip_percentage = percentage;
        },
        calculatePercentageTip(percentage) {
            return parseFloat(((parseFloat(this.subtotal) * percentage) / 100).toFixed(2));
        },
    },
    watch: {
        globalState: {
            deep: true,
            handler(global) {
                if (global.branch_id !== "undefined") {
                    this.loading.isActive = true;
                    this.checkoutProps.form.branch_id = global.branch_id;
                    this.$store.dispatch("frontendBranch/show", this.checkoutProps.form.branch_id).then(res => {
                        this.loading.isActive = false;
                        this.location.lat = res.data.data.latitude;
                        this.location.lng = res.data.data.longitude;
                        this.branchAddress = res.data.data.address;
                    }).catch();

                    window.setTimeout(() => {
                        this.mapShow = true;
                    }, 3000);
                }
            }
        },
        subtotal(newVal, oldVal) {
            if (this.$store.getters['frontendDeliveryAddress/address']) {
                this.deliveryChargeCalculation(this.$store.getters['frontendDeliveryAddress/address']);
            }
        },
        orderType: {
            deep: true,
            handler(orderTypeObject) {
                this.checkoutProps.form.order_type = orderTypeObject;
                if (orderTypeObject === orderTypeEnum.TAKEAWAY) {
                    this.checkoutProps.form.delivery_charge = 0;
                    this.checkoutProps.form.address_id = null;
                    this.localAddress = {};
                }
            }
        },
        subtotal: function() {
            if (this.checkoutProps.form.rider_tip_percentage > 0) {
                this.checkoutProps.form.rider_tip = this.calculatePercentageTip(this.checkoutProps.form.rider_tip_percentage);
            }
        }
    }
}
</script>