<template>

    <div class="container-fluid">

        <div id="ui-view">

            <div class="panel panel-success">

                <div class="card">

                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                New Provision Posting
                            </div>
                        </div>
                    </div>

                    <div class="card-body text-center" v-if="loading">
                        Loading data...
                    </div>

                    <div class="card-body" v-if="!loading">

                        <div class="row">

                            <div class="col">
                                <div class="form-group">
                                    <label for="contract_identifier">Contract</label>
                                    <input-icon type="text" icon="fas fa-id-card" :value="provision.provider_contract.identifier" disabled="disabled" />
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="provider">Supplier</label>
                                    <input-icon type="text" icon="fas fa-id-card" :value="provision.provider_contract.provider.name" disabled="disabled" />
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col">
                                <div class="form-group">
                                    <label for="order_po">PO</label>
                                    <input-icon type="text" icon="fas fa-id-card" :value="provision.order.number" disabled="disabled" />
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="order_nfe">NFE</label>
                                    <input-icon type="text" icon="fas fa-id-card" :value="provision.order.mapa ? provision.order.mapa.nfe_key : ''" disabled="disabled" />
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="order_bkg">Booking</label>
                                    <input-icon type="text" icon="fas fa-id-card" :value="provision.booking" disabled="disabled" />
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col">
                                <div class="form-group">
                                    <label for="budgeted_amount">Amount USD</label>
                                    <input-icon type="text" icon="fas fa-id-card" :value="provision.dolar_budgeted_amount" disabled="disabled" />
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="budgeted_amount">Amount BRL</label>
                                    <input-icon type="text" icon="fas fa-id-card" :value="provision.real_budgeted_amount" disabled="disabled" />
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col">
                                <div class="form-group">
                                    <label for="provision_status">Status</label>
                                    <input-icon type="text" icon="fas fa-id-card" :value="provision.status_text" disabled="disabled" />
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="provision_amount">Container Qty</label>
                                    <input-icon type="text" icon="fas fa-id-card" :value="provision.quantity_container" disabled="disabled" />
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="row">

                            <div class="col">

                                <p class="text-center" v-if="provision.provision_posting.length == 0" :class="error.provision_posting != '' && error.provision_posting ? 'is-invalid' : ''">
                                    <strong>No services were added to this provider's contract.</strong>
                                </p>

                                <div class="invalid-feedback" v-if="error.provision_posting" v-for="message in error.provision_posting">
                                    {{ message }}
                                </div>

                                <table class="table table-striped table-responsive-sm" v-if="provision.provision_posting.length > 0">
                                    <tr>
                                        <th>Invoice Identifier</th>
                                        <th>Invoice Amount</th>
                                        <th>Invoice Amount Converted</th>
                                        <th>Currency Fee</th>
                                        <th>Currency Type</th>
                                        <th>Due Date</th>
                                        <th style="width: 120px;" class="text-right">Action</th>
                                    </tr>

                                    <tr v-for="(posting, index) in provision.provision_posting">

                                        <td>{{ posting.invoice_id }}</td>
                                        <td>{{ formatPrice(posting.invoice_amount, posting.currency_type)  }}</td>
                                        <td>{{ formatPrice(posting.invoice_amount_converted, 'BRL')  }}</td>
                                        <td>{{ posting.currency_fee }}</td>
                                        <td>{{ posting.currency_type }}</td>
                                        <td>{{ formatDate(posting.due_date) }}</td>

                                        <td class="action-column">
                                            <button class="btn btn-sm btn-danger pull-right" @click="DeleteItem(index)">delete</button>
                                            <button class="btn btn-sm btn-success pull-right" data-toggle="modal" :href="'#modal-edit-item-' + index" @click="current_provision_posting = JSON.parse(JSON.stringify(posting))">edit</button>

                                            <div class="modal fade" :id="'modal-edit-item-' + index">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Item</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        </div>

                                                        <div class="modal-body">

                                                            <div class="row">

                                                                <div class="col">
                                                                    <label for="contract_service_id"></label>
                                                                    <div class="input-group" v-bind:class="error.contract_service_id != '' && error.contract_service_id ? 'is-invalid' : ''">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                        <i class="fas fa-certificate"></i>
                                                                        </div>
                                                                    </div>
                                                                    <select name="" id="" class="form-control" v-model="current_provision_posting.service_type">
                                                                        <option value="">Choose an option</option>
                                                                        <option :value="service.service_type" v-for="service in contract_services">{{ service.service_type }}</option>
                                                                    </select>
                                                                    </div>
                                                                </div>


                                                                <div class="col">
                                                                    <div class="form-group">
                                                                        <label for="invoice_id">Invoice Identifier</label>
                                                                        <div class="input-group" v-bind:class="error.invoice_id != '' && error.invoice_id ? 'is-invalid' : ''">
                                                                            <div class="input-group-prepend">
                                                                                <div class="input-group-text">
                                                                                    <i class="fas fa-pen"></i>
                                                                                </div>
                                                                            </div>
                                                                            <input type="text" name="invoice_id" id="invoice_id" placeholder="Invoice Identifier" class="form-control" v-model="current_provision_posting.invoice_id" :class="error.invoice_id != '' && error.invoice_id ? 'is-invalid' : ''">
                                                                        </div>
                                                                        <p class="invalid-feedback" v-if="error.invoice_id" v-for="message in error.invoice_id">{{ message }}</p>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            <div class="row">

                                                                <div class="col">

                                                                    <label for="currency_type">Currency Type</label>
                                                                    <div class="form-group">
                                                                        <div class="caixa-icone"><i class="fas fa-info-circle"></i></div>
                                                                            <vue-select2 icon="fas fa-info-circle" class="vue-select1 form-control" name="currency_type" :options="provision_posting_currency_types" :model.sync="current_provision_posting.currency_type" v-model="current_provision_posting.currency_type" v-bind:class="error.currency_type != '' && error.currency_type ? 'is-invalid select2-hidden-accessible' : ''">
                                                                                <option value="">Select a currency type</option>
                                                                            </vue-select2>
                                                                        <div class="invalid-feedback" v-if="error.currency_type" v-for="message in error.currency_type">{{ message }}</div>
                                                                    </div>

                                                                </div>

                                                                <div class="col">

                                                                    <div class="form-group">
                                                                        <label for="invoice_amount">Amount</label>
                                                                        <div class="input-group" v-bind:class="error.invoice_amount != '' && error.invoice_amount ? 'is-invalid' : ''">
                                                                            <div class="input-group-prepend">
                                                                            <div class="input-group-text">
                                                                                <i class="fas fa-dollar-sign"></i>
                                                                            </div>
                                                                            </div>
                                                                            <input type="number" name="invoice_amount" id="invoice_amount" placeholder="Advance Payment" class="form-control" v-model="current_provision_posting.invoice_amount" :class="error.invoice_amount != '' && error.invoice_amount ? 'is-invalid' : ''">
                                                                        </div>
                                                                        <p class="invalid-feedback" v-if="error.invoice_amount" v-for="message in error.invoice_amount">{{ message }}</p>
                                                                    </div>

                                                                </div>

                                                                <div class="col">

                                                                    <div class="form-group">
                                                                        <label for="currency_fee">Currency Fee</label>
                                                                        <div class="input-group" v-bind:class="error.currency_fee != '' && error.currency_fee ? 'is-invalid' : ''">
                                                                            <div class="input-group-prepend">
                                                                            <div class="input-group-text">
                                                                                <i class="fas fa-dollar-sign"></i>
                                                                            </div>
                                                                            </div>
                                                                            <input type="number" name="currency_fee" id="currency_fee" placeholder="Currency_fee" class="form-control" v-model="current_provision_posting.currency_fee" :class="error.currency_fee != '' && error.currency_fee ? 'is-invalid' : ''">
                                                                        </div>
                                                                        <p class="invalid-feedback" v-if="error.currency_fee" v-for="message in error.currency_fee">{{ message }}</p>
                                                                    </div>

                                                                </div>

                                                            </div>

                                                            <div class="row">

                                                                <div class="col">
                                                                    <div class="form-group">
                                                                        <iconinput label="Due Date:" type="date" v-model="current_provision_posting.due_date" id="due_date" name="due_date" icon="fas fa-calendar" :error="error.due_date" />
                                                                        <div class="invalid-feedback" v-if="error.due_date" v-for="message in error.due_date">{{message}}</div>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            <div class="row mt-1">
                                                                <div class="col">
                                                                    <label><input type="checkbox" v-model="current_provision_posting.foreign_account" id="foreign_account" name="foreign_account">Foreign Account</label>
                                                                </div>
                                                            </div>

                                                        </div> <!-- modal-body -->

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal" @click="current_provision_posting = {}">Close</button>
                                                            <button type="button" class="btn btn-primary" @click.prevent="StoreItem(index)">Save</button>
                                                        </div> <!-- modal-footer -->

                                                    </div> <!-- modal-content -->
                                                </div> <!-- modal-dialog modal-lg -->
                                            </div> <!-- modal-fade -->
                                        </td>
                                    </tr>

                                </table>

                                <p class="text-right">
                                    <button class="btn btn-success" data-toggle="modal" href='#modal-new-item' @click="current_provision_posting = {}">Add new item</button>
                                </p>

                                <div class="modal fade" id="modal-new-item">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h4 class="modal-title">New Item</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            </div>    

                                            <div class="modal-body">

                                                <div class="row">

                                                    <div class="col">
                                                        <label for="contract_service_id"></label>
                                                        <div class="input-group" v-bind:class="error.contract_service_id != '' && error.contract_service_id ? 'is-invalid' : ''">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                            <i class="fas fa-certificate"></i>
                                                            </div>
                                                        </div>
                                                        <select name="" id="" class="form-control" v-model="current_provision_posting.service_type">
                                                            <option value="">Choose an option</option>
                                                            <option :value="service.service_type" v-for="service in contract_services">{{ service.service_type }}</option>
                                                        </select>
                                                        </div>
                                                    </div>


                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="invoice_id">Invoice Identifier</label>
                                                            <div class="input-group" v-bind:class="error.invoice_id != '' && error.invoice_id ? 'is-invalid' : ''">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text">
                                                                        <i class="fas fa-pen"></i>
                                                                    </div>
                                                                </div>
                                                                <input type="text" name="invoice_id" id="invoice_id" placeholder="Invoice Identifier" class="form-control" v-model="current_provision_posting.invoice_id" :class="error.invoice_id != '' && error.invoice_id ? 'is-invalid' : ''">
                                                            </div>
                                                            <p class="invalid-feedback" v-if="error.invoice_id" v-for="message in error.invoice_id">{{ message }}</p>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row">

                                                    <div class="col">

                                                        <label for="currency_type">Currency Type</label>
                                                        <div class="form-group">
                                                            <div class="caixa-icone"><i class="fas fa-info-circle"></i></div>
                                                                <vue-select2 icon="fas fa-info-circle" class="vue-select1 form-control" name="currency_type" :options="provision_posting_currency_types" :model.sync="current_provision_posting.currency_type" v-model="current_provision_posting.currency_type" v-bind:class="error.currency_type != '' && error.currency_type ? 'is-invalid select2-hidden-accessible' : ''">
                                                                    <option value="">Select a currency type</option>
                                                                </vue-select2>
                                                            <div class="invalid-feedback" v-if="error.currency_type" v-for="message in error.currency_type">{{ message }}</div>
                                                        </div>

                                                    </div>

                                                    <div class="col">

                                                        <div class="form-group">
                                                            <label for="invoice_amount">Amount</label>
                                                            <div class="input-group" v-bind:class="error.invoice_amount != '' && error.invoice_amount ? 'is-invalid' : ''">
                                                                <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-dollar-sign"></i>
                                                                </div>
                                                                </div>
                                                                <input type="number" name="invoice_amount" id="invoice_amount" placeholder="Advance Payment" class="form-control" v-model="current_provision_posting.invoice_amount" :class="error.invoice_amount != '' && error.invoice_amount ? 'is-invalid' : ''">
                                                            </div>
                                                            <p class="invalid-feedback" v-if="error.invoice_amount" v-for="message in error.invoice_amount">{{ message }}</p>
                                                        </div>

                                                    </div>

                                                    <div class="col">

                                                        <div class="form-group">
                                                            <label for="currency_fee">Currency Fee</label>
                                                            <div class="input-group" v-bind:class="error.currency_fee != '' && error.currency_fee ? 'is-invalid' : ''">
                                                                <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-dollar-sign"></i>
                                                                </div>
                                                                </div>
                                                                <input type="number" name="currency_fee" id="currency_fee" placeholder="Currency_fee" class="form-control" v-model="current_provision_posting.currency_fee" :class="error.currency_fee != '' && error.currency_fee ? 'is-invalid' : ''">
                                                            </div>
                                                            <p class="invalid-feedback" v-if="error.currency_fee" v-for="message in error.currency_fee">{{ message }}</p>
                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="row">

                                                    <div class="col">
                                                        <div class="form-group">
                                                            <iconinput label="Due Date:" type="date" v-model="current_provision_posting.due_date" id="due_date" name="due_date" icon="fas fa-calendar" :error="error.due_date" />
                                                            <div class="invalid-feedback" v-if="error.due_date" v-for="message in error.due_date">{{message}}</div>
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="row mt-1">
                                                    <div class="col">
                                                        <label><input type="checkbox" v-model="current_provision_posting.foreign_account" id="foreign_account" name="foreign_account">Foreign Account</label>
                                                    </div>
                                                </div>

                                            </div> <!-- modal-body -->

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary" @click.prevent="StoreNewItem">Save</button>
                                            </div> <!-- modal-footr -->

                                        </div> <!-- modal-content -->
                                    </div>
                                </div>


                            </div>
                        </div>


                    </div>


                    <div class="card-footer text-right">
                        <router-link :to="{ name: 'Provisions' }" class="btn btn-success">Back to list</router-link>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
</template>

<style>
.is-invalid~.invalid-feedback {
  display: block;
}

.action-column {
  width: 121px;
}

.caixa-icone {
  border-top-left-radius: 4px;
  border-bottom-left-radius: 4px;
  background-color: #f0f3f5;
  border: 1px solid #e4e7ea;
  padding: 6px 12px;
  width: auto;
  float: left;
  color: #5c6873;
}

.caixa-icone ~ .select2-container {
  width: calc(100% - 39.25px) !important;
}

.table-amarelo {
  background-color: #fef101;
}

.table-amarelo th,
.table-amarelo td {
  border: 2px solid #fff;
}


</style>

<script>

    import currencyFormatter from './../../mixins/currencyFormatter';
    import datesFormatter from './../../mixins/datesFormatter';

    export default {

    mixins: [ currencyFormatter, datesFormatter ],

    data() {
      return {

        provision: {
            invoice_id: '',
            invoice_amount: '',
            invoice_amount_converted: '',
            currency_fee: '',
            currency_type: '',
            due_date: '',
            provision_posting: {},
        },
        error: {},
        loading: true,
        provision_posting_currency_types: null,
        contract_services: {},
        current_provision_posting: {},
      }
    },

    methods:{

        Store: function () {

            this.error = {}
            this.loading = true;

            this.$http.post('/api/provisions/' + this.provision.id + '/contracts', this.provision)
                .then(function (e) {

                    this.loading = false;

                    if (!e.body.success) {
                        this.error = e.body.errors;
                        return;
                    }

                    this.$toaster.success(e.body.success);
                    this.$router.push('/provisions');

                }).catch(function (error) {

                    this.loading = false;

                    if (error.status == 404) {

                    } else {

                        this.error = error.body.errors;
                    }

                    this.$toaster.error('Supplier couldnt be save at database');

                });

        },

        StoreNewItem: function () {

            this.error = {};

            this.current_provision_posting.supplier_id = this.provision.provider_contract.provider.id;

            this.$http
                .post(`/api/provisions/${this.provision.id}/postings`, this.current_provision_posting)
                .then(function (e) {

                    if (e.body.errors) {
                        this.error = e.body.errors;
                        return;
                    }

                    this.provision.provision_posting.push(e.body);

                    $('#modal-new-item').modal('hide')
                    this.current_provision_posting = {};

                })

        },

        StoreItem: function (index) {

            const posting = this.provision.provision_posting[index];
            this.current_provision_posting.supplier_id = this.provision.provider_contract.provider.id;
            this.$http
                .put(`/api/provisions/${posting.contract_provision_id}/postings/${posting.id}`, this.current_provision_posting)
                .then(result => {

                    this.$set(this.provision.provision_posting, index, result.body);
                })
                .catch(error => console.error(error));

            $('.modal').modal('hide');
            this.current_provision_posting = {}
        },

        DeleteItem: function (index) {

            const posting = this.provision.provision_posting[index];

            this.$http
                .delete(`/api/provisions/${posting.contract_provision_id}/postings/${posting.id}`)
                .then(result => {
                    this.provision.provision_posting.splice(index, 1);
                })
                .catch(error => console.log(error));


        },

    },

    mounted: function () {

        this.$http.get(`/api/provisions/${this.$route.params.id}/postings`)
            .then(result => {

                this.provision = result.body;

                const allServicesOption = {
                    id: 'todos-servicos',
                    service_type: 'Todos serviços'
                };

                this.contract_services = this.provision
                    .provider_contract
                    .contract_services
                    .flatMap(service => {
                        return {
                            id: service.id,
                            service_type: service.description
                        };
                    });

                this.contract_services.unshift(allServicesOption);

            }).catch(error => {
                console.error(error);
            });


        this.provision_posting_currency_types = [
            {
                id: 'BRL',
                text: 'Real'
            },
            {
                id: 'USD',
                text: 'Dólar'
            },
        ];

        this.loading = false;
    }

  }
</script>
