import OfferComponent from "../../components/admin/offers/OfferComponent.vue";
import OfferListComponent from "../../components/admin/offers/OfferListComponent.vue";
import OfferShowComponent from "../../components/admin/offers/OfferShowComponent.vue";

export default [
    {
        path: '/admin/offers',
        component: OfferComponent,
        name: 'admin.offers',
        redirect: {name: 'admin.offers.list'},
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'offers',
            breadcrumb: 'offers'
        },
        children: [
            {
                path: '',
                component: OfferListComponent,
                name: 'admin.offers.list',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'offers',
                    breadcrumb: ''
                },
            },
            {
                path: "show/:id",
                component: OfferShowComponent,
                name: "admin.offer.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "offers",
                    breadcrumb: "view",
                },
            },
        ]
    }
]
