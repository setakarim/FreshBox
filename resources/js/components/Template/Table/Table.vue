<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div
                        v-bind:class="{
                            'col-lg-5': config.daterange,
                            'col-lg-6': config.action || config.actionPrint
                        }"
                    >
                        <div class="row">
                            <h4 class="text-danger ml-2">{{ config.title }}</h4>
                            <router-link
                                :to="{ name: config.route_create }"
                                class="btn btn-danger ml-2"
                                v-if="config.route_create"
                            >
                                Add
                                <i class="fas fa-plus"></i>
                            </router-link>
                            <a
                                @click="exportSO()"
                                class="btn btn-danger ml-2"
                                style="color: white"
                                v-if="config.export_excel"
                                >Export Excel</a
                            >

                            <a
                                :href="config.route_add"
                                v-if="config.route_add"
                                class="btn btn-danger ml-2"
                            >
                                Add
                                <i class="fas fa-plus"></i>
                            </a>

                            <a
                                :href="config.route_upload"
                                v-if="config.route_upload"
                                class="btn btn-success ml-2"
                            >
                                Bulk Upload
                                <i class="fas fa-plus"></i>
                            </a>

                            <a
                                class="btn btn-info ml-2"
                                style="color: white"
                                @click="print()"
                                v-if="
                                    config.route_multiple_print && selected != 0
                                "
                            >
                                Print
                                <i class="fas fa-print"></i>
                            </a>

                            <a
                                class="btn btn-primary ml-2"
                                @click="printRekap"
                                style="color: white"
                                v-if="config.route_print_recap && selected != 0"
                            >
                                Generate Recap Invoice
                                <i class="fas fa-print"></i>
                            </a>

                            <router-link
                                :to="{ name: config.route_print_all }"
                                class="btn btn-warning ml-2"
                                style="color: white"
                                v-if="config.route_print_all"
                            >
                                Print All Invoice Invoice
                                <i class="fas fa-print"></i>
                            </router-link>

                            <router-link
                                :to="{
                                    name: config.route_confirm,
                                    query: { id: selected }
                                }"
                                class="btn btn-warning ml-2"
                                style="color: white"
                                v-if="config.route_confirm && selected != 0"
                            >
                                Confirm Multiple
                                <i class="fas fa-print"></i>
                            </router-link>
                        </div>
                    </div>
                    <div class="card-header-action ml-auto mt-3 mb-3">
                        <div class="input-group" v-if="config.daterange">
                            <date-picker
                                v-model="params.start"
                                lang="en"
                                type="date"
                                placeholder="Start Date"
                                valuetype="format"
                                format="YYYY-MM-DD"
                            ></date-picker>
                            <date-picker
                                v-model="params.end"
                                lang="en"
                                type="date"
                                valuetype="format"
                                placeholder="End Date"
                                format="YYYY-MM-DD"
                            ></date-picker>
                            <input
                                type="text"
                                class="form-control ml-2"
                                placeholder="Search"
                                v-model="params.query"
                            />
                            <div class="input-group-btn ml-1">
                                <button
                                    class="btn btn-danger"
                                    v-on:click="search"
                                    :disabled="!loading"
                                >
                                    <i class="fas fa-search" v-if="loading"></i>
                                    <span
                                        class="spinner-border spinner-border-sm"
                                        role="status"
                                        aria-hidden="true"
                                        v-if="!loading"
                                    ></span>
                                    <span class="sr-only" v-if="!loading"
                                        >Loading...</span
                                    >
                                </button>
                            </div>
                        </div>
                        <div class="input-group" v-else-if="config.noStartEnd">
                            <div class="col-lg-6" v-if="config.customerGroup">
                                <model-list-select
                                    class="form-control ml-2"
                                    :list="customerGroups"
                                    v-model="customerGroupId"
                                    v-on:input="customerGroup()"
                                    option-value="id"
                                    option-text="name"
                                    placeholder="Customer Group"
                                />
                            </div>
                            <!-- <input
                                type="select"
                                class="form-control ml-2"
                                placeholder="Select"
                                v-model="params.query"
                            > -->
                            <input
                                type="text"
                                class="form-control ml-2"
                                placeholder="Search"
                                v-model="params.query"
                            />
                            <div class="input-group-btn ml-1">
                                <button
                                    class="btn btn-danger"
                                    v-on:click="search"
                                    :disabled="!loading"
                                >
                                    <i class="fas fa-search" v-if="loading"></i>
                                    <span
                                        class="spinner-border spinner-border-sm"
                                        role="status"
                                        aria-hidden="true"
                                        v-if="!loading"
                                    ></span>
                                    <span class="sr-only" v-if="!loading"
                                        >Loading...</span
                                    >
                                </button>
                            </div>
                        </div>
                        <div class="input-group" v-else>
                            <input
                                type="text"
                                class="form-control ml-2"
                                placeholder="Start"
                                v-model="params.start"
                            />
                            <input
                                type="text"
                                class="form-control ml-2"
                                placeholder="End"
                                v-model="params.end"
                            />
                            <input
                                type="text"
                                class="form-control ml-2"
                                placeholder="Search"
                                v-model="params.query"
                            />
                            <div class="input-group-btn ml-1">
                                <button
                                    class="btn btn-danger"
                                    v-on:click="search"
                                    :disabled="!loading"
                                >
                                    <i class="fas fa-search" v-if="loading"></i>
                                    <span
                                        class="spinner-border spinner-border-sm"
                                        role="status"
                                        aria-hidden="true"
                                        v-if="!loading"
                                    ></span>
                                    <span class="sr-only" v-if="!loading"
                                        >Loading...</span
                                    >
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label ml-5">Show</label>
                <div class="ml-3">
                    <label>
                        <select
                            class="form-control"
                            v-model="params.perPage"
                            v-on:change="getData"
                        >
                            <option
                                v-for="(perPage, index) in perPages"
                                v-bind:key="index"
                                :value="perPage"
                                >{{ perPage }}</option
                            >
                        </select>
                    </label>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" v-if="loading">
                    <table
                        class="table table-bordered"
                        v-if="data.length"
                        id="vuetable"
                    >
                        <tbody>
                            <tr>
                                <th v-if="config.action || config.actionPrint">
                                    <div class="custom-checkbox custom-control">
                                        <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            id="checkbox-all"
                                            v-model="selectAll"
                                        />
                                        <label
                                            for="checkbox-all"
                                            class="custom-control-label"
                                            >&nbsp;</label
                                        >
                                    </div>
                                </th>
                                <th v-if="config.action || config.pureAction">
                                    Action
                                </th>
                                <th
                                    v-for="(column, index) in columns"
                                    v-bind:key="index"
                                    style="overflow:hidden; white-space:nowrap"
                                >
                                    {{ column.title }}
                                </th>
                            </tr>
                            <tr
                                v-for="(item, index) in data"
                                v-bind:key="index"
                            >
                                <!--CheckBox-->
                                <td v-if="config.action || config.actionPrint">
                                    <div class="custom-checkbox custom-control">
                                        <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            :value="item.id"
                                            v-model="selected"
                                            :id="'checkbox-' + index"
                                        />
                                        <label
                                            :for="'checkbox-' + index"
                                            class="custom-control-label"
                                            >&nbsp;</label
                                        >
                                    </div>
                                </td>
                                <!--Button-->
                                <td v-if="config.action || config.pureAction">
                                    <router-link
                                        v-if="config.route_view"
                                        class="badge badge-primary ml-1 mr-1 mt-1 mb-1"
                                        :to="{
                                            name: config.route_view,
                                            params: { id: item.id }
                                        }"
                                        >View</router-link
                                    >

                                    <router-link
                                        v-if="
                                            config.route_edit &&
                                                item.status === 1 &&
                                                item.is_printed === 0
                                        "
                                        class="badge badge-warning ml-1 mr-1 mt-1 mb-1"
                                        :to="{
                                            name: config.route_edit,
                                            params: { id: item.id }
                                        }"
                                        >Edit</router-link
                                    >

                                    <a
                                        @click="editPrice(item.id)"
                                        class="badge badge-warning ml-1 mr-1 mt-1 mb-1"
                                        style="color: white"
                                        v-if="config.route_edit_price"
                                    >
                                        Edit
                                    </a>

                                    <router-link
                                        v-if="config.route_confirm"
                                        class="badge badge-warning ml-1 mr-1 mt-1 mb-1"
                                        :to="{
                                            name: config.route_confirm,
                                            params: { id: item.id }
                                        }"
                                        >Confirm</router-link
                                    >

                                    <router-link
                                        v-if="
                                            config.route_edit_payment &&
                                                (item.status === 1 ||
                                                    item.status === 8)
                                        "
                                        class="badge badge-secondary ml-1 mr-1 mt-1 mb-1"
                                        :to="{
                                            name: config.route_edit_payment,
                                            params: { id: item.id }
                                        }"
                                        >Edit</router-link
                                    >

                                    <router-link
                                        v-if="
                                            config.route_upload_payment &&
                                                item.status === 1
                                        "
                                        class="badge badge-warning ml-1 mr-1 mt-1 mb-1"
                                        :to="{
                                            name: config.route_upload_payment,
                                            params: { id: item.id }
                                        }"
                                        >Upload Dokumen</router-link
                                    >

                                    <a
                                        @click="replenish(item.id)"
                                        class="badge badge-warning ml-1 mr-1 mt-1 mb-1"
                                        style="color: white"
                                        v-if="
                                            config.route_replenish &&
                                                item.status === 3
                                        "
                                    >
                                        Replenish
                                    </a>

                                    <!-- <a href="#" onclick="someFunction(); return false;">LINK</a> -->

                                    <router-link
                                        v-if="
                                            config.route_receive_inout &&
                                                (item.status === 2 ||
                                                    item.status === 8)
                                        "
                                        class="badge badge-warning ml-1 mr-1 mt-1 mb-1"
                                        :to="{
                                            name: config.route_receive_inout,
                                            params: {
                                                id: item.finance_request_id
                                            }
                                        }"
                                    >
                                        Receive
                                    </router-link>

                                    <!-- <a
                                        @click="
                                            changeStatus(
                                                item.finance_request_id
                                            )
                                        "
                                        v-if="
                                            config.route_receive_inout &&
                                                (item.status === 2 ||
                                                    item.status === 8)
                                        "
                                        class="badge badge-warning ml-1 mr-1 mt-1 mb-1"
                                        style="color: white"
                                        >Receive</a
                                    > -->

                                    <a
                                        @click="
                                            changeStatusReject(
                                                item.finance_request_id
                                            )
                                        "
                                        v-if="
                                            config.route_receive_inout &&
                                                item.status === 2
                                        "
                                        class="badge badge-danger ml-1 mr-1 mt-1 mb-1"
                                        style="color: white"
                                        >Reject</a
                                    >

                                    <router-link
                                        v-if="
                                            config.route_confirm_inout &&
                                                (item.status === 3 ||
                                                    item.status === 6)
                                        "
                                        class="badge badge-success"
                                        :to="{
                                            name: config.route_confirm_inout,
                                            params: { id: item.id }
                                        }"
                                        >Confirm</router-link
                                    >

                                    <!-- <a
                                        @click="changeStatus(item.id)"
                                        v-if="
                                            config.route_confirm_inout &&
                                                item.status === 3
                                        "
                                        class="badge badge-success ml-1 mr-1 mt-1 mb-1"
                                        style="color: white"
                                    >Confirm</a> -->
                                </td>
                                <td
                                    v-for="(column, index) in columns"
                                    v-bind:key="index"
                                >

                                    <span
                                        v-html="item[column.field]"
                                        v-if="column.type === 'html'"
                                    ></span>
                                    <barcode
                                        v-else-if="column.type === 'barcode'"
                                        v-bind:value="item[column.field]" format="CODE39">
                                        Show this if the rendering fails.
                                    </barcode>
                                    <p v-else-if="column.type === 'file'">
                                        <a
                                            href="#"
                                            class="badge badge-info ml-1 mr-1 mt-1 mb-1"
                                            @click="
                                                showFile(
                                                    item[column.field],
                                                    item.file
                                                )
                                            "
                                            v-if="
                                                item.file !== '' &&
                                                    item.file !== null
                                            "
                                            >{{ item.file }}</a
                                        >
                                    </p>
                                    <p
                                        v-else-if="column.type === 'price'"
                                        style="white-space: nowrap;"
                                    >
                                        Rp {{ item[column.field] | toIDR }}
                                    </p>
                                    <p v-else>{{ item[column.field] }}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-if="!data.length" class="text-center p-3 text-muted">
                        <h5>No Results</h5>
                        <p>Looks like you have not added any data yet!</p>
                    </div>
                    <!--                    <stisla-pagination :offset="5" :pagination="pagination" @paginate="getData"></stisla-pagination>-->
                </div>
                <loading-table v-else></loading-table>
            </div>

            <nav class="row mt-4" v-if="pagination">
                <div class="col-md-6 text-left">
                    <span>
                        &nbsp;Showing&nbsp;
                        {{ pagination.from }}
                        &nbsp;to&nbsp;
                        {{ pagination.to }}
                        &nbsp;of&nbsp;
                        {{ pagination.total }}
                        &nbsp;entries&nbsp;
                    </span>
                </div>
                <div class="col-md-6 text-right">
                    <button
                        v-if="pagination.prev"
                        :class="buttonClasses"
                        @click="changePage(pagination.current_page - 1)"
                    >
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        &nbsp;Prev
                    </button>
                    <button
                        v-if="pagination.next"
                        :class="buttonClasses"
                        @click="changePage(pagination.current_page + 1)"
                    >
                        Next&nbsp;
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    </button>
                </div>
            </nav>
        </div>
    </div>
</template>
<script>
import LoadingTable from "./partials/LoadingTable";
import StislaPagination from "./partials/StislaPagination";
import { ModelListSelect } from "vue-search-select";
import VueMoment from "vue-moment";
import moment from "moment-timezone";

Vue.use(VueMoment, {
    moment
});

export default {
    components: { LoadingTable, StislaPagination, ModelListSelect },
    props: ["columns", "config"],
    data() {
        return {
            user: {},
            perPages: ["5", "10", "25", "50", "100", "150", "300"],
            buttonClasses: "btn btn-primary",
            selected: [0],
            data: [],
            customerGroups: [],
            customerGroupId: "",
            loading: false,
            pagination: {
                current_page: 1
            },
            params: {
                query: null,
                start: "",
                page: 1,
                end: null,
                perPage: 5
            }
        };
    },
    async mounted() {
        await this.getData();
        this.getCustomerGroup();
        this.user = AuthUser;
    },
    methods: {
        async search() {
            this.loading = false;
            this.pagination = null;
            try {
                await this.getData();
                this.loading = true;
            } catch (e) {
                console.log(e);
            }
        },
        async getData() {
            axios
                .get(this.config.base_url, {
                    params: this.params
                    // params: {
                    //     query: this.params.query,
                    //     start: this.params.start,
                    //     end: this.params.end,
                    //     perPage: this.params.perPage,
                    //     page: this.pagination.current_page
                    // }
                })
                .then(res => {
                    this.data = res.data.data;
                    if (
                        res.data.links != undefined &&
                        res.data.meta != undefined
                    ) {
                        this.pagination = {
                            first: res.data.links.first,
                            last: res.data.links.last,
                            prev: res.data.links.prev,
                            next: res.data.links.next,
                            from: res.data.meta.from,
                            to: res.data.meta.to,
                            total: res.data.meta.total,
                            current_page: res.data.meta.current_page,
                            last_page: res.data.meta.last_page,
                            path: res.data.meta.path
                        };
                    }
                    console.log(res);
                    this.loading = true;
                })
                .catch(e => {
                    // if (e.response.status === 500) {
                    //     this.getData();
                    // }
                    console.log(e);
                });
        },
        getCustomerGroup() {
            if (this.config.customerGroup) {
                axios
                    .get("/api/v1/master_data/customer-group")
                    .then(res => {
                        this.customerGroups = res.data;
                        console.log("customerGroup : ", this.customerGroups);
                        this.loading = true;
                    })
                    .catch(e => {
                        console.log(e);
                    });
            }
        },
        changePage(page) {
            if (page > this.pagination.last_page) {
                page = this.pagination.last_page;
            }
            this.params.page = page;
            this.getData();
        },
        multipleConfirm() {
            this.$router.push({
                name: this.config.route_confirm,
                query: { id: this.selected }
            });
        },
        print() {
            this.$router.push({
                name: this.config.route_multiple_print,
                query: { id: this.selected }
            });
        },
        printRekap() {
            const payload = {
                id: this.selected,
                userId: this.user.id
            };
            axios
                .get(
                    BaseUrl(
                        "api/v1/finance/invoice_order/generateRecapInvoice"
                    ),
                    { params: payload }
                )
                .then(res => {
                    if (res.data.status === true) {
                        Vue.swal({
                            type: "success",
                            title: "Success!",
                            text: "Berhasil Membuat Rekap Invoice!"
                        }).then(next => {
                            this.$router.push({
                                name: this.config.route_print_recap,
                                params: { id: res.data.invoice_recap_id }
                            });
                        });
                    } else {
                        Vue.swal({
                            type: "error",
                            title: "Danger!",
                            text: "Customer Harus Sama!"
                        });
                    }
                    console.log(res);
                })
                .catch(e => {});
        },
        confirmRequestAdvance(id) {
            Vue.swal({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, confirm it!"
            }).then(result => {
                if (result.value) {
                    axios.post(
                        BaseUrl("api/v1/finance-ap/request-advance/" + id)
                    );
                    Vue.swal(
                        "Confirm!",
                        "The data has been confirm.",
                        "success"
                    ).then(next => {
                        console.log(next);
                        this.getData();
                    });
                }
            });
            console.log(id);
        },
        replenish(id) {
            Vue.swal({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, replenish it!"
            }).then(result => {
                if (result.value) {
                    axios.post(
                        BaseUrl(
                            "api/v1/finance-ap/replenish/replenishUpdate/" + id
                        )
                    );
                    Vue.swal(
                        "Replenish!",
                        "The data has been replenish.",
                        "success"
                    ).then(next => {
                        this.getData();
                    });
                }
            });
            console.log(id);
        },
        editPrice(id) {
            window.location.href = `price/${id}/edit`;
            console.log(id);
        },
        customerGroup() {
            axios
                .get(
                    "/api/v1/master_data/price/customer_group/" +
                        this.customerGroupId,
                    {
                        params: this.params
                    }
                )
                .then(res => {
                    this.data = res.data.data;
                    if (
                        res.data.links != undefined &&
                        res.data.meta != undefined
                    ) {
                        this.pagination = {
                            first: res.data.links.first,
                            last: res.data.links.last,
                            prev: res.data.links.prev,
                            next: res.data.links.next,
                            from: res.data.meta.from,
                            to: res.data.meta.to,
                            total: res.data.meta.total,
                            current_page: res.data.meta.current_page,
                            last_page: res.data.meta.last_page,
                            path: res.data.meta.path
                        };
                    }
                    console.log("res customerGroup: ", res);
                    this.loading = true;
                })
                .catch(e => {
                    console.log(e);
                });
        },
        // changeStatus(id) {
        //     Vue.swal({
        //         title: "Are you sure?",
        //         text: "You won't be able to revert this!",
        //         type: "warning",
        //         showCancelButton: true,
        //         confirmButtonColor: "#3085d6",
        //         cancelButtonColor: "#d33",
        //         confirmButtonText: "Yes!"
        //     }).then(result => {
        //         if (result.value) {
        //             axios.post(
        //                 BaseUrl("api/v1/finance-ap/in-out-payment/" + id)
        //             );
        //             Vue.swal(
        //                 "Success!",
        //                 "Status has been changed!",
        //                 "success"
        //             ).then(next => {
        //                 this.getData();
        //             });
        //         }
        //     });
        // },
        changeStatusReject(id) {
            Vue.swal({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes!"
            }).then(result => {
                if (result.value) {
                    axios.post(
                        BaseUrl("api/v1/finance-ap/in-out-payment/reject/" + id)
                    );
                    Vue.swal(
                        "Success!",
                        "Status has been changed!",
                        "success"
                    ).then(next => {
                        this.getData();
                    });
                }
            });
        },
        showFile(fileUrl, fileName) {
            Vue.swal({
                title: fileName,
                imageUrl: fileUrl,
                imageAlt: "Custom image"
            });
            console.log(fileUrl);
        },
        exportSO() {
            window.location.href =
                "/admin/report/report-so/export?start=" +
                this.params.start +
                "&end=" +
                this.params.end;
        }
    },
    computed: {
        selectAll: {
            get: function() {
                return this.data
                    ? this.selected.length === this.data.length
                    : false;
            },
            set: function(value) {
                let selected = [];
                if (value) {
                    this.data.forEach(function(data) {
                        selected.push(data.id);
                    });
                }
                this.selected = selected;
            }
        }
    }
};
</script>
