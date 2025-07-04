import HomeComponent from "../../components/frontend/home/HomeComponent.vue";
import MenuComponent from "../../components/frontend/menu/MenuComponent.vue";
import OffersComponent from "../../components/frontend/offers/OffersComponent.vue";
import OffersItemComponent from "../../components/frontend/offers/OffersItemComponent.vue";
import PageComponent from "../../components/frontend/page/PageComponent.vue";
import EditProfileComponent from "../../components/frontend/account/editProfile/EditProfileComponent.vue";
import MyOrderComponent from "../../components/frontend/account/myOrder/MyOrderComponent.vue";
import OrderDetailsComponent from "../../components/frontend/account/myOrder/OrderDetailsComponent.vue";
import ChatComponent from "../../components/frontend/account/chat/ChatComponent.vue";
import AddressComponent from "../../components/frontend/account/address/AddressComponent.vue";
import ChangePasswordComponent from "../../components/frontend/account/changePassword/ChangePasswordComponent.vue";
import CheckoutComponent from "../../components/frontend/checkout/CheckoutComponent.vue";
import SearchItemComponent from "../../components/frontend/search/SearchItemComponent.vue";
import LandingPage from "../../components/frontend/landing/LandingPage.vue";

export default [
    {
        path: "/home",
        component: HomeComponent,
        name: "frontend.home",
        meta: {
            isFrontend: true,
            auth: false,
        },
    },
    {
        path: "/menu",
        component: MenuComponent,
        name: "frontend.menu",
        meta: {
            isFrontend: true,
            auth: false,
        },
    },
    {
        path: "/offers",
        component: OffersComponent,
        name: "frontend.offers",
        meta: {
            isFrontend: true,
            auth: false,
        },
    },
    {
        path: "/offers/:slug",
        component: OffersItemComponent,
        name: "frontend.offers.item",
        meta: {
            isFrontend: true,
            auth: false,
        },
    },
    {
        path: "/page/:slug",
        component: PageComponent,
        name: "frontend.page",
        meta: {
            isFrontend: true,
            auth: false,
        },
    },
    {
        path: "/edit-profile",
        component: EditProfileComponent,
        name: "frontend.editProfile",
        meta: {
            isFrontend: true,
            auth: true,
        },
    },
    {
        path: "/my-orders",
        component: MyOrderComponent,
        name: "frontend.myOrder",
        meta: {
            isFrontend: true,
            auth: true,
        },
    },
    {
        path: "/my-orders/:id",
        component: OrderDetailsComponent,
        name: "frontend.myOrder.details",
        meta: {
            isFrontend: true,
            auth: true,
        },
    },
    {
        path: "/chat",
        component: ChatComponent,
        name: "frontend.chat",
        meta: {
            isFrontend: true,
            auth: true,
            permissionUrl: "messages"
        },
    },
    {
        path: "/address",
        component: AddressComponent,
        name: "frontend.address",
        meta: {
            isFrontend: true,
            auth: true,
        },
    },
    {
        path: "/change-password",
        component: ChangePasswordComponent,
        name: "frontend.changePassword",
        meta: {
            isFrontend: true,
            auth: true,
        },
    },
    {
        path: "/checkout",
        component: CheckoutComponent,
        name: "frontend.checkout",
        meta: {
            isFrontend: true,
            auth: true,
        },
    },
    {
        path: "/search",
        component: SearchItemComponent,
        name: "frontend.search",
        meta: {
            isFrontend: true,
            auth: false,
        },
    },
    {
        path: '/landing',
        component: LandingPage,
        name: 'frontend.landing',
        meta: {
            isFrontend: true,
            auth: false
        }
    },
];
