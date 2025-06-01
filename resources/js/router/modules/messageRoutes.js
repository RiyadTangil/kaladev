import MessageComponent from "../../components/admin/messages/MessageComponent.vue";
import MessageListComponent from "../../components/admin/messages/MessageListComponent.vue";

export default [
    {
        path: "/admin/messages",
        component: MessageComponent,
        name: "admin.messages",
        redirect: { name: "admin.messages.list" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "messages",
            breadcrumb: "messages",
        },
        children: [
            {
                path: "",
                component: MessageListComponent,
                name: "admin.messages.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "messages",
                    breadcrumb: "",
                },
            },
        ],
    },
];
