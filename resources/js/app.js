
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('./bootstrap');

import Vue from 'vue'

import { BootstrapVue, BootstrapVueIcons, TooltipPlugin } from 'bootstrap-vue'

Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)
Vue.use(TooltipPlugin)

import { VMoney } from 'v-money'

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import VueResource from 'vue-resource'
Vue.use(VueResource);

import Toaster from 'v-toaster'
Vue.use(Toaster, { timeout: 5000 })

import Modal from './plugins/Modal.js'
Vue.use(Modal);

import NProgress from 'nprogress'

import App from './views/App'
import Dashboard from './views/Dashboard'
import Transactions from './views/Transactions'
import ClientDashboard from './views/ClientDashboard'
import Settings from './views/Settings'
import PageNotFound from './views/PageNotFound'
import Transaction from './views/Transaction'
import TransactionClient from './views/TransactionClient'

import Users from './views/Users'
import UserNew from './views/UserNew'
import UserEdit from './views/UserEdit'
import MyProfile from './views/MyProfile'
import MyOrders from './views/MyOrders'
import MyOrder from './views/MyOrder'

import Calendar from './views/Calendar'

import Orders from './views/Orders'
import CertifiedFumigationOrders from './views/CertifiedFumigationOrders'
import CertifiedQualityOrders from './views/CertifiedQualityOrders'
import CertifiedSecurityOrders from './views/CertifiedSecurityOrders'
import CertifiedWeightOrders from './views/CertifiedWeightOrders'
import InspectionAgencyOrders from './views/InspectionAgencyOrders'
import ForwardingAgentOrders from './views/ForwardingAgentOrders'
import TerminalOrders from './views/TerminalOrders'
import RailwayOrders from './views/RailwayOrders'
import OrderNew from './views/OrderNew'
import OrderEdit from './views/OrderEdit'
import Order from './views/Order'
import OrderView from './views/OrderView'
import OrderInfo from './views/OrderInfo'
import OrderDetails from './views/OrderDetails'
import OrdersRailway from './views/OrdersRailway'
import OrderContainers from './views/OrderContainers'
import OrderReports from './views/OrderReports'
import MyOrderInfo from './views/MyOrderInfo'
import OrderInfoBuilder from './views/OrderInfoBuilder'
import OrderInfoFumigation from './views/OrderInfoFumigation'
import OrderInfoForwardingAgent from './views/OrderInfoForwardingAgent'
import OrderInfoTerminal from './views/OrderInfoTerminal'
import OrderInfoRailway from './views/OrderInfoRailway'
import OrderInfoInspectionAgency from './views/OrderInfoInspectionAgency'
import OrderInfoInsuranceAgency from './views/OrderInfoInsuranceAgency'
import Report from './views/Report'
import DocumentOrders from './views/DocumentOrders'
import DocumentOrdersNew from './views/DocumentOrdersNew'
import DocumentOrdersEdit from './views/DocumentOrdersEdit'
import Specifications from './views/Specifications'
import SpecificationsNew from './views/SpecificationsNew'
import SpecificationsEdit from './views/SpecificationsEdit'
import Banks from './views/Banks'
import BanksNew from './views/BanksNew'
import BanksEdit from './views/BanksEdit'
import Drivers from './views/Drivers'
import DriversNew from './views/DriversNew'
import DriversEdit from './views/DriversEdit'

import Signatures from './views/Signatures'
import SignaturesNew from './views/SignaturesNew'
import SignaturesEdit from './views/SignaturesEdit'

import Groups from './views/Groups'
import GroupsNew from './views/GroupsNew'
import GroupsEdit from './views/GroupsEdit'

import Services from './views/Services'
import ServicesNew from './views/ServicesNew'
import ServicesEdit from './views/ServicesEdit'

import Contracts from './views/contracts/Contracts'
import ContractsNew from './views/contracts/ContractsNew'
import ContractsEdit from './views/contracts/ContractsEdit'
import ContractsReport from './views/contracts/ContractsReport';

import Provisions from './views/provisions/Provisions';
import ProvisionsPostingsNew from './views/provisions/ProvisionsPostingsNew';


import ReportDre from './views/reports/DreOperations';
import ReportInvoices from './views/reports/Invoices';

import Drafts from './views/Drafts'
import DraftEdit from './views/DraftEdit'

import PopcornHeader from './components/PopcornHeader'
import PopcornFooter from './components/PopcornFooter'
import PopcornBreadcrumb from './components/PopcornBreadcrumb'
import PopcornNotification from './components/PopcornNotification'
import PopcornUpload from './components/PopcornUpload'
import PopcornUploadNew from './components/PopcornUploadNew'
import PopcornTasks from './components/PopcornTasks'
import PopcornMessages from './components/PopcornMessages'
import PopcornPagination from './components/PopcornPagination'
import PopcornShipping from './components/PopcornShipping'
import PopcornShippingView from './components/PopcornShippingView'
import PopcornShippingViewV2 from './components/PopcornShippingViewV2'
import PopcornDraftDocuments from './components/PopcornDraftDocuments'
import PopcornDraftDocumentsView from './components/PopcornDraftDocumentsView'
import PopcornDraftForApprove from './components/PopcornDraftForApprove'
import PopcornRailroad from './components/PopcornRailroad'
import PopcornRailroadView from './components/PopcornRailroadView'
import PopcornOriginalDocuments from './components/PopcornOriginalDocuments'
import PopcornOriginalDocumentsView from './components/PopcornOriginalDocumentsView'
import PopcornLoading from './components/PopcornLoading'
import PopcornLoadingView from './components/PopcornLoadingView'
import PopcornContainerStuffing from './components/PopcornContainerStuffing'
import PopcornContainerStuffingV2 from './components/PopcornContainerStuffingV2'
import PopcornContainerStuffingTerminal from './components/PopcornContainerStuffingTerminal'
import PopcornContainerStuffingView from './components/PopcornContainerStuffingView'
import PopcornContainerStuffingViewFumigation from './components/PopcornContainerStuffingViewFumigation'
import PopcornInspectionAgency from './components/PopcornInspectionAgency'
import PopcornInspectionAgencyV2 from './components/PopcornInspectionAgencyV2'
import PopcornInspectionAgencyView from './components/PopcornInspectionAgencyView'
import PopcornInsuranceAgency from './components/PopcornInsuranceAgency'
import PopcornInsuranceAgencyV2 from './components/PopcornInsuranceAgencyV2'
import PopcornMapa from './components/PopcornMapa'
import PopcornMapaView from './components/PopcornMapaView'
import PopcornMapaViewV2 from './components/PopcornMapaViewV2'
import PopcornFumigation from './components/PopcornFumigation'
import PopcornFumigationView from './components/PopcornFumigationView'
import PopcornTransaction from './components/PopcornTransaction'
import PopcornTraking from './components/PopcornTraking'
import PopcornTrakingClient from './components/PopcornTrakingClient'
import PopcornExportationDocs from './components/PopcornExportationDocs'
import SeaRates from './components/SeaRates'

import ProductsModal from './components/ProductsModal';
import ProvidersModal from './components/ProvidersModal';
import DinamicModal from './components/DinamicModal';
import TruckModal from './components/TruckModal';
import EtdModal from './components/EtdModal';

import ChangeTheme from './components/ChangeTheme';

import NoteModal from './components/NoteModal';
import AssignContractOrderModal from './components/AssignContractOrderModal';

import HelpTips from './components/HelpTips';

import PopcornContainerExtraFields from './components/PopcornContainerExtraFields'
import DRHistory from './components/DRHistory'

import VueSelect2 from './components/VueSelect2'
import Select2 from 'v-select2-component';
import Multiselect from 'vue-multiselect';

import IconInput from './components/Fields/IconInput'
import SelectIcon from './components/Fields/SelectIcon'
import InputIcon from './components/Fields/InputIcon'
import TextareaIcon from './components/Fields/TextareaIcon'


import PopcornAdminSidebar from './components/PopcornAdminSidebar'
import PopcornClientSidebar from './components/PopcornClientSidebar'
import PopcornCertifiedSidebar from './components/PopcornCertifiedSidebar'
import Containers from './components/ContainersDisplay'
import ContainersDisplay from './components/ContainersDisplay'
import RegisterInitialBalance from './components/RegisterInitialBalance'

import * as VueGoogleMaps from 'vue2-google-maps'
import { createRouter, createWebHistory } from 'vue-router';

Vue.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyBj0M2HsY6cSqT3jiudlmjx5Pyl-9pjatQ',
        libraries: 'places', // This is required if you use the Autocomplete plugin
        // OR: libraries: 'places,drawing'
        // OR: libraries: 'places,drawing,visualization'
        // (as you require)

        //// If you want to set the version, you can do so:
        // v: '3.26',
    },

    //// If you intend to programmatically custom event listener code
    //// (e.g. `this.$refs.gmap.$on('zoom_changed', someFunc)`)
    //// instead of going through Vue templates (e.g. `<GmapMap @zoom_changed="someFunc">`)
    //// you might need to turn this on.
    // autobindAllEvents: false,

    //// If you want to manually install components, e.g.
    //// import {GmapMarker} from 'vue2-google-maps/src/components/marker'
    //// Vue.component('GmapMarker', GmapMarker)
    //// then disable the following:
    // installComponents: true,
})


import 'v-toaster/dist/v-toaster.css'
import 'nprogress/nprogress.css'
import 'vue-multiselect/dist/vue-multiselect.min.css'


Vue.component('popcorn-header', PopcornHeader)
Vue.component('popcorn-footer', PopcornFooter)
Vue.component('popcorn-breadcrumb', PopcornBreadcrumb)
Vue.component('popcorn-notification', PopcornNotification)
Vue.component('popcorn-upload', PopcornUpload)
Vue.component('popcorn-upload-new', PopcornUploadNew)
Vue.component('popcorn-tasks', PopcornTasks)
Vue.component('popcorn-messages', PopcornMessages)
Vue.component('popcorn-pagination', PopcornPagination)
Vue.component('popcorn-shipping', PopcornShipping)
Vue.component('popcorn-shipping-view', PopcornShippingView)
Vue.component('popcorn-shipping-view-v2', PopcornShippingViewV2)
Vue.component('popcorn-draft-documents', PopcornDraftDocuments)
Vue.component('popcorn-draft-documents-view', PopcornDraftDocumentsView)
Vue.component('popcorn-draft-for-aprove', PopcornDraftForApprove)
Vue.component('popcorn-railroad', PopcornRailroad)
Vue.component('popcorn-railroad-view', PopcornRailroadView)
Vue.component('popcorn-original-documents', PopcornOriginalDocuments)
Vue.component('popcorn-original-documents-view', PopcornOriginalDocumentsView)
Vue.component('popcorn-loading', PopcornLoading)
Vue.component('popcorn-loading-view', PopcornLoadingView)
Vue.component('popcorn-container-stuffing', PopcornContainerStuffing)
Vue.component('popcorn-container-stuffing-v2', PopcornContainerStuffingV2)
Vue.component('popcorn-container-stuffing-terminal', PopcornContainerStuffingTerminal)
Vue.component('popcorn-container-stuffing-view', PopcornContainerStuffingView)
Vue.component('popcorn-container-stuffing-view-fumigation', PopcornContainerStuffingViewFumigation)
Vue.component('popcorn-inspection-agency', PopcornInspectionAgency)
Vue.component('popcorn-inspection-agency-v2', PopcornInspectionAgencyV2)
Vue.component('popcorn-inspection-agency-view', PopcornInspectionAgencyView)
Vue.component('popcorn-insurance-agency', PopcornInsuranceAgency)
Vue.component('popcorn-insurance-agency-v2', PopcornInsuranceAgencyV2)
Vue.component('popcorn-mapa', PopcornMapa)
Vue.component('popcorn-mapa-view', PopcornMapaView)
Vue.component('popcorn-mapa-view-v2', PopcornMapaViewV2)
Vue.component('popcorn-fumigation', PopcornFumigation)
Vue.component('popcorn-fumigation-view', PopcornFumigationView)
Vue.component('popcorn-transaction', PopcornTransaction)
Vue.component('popcorn-traking', PopcornTraking)
Vue.component('popcorn-traking-client', PopcornTrakingClient)
Vue.component('popcorn-exportation-docs', PopcornExportationDocs)
Vue.component('sea-rates', SeaRates)

Vue.component('products-modal', ProductsModal)
Vue.component('providers-modal', ProvidersModal)
Vue.component('dinamic-modal', DinamicModal)

Vue.component('truck-modal', TruckModal)
Vue.component('etd-modal', EtdModal)

Vue.component('change-theme', ChangeTheme)

Vue.component('note-modal', NoteModal)
Vue.component('assign-contract-order-modal', AssignContractOrderModal)

Vue.component('help-tips', HelpTips)

Vue.component('popcorn-modal-container-extra-field', PopcornContainerExtraFields)
Vue.component('dr-history', DRHistory)

Vue.component('vue-select2', VueSelect2)
Vue.component('Select2', Select2)
Vue.component('multiselect', Multiselect)

Vue.component('iconinput', IconInput)
Vue.component('select-icon', SelectIcon)
Vue.component('input-icon', InputIcon)
Vue.component('textarea-icon', TextareaIcon)
Vue.component('containers-display', ContainersDisplay)
Vue.component('register-initial-balance', RegisterInitialBalance)

NProgress.configure({
    showSpinner: false
})

Vue.http.interceptors.push((request, next) => {
    NProgress.start()
    request.headers.set('Authorization', window.api_token)
    request.headers.set('AppVersion', window.app_version)

    let url = window.location;

    next((response) => {
        NProgress.done()
        if (response.status == 500) {
            Vue.$modal.danger({ title: 'Oh, no!', text: `There was an error with our server.<br>Please wait a moment and try again.<br><br>${response.body.message}<br>${url}` });
        }
        if (response.status == 401) {
            window.location.href = "/";
        }
        if (response.status == 426) {
            window.location.reload(true);
        }
    })
})

window.adminRoutes = [
    {
        path: '/dashboard',
        name: 'Dashboard',
        components: {
            default: Dashboard,
            sidebar: PopcornAdminSidebar
        }
    },
    {
        path: '/calendar',
        name: 'Calendar',
        components: {
            default: Calendar,
            sidebar: PopcornAdminSidebar
        }
    },
    {
        path: '/transactions',
        name: 'Transactions',
        components: {
            default: Transactions,
            sidebar: PopcornAdminSidebar
        }
    },
    {
        path: '/transactions/report',
        name: 'Transactions Report',
        components: {
            default: Report,
            sidebar: PopcornAdminSidebar
        }
    },
    {
        path: '/transaction/:id',
        name: 'Transaction',
        components: {
            default: Transaction,
            sidebar: PopcornAdminSidebar
        }
    },
    {
        path: '/settings',
        name: 'Settings',
        components: {
            default: Settings,
            sidebar: PopcornAdminSidebar
        }
    },
    {
        path: '/users',
        name: 'Users',
        components: {
            default: Users,
            sidebar: PopcornAdminSidebar
        }
    },
    {
        path: '/user/new',
        name: 'New user',
        components: {
            default: UserNew,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Users'
            ]
        }
    },
    {
        path: '/user/edit/:id',
        name: 'Edit user',
        components: {
            default: UserEdit,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Users'
            ]
        }
    },
    {
        path: '/user/me',
        name: 'My profile',
        components: {
            default: MyProfile,
            sidebar: PopcornAdminSidebar
        },
    },
    {
        path: '/orders',
        name: 'Orders',
        components: {
            default: Orders,
            sidebar: PopcornAdminSidebar
        },
        props: {
            default: true // Pode ser qualquer valor
        }
    },
    {
        path: '/order/new',
        name: 'New order',
        components: {
            default: Order,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Orders'
            ]
        },
        props: {
            default: true // Pode ser qualquer valor
        }
    },
    {
        path: '/order/edit/:id',
        name: 'Edit order',
        components: {
            default: Order,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Orders'
            ]
        },
        props: {
            default: true // Pode ser qualquer valor
        }
    },
    {
        path: '/order/copy/:id',
        name: 'Copy order',
        components: {
            default: Order,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Orders'
            ]
        },
        props: {
            default: true // Pode ser qualquer valor
        }
    },
    {
        path: '/order/details',
        name: 'Details Orders',
        components: {
            default: OrderDetails,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Orders'
            ]
        }
    },
    {
        path: '/order/railway',
        name: 'Railway Orders',
        components: {
            default: OrdersRailway,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Orders'
            ]
        }
    },
    {
        path: '/order/containers',
        name: 'Order Containers',
        components: {
            default: OrderContainers,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Orders'
            ]
        }
    },
    {
        path: '/order/reports',
        name: 'Order Reports',
        components: {
            default: OrderReports,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Orders'
            ]
        }
    },
    {
        path: '/order/view/:id',
        name: 'View order',
        components: {
            default: OrderView,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Orders'
            ]
        }
    },
    {
        path: '/order/info/:id',
        name: 'Order information',
        components: {
            default: OrderInfo,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Orders'
            ]
        }
    },
    {
        path: '/document-order',
        name: 'DocumentOrders',
        components: {
            default: DocumentOrders,
            sidebar: PopcornAdminSidebar
        }
    },
    {
        path: '/document-order/new',
        name: 'New Document Order',
        components: {
            default: DocumentOrdersNew,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'DocumentOrders'
            ]
        }
    },
    {
        path: '/document-order/edit/:id',
        name: 'Edit Document Order',
        components: {
            default: DocumentOrdersEdit,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'DocumentOrders'
            ]
        }
    },
    {
        path: '/specifications',
        name: 'Specifications',
        components: {
            default: Specifications,
            sidebar: PopcornAdminSidebar
        }
    },
    {
        path: '/specifications/new',
        name: 'New Specification',
        components: {
            default: SpecificationsNew,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Specifications'
            ]
        }
    },
    {
        path: '/specifications/edit/:id',
        name: 'Edit Specification',
        components: {
            default: SpecificationsEdit,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Specifications'
            ]
        }
    },
    {
        path: '/banks',
        name: 'Banks',
        components: {
            default: Banks,
            sidebar: PopcornAdminSidebar
        }
    },
    {
        path: '/banks/new',
        name: 'New Bank',
        components: {
            default: BanksNew,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Banks'
            ]
        }
    },
    {
        path: '/banks/edit/:id',
        name: 'Edit Bank',
        components: {
            default: BanksEdit,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Banks'
            ]
        }
    },
    {
        path: '/drivers',
        name: 'Drivers',
        components: {
            default: Drivers,
            sidebar: PopcornAdminSidebar
        }
    },
    {
        path: '/drivers/new',
        name: 'New Driver',
        components: {
            default: DriversNew,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Drivers'
            ]
        }
    },
    {
        path: '/drivers/edit/:id',
        name: 'Edit Driver',
        components: {
            default: DriversEdit,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Drivers'
            ]
        }
    },

    {
        path: '/signatures',
        name: 'Signatures',
        components: {
            default: Signatures,
            sidebar: PopcornAdminSidebar
        }
    },
    {
        path: '/signatures/new',
        name: 'New Signature',
        components: {
            default: SignaturesNew,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Drivers'
            ]
        }
    },
    {
        path: '/signatures/edit/:id',
        name: 'Edit Signature',
        components: {
            default: SignaturesEdit,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Drivers'
            ]
        }
    },


    {
        path: '/groups',
        name: 'Groups',
        components: {
            default: Groups,
            sidebar: PopcornAdminSidebar
        }
    },
    {
        path: '/groups/new',
        name: 'New Group',
        components: {
            default: GroupsNew,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Groups'
            ]
        }
    },
    {
        path: '/groups/edit/:id',
        name: 'Edit Group',
        components: {
            default: GroupsEdit,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Groups'
            ]
        }
    },


    {
        path: '/services',
        name: 'Services',
        components: {
            default: Services,
            sidebar: PopcornAdminSidebar
        }
    },
    {
        path: '/services/new',
        name: 'New Service',
        components: {
            default: ServicesNew,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Services'
            ]
        }
    },
    {
        path: '/services/edit/:id',
        name: 'Edit Service',
        components: {
            default: ServicesEdit,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Services'
            ]
        }
    },


    {
        path: '/contracts',
        name: 'Contracts',
        components: {
            default: Contracts,
            sidebar: PopcornAdminSidebar
        }
    },
    {
        path: '/contracts/new',
        name: 'New Contract',
        components: {
            default: ContractsNew,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Contracts'
            ]
        }
    },
    {
        path: '/contracts/edit/:id',
        name: 'Edit Contract',
        components: {
            default: ContractsEdit,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Contracts'
            ]
        }
    },

    {
        path: '/provisions',
        name: 'Provisions',
        components: {
            default: Provisions,
            sidebar: PopcornAdminSidebar
        }
    },
    {
        path: '/provisions/:id/postings/new',
        name: 'New Provision Posting',
        components: {
            default: ProvisionsPostingsNew,
            sidebar: PopcornAdminSidebar
        },
        meta: {
            breadcrumb: [
                'Provisions'
            ]
        }
    },

    {

        path: '/contracts/report',
        name: 'Contracts Report',
        components: {
            default: ContractsReport,
            sidebar: PopcornAdminSidebar
        },
    },

    {
        path: '/reports/dre-operations',
        name: 'DRE Operations',
        components: {
            default: ReportDre,
            sidebar: PopcornAdminSidebar
        },
    },

    {
        path: '/reports/invoces',
        name: 'Invoices',
        components: {
            default: ReportInvoices,
            sidebar: PopcornAdminSidebar
        },
    },


    {
        path: "*", components: {
            default: PageNotFound,
            sidebar: PopcornAdminSidebar
        }
    }
]

window.userRoutes = [
    {
        path: '/dashboard',
        name: 'Dashboard',
        components: {
            default: ClientDashboard,
            sidebar: PopcornClientSidebar
        }
    },
    {
        path: '/transaction/:id',
        name: 'Transaction',
        components: {
            default: TransactionClient,
            sidebar: PopcornClientSidebar
        }
    },
    {
        path: '/user/me',
        name: 'My profile',
        components: {
            default: MyProfile,
            sidebar: PopcornClientSidebar
        }
    },
    {
        path: '/order',
        name: 'My orders',
        components: {
            default: MyOrders,
            sidebar: PopcornClientSidebar
        }
    },
    {
        path: '/order/:id',
        name: 'View my order',
        components: {
            default: MyOrder,
            sidebar: PopcornClientSidebar
        }
    },
    {
        path: '/order-info/:id',
        name: 'View my order info',
        components: {
            default: MyOrderInfo,
            sidebar: PopcornClientSidebar
        }
    },
    {
        path: "*", components: {
            default: PageNotFound,
            sidebar: PopcornClientSidebar
        }
    }
]

// Rotas para as roles de certificados
window.certificationFumigationRoutes = [
    {
        path: '/dashboard',
        name: 'Dashboard',
        components: {
            default: CertifiedFumigationOrders,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/user/me',
        name: 'My profile',
        components: {
            default: MyProfile,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/order',
        name: 'All orders',
        components: {
            default: CertifiedFumigationOrders,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/order-info/:id',
        name: 'Order information',
        components: {
            default: OrderInfoFumigation,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: "*", components: {
            default: PageNotFound,
            sidebar: PopcornCertifiedSidebar
        }
    }
]

window.certificationQualityRoutes = [
    {
        path: '/dashboard',
        name: 'Dashboard',
        components: {
            default: CertifiedQualityOrders,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/user/me',
        name: 'My profile',
        components: {
            default: MyProfile,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/order',
        name: 'All orders',
        components: {
            default: CertifiedQualityOrders,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/order-info/:id',
        name: 'Order information',
        components: {
            default: OrderInfoFumigation,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: "*", components: {
            default: PageNotFound,
            sidebar: PopcornCertifiedSidebar
        }
    }
]

window.certificationSecurityRoutes = [
    {
        path: '/dashboard',
        name: 'Dashboard',
        components: {
            default: CertifiedSecurityOrders,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/user/me',
        name: 'My profile',
        components: {
            default: MyProfile,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/order',
        name: 'All orders',
        components: {
            default: CertifiedSecurityOrders,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/order-info/:id',
        name: 'Order information',
        components: {
            default: OrderInfoInsuranceAgency,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: "*", components: {
            default: PageNotFound,
            sidebar: PopcornCertifiedSidebar
        }
    }
]

window.certificationWeightRoutes = [
    {
        path: '/dashboard',
        name: 'Dashboard',
        components: {
            default: CertifiedWeightOrders,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/user/me',
        name: 'My profile',
        components: {
            default: MyProfile,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/order',
        name: 'All orders',
        components: {
            default: CertifiedWeightOrders,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/order-info/:id',
        name: 'Order information',
        components: {
            default: OrderInfoFumigation,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: "*", components: {
            default: PageNotFound,
            sidebar: PopcornCertifiedSidebar
        }
    }
]


window.inspectionAgencyRoutes = [
    {
        path: '/dashboard',
        name: 'Dashboard',
        components: {
            default: InspectionAgencyOrders,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/user/me',
        name: 'My profile',
        components: {
            default: MyProfile,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/order',
        name: 'All orders',
        components: {
            default: InspectionAgencyOrders,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/order-info/:id',
        name: 'Order information',
        components: {
            default: OrderInfoInspectionAgency,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: "*", components: {
            default: PageNotFound,
            sidebar: PopcornCertifiedSidebar
        }
    }
]

window.forwardingAgentRoutes = [
    {
        path: '/dashboard',
        name: 'Dashboard',
        components: {
            default: ForwardingAgentOrders,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/user/me',
        name: 'My profile',
        components: {
            default: MyProfile,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/order',
        name: 'All orders',
        components: {
            default: ForwardingAgentOrders,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/order-info/:id',
        name: 'Order information',
        components: {
            default: OrderInfoForwardingAgent,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: "*", components: {
            default: PageNotFound,
            sidebar: PopcornCertifiedSidebar
        }
    }
]

window.terminalRoutes = [
    {
        path: '/dashboard',
        name: 'Dashboard',
        components: {
            default: TerminalOrders,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/user/me',
        name: 'My profile',
        components: {
            default: MyProfile,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/order',
        name: 'All orders',
        components: {
            default: TerminalOrders,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/order-info/:id',
        name: 'Order information',
        components: {
            default: OrderInfoTerminal,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: "*", components: {
            default: PageNotFound,
            sidebar: PopcornCertifiedSidebar
        }
    }
]

window.railwayRoutes = [
    {
        path: '/dashboard',
        name: 'Dashboard',
        components: {
            default: RailwayOrders,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/user/me',
        name: 'My profile',
        components: {
            default: MyProfile,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/order',
        name: 'All orders',
        components: {
            default: RailwayOrders,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: '/order-info/:id',
        name: 'Order information',
        components: {
            default: OrderInfoRailway,
            sidebar: PopcornCertifiedSidebar
        }
    },
    {
        path: "*", components: {
            default: PageNotFound,
            sidebar: PopcornCertifiedSidebar
        }
    }
]

import { datadogLogs } from '@datadog/browser-logs';

datadogLogs.init({
  clientToken: 'pubeca392fe2679765385c20e248656efff',
  site: 'us5.datadoghq.com',
  forwardErrorsToLogs: true,
  sessionSampleRate: 100,
});

const router = new VueRouter({
    mode: 'history',
    routes: [],
    linkActiveClass: "open",
    linkExactActiveClass: "active"
})


document.addEventListener('DOMContentLoaded', function () {
    const app = new Vue({
        el: '#app',
        data: {
            appName: 'Popcorn'
        },
        components: { App },
        router,
        directives: { money: VMoney }
    })
});