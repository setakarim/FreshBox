<template>
    <div class="row">
        <div class="col-12">
            <div class="card col-12">
                <div class="card-header">
                    <h4 class="text-danger">Confirm Delivery Order</h4>
                </div>
                <div class="col-12">
                    <div class="text-center" v-if="loading">
                        <LoadingTable/>
                    </div>
                    <div class="row" v-else>
                        <!-- Delivery Order No -->
                        <s-form-input :model="delivery_order.delivery_order_no" col="6" title="Delivery Order No"
                                      type="text" :disabled="true"/>
                        <!-- Confirm Date -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    <b>Confirm Date</b>
                                    <span style="color: red;">*</span>
                                </label>
                                <div>
                                    <date-picker v-model="confirm_date" lang="en" valueType="format"/>
                                </div>
                                <div style="margin-top: .25rem; font-size: 80%;color: #dc3545"
                                     v-if="errors.confirm_date">
                                    <p>{{ errors.confirm_date[0] }}</p>
                                </div>
                            </div>
                        </div>
                        <div v-if="delivery_order.sales_order_id !== ''" class="col-12">
                            <div class="table-responsive m-t-40" style="clear: both;">
                                <table class="table table-hover" style="font-size: 9pt;">
                                    <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">SKUID</th>
                                        <th class="text-center">Item Name</th>
                                        <th class="text-center">UOM</th>
                                        <th class="text-center">Qty Order</th>
                                        <th class="text-center">Qty Do</th>
                                        <th class="text-center">Amount Price</th>
                                        <th class="text-center">Total Price</th>
                                        <th class="text-center">Qty Confirm</th>
                                        <th class="text-center">Qty Minus</th>
                                        <th class="text-center">Remark Confirm</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(orders, index) in do_details" v-bind:key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ orders.skuid }}</td>
                                        <td>{{ orders.item_name }}</td>
                                        <td>{{ orders.uom_name }}</td>
                                        <td>{{ orders.qty_order }}</td>
                                        <td>{{ orders.qty_do }}</td>
                                        <td>{{ orders.amount_price }}</td>
                                        <td>{{ orders.total_amount_price }}</td>
                                        <td>
                                            <input
                                                v-model="qty_confirm[index].qty"
                                                type="number"
                                                class="form-control"
                                                :max="orders.qty"
                                            />
                                        </td>
                                        <td>
                                            <input
                                                v-model="qty_minus[index].qty"
                                                type="number"
                                                min="0"
                                                class="form-control"
                                            />
                                        </td>
                                        <td>
                                            <input
                                                v-model="orders.remark"
                                                type="text"
                                                class="form-control"
                                                placeholder="Remark Confirm"
                                            />
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-for="(orders, index) in do_details" v-bind:key="index">
                                <div style="margin-top: .25rem; font-size: 80%;color: #dc3545"
                                     v-if="errors[`do_details.${index}.qty_confirm`]">
                                    <p>The {{ do_details[index].item_name }} of qty confirm field is required.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>
                                    <b>Remark</b>
                                </label>
                                <textarea
                                    v-model="delivery_order.remark"
                                    class="form-control"
                                ></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card-body" v-if="loadingSubmit">
                                <loading-button/>
                            </div>
                            <div class="card-body" v-else>
                                <button
                                    class="btn btn-danger"
                                    v-on:click="submitForm()"
                                >Submit
                                </button>
                                <back-button/>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import LoadingTable from "../Template/Table/partials/LoadingTable";

    export default {
        components: {LoadingTable},
        props: ['do_id'],
        data() {
            return {
                confirm_date: "",
                delivery_order: {},
                do_details: [],
                qty_minus: [],
                errors: [],
                loading: true,
                loadingSubmit: false,
            };
        },
        mounted() {
            this.getData();
        },
        methods: {
            // validateQtyDO (idx) {
            //   let qty_do = parseFloat(this.qty_confirm[idx].qty);
            //   let qty_so = parseFloat(this.sales_order_details[idx].qty) + (this.sales_order_details[idx].qty * 0.1);
            //   if (qty_do > qty_so) {
            //     this.qty_do[idx].qty = qty_so;
            //   }
            // },
            getData() {
                axios.get(this.$parent.MakeUrl("api/v1/warehouse/confirm-delivery-order/show?id=" + this.$route.params.id))
                    .then(res => {
                        console.log(res);
                        this.delivery_order = res.data.data;
                        this.do_details = res.data.data.do_details;
                        this.qty_confirm = this.do_details.map((item, idx) => ({
                            qty: item.qty_do
                        }));
                        this.qty_minus = this.do_details.map((item, idx) => ({
                            qty: 0
                        }));
                        this.loading = false;
                    })
                    .catch(err => {
                    });
            },
            async submitForm() {
                this.loadingSubmit = true;
                const payload = {
                    id: this.delivery_order.id,
                    sales_order_id: this.delivery_order.sales_order_id,
                    confirm_date: this.confirm_date,
                    do_details: this.do_details.map((item, idx) => ({
                        id: item.id,
                        remark: item.remark,
                        qty_confirm: this.qty_confirm[idx].qty,
                        qty_minus: this.qty_minus[idx].qty
                    }))

                };
                try {
                    const res = await axios.patch(this.$parent.MakeUrl("api/v1/warehouse/confirm-delivery-order/update"), payload);
                    Vue.swal({
                        type: "success",
                        title: "Success!",
                        text: "Successfully Confirm Delivery Order!"
                    }).then(next => {
                        window.location.href = "/admin/warehouse/confirm-delivery-order";
                    });
                    console.log(res);
                } catch (e) {
                    this.errors = e.response.data.errors;
                    this.loadingSubmit = false;
                    console.error(e.response.data);
                }
            },
        }
    };
</script>
