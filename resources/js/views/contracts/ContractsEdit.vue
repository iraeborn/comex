<template>
  <div class="container-fluid">
    <div id="ui-view">
      <div class="panel panel-success">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">Edit Contract</div>
            </div>
          </div>

          <div class="card-body text-center" v-if="loading">
            Loading data...
          </div>

          <div class="card-body text-center" v-if="!providers && !loading">
            There is no registered supplier, to start register a supplier
            <router-link :to="{ name: 'New user' }">here</router-link>
          </div>

          <div class="card-body" v-if="providers && !loading">
            <div class="row">
              <div class="col">
                <label for="provider_id">Supplier</label>
                <div class="form-group reto">
                  <div class="caixa-icone"><i class="fas fa-user"></i></div>
                  <vue-select2
                    icon="fas fa-user"
                    class="vue-select1 form-control"
                    name="provider_select"
                    :options="providers"
                    :model.sync="obj.provider_id"
                    v-model="obj.provider_id"
                    :class="
                      error.provider_id != '' && error.provider_id
                        ? 'is-invalid select2-hidden-accessible'
                        : ''
                    "
                  >
                    <option value="">Select a Supplier</option>
                  </vue-select2>
                  <div
                    class="invalid-feedback"
                    v-if="error.provider_id"
                    v-for="message in error.provider_id"
                  >
                    {{ message }}
                  </div>
                </div>
              </div>

              <div class="col">
                <div class="form-group">
                  <label for="qtd_container">Identifier</label>
                  <input-icon
                    type="text"
                    icon="fas fa-id-card"
                    v-model="obj.identifier"
                    name="identifier"
                    :class="
                      error.identifier != '' && error.identifier
                        ? 'is-invalid'
                        : ''
                    "
                    id="identifier"
                  />
                  <div
                    class="invalid-feedback"
                    v-if="error.identifier"
                    v-for="message in error.identifier"
                  >
                    {{ message }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="qtd_container">Service Type</label>
                  
                  <div class="form-group reto">
                    <div class="caixa-icone"><i class="fas fa-edit"></i></div>
                        <vue-select2
                            icon="fas fa-user"
                            class="vue-select1 form-control"
                            name="provider_select"
                            :options="services"
                            :model.sync="obj.service_id"
                            v-model="obj.service_id"
                            :class="
                            error.service_id != '' && error.service_id
                                ? 'is-invalid select2-hidden-accessible'
                                : ''
                            "
                        >
                            <option value="">Select a Supplier</option>
                        </vue-select2>
                        <div
                            class="invalid-feedback"
                            v-if="error.service_id"
                            v-for="message in error.service_id"
                        >
                            {{ message }}
                        </div>
                    </div>
                </div>
              </div>

              <div class="col">
                <div class="form-group">
                  <label for="qtd_container">Service Location</label>
                  <input-icon
                    type="text"
                    icon="fas fa-map-marker-alt"
                    v-model="obj.service_location"
                    name="service_location"
                    :class="
                      error.service_location != '' && error.service_location
                        ? 'is-invalid'
                        : ''
                    "
                    id="service_location"
                  />
                  <div
                    class="invalid-feedback"
                    v-if="error.service_location"
                    v-for="message in error.service_location"
                  >
                    {{ message }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="qtd_container">Negotiated Terms</label>
                  <input-icon
                    type="text"
                    icon="fas fa-briefcase"
                    v-model="obj.negotiated_terms"
                    name="negotiated_terms"
                    :class="
                      error.negotiated_terms != '' && error.negotiated_terms
                        ? 'is-invalid'
                        : ''
                    "
                    id="negotiated_terms"
                  />
                  <div
                    class="invalid-feedback"
                    v-if="error.negotiated_terms"
                    v-for="message in error.negotiated_terms"
                  >
                    {{ message }}
                  </div>
                </div>
              </div>

              <div class="col">
                <label for="is_active">Contract Status</label>
                <div class="form-group reto">
                  <div class="caixa-icone">
                    <i class="fas fa-info-circle"></i>
                  </div>
                  <vue-select2
                    icon="fas fa-info-circle"
                    class="vue-select1 form-control"
                    name="status_select"
                    :options="contract_status"
                    :model.sync="obj.is_active"
                    v-model="obj.is_active"
                    v-bind:class="
                      error.is_active != '' && error.is_active
                        ? 'is-invalid select2-hidden-accessible'
                        : ''
                    "
                  >
                    <option value="">Select a status</option>
                  </vue-select2>
                  <div
                    class="invalid-feedback"
                    v-if="error.is_active"
                    v-for="message in error.is_active"
                  >
                    {{ message }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="form-group">
                  <iconinput
                    label="Expirated At:"
                    type="date"
                    v-model="obj.expirated_at"
                    id="expirated_at"
                    name="expirated_at"
                    icon="fas fa-calendar"
                    :error="error.expirated_at"
                  />
                  <div
                    class="invalid-feedback"
                    v-if="error.is_active"
                    v-for="message in error.is_active"
                  >
                    {{ message }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <p
                  class="text-center"
                  v-if="obj.contract_services.length == 0"
                  :class="
                    error.contract_services != '' && error.contract_services
                      ? 'is-invalid'
                      : ''
                  "
                >
                  <strong
                    >No services were added to this supplier's contract.</strong
                  >
                </p>

                <div
                  class="invalid-feedback"
                  v-if="error.contract_services"
                  v-for="message in error.contract_services"
                >
                  {{ message }}
                </div>

                <p class="text-left"><strong>Services</strong></p>

                <table
                  class="table table-striped table-responsive-sm"
                  v-if="obj.contract_services.length > 0"
                  >
                  <tr>
                    <th>Description</th>
                    <th>Factor Type</th>
                    <th>Currency Type</th>
                    <th>Amount</th>
                    <th style="width: 120px" class="text-right">Action</th>
                  </tr>

                  <tr v-for="(service, index) in obj.contract_services">
                    <td>{{ service.description }}</td>
                    <td>{{ service.billing_factor_type }}</td>
                    <td>{{ service.currency_type }}</td>
                    <td>{{ service.billing_value }}</td>

                    <td class="action-column text-right">
                      <button
                        class="btn btn-sm btn-success"
                        data-toggle="modal"
                        :href="'#modal-edit-item-' + index"
                        @click="
                          current_contract_service = JSON.parse(
                            JSON.stringify(service)
                          )
                        "
                      >
                        edit
                      </button>
                      <button
                        class="btn btn-sm btn-danger"
                        @click="DeleteItem(index)"
                      >
                        delete
                      </button>


                    </td>
                  </tr>
                </table>

                <p class="text-right">
                  <button
                    class="btn btn-success"
                    data-toggle="modal"
                    href="#modal-new-item"
                  >
                    Add new service
                  </button>
                </p>

                <p class="text-left"><strong>Orders</strong></p>

                <table
                  class="table table-striped table-responsive-sm"
                  v-if="obj.orders.length > 0"
                >
                  <tr>
                    <th>Number</th>
                    <th style="width: 120px" class="text-right">Action</th>
                  </tr>

                  <tr v-for="(order, index) in obj.orders" v-if="order">
                    <td>{{ order.number }}</td>

                    <td class="action-column text-right">
                      <button
                        class="btn btn-sm btn-danger"
                        @click="UnlinkOrderContract(order.id, order.number)"
                      >
                        unlink
                      </button>

                    </td>

                    <div class="modal fade" :id="'modal-edit-item-' + index">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Edit Item</h4>
                        <button
                          type="button"
                          class="close"
                          data-dismiss="modal"
                          aria-hidden="true"
                        >
                          &times;
                        </button>
                      </div>

                      <div class="modal-body">
                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="description"
                                >Service description</label
                              >
                              <div
                                class="input-group"
                                v-bind:class="
                                  error.description != '' &&
                                  error.description
                                    ? 'is-invalid'
                                    : ''
                                "
                              >
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-pen"></i>
                                  </div>
                                </div>
                                <input
                                  type="text"
                                  name="description"
                                  id="description"
                                  placeholder="Service description"
                                  class="form-control"
                                  v-model="
                                    current_contract_service.description
                                  "
                                  :class="
                                    error.description != '' &&
                                    error.description
                                      ? 'is-invalid'
                                      : ''
                                  "
                                />
                              </div>
                              <p
                                class="invalid-feedback"
                                v-if="error.description"
                                v-for="message in error.description"
                              >
                                {{ message }}
                              </p>
                            </div>
                          </div>

                          <div class="col">
                            <label for="billing_factor_type"
                              >Factor Type</label
                            >
                            <div class="form-group">
                              <div class="caixa-icone">
                                <i class="fas fa-info-circle"></i>
                              </div>
                              <vue-select2
                                icon="fas fa-info-circle"
                                class="vue-select1 form-control"
                                name="factor_select"
                                :options="contract_services_factor_types"
                                :model.sync="
                                  current_contract_service.billing_factor_type
                                "
                                v-model="
                                  current_contract_service.billing_factor_type
                                "
                                v-bind:class="
                                  error.billing_factor_type != '' &&
                                  error.billing_factor_type
                                    ? 'is-invalid select2-hidden-accessible'
                                    : ''
                                "
                              >
                                <option value="">
                                  Select a factor type
                                </option>
                              </vue-select2>
                              <div
                                class="invalid-feedback"
                                v-if="error.billing_factor_type"
                                v-for="message in error.billing_factor_type"
                              >
                                {{ message }}
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col">
                            <label for="currency_type"
                              >Currency Type</label
                            >
                            <div class="form-group">
                              <div class="caixa-icone">
                                <i class="fas fa-info-circle"></i>
                              </div>
                              <vue-select2
                                icon="fas fa-info-circle"
                                class="vue-select1 form-control"
                                name="currency_type"
                                :options="
                                  contract_services_currency_types
                                "
                                :model.sync="
                                  current_contract_service.currency_type
                                "
                                v-model="
                                  current_contract_service.currency_type
                                "
                                v-bind:class="
                                  error.currency_type != '' &&
                                  error.currency_type
                                    ? 'is-invalid select2-hidden-accessible'
                                    : ''
                                "
                              >
                                <option value="">
                                  Select a currency type
                                </option>
                              </vue-select2>
                              <div
                                class="invalid-feedback"
                                v-if="error.currency_type"
                                v-for="message in error.currency_type"
                              >
                                {{ message }}
                              </div>
                            </div>
                          </div>

                          <div class="col">
                            <div class="form-group">
                              <label for="billing_value">Amount</label>
                              <div
                                class="input-group"
                                v-bind:class="
                                  error.billing_value != '' &&
                                  error.billing_value
                                    ? 'is-invalid'
                                    : ''
                                "
                              >
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-dollar-sign"></i>
                                  </div>
                                </div>
                                <input
                                  type="number"
                                  name="billing_value"
                                  id="billing_value"
                                  placeholder="Advance Payment"
                                  class="form-control"
                                  v-model="
                                    current_contract_service.billing_value
                                  "
                                  :class="
                                    error.billing_value != '' &&
                                    error.billing_value
                                      ? 'is-invalid'
                                      : ''
                                  "
                                />
                              </div>
                              <p
                                class="invalid-feedback"
                                v-if="error.billing_value"
                                v-for="message in error.billing_value"
                              >
                                {{ message }}
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>

                      

                      <div class="modal-footer">
                        <button
                          type="button"
                          class="btn btn-default"
                          data-dismiss="modal"
                          @click="current_contract_service = {}"
                        >
                          Close
                        </button>
                        <button
                          type="button"
                          class="btn btn-primary"
                          @click.prevent="StoreItem(index)"
                        >
                          Save
                        </button>
                      </div>
                      
                    </div>
                   
                  </div>
                  
                </div>
                
                <div class="modal fade" id="modal-new-item">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">New Item</h4>
                        <button
                          type="button"
                          class="close"
                          data-dismiss="modal"
                          aria-hidden="true"
                        >
                          &times;
                        </button>
                      </div>

                      <div class="modal-body">
                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="description"
                                >Service description</label
                              >
                              <div
                                class="input-group"
                                v-bind:class="
                                  error.description != '' && error.description
                                    ? 'is-invalid'
                                    : ''
                                "
                              >
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-pen"></i>
                                  </div>
                                </div>
                                <input
                                  type="text"
                                  name="description"
                                  id="description"
                                  placeholder="Service description"
                                  class="form-control"
                                  v-model="current_contract_service.description"
                                  :class="
                                    error.description != '' && error.description
                                      ? 'is-invalid'
                                      : ''
                                  "
                                />
                              </div>
                              <p
                                class="invalid-feedback"
                                v-if="error.description"
                                v-for="message in error.description"
                              >
                                {{ message }}
                              </p>
                            </div>
                          </div>

                          <div class="col">
                            <label for="billing_factor_type">Factor Type</label>
                            <div class="form-group">
                              <div class="caixa-icone">
                                <i class="fas fa-info-circle"></i>
                              </div>
                              <vue-select2
                                icon="fas fa-info-circle"
                                class="vue-select1 form-control"
                                name="factor_select"
                                :options="contract_services_factor_types"
                                :model.sync="
                                  current_contract_service.billing_factor_type
                                "
                                v-model="
                                  current_contract_service.billing_factor_type
                                "
                                v-bind:class="
                                  error.billing_factor_type != '' &&
                                  error.billing_factor_type
                                    ? 'is-invalid select2-hidden-accessible'
                                    : ''
                                "
                              >
                                <option value="">Select a factor type</option>
                              </vue-select2>
                              <div
                                class="invalid-feedback"
                                v-if="error.billing_factor_type"
                                v-for="message in error.billing_factor_type"
                              >
                                {{ message }}
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col">
                            <label for="currency_type">Currency Type</label>
                            <div class="form-group">
                              <div class="caixa-icone">
                                <i class="fas fa-info-circle"></i>
                              </div>
                              <vue-select2
                                icon="fas fa-info-circle"
                                class="vue-select1 form-control"
                                name="currency_type"
                                :options="contract_services_currency_types"
                                :model.sync="
                                  current_contract_service.currency_type
                                "
                                v-model="current_contract_service.currency_type"
                                v-bind:class="
                                  error.currency_type != '' &&
                                  error.currency_type
                                    ? 'is-invalid select2-hidden-accessible'
                                    : ''
                                "
                              >
                                <option value="">Select a currency type</option>
                              </vue-select2>
                              <div
                                class="invalid-feedback"
                                v-if="error.currency_type"
                                v-for="message in error.currency_type"
                              >
                                {{ message }}
                              </div>
                            </div>
                          </div>

                          <div class="col">
                            <div class="form-group">
                              <label for="billing_value">Amount</label>
                              <div
                                class="input-group"
                                v-bind:class="
                                  error.billing_value != '' &&
                                  error.billing_value
                                    ? 'is-invalid'
                                    : ''
                                "
                              >
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-dollar-sign"></i>
                                  </div>
                                </div>
                                <input
                                  type="number"
                                  name="billing_value"
                                  id="billing_value"
                                  placeholder="Advance Payment"
                                  class="form-control"
                                  v-model="
                                    current_contract_service.billing_value
                                  "
                                  :class="
                                    error.billing_value != '' &&
                                    error.billing_value
                                      ? 'is-invalid'
                                      : ''
                                  "
                                />
                              </div>
                              <p
                                class="invalid-feedback"
                                v-if="error.billing_value"
                                v-for="message in error.billing_value"
                              >
                                {{ message }}
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                      

                      <div class="modal-footer">
                        <button
                          type="button"
                          class="btn btn-default"
                          data-dismiss="modal"
                        >
                          Close
                        </button>
                        <button
                          type="button"
                          class="btn btn-primary"
                          @click.prevent="StoreNewItem"
                        >
                          Save
                        </button>
                      </div>
                      
                    </div>
                    
                  </div>
                </div>

                  </tr>
                </table>
                
                <p class="text-right">
                  <button
                    class="btn btn-success"
                    data-toggle="modal"
                    @click="openModal()"
                  >
                  Assign new order
                  </button>
                </p>

                

              </div>
            </div>
          </div>

          <div class="card-footer text-right">
            <router-link :to="{ name: 'Contracts' }" class="btn btn-danger"
              >Cancel</router-link
            >
            <input
              type="button"
              value="Save"
              @click="Store"
              class="btn btn-success"
            />
          </div>
        </div>
      </div>
    </div>

    <dinamic-modal
        v-if="dinamicModal.isOpen"
        :modalId="dinamicModal.id"
        :modalTitle="dinamicModal.title"
        :saveButtonLabel="dinamicModal.saveButtonLabel"
        :contentComponent="dinamicModal.component"
        :componentProps="dinamicModal.props"
        :contentHtml="dinamicModal.html"
        :saveAction="dinamicModal.saveAction"
        @callback="handleDinamicModalCallback"
        @update-data="handleUpdateData"
    />

  </div>
</template>

<style>
.is-invalid ~ .invalid-feedback {
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
import AssignContractOrderModal from '../../components/AssignContractOrderModal';

export default {
  name: 'contractEdit',
        components: {
            'assign-contract-order-modal': AssignContractOrderModal,
        },
  data() {
    return {
      loading: true,
      error: {
        provider_id: "",
        service_id: "",
        identifier: "",
        service_type: "",
        service_location: "",
        negotiated_terms: "",
        is_active: "",
        expirated_at: "",
      },
      obj: {},
      orders_ids: [],
      providers: null,
      services:[],
      contract_status: null,
      contract_services_factor_types: null,
      contract_services_currency_types: null,
      current_contract_service: {},
      dinamicModal: {
          isOpen: false,
          id: null,
          title: '',
          saveButtonLabel: '',
          component: null,
          props: {},
          html: '',
          saveAction: null
      },
      defaultModalValues: {
          linkOrder: {
              id: 'linkOrder',
              title: 'Select a order to link in this contract',
              component: 'assign-contract-order-modal',
              saveAction: this.SaveContractData,
              saveButtonLabel: 'Bind'
          },
      },
    };
  },

  methods: {

    handleDinamicModalCallback(payload) {
        this.dinamicModal.isOpen = false;
        $('#dinamic-modal').modal('hide');
    },

    UnlinkOrderContract: async function (orderId, orderNumber) {

      try {
        const response = await this.$http.post(`/api/unlink-orders-provider`, {
          contract_id:  this.obj.id,
          order_id: orderId,
          order_number: orderNumber
        });

        this.GetContracts();
        this.$toaster.success(response.body.message);

      } catch (error){
        console.log(error);
        this.$toaster.success(error.message);
        throw error;
      }
    },

    SaveContractData: async function () {

      try {
        const response = await this.$http.post(`/api/link-orders-provider`, {
          contract_id:  this.obj.id,
          provider_id: this.obj.provider_id,
          orders_ids: this.orders_ids
        });

        this.GetContracts();
        this.$toaster.success(response.body.message);

      } catch (error){
        console.log(error);
        this.$toaster.success(error.message);
        throw error;
      }
    },

    openModal() {

      const modal = this.defaultModalValues['linkOrder'];

      this.dinamicModal = {
          isOpen: true,
          id: modal.id,
          title: modal.title,
          saveButtonLabel: modal.saveButtonLabel,
          component: this.$options.components[modal.component],
          props: {
              data: this.obj,
              error: null,
              modalIsOpen: this.dinamicModal.isOpen,
              type: 'contract',
              field: 'provider',
              label: 'provision'
          },
          html: modal.html || '',
          saveAction: modal.saveAction,

      };


      this.$nextTick(() => {
          this.dinamicModal.isOpen = true;
          $('#dinamic-modal').modal('show');
      });
    },

    handleUpdateData({ordersIds}) {
      this.orders_ids = ordersIds;
    },

    Store: function () {
      this.error = {};

      this.$http
        .put(
          "/api/providers/" + this.$route.params.id + "/contracts",
          this.obj
        )
        .then(function (e) {
          if (!e.body.success) {
            this.error = e.body.errors;
            return;
          }

          this.$toaster.success(e.body.success);
          this.$router.push("/contracts");
        })
        .catch(function (error) {
          this.loading = false;

          if (error.status == 404) {
            this.error = {
              provider_id: ["The supplier field is required"],
            };
          } else {
            this.error = error.body.errors;
          }

          this.$toaster.error("Contract couldnt be updated");
        });
    },

    StoreNewItem: function () {
      this.error = {};
      this.obj.contract_services.push(
        this.current_contract_service
      );

      $("#modal-new-item").modal("hide");
      this.current_contract_service = {};
    },

    StoreItem: function (index) {
      $(".modal").modal("hide");
      this.obj.contract_services[
        index
      ] = this.current_contract_service;
      this.current_contract_service = {};
    },

    DeleteItem: function (index) {
      this.obj.contract_services.splice(index, 1);
    },

    GetContracts: async function () {
      try {
        await this.$http.get("/api/providers/" + this.$route.params.id + "/contracts")
        .then((response) => {
          this.obj = response.body;
        });
      } catch (error){
        console.log(error);
        throw error;
      }
    }
  },

  mounted: function () {
    this.loading = false;

    this.GetContracts();

    this.$http.get("/api/users").then((e) => {
      if (!e.body.length) {
        return;
      }

      let arrayData = [];

      e.body.forEach((element) => {
        if(element.id == "") next
        
        arrayData.push({
          id: element.id,
          text: element.name,
          name: element.name,
        });
      });

      this.providers = arrayData;      
    });

    this.$http.get("/api/services?active=1").then((e) => {
      if (!e.body.length) {
        return;
      }

      this.$data.services = e.body.map((e) => {
        e.text = e.name;
        return e;
      });
    });

    this.$http
      .get("/api/contracts/services/factor-types")
      .then((result) => {
        if (!result.body.length) {
          return;
        }

        this.contract_services_factor_types = result.body;
      })
      .catch((error) => console.error(error));

    this.contract_status = [
      {
        id: 1,
        text: "Active",
      },
      {
        id: 0,
        text: "Inactive",
      },
    ];

    this.contract_services_currency_types = [
      {
        id: "BRL",
        text: "Real",
      },
      {
        id: "USD",
        text: "Dólar",
      },
    ];

    this.loading = false;
  },
};
</script>
