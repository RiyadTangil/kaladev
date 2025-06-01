import LoginComponent from "../../components/frontend/auth/LoginComponent.vue";
import ForgetPasswordComponent from "../../components/frontend/auth/ForgetPasswordComponent.vue";
import GuestLoginComponent from "../../components/frontend/auth/GuestLoginComponent.vue";
import VerifyEmailComponent from "../../components/frontend/auth/VerifyEmailComponent.vue";
import ResetPasswordComponent from "../../components/frontend/auth/ResetPasswordComponent.vue";
import GuestVerifyComponent from "../../components/frontend/auth/GuestVerifyComponent.vue";
import SignupPhoneComponent from "../../components/frontend/auth/SignupPhoneComponent.vue";
import SignupVerifyComponent from "../../components/frontend/auth/SignupVerifyComponent.vue";
import SignupRegisterComponent from "../../components/frontend/auth/SignupRegisterComponent.vue";

export default [
    {
        path: '/login',
        component: LoginComponent,
        name: 'auth.login',
        meta: {
            isFrontend: true,
            auth: false
        }
    },
    {
        path: '/forget-password',
        component: ForgetPasswordComponent,
        name: 'auth.forgetPassword',
        meta: {
            isFrontend: true,
            auth: false
        }
    },
    {
        path: '/forget-password/verify',
        name: 'auth.verifyEmail',
        component: VerifyEmailComponent,
        meta: {
            isFrontend: true,
            auth: false
        }
    },
    {
        path: '/forget-password/reset-password',
        name: 'auth.resetPassword',
        component: ResetPasswordComponent,
        meta: {
            isFrontend: true,
            auth: false
        }
    },
    {
        path: '/signup',
        component: SignupPhoneComponent,
        name: 'auth.signupPhone',
        meta: {
            isFrontend: true,
            auth: false
        }
    },
    {
        path: '/signup/verify',
        component: SignupVerifyComponent,
        name: 'auth.signupVerify',
        meta: {
            isFrontend: true,
            auth: false
        }
    },
    {
        path: '/signup/register',
        component: SignupRegisterComponent,
        name: 'auth.signupRegister',
        meta: {
            isFrontend: true,
            auth: false
        }
    },
    {
        path: '/guest-login',
        component: GuestLoginComponent,
        name: 'auth.guestLogin',
        meta: {
            isFrontend: true,
            auth: false
        }
    },
    {
        path: '/guest-login/verify',
        component: GuestVerifyComponent,
        name: 'auth.guestLoginVerify',
        meta: {
            isFrontend: true,
            auth: false
        }
    }
];
