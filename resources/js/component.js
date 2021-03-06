/**
 * List Component
 */
// import DataTable from "./components/DataTable/DataTable";
import UsersComponent from './components/UsersComponent';
import ProfileComponent from './components/ProfileComponent';
import AdduserComponent from './components/AdduserComponent';

import ConfirmDeliveryOrder from './components/Warehouse/ConfirmDeliveryOrder';
import StislaFromInput from './components/Template/Etc/s-form-input';

import Table from "./components/Template/Table/Table";
import Testing from "./components/Testing";
import ErrorPage from "./components/Template/ErrorPage";
import ConfirmButton from "./components/Template/ConfirmButton";
import HeaderPrint from "./components/Template/Print/HeaderPrint";
import HeaderInfoPrint from "./components/Template/Print/HeaderInfoPrint";
import ImageModal from "./components/Template/ImageModal";

import TablePrint from "./components/Template/Print/TablePrint";
import IndexReportSO from "./components/Report/SO/IndexReportSO"
import IndexReportFinanceAR from "./components/Report/FinanceAR/IndexReportFinanceAR";
import IndexReportPriceUpload from "./components/Report/Price/IndexUploadReport";

import AddUserProc from "./components/MasterData/AddUserProc";
import AddWarehouseConfirm from "./components/WarehouseIn/AddWarehouseConfirm";
import LoadingButton from "./components/Button/LoadingButton";
import BackButton from "./components/Button/BackButton";
import LoadingTable from "./components/Template/Table/partials/LoadingTable";

import VueBarcode from 'vue-barcode';


Vue.component('barcode', VueBarcode);
Vue.component('AddWarehouseConfirm', AddWarehouseConfirm);
Vue.component('index-report-so', IndexReportSO);
Vue.component('index-report-finance-ar', IndexReportFinanceAR);
Vue.component('index-report-price-upload', IndexReportPriceUpload);

Vue.component('users-component', UsersComponent);
Vue.component('profile-component', ProfileComponent);
Vue.component('adduser-component', AdduserComponent);


Vue.component('confirm-delivery-order', ConfirmDeliveryOrder);

Vue.component('print-header', HeaderPrint);
Vue.component('print-header-info', HeaderInfoPrint);

Vue.component('print-table', TablePrint);

Vue.component('s-form-input', StislaFromInput);
Vue.component('s-table', Table);
Vue.component('s-testing', Testing);
Vue.component('s-error-page', ErrorPage);
Vue.component('s-btn-confirm', ConfirmButton);

Vue.component('back-button', BackButton);
Vue.component('loading-button', LoadingButton);
Vue.component('loading-table', LoadingTable);

Vue.component('AddUserProc', AddUserProc);
Vue.component('ImageModal', ImageModal);

// Vue.component("data-table", DataTable);
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);
