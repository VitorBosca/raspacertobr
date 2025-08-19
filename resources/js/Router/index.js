import {createWebHistory, createRouter} from "vue-router";
import HomePage from "@/Pages/Home/HomePage.vue";
import {useAuthStore} from "@/Stores/Auth.js";
import CassinoListPage from "@/Pages/Cassino/CassinoListPage.vue";
import RegisterPage from "@/Pages/Auth/RegisterPage.vue";
import WalletPage from "@/Pages/Profile/WalletPage.vue";
import DepositPage from "@/Pages/Profile/DepositPage.vue";
import WithdrawPage from "@/Pages/Profile/WithdrawPage.vue";
import TransactionPage from "@/Pages/Profile/TransactionPage.vue";
import ConditionsReference from "@/Pages/Terms/ConditionsReference.vue";
import ServiceTerms from "@/Pages/Terms/ServiceTerms.vue";
import PrivacyPolicy from "@/Pages/Terms/PrivacyPolicy.vue";
import SupportPage from "@/Pages/Home/SupportPage.vue";
import SupportCenterPage from "@/Pages/Home/SupportCenterPage.vue";
import DataPage from "@/Pages/ApiData/DataPage.vue";
import RecentsPage from "@/Pages/Profile/RecentsPage.vue";
import EventsPage from "@/Pages/Home/EventsPage.vue";
import CasinoPlayPage from "@/Pages/Cassino/CasinoPlayPage.vue";
import ForgotPassword from "@/Pages/Auth/ForgotPassword.vue";
import ResetPassword from "@/Pages/Auth/ResetPassword.vue";
import AffiliatePage from "@/Pages/Profile/AffiliatePage.vue";
import RaspadinhasPage from "@/Pages/Home/RaspadinhasPage.vue";
import GanhouPage from "@/Pages/Home/GanhouPage.vue";

export const routes = [
    {
        name: 'home',
        path: '/:action?',
        component: HomePage
    },
    {
        name: 'forgotPassword',
        path: '/forgot-password',
        component: ForgotPassword
    },
    {
        name: 'resetPassword',
        path: '/reset-password/:token',
        component: ResetPassword
    },
    {
        name: 'support',
        path: '/support',
        component: SupportPage
    },
    {
        name: 'supportCenter',
        path: '/support-center',
        component: SupportCenterPage
    },
    {
        name: 'register',
        path: '/register/:code?',
        component: RegisterPage
    },
    {
        name: 'casinos',
        path: '/casinos',
        component: HomePage
    },
    {
        name: 'casinoPlayPage',
        path: '/games/play/:id/:slug',
        component: CasinoPlayPage,
    },
    {
        name: 'casinosAll',
        path: '/casino/provider/:provider?/category/:category?',
        component: CassinoListPage
    },
    {
        name: 'profileWallet',
        path: '/profile/wallet',
        component: WalletPage,
        meta: {
            auth: true
        }
    },
    {
        name: 'recentsPage',
        path: '/profile/recents',
        component: RecentsPage,
        meta: {
            auth: true
        }
    },
    {
        name: 'profileDeposit',
        path: '/profile/deposit',
        component: DepositPage,
        meta: {
            auth: true
        }
    },
    {
        name: 'profileWithdraw',
        path: '/profile/withdraw',
        component: WithdrawPage,
        meta: {
            auth: true
        }
    },
    {
        name: 'profileTransactions',
        path: '/profile/transactions',
        component: TransactionPage,
        meta: {
            auth: true
        }
    },
    {
        name: 'termsConditionsReference',
        path: '/terms/conditions-reference',
        component: ConditionsReference
    },
    {
        name: 'serviceTerms',
        path: '/terms/service',
        component: ServiceTerms
    },
    {
        name: 'privacyPolicy',
        path: '/terms/privacy-policy',
        component: PrivacyPolicy
    },
    {
        name: 'dataPage',
        path: '/datapage',
        component: DataPage
    },
    {
        name: 'eventsPage',
        path: '/events',
        component: EventsPage,
    },
    {
        name: 'indiquePage',
        path: '/indique',
        component: AffiliatePage,
    },
    {
        name: 'raspadinhasPage',
        path: '/raspadinhas',
        component: RaspadinhasPage
    },
    {
        name: 'ganhouPage',
        path: '/ganhou',
        component: GanhouPage
    }
];

const router = createRouter({
    history: createWebHistory(import.meta.env.VITE_BASE_URL || '/'),
    routes: routes,
});

router.beforeEach(async (to, from, next) => {
    if(to.meta?.auth) {
        const auth = useAuthStore();
        auth.isAuth ? next() : next({ name: 'home' });
    }else{
        next();
    }
});

export default router;
