import TableOrderComponent from "../../components/admin/tableOrders/TableOrderComponent.vue";
import TableOrderListComponent from "../../components/admin/tableOrders/TableOrderListComponent.vue";
import TableOrderShowComponent from "../../components/admin/tableOrders/TableOrderShowComponent.vue";

export default [
    {
        path: '/admin/table-orders',
        component: TableOrderComponent,
        name: 'admin.table.order',
        redirect: {name: 'admin.table.order.list'},
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'table-orders',
            breadcrumb: 'table_orders'
        },
        children: [
            {
                path: '',
                component: TableOrderListComponent,
                name: 'admin.table.order.list',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'table-orders',
                    breadcrumb: ''
                },
            },
            {
                path: "show/:id",
                component: TableOrderShowComponent,
                name: "admin.table.order.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "table-orders",
                    breadcrumb: "view",
                },
            }
        ]
    }
]
