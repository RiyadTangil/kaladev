import CopyItemListComponent from "../../components/admin/items/CopyItemListComponent.vue";

export default [
    {
        path: '/admin/copy-items',
        component:CopyItemListComponent , 
        name: 'admin.copy.items',
        meta: {
            isFrontend: false,
            auth:true,
            permissionUrl: 'copy-items',
            breadcrumb: 'copy_items'
        },
        children: [
            {
                path: '',
                component: CopyItemListComponent,
                name: 'admin.copy.items.list',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'copy-items',
                    breadcrumb: ''
                },
            },
        ],
    }
]
