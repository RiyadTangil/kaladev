import { createStore } from "vuex";

import createPersistedState from "vuex-persistedstate";
import { auth } from "./modules/auth.js";
import { company } from "./modules/company.js";
import { itemCategory } from "./modules/itemCategory.js";
import { itemAttribute } from "./modules/itemAttribute.js";
import { slider } from "./modules/slider.js";
import { branch } from "./modules/branch.js";
import { offer } from "./modules/offer.js";
import { item } from "./modules/item.js";
import { itemVariation } from "./modules/itemVariation.js";
import { onlineOrder } from "./modules/onlineOrder.js";
import { tax } from "./modules/tax.js";
import { currency } from "./modules/currency.js";
import { mail } from "./modules/mail.js";
import { menuSection } from "./modules/menuSection.js";
import { page } from "./modules/page.js";
import { notification } from "./modules/notification.js";
import { pushNotification } from "./modules/pushNotification.js";
import { menuTemplate } from "./modules/menuTemplate.js";
import { coupon } from "./modules/coupon.js";
import { customer } from "./modules/customer.js";
import { otp } from "./modules/otp.js";
import { administrator } from "./modules/administrator.js";
import { deliveryBoy } from "./modules/deliveryBoy.js";
import { deliveryBoyAddress } from "./modules/deliveryBoyAddress.js";
import { defaultAccess } from "./modules/defaultAccess.js";
import { administratorAddress } from "./modules/administratorAddress.js";
import { customerAddress } from "./modules/customerAddress.js";
import { socialMedia } from "./modules/socialMedia.js";
import { license } from "./modules/license.js";
import { analytic } from "./modules/analytic.js";
import { analyticSection } from "./modules/analyticSection.js";
import { role } from "./modules/role.js";
import { permission } from "./modules/permission.js";
import { cookies } from './modules/cookies';
import { theme } from './modules/theme';
import { timeSlot } from './modules/timeSlot';
import { employee } from './modules/employee';
import { employeeAddress } from './modules/employeeAddress';
import { itemExtra } from './modules/itemExtra';
import { itemAddon } from './modules/itemAddon';
import { language } from './modules/language';
import { frontendBranch } from "./modules/frontend/frontendBranch.js";
import { frontendLanguage } from "./modules/frontend/frontendLanguage.js";
import { frontendSetting } from "./modules/frontend/frontendSetting.js";
import { frontendCompany } from "./modules/frontend/frontendCompany.js";
import { frontendPage } from "./modules/frontend/frontendPage.js";
import { globalState } from "./modules/frontend/globalState.js";
import { frontendSlider } from "./modules/frontend/frontendSlider.js";
import { frontendItemCategory } from "./modules/frontend/frontendItemCategory.js";
import { timezone } from './modules/timezone';
import { site } from './modules/site';
import { dashboard } from './modules/dashboard';
import { orderSetup } from './modules/orderSetup';
import { offerItem } from './modules/offerItem';
import { paymentGateway } from './modules/paymentGateway';
import { smsGateway } from './modules/smsGateway';
import { salesReport } from './modules/salesReport';
import { frontendCart } from "./modules/frontend/frontendCart.js";
import { itemsReport } from './modules/itemsReport';
import { frontendEditProfile } from './modules/frontend/frontendEditProfile';
import { frontendCountryCode } from './modules/frontend/frontendCountryCode';
import { frontendAddress } from './modules/frontend/frontendAddress';
import { message } from './modules/message';
import { diningTable } from "./modules/diningTable.js";
import { frontendTimeSlot } from "./modules/frontend/frontendTimeSlot.js";
import { frontendItem } from "./modules/frontend/frontendItem.js";
import { frontendOffer } from './modules/frontend/frontendOffer';
import { frontendCoupon } from "./modules/frontend/frontendCoupon.js";
import { countryCode } from './modules/countryCode';
import { frontendOrder } from "./modules/frontend/frontendOrder.js";
import { frontendSignup } from "./modules/frontend/frontendSignup.js";
import { GuestSignup } from "./modules/frontend/GuestSignup.js";
import { backendGlobalState } from "./modules/backendGlobalState.js";
import { myOrderDetails } from './modules/myOrderDetails';
import { posCart } from './modules/posCart';
import { posOrder } from './modules/posOrder';
import { transaction } from './modules/transaction';
import { notificationAlert } from './modules/notificationAlert';
import { creditBalanceReport } from './modules/creditBalanceReport';
import { pointBalanceReport } from './modules/pointBalanceReport';
import { deliveryBoyOrder } from './modules/deliveryBoyOrder';
import { user } from './modules/user';
import { frontendMessage } from "./modules/frontend/frontendMessage.js";
import { posCategory } from './modules/posCategory';
import { tableItemCategory } from "./modules/table/tableItemCategory.js";
import { tableCart } from "./modules/table/tableCart.js";
import { tableDiningTable } from "./modules/table/tableDiningTable.js";
import { tableDiningOrder } from "./modules/table/tableDiningOrder.js";
import { tableOrder } from './modules/tableOrder';
import { subscriber } from './modules/subscriber';
import { pointSetup } from './modules/pointSetup';
import { frontendPoint } from './modules/frontend/frontendPoint';
import { riderTip } from './modules/riderTip';
import { frontendPaymentGateway } from './modules/frontend/frontendPaymentGateway';
import { deliverySetup } from './modules/deliverySetup';
import { frontendRiderTip } from './modules/frontend/frontendRiderTip';
import { frontendDeliverySetup } from './modules/frontend/frontendDeliverySetup';
import { tableItem } from "./modules/table/tableItem.js";
import { frontendDeliveryAddress } from './modules/frontend/frontendDeliveryAddress';


export default new createStore({
    state: {},
    mutations: {},
    actions: {},
    modules: {
        auth,
        company,
        itemCategory,
        itemAttribute,
        slider,
        branch,
        offer,
        item,
        itemVariation,
        tax,
        currency,
        mail,
        pushNotification,
        notification,
        page,
        onlineOrder,
        menuSection,
        menuTemplate,
        coupon,
        customer,
        customerAddress,
        otp,
        administrator,
        deliveryBoy,
        deliveryBoyAddress,
        defaultAccess,
        administratorAddress,
        socialMedia,
        license,
        analytic,
        analyticSection,
        role,
        permission,
        cookies,
        theme,
        timeSlot,
        employee,
        employeeAddress,
        itemExtra,
        itemAddon,
        language,
        globalState,
        frontendBranch,
        frontendLanguage,
        frontendSetting,
        frontendCompany,
        frontendPage,
        frontendSlider,
        frontendItemCategory,
        frontendCart,
        timezone,
        site,
        dashboard,
        orderSetup,
        offerItem,
        paymentGateway,
        smsGateway,
        salesReport,
        itemsReport,
        frontendEditProfile,
        frontendCountryCode,
        frontendAddress,
        message,
        frontendTimeSlot,
        frontendItem,
        frontendOffer,
        frontendCoupon,
        countryCode,
        frontendOrder,
        frontendSignup,
        GuestSignup,
        backendGlobalState,
        myOrderDetails,
        posCart,
        posOrder,
        transaction,
        notificationAlert,
        creditBalanceReport,
        pointBalanceReport,
        deliveryBoyOrder,
        user,
        frontendMessage,
        posCategory,
        diningTable,
        tableItemCategory,
        tableCart,
        tableDiningTable,
        tableDiningOrder,
        tableOrder,
        subscriber,
        pointSetup,
        frontendPoint,
        riderTip,
        frontendPaymentGateway,
        deliverySetup,
        frontendRiderTip,
        frontendDeliverySetup,
        tableItem,
        frontendDeliveryAddress
    },
    plugins: [
        createPersistedState({
            paths: ["auth", "globalState", "frontendCart", "frontendSignup", "GuestSignup", "posCart", "tableCart", 'frontendDeliveryAddress'],
        }),
    ],
});
