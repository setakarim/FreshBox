<template>
    <div>
        <div id="printMe">
            <print-header></print-header>
            <div class="row" v-if="loading">
                <div class="col-md-12">
                    <print-header-info
                        :header_info="header_info"
                        :data="invoice_order"
                        :info="info"
                    ></print-header-info>
                    <br />
                    <print-table
                        :columns="columns"
                        :data="details"
                    ></print-table>
                    <div class="col-md-12">
                        <table width="100%" style="border-top: 1px solid black">
                            <tbody>
                                <tr>
                                    <td width="56%"><b>Keterangan :</b></td>
                                    <td
                                        style="border-bottom: 1px solid black;"
                                        class="text-right"
                                    >
                                        Subtotal Rp
                                    </td>
                                    <td
                                        style="border-bottom: 1px solid black;"
                                        class="text-right"
                                    >
                                        {{ invoice_order.total_price | toIDR }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="56%"></td>
                                    <td
                                        style="border-bottom: 1px solid black;"
                                        class="text-right"
                                    >
                                        Discount Rp
                                    </td>
                                    <td
                                        style="border-bottom: 1px solid black;"
                                        class="text-right"
                                    ></td>
                                </tr>
                                <tr>
                                    <td width="56%"></td>
                                    <td
                                        style="border-bottom: 1px solid black;"
                                        class="text-right"
                                    >
                                        Pajak Rp
                                    </td>
                                    <td
                                        style="border-bottom: 1px solid black;"
                                        class="text-right"
                                    ></td>
                                </tr>
                                <tr style="border-bottom: 1px solid black;">
                                    <td width="56%"></td>
                                    <td class="text-right">Total Rp</td>
                                    <td class="text-right">
                                        {{ invoice_order.total_price | toIDR }}
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid black;">
                                    <td width="56%">
                                        <b
                                            >&emsp; &emsp;Terbilang :
                                            {{ invoice_order.terbilang }}</b
                                        >
                                    </td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="text-right mr-2">
                            <h6>
                                <b>{{ info.nama_pt }}</b>
                            </h6>
                            <br />
                            <br />
                            <br />
                            <br />
                            <br />
                            <div class="mr-5">
                                <h6>{{ info.nama_ttd }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table width="60%">
                            <tbody>
                                <tr>
                                    <td width="10%" class="text-right">
                                        <b><h6>Harap transfer ke</h6></b>
                                    </td>
                                    <td width="2%">&nbsp:</td>
                                    <td width="30%"></td>
                                </tr>
                                <tr>
                                    <td width="2%" class="text-right">
                                        <b>Penerima</b>
                                    </td>
                                    <td width="2%">&nbsp:</td>
                                    <td width="30%">{{ info.nama_pt }}</td>
                                </tr>
                                <tr>
                                    <td width="2%" class="text-right">
                                        <b>No Rekening</b>
                                    </td>
                                    <td width="2%">&nbsp:</td>
                                    <td width="30%">{{ info.bank_account }}</td>
                                </tr>
                                <tr>
                                    <td width="2%" class="text-right">
                                        <b>Bank</b>
                                    </td>
                                    <td width="2%">&nbsp:</td>
                                    <td width="30%">{{ info.bank }}</td>
                                </tr>
                                <tr>
                                    <td><br /></td>
                                </tr>
                                <tr>
                                    <td width="10%" colspan="5">
                                        <i>
                                            *) Pembayaran dianggap sah jika
                                            bukti transfer sudah dikirimkan
                                            kepada kami
                                        </i>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="10%" colspan="5">
                                        <i>
                                            *) Nilai tagihan dibuat berdasarkan
                                            pesanan yang diterima. Tidak ada
                                            coretan, berarti pesanan diterima
                                        </i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="text-center p-4 text-muted" v-else>
                <h5>Loading</h5>
                <p>Please wait, data is being loaded...</p>
            </div>
        </div>
        <br />
        <div class="text-right">
            <button class="btn btn-secondary" type="button" @click="back()">
                Back
            </button>
            <button class="btn btn-success" @click="print">Print</button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            columns: [
                {
                    title: "Item No",
                    field: "skuid",
                    type: "text"
                },
                {
                    title: "Item Name",
                    field: "item_name",
                    type: "text",
                    alignmentLeft: true
                },
                {
                    title: "Qty",
                    field: "qty_confirm",
                    type: "text"
                },
                {
                    title: "Unit",
                    field: "uom_name",
                    type: "text"
                },
                {
                    title: "Price",
                    field: "amount_price",
                    type: "currency"
                },
                {
                    title: "Amount",
                    field: "total_amount",
                    type: "currency"
                }
            ],
            header_info: [
                {
                    title: "Invoice Date",
                    field: "invoice_date"
                },
                {
                    title: "Delivery Order No",
                    field: "delivery_order_no"
                },
                {
                    page_break: true
                },
                {
                    title: "Kepada Yth",
                    field: "",
                    alignmentRight: true
                },
                {
                    title: "PO No.",
                    field: "no_po",
                    alignmentRight: true
                },
                {
                    title: "Customer",
                    field: "customer_name",
                    alignmentRight: true
                }
            ],
            info: {
                title: "Invoice",
                no: "invoice_no",
                nama_pt: "PT BERKAH TANI SEJAHTERA",
                nama_ttd: "Faizal Finanda",
                bank_account: "006 500 9779",
                bank: "BCA Cabang SCBD"
            },
            invoice_order: {},
            details: [],
            loading: false
        };
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            axios
                .get(
                    this.$parent.MakeUrl(
                        "api/v1/finance/invoice_order/show?id=" +
                            this.$route.params.id
                    )
                )
                .then(res => {
                    this.invoice_order = res.data;
                    this.details = res.data.do_details;
                    this.loading = true;
                })
                .catch(err => {
                    if (err.response.status === 500) {
                        this.getData();
                    }
                });
        },
        print() {
            Vue.swal({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Print it!"
            }).then(result => {
                const res = axios.post(
                    this.$parent.MakeUrl(
                        "api/v1/finance/invoice_order/print?id=" +
                            this.$route.params.id
                    )
                );
                console.log(res);
                if (result.value) {
                    this.$htmlToPaper("printMe");
                }
            });
        },
        back() {
            return (window.location.href = this.$parent.MakeUrl(
                "admin/finance/invoice_order"
            ));
        }
    }
};
</script>
