<template>
  <div class="container-fluid">
    <div id="ui-view">
      <div class="panel panel-success">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">Edit order</div>
            </div>
          </div>
          <div class="card-body text-center" v-if="loading">
            Loading data...
          </div>

          <div class="card-body text-center" v-if="!importers && !loading">
            There is no registered importer, to start register an importer
            <router-link :to="{ name: 'New user' }">here</router-link>
          </div>

          <div class="card-body" v-if="importers && !loading">
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="name">Order number</label>
                  <div
                    class="input-group"
                    v-bind:class="
                      error.number != '' && error.number ? 'is-invalid' : ''
                    "
                  >
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-calculator"></i>
                      </div>
                    </div>
                    <input
                      type="text"
                      name="number"
                      id="number"
                      placeholder="Item number"
                      class="form-control"
                      v-model="order.number"
                      :class="
                        error.number != '' && error.number ? 'is-invalid' : ''
                      "
                    />
                  </div>
                  <div
                    class="invalid-feedback"
                    v-if="error.number"
                    v-for="message in error.number"
                  >
                    {{ message }}
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="name">Importer</label>
                  <div class="form-group reto">
                    <div class="caixa-icone"><i class="fas fa-user"></i></div>
                    <vue-select2
                      icon="fas fa-user"
                      class="vue-select1 form-control"
                      name="select1"
                      :options="importers"
                      :model.sync="order.importer_id"
                      v-model="order.importer_id"
                      v-bind:class="
                        error.importer != '' && error.importer
                          ? 'is-invalid select2-hidden-accessible'
                          : ''
                      "
                    >
                      <option value="">Select Importer</option>
                    </vue-select2>
                    <div
                      class="invalid-feedback"
                      v-if="error.importer"
                      v-for="message in error.importer"
                    >
                      {{ message }}
                    </div>
                  </div>

                  <div
                    class="invalid-feedback"
                    v-if="error.importer"
                    v-for="message in error.importer"
                  >
                    {{ message }}
                  </div>
                </div>
              </div>

              <div class="col">
                <div class="form-group">
                  <label for="name">Exporter</label>
                  <div class="form-group reto">
                    <div class="caixa-icone"><i class="fas fa-user"></i></div>
                    <vue-select2
                      icon="fas fa-user"
                      class="vue-select1 form-control"
                      name="select1"
                      :options="exporters"
                      :model.sync="order.exporter_id"
                      v-model="order.exporter_id"
                      v-bind:class="
                        error.exporter_id != '' && error.exporter_id
                          ? 'is-invalid select2-hidden-accessible'
                          : ''
                      "
                    >
                      <option value="">Select Exporter</option>
                    </vue-select2>
                    <div
                      class="invalid-feedback"
                      v-if="error.exporter_id"
                      v-for="message in error.exporter_id"
                    >
                      {{ message }}
                    </div>
                  </div>

                  <div
                    class="invalid-feedback"
                    v-if="error.importer"
                    v-for="message in error.importer"
                  >
                    {{ message }}
                  </div>
                </div>
              </div>

              <div class="col">
                <label for="">Broker</label>
                <div
                  class="input-group"
                  v-bind:class="
                    error.broker_id != '' && error.broker_id ? 'is-invalid' : ''
                  "
                >
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-user-tie"></i>
                    </div>
                  </div>
                  <select
                    name=""
                    id=""
                    class="form-control"
                    v-model="order.broker_id"
                  >
                    <option value="">Chose an option</option>
                    <option
                      :value="editor.id"
                      v-for="editor in editors.broker_id"
                    >
                      {{ editor.name }}
                    </option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row margin-bottom">
              <div class="col">
                <label for="">Fumigation Agency</label>
                <div
                  class="input-group"
                  v-bind:class="
                    error.fumigation_id != '' && error.fumigation_id
                      ? 'is-invalid'
                      : ''
                  "
                >
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-bug"></i>
                    </div>
                  </div>
                  <select
                    name=""
                    id=""
                    class="form-control"
                    v-model="order.fumigation_id"
                  >
                    <option value="">Chose an option</option>
                    <option
                      :value="editor.id"
                      v-for="editor in editors.fumigation_agency_id"
                    >
                      {{ editor.name }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="col">
                <label for="">Insurance Agency</label>
                <div
                  class="input-group"
                  v-bind:class="
                    error.weight_id != '' && error.weight_id ? 'is-invalid' : ''
                  "
                >
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-shield-alt"></i>
                    </div>
                  </div>
                  <select
                    name=""
                    id=""
                    class="form-control"
                    v-model="order.weight_id"
                  >
                    <option value="">Chose an option</option>
                    <option
                      :value="editor.id"
                      v-for="editor in editors.insurance_agency_id"
                    >
                      {{ editor.name }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="col">
                <label for="">Inspection Agency</label>
                <div
                  class="input-group"
                  v-bind:class="
                    error.inspection_agency_id != '' &&
                    error.inspection_agency_id
                      ? 'is-invalid'
                      : ''
                  "
                >
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-certificate"></i>
                    </div>
                  </div>
                  <select
                    name=""
                    id=""
                    class="form-control"
                    v-model="order.inspection_id"
                  >
                    <option value="">Chose an option</option>
                    <option
                      :value="editor.id"
                      v-for="editor in editors.inspection_agency_id"
                    >
                      {{ editor.name }}
                    </option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row margin-bottom">
              <div class="col">
                <label for="">Forwarding Agent</label>
                <div
                  class="input-group"
                  v-bind:class="
                    error.forwarding_agent_id != '' && error.forwarding_agent_id
                      ? 'is-invalid'
                      : ''
                  "
                >
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-forward"></i>
                    </div>
                  </div>
                  <select
                    name=""
                    id=""
                    class="form-control"
                    v-model="order.forwarding_agent_id"
                  >
                    <option value="">Chose an option</option>
                    <option
                      :value="editor.id"
                      v-for="editor in editors.forwarding_agent_id"
                    >
                      {{ editor.name }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="col">
                <label for="">Terminal Agent</label>
                <div
                  class="input-group"
                  v-bind:class="
                    error.terminal_agent_id != '' && error.terminal_agent_id
                      ? 'is-invalid'
                      : ''
                  "
                >
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-forward"></i>
                    </div>
                  </div>
                  <select
                    name=""
                    id=""
                    class="form-control"
                    v-model="order.terminal_agent_id"
                  >
                    <option value="">Chose an option</option>
                    <option
                      :value="editor.id"
                      v-for="editor in editors.terminal_agent_id"
                    >
                      {{ editor.name }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="col">
                <label for="">Railway Agent</label>
                <div
                  class="input-group"
                  v-bind:class="
                    error.railway_agent_id != '' && error.railway_agent_id
                      ? 'is-invalid'
                      : ''
                  "
                >
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-forward"></i>
                    </div>
                  </div>
                  <select
                    name=""
                    id=""
                    class="form-control"
                    v-model="order.railway_agent_id"
                  >
                    <option value="">Chose an option</option>
                    <option
                      :value="editor.id"
                      v-for="editor in editors.railway_agent_id"
                    >
                      {{ editor.name }}
                    </option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <label for="name">Notify</label>
                <div class="form-group reto">
                  <div class="caixa-icone"><i class="fas fa-user"></i></div>
                  <vue-select2
                    icon="fas fa-user"
                    class="vue-select1 form-control"
                    name="select1"
                    :options="importers"
                    :model.sync="order.notify_id"
                    v-model="order.notify_id"
                    v-bind:class="
                      error.notify_id != '' && error.notify_id
                        ? 'is-invalid select2-hidden-accessible'
                        : ''
                    "
                  >
                    <option value="">Select Notify</option>
                  </vue-select2>
                  <div
                    class="invalid-feedback"
                    v-if="error.notify_id"
                    v-for="message in error.notify_id"
                  >
                    {{ message }}
                  </div>
                </div>
              </div>
              <div class="col">
                <label for="name">Consignee</label>
                <div class="form-group reto">
                  <div class="caixa-icone"><i class="fas fa-user"></i></div>
                  <vue-select2
                    icon="fas fa-user"
                    class="vue-select1 form-control"
                    name="select1"
                    :options="importers"
                    :model.sync="order.consignee_id"
                    v-model="order.consignee_id"
                    v-bind:class="
                      error.consignee_id != '' && error.consignee_id
                        ? 'is-invalid select2-hidden-accessible'
                        : ''
                    "
                  >
                    <option value="">Select Consignee</option>
                  </vue-select2>
                  <div
                    class="invalid-feedback"
                    v-if="error.consignee_id"
                    v-for="message in error.consignee_id"
                  >
                    {{ message }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="name">Packing</label>
                  <div
                    class="input-group"
                    v-bind:class="
                      error.name != '' && error.name ? 'is-invalid' : ''
                    "
                  >
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-archive"></i>
                      </div>
                    </div>
                    <input
                      type="text"
                      name="full_packing"
                      id="full_packing"
                      placeholder="Packing"
                      class="form-control"
                      v-model="order.full_packing"
                      :class="
                        error.full_packing != '' && error.full_packing
                          ? 'is-invalid'
                          : ''
                      "
                    />
                  </div>
                  <div
                    class="invalid-feedback"
                    v-if="error.full_packing"
                    v-for="message in error.full_packing"
                  >
                    {{ message }}
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <p for="name">Label?</p>
                  <input
                    type="radio"
                    name="label"
                    id="label_1"
                    v-model="order.label"
                    value="1"
                  />&nbsp;<label for="label_1">Yes</label>&emsp;
                  <input
                    type="radio"
                    name="label"
                    id="label_0"
                    v-model="order.label"
                    value="0"
                  />&nbsp;<label for="label_0">No</label>
                  <div
                    class="invalid-feedback"
                    v-if="error.label"
                    v-for="message in error.label"
                  >
                    {{ message }}
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="name">Comission</label>
                  <div
                    class="input-group"
                    v-bind:class="
                      error.name != '' && error.name ? 'is-invalid' : ''
                    "
                  >
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-money-bill"></i>
                      </div>
                    </div>
                    <input
                      type="text"
                      name="unit_comission"
                      id="unit_comission"
                      placeholder="Comissão"
                      class="form-control"
                      maxlength="50"
                      min="1"
                      v-model="order.unit_comission"
                      :class="
                        error.unit_comission != '' && error.unit_comission
                          ? 'is-invalid'
                          : ''
                      "
                    />
                    <div
                      class="invalid-feedback"
                      v-if="error.unit_comission"
                      v-for="message in error.unit_comission"
                    >
                      {{ message }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="name">Payment Conditions</label>
                  <div
                    class="input-group"
                    v-bind:class="
                      error.name != '' && error.name ? 'is-invalid' : ''
                    "
                    >
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-credit-card"></i>
                      </div>
                    </div>
                    <input
                      type="text"
                      name="payment_conditions"
                      id="payment_conditions"
                      placeholder="Payment Conditions"
                      class="form-control"
                      v-model="order.payment_conditions"
                      :class="
                        error.payment_conditions != '' &&
                        error.payment_conditions
                          ? 'is-invalid'
                          : ''
                      "
                    />
                  </div>
                  <div
                    class="invalid-feedback"
                    v-if="error.payment_conditions"
                    v-for="message in error.payment_conditions"
                  >
                    {{ message }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="name">Port Origin</label>
                  <div
                    class="input-group"
                    v-bind:class="
                      error.name != '' && error.name ? 'is-invalid' : ''
                    "
                  >
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-location-arrow"></i>
                      </div>
                    </div>
                    <input
                      type="text"
                      name="port_origin"
                      id="port_origin"
                      placeholder="Port Origin"
                      class="form-control"
                      v-model="order.port_origin"
                      :class="
                        error.port_origin != '' && error.port_origin
                          ? 'is-invalid'
                          : ''
                      "
                    />
                  </div>
                  <div
                    class="invalid-feedback"
                    v-if="error.port_origin"
                    v-for="message in error.port_origin"
                  >
                    {{ message }}
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="name">Port Destiny</label>
                  <div
                    class="input-group"
                    v-bind:class="
                      error.name != '' && error.name ? 'is-invalid' : ''
                    "
                  >
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-map-marker"></i>
                      </div>
                    </div>
                    <input
                      type="text"
                      name="port_destiny"
                      id="port_destiny"
                      placeholder="Port Destiny"
                      class="form-control"
                      v-model="order.port_destiny"
                      :class="
                        error.port_destiny != '' && error.port_destiny
                          ? 'is-invalid'
                          : ''
                      "
                    />
                  </div>
                  <div
                    class="invalid-feedback"
                    v-if="error.port_destiny"
                    v-for="message in error.port_destiny"
                  >
                    {{ message }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row pb-2">
              <div class="col">
                <label class="mt-2 mb-0">Bank Data</label>
                <div class="d-flex">
                  <div class="flex-grow-1">
                    
                    <div class="form-group reto">
                      
                      <vue-select2
                        icon="fas fa-university"
                        class="vue-select1 form-control"
                        name="select1"
                        :options="banks"
                        :model.sync="order.banks_id"
                        v-model="order.banks_id"
                        v-bind:class="
                          error.banks_id != '' && error.banks_id
                            ? 'is-invalid select2-hidden-accessible'
                            : ''
                        "
                      >
                        <option value="">Select Bank</option>
                      </vue-select2>
                      <div
                        class="invalid-feedback"
                        v-if="error.banks_id"
                        v-for="message in error.banks_id"
                      >
                        {{ message }}
                      </div>
                    </div>

                  </div>
                  <div class="ml-2">
                      <button class="btn btn-success" data-toggle="modal" href="#modal-new-bank" style="min-width: 130px;">
                        Add new Bank
                      </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="modal-new-bank">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">New Bank</h4>
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
                    <PopcornBank
                      :parent-action="action"
                      :is-loading="loading"
                      :store-function="callChildStoreFunction"
                      @action-success="handleActionSuccess"
                      @action-error="handleActionError"
                      @update:is-loading="updateLoading"
                      ref="popcornBank"
                    />
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
                      class="btn btn-success"
                      @click="callChildStoreFunction()"
                    >
                      Save
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="row pb-2">

              <div class="col">
                <label for="container_type">Transportion</label>

                <multiselect
                  v-model="order.transportion"
                  track-by="id"
                  @select="updateIncoterms"
                  placeholder="Select one"
                  :options="transportationTypes"
                  :custom-label="customLabel"
                  :show-labels="false"
                  :allow-empty="false"
                  :class="error.transportion != '' && error.transportion ? 'invalid' : ''"
                  >
                </multiselect>
                      
                <p
                  :class="error.transportion ? 'invalid-feedback' : ''"
                  v-if="error.transportion"
                  v-for="message in error.transportion"
                  >
                    {{ message }}
                </p>

              </div>

              <div class="col">
                <label for="container_type">Incoterm</label>

                <multiselect
                  v-model="order.incoterm"
                  track-by="id"
                  placeholder="Select one"
                  :options="filteredIncoterms"
                  :custom-label="customIncotermsLabel"
                  :show-labels="false"
                  :allow-empty="false"
                  :class="error.incoterm != '' && error.incoterm ? 'invalid' : ''"
                  >
                </multiselect>
                      
                <p
                  :class="error.incoterm ? 'invalid-feedback' : ''"
                  v-if="error.incoterm"
                  v-for="message in error.incoterm"
                  >
                    {{ message }}
                </p>

              </div>

              <div class="col">
                <div class="form-group">
                <iconinput
                  label="Shipping forecast:"
                  type="month"
                  v-model="order.shipment"
                  id="shipment"
                  name="shipment"
                  icon="fas fa-calendar"
                  :error="error.shipment"
                />
                </div>
              </div>

              <div class="col">
                <label for="container_type">Container Type</label>

                <multiselect
                  v-model="order.container_type"
                  track-by="id"
                  :options="containerTypes"
                  :custom-label="customLabel"
                  :show-labels="false"
                  :allow-empty="false"
                  :class="error.container_type != '' && error.container_type ? 'invalid' : ''"
                  >
                </multiselect>
                      
                <p
                  :class="error.container_type ? 'invalid-feedback' : ''"
                  v-if="error.container_type"
                  v-for="message in error.container_type"
                  >
                    {{ message }}
                </p>
              </div>

            </div>

            <div class="row pb-2">
              <div class="col">
                <div class="form-group">
                  <label for="note">Short Note</label>
                  <div
                    class="input-group"
                    v-bind:class="
                      error.note != '' && error.note ? 'is-invalid' : ''
                    "
                  >
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fa fa-sticky-note"></i>
                      </div>
                    </div>
                    <input
                      type="text"
                      name="note"
                      id="note"
                      placeholder="Short note"
                      class="form-control"
                      v-model="order.note"
                      :class="
                        error.note != '' && error.note
                          ? 'is-invalid'
                          : ''
                      "
                    />
                  </div>
                  <div
                    class="invalid-feedback"
                    v-if="error.note"
                    v-for="message in error.note"
                  >
                    {{ message }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row pb-2">
              <div class="col">
                <div class="d-flex pb-2 align-items-center">
                  <label for="">Specifications</label>
                  <p class="text-right ml-auto mb-0">
                    <button
                      class="btn btn-success"
                      data-toggle="modal"
                      href="#modal-new-specification"
                    >
                        Add new Specification
                      </button>
                  </p>
                </div>

                <div class="modal fade" id="modal-new-specification">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">New Specification</h4>
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
                          <div class="col-12">
                              <div class="form-group">
                                  <label for="qtd_container">Description</label>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <div class="input-group-text">
                                              <i class="fas fa-box"></i>
                                          </div>
                                      </div>
                                      <input type="text" class="form-control" v-model="newSpecification.description" name="newSpecification" 
                                      :class="newSpecification.error != '' && newSpecification.error ? 'is-invalid' : ''"/>
                                  </div>
                              </div>
                              <p
                                  :class="newSpecification.error ? 'invalid-feedback' : ''"
                                  v-if="newSpecification.error"
                                  v-for="message in newSpecification.error"
                                >
                                  {{ message }}
                              </p>
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
                          @click.prevent="StoreNewSpecification"
                        >
                          Save
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-forward"></i>
                    </div>
                  </div>
                  <select
                    multiple name="" id="" class="form-control" v-model="order.specifications"
                    v-bind:class="
                      error.specification != '' && error.specification
                        ? 'is-invalid' : ''"
                    >
                    <option value="">Chose an option</option>
                    <option
                      :value="specification.id"
                      v-for="specification in specificationsList"
                      >
                      {{ specification.description }}
                    </option>
                  </select>
                </div>

                <div class="invalid-feedback" v-if="error.specifications" v-for="message in error.specifications">
                  {{ message }}
                </div>

              </div>
            </div>

            <div class="row pb-2">
              <div class="col">

                <div class="d-flex pb-2 align-items-center">
                  <label class="m-0">Documents</label>
                  <p class="text-right ml-auto mb-0">
                    <button
                      class="btn btn-success"
                      data-toggle="modal"
                      href="#modal-new-document"
                    >
                      Add new Document
                    </button>
                  </p>
                </div>

                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-forward"></i>
                    </div>
                  </div>
                  <select
                    multiple name="" id="" class="form-control selectpicker" v-model="order.document"
                    v-bind:class="
                      error.document != '' &&
                      error.document
                        ? 'is-invalid'
                        : ''
                    ">
                    <option value="">Chose an option</option>
                    <option :value="document.id"
                      v-for="document in documentsList"
                      >
                      {{ document.description }}
                    </option>
                  </select>
                </div>

                <div class="invalid-feedback" v-if="error.document" v-for="message in error.document">
                  {{ message }}
                </div>

                <div class="modal fade" id="modal-new-document">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">New document</h4>
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
                          <div class="col-12">
                            <div class="form-group">
                              <label for="newDocument">Description</label>
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                      <i class="fas fa-box"></i>
                                  </div>
                                </div>
                                <input type="text" class="form-control" v-model="newDocument.description" name="newDocument" 
                                :class="newDocument.error != '' && newDocument.error ? 'is-invalid' : ''"/>
                              </div>
                            </div>
                            <p
                                :class="newDocument.error ? 'invalid-feedback' : ''"
                                v-if="newDocument.error"
                                v-for="message in newDocument.error"
                              >
                                {{ message }}
                            </p>
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
                          @click="StoreNewDocument"
                        >
                          Save
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <div class="row">
              <div class="col">
                <p
                  class="text-center"
                  v-if="!orderItemsCount"
                  :class="error.items != '' && error.items ? 'is-invalid' : ''"
                >
                  <strong>No items were added to this order.</strong>
                </p>
                <div
                  class="invalid-feedback"
                  v-if="error.items"
                  v-for="message in error.items"
                >
                  {{ message }}
                </div>

                <table
                  class="table table-striped table-responsive-sm"
                  v-if="orderItemsCount > 0"
                >
                  <tr>
                    <th>Description</th>
                    <th>HS Code</th>
                    <th>Year</th>
                    <th>Quantity</th>
                    <th>Container</th>
                    <th>Unit price</th>
                    <th>Total price</th>
                    <th>Advance Payment</th>
                    <th>Total bags</th>
                    <th>Net Weight</th>
                    <th>Gross Weight</th>
                    <th style="width: 120px" class="text-right">Action</th>
                  </tr>
                  <tr v-for="(item, index) in order.items">
                    <td>{{ item.description }}</td>
                    <td>{{ item.hs_code }}</td>
                    <td>{{ item.crop_year }}</td>
                    <td>{{ FormatNumberDecimal(item.quantity) }} TONS</td>
                    <td>{{ item.container }}</td>
                    <td>US$ {{ FormatPrice(item.unit_price) }}</td>
                    <td>US$ {{ FormatPrice(item.total_price) }}</td>
                    <td>US$ {{ FormatPrice(item.advance_payment) }}</td>
                    <td>{{ FormatNumberDecimal(item.total_bags) }}</td>
                    <td>{{ FormatNumberDecimal(item.net_weight) }} KGS</td>
                    <td>{{ FormatNumberDecimal(item.gross_weight) }} KGS</td>
                    <td class="action-column text-right">
                      <button
                        class="btn btn-sm btn-success"
                        data-toggle="modal"
                        :href="'#modal-edit-item-' + index"
                        @click="current_item = JSON.parse(JSON.stringify(item))"
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
                                    >Description of Goods</label
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
                                      placeholder="Item description"
                                      class="form-control"
                                      v-model="current_item.description"
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
                              <div class="col-3">
                                <div class="form-group">
                                  <label for="hs_code"
                                    >HS Code</label
                                  >
                                  <div
                                    class="input-group"
                                    v-bind:class="
                                      error.hs_code != '' &&
                                      error.hs_code
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
                                      name="hs_code"
                                      id="hs_code"
                                      class="form-control"
                                      v-model="current_item.hs_code"
                                      :class="
                                        error.hs_code != '' &&
                                        error.hs_code
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
                            </div>

                            <div class="row">
                              <div class="col">
                                <div class="form-group">
                                  <label for="description"
                                    >Botanical name</label
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
                                      name="botanical_name"
                                      id="botanical_name"
                                      placeholder="Item botanical name"
                                      class="form-control"
                                      v-model="current_item.botanical_name"
                                      :class="
                                        error.botanical_name != '' &&
                                        error.botanical_name
                                          ? 'is-invalid'
                                          : ''
                                      "
                                    />
                                  </div>
                                  <p
                                    class="invalid-feedback"
                                    v-if="error.botanical_name"
                                    v-for="message in error.botanical_name"
                                  >
                                    {{ message }}
                                  </p>
                                </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col">
                                <div class="form-group">
                                  <label for="crop_year">Crop Year</label>
                                  <div
                                    class="input-group"
                                    v-bind:class="
                                      error.crop_year != '' && error.crop_year
                                        ? 'is-invalid'
                                        : ''
                                    "
                                  >
                                    <div class="input-group-prepend">
                                      <div class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                      </div>
                                    </div>
                                    <input
                                      type="number"
                                      name="crop_year"
                                      id="crop_year"
                                      placeholder="Crop Year"
                                      class="form-control"
                                      min="2017"
                                      v-model="current_item.crop_year"
                                      :class="
                                        error.crop_year != '' &&
                                        error.crop_year
                                          ? 'is-invalid'
                                          : ''
                                      "
                                    />
                                  </div>
                                  <p
                                    class="invalid-feedback"
                                    v-if="error.crop_year"
                                    v-for="message in error.crop_year"
                                  >
                                    {{ message }}
                                  </p>
                                </div>
                              </div>

                              <div class="col">
                                <div class="form-group">
                                  <label for="quantity">Quantity (TON)</label>
                                  <div
                                    class="input-group"
                                    v-bind:class="
                                      error.quantity != '' && error.quantity
                                        ? 'is-invalid'
                                        : ''
                                    "
                                  >
                                    <div class="input-group-prepend">
                                      <div class="input-group-text">
                                        <i class="fas fa-weight-hanging"></i>
                                      </div>
                                    </div>
                                    <input
                                      @input="calcTotalPrice"
                                      type="number"
                                      name="quantity"
                                      id="quantity"
                                      placeholder="Quantity"
                                      class="form-control"
                                      min="1"
                                      v-model="current_item.quantity"
                                      :class="
                                        error.quantity != '' && error.quantity
                                          ? 'is-invalid'
                                          : ''
                                      "
                                    />
                                  </div>
                                  <p
                                    class="invalid-feedback"
                                    v-if="error.quantity"
                                    v-for="message in error.quantity"
                                  >
                                    {{ message }}
                                  </p>
                                </div>
                              </div>

                              <div class="col">
                                <div class="form-group">
                                  <label for="unit_price"
                                    >Unit Price (US$)</label
                                  >
                                  <div
                                    class="input-group"
                                    v-bind:class="
                                      error.unit_price != '' &&
                                      error.unit_price
                                        ? 'is-invalid'
                                        : ''
                                    "
                                  >
                                    <div class="input-group-prepend">
                                      <div class="input-group-text">
                                        <i class="fas fa-money-bill"></i>
                                      </div>
                                    </div>
                                    <input
                                      @input="calcTotalPrice"
                                      type="number"
                                      name="unit_price"
                                      id="unit_price"
                                      placeholder="Unit Price"
                                      class="form-control"
                                      min="1"
                                      v-model="current_item.unit_price"
                                      :class="
                                        error.unit_price != '' &&
                                        error.unit_price
                                          ? 'is-invalid'
                                          : ''
                                      "
                                    />
                                  </div>
                                  <p
                                    class="invalid-feedback"
                                    v-if="error.unit_price"
                                    v-for="message in error.unit_price"
                                  >
                                    {{ message }}
                                  </p>
                                </div>
                              </div>

                              <div class="col">
                                <div class="form-group">
                                  <label for="total_price"
                                    >Total Price (US$)</label
                                  >
                                  <div
                                    class="input-group"
                                    v-bind:class="
                                      error.total_price != '' &&
                                      error.total_price
                                        ? 'is-invalid'
                                        : ''
                                    "
                                  >
                                    <div class="input-group-prepend">
                                      <div class="input-group-text">
                                        <i class="fas fa-money-bill"></i>
                                      </div>
                                    </div>
                                    <input
                                      readonly
                                      type="number"
                                      name="total_price"
                                      id="total_price"
                                      placeholder="Total Price"
                                      class="form-control"
                                      min="1"
                                      v-model="current_item.total_price"
                                      :class="
                                        error.total_price != '' &&
                                        error.total_price
                                          ? 'is-invalid'
                                          : ''
                                      "
                                    />
                                  </div>
                                  <p
                                    class="invalid-feedback"
                                    v-if="error.total_price"
                                    v-for="message in error.total_price"
                                  >
                                    {{ message }}
                                  </p>
                                </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col">
                                <div class="form-group">
                                  <label for="value"
                                    >Total Price in Words</label
                                  >
                                  <div
                                    class="input-group"
                                    v-bind:class="
                                      error.value != '' && error.value
                                        ? 'is-invalid'
                                        : ''
                                    "
                                  >
                                    <div class="input-group-prepend">
                                      <div class="input-group-text">
                                        <i class="fas fa-money-bill"></i>
                                      </div>
                                    </div>
                                    <input
                                      type="text"
                                      name="value"
                                      id="value"
                                      placeholder="Value in words"
                                      class="form-control"
                                      v-model="current_item.value"
                                      :class="
                                        error.value != '' && error.value
                                          ? 'is-invalid'
                                          : ''
                                      "
                                    />
                                  </div>
                                  <p
                                    class="invalid-feedback"
                                    v-if="error.value"
                                    v-for="message in error.value"
                                  >
                                    {{ message }}
                                  </p>
                                </div>
                              </div>

                              <div class="col">
                                <div class="form-group">
                                  <label for="value">Advance Payment</label>
                                  <div
                                    class="input-group"
                                    v-bind:class="
                                      error.advance_payment != '' &&
                                      error.advance_payment
                                        ? 'is-invalid'
                                        : ''
                                    "
                                  >
                                    <div class="input-group-prepend">
                                      <div class="input-group-text">
                                        <i class="fas fa-money-bill"></i>
                                      </div>
                                    </div>
                                    <input
                                      type="number"
                                      name="advance_payment"
                                      id="advance_payment"
                                      placeholder="0"
                                      class="form-control"
                                      min="1"
                                      v-model="current_item.advance_payment"
                                      :class="
                                        error.advance_payment != '' &&
                                        error.advance_payment
                                          ? 'is-invalid'
                                          : ''
                                      "
                                    />
                                  </div>
                                  <p
                                    class="invalid-feedback"
                                    v-if="error.advance_payment"
                                    v-for="message in error.advance_payment"
                                  >
                                    {{ message }}
                                  </p>
                                </div>
                              </div>

                              <div class="col">
                                <div class="form-group">
                                  <label for="value">Container</label>
                                  <div
                                    class="input-group"
                                    v-bind:class="
                                      error.container != '' && error.container
                                        ? 'is-invalid'
                                        : ''
                                    "
                                  >
                                    <div class="input-group-prepend">
                                      <div class="input-group-text">
                                        <i class="fas fa-weight-hanging"></i>
                                      </div>
                                    </div>
                                    <input
                                      type="number"
                                      name="container"
                                      id="container"
                                      placeholder="0"
                                      class="form-control"
                                      min="1"
                                      v-model="current_item.container"
                                      :class="
                                        error.container != '' &&
                                        error.container
                                          ? 'is-invalid'
                                          : ''
                                      "
                                    />
                                  </div>
                                  <p
                                    class="invalid-feedback"
                                    v-if="error.container"
                                    v-for="message in error.container"
                                  >
                                    {{ message }}
                                  </p>
                                </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col">
                                <div class="form-group">
                                  <label for="total_bags"
                                    >Total Quantity (Bags)</label
                                  >
                                  <div
                                    class="input-group"
                                    v-bind:class="
                                      error.total_bags != '' &&
                                      error.total_bags
                                        ? 'is-invalid'
                                        : ''
                                    "
                                  >
                                    <div class="input-group-prepend">
                                      <div class="input-group-text">
                                        <i class="fas fa-sort-amount-up"></i>
                                      </div>
                                    </div>
                                    <input
                                      type="number"
                                      name="total_bags"
                                      id="total_bags"
                                      placeholder="Total bags"
                                      class="form-control"
                                      min="1"
                                      v-model="current_item.total_bags"
                                      :class="
                                        error.total_bags != '' &&
                                        error.total_bags
                                          ? 'is-invalid'
                                          : ''
                                      "
                                    />
                                  </div>
                                  <p
                                    class="invalid-feedback"
                                    v-if="error.total_bags"
                                    v-for="message in error.total_bags"
                                  >
                                    {{ message }}
                                  </p>
                                </div>
                              </div>

                              <div class="col">
                                <div class="form-group">
                                  <label for="net_weight">Net Weight</label>
                                  <div
                                    class="input-group"
                                    v-bind:class="
                                      error.net_weight != '' &&
                                      error.net_weight
                                        ? 'is-invalid'
                                        : ''
                                    "
                                  >
                                    <div class="input-group-prepend">
                                      <div class="input-group-text">
                                        <i class="fas fa-weight-hanging"></i>
                                      </div>
                                    </div>
                                    <input
                                      type="number"
                                      name="net_weight"
                                      id="net_weight"
                                      placeholder="Net Weight"
                                      class="form-control"
                                      min="1"
                                      v-model="current_item.net_weight"
                                      :class="
                                        error.net_weight != '' &&
                                        error.net_weight
                                          ? 'is-invalid'
                                          : ''
                                      "
                                    />
                                  </div>
                                  <p
                                    class="invalid-feedback"
                                    v-if="error.net_weight"
                                    v-for="message in error.net_weight"
                                  >
                                    {{ message }}
                                  </p>
                                </div>
                              </div>

                              <div class="col">
                                <div class="form-group">
                                  <label for="gross_weight"
                                    >Gross Weight</label
                                  >
                                  <div
                                    class="input-group"
                                    v-bind:class="
                                      error.gross_weight != '' &&
                                      error.gross_weight
                                        ? 'is-invalid'
                                        : ''
                                    "
                                  >
                                    <div class="input-group-prepend">
                                      <div class="input-group-text">
                                        <i class="fas fa-weight-hanging"></i>
                                      </div>
                                    </div>
                                    <input
                                      type="number"
                                      name="gross_weight"
                                      id="gross_weight"
                                      placeholder="Gross Weight"
                                      class="form-control"
                                      min="1"
                                      v-model="current_item.gross_weight"
                                      :class="
                                        error.gross_weight != '' &&
                                        error.gross_weight
                                          ? 'is-invalid'
                                          : ''
                                      "
                                    />
                                  </div>
                                  <p
                                    class="invalid-feedback"
                                    v-if="error.gross_weight"
                                    v-for="message in error.gross_weight"
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
                              @click="current_item = {}"
                            >
                              Close
                            </button>
                            <button
                              type="button"
                              class="btn btn-primary"
                              @click="StoreItem(index)"
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
                    href="#modal-new-item"
                  >
                    Add new item
                  </button>
                </p>

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
                                >Description of Goods</label
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
                                  placeholder="Item description"
                                  class="form-control"
                                  v-model="current_item.description"
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
                        </div>

                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="botanical_name">Botanical name</label>
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
                                  name="botanical_name"
                                  id="botanical_name"
                                  placeholder="Item botanical name"
                                  class="form-control"
                                  v-model="current_item.botanical_name"
                                  :class="
                                    error.botanical_name != '' &&
                                    error.botanical_name
                                      ? 'is-invalid'
                                      : ''
                                  "
                                />
                              </div>
                              <p
                                class="invalid-feedback"
                                v-if="error.botanical_name"
                                v-for="message in error.botanical_name"
                              >
                                {{ message }}
                              </p>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="crop_year">Crop Year</label>
                              <div
                                class="input-group"
                                v-bind:class="
                                  error.crop_year != '' && error.crop_year
                                    ? 'is-invalid'
                                    : ''
                                "
                              >
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-calendar"></i>
                                  </div>
                                </div>
                                <input
                                  type="number"
                                  name="crop_year"
                                  id="crop_year"
                                  placeholder="Crop Year"
                                  class="form-control"
                                  min="2017"
                                  v-model="current_item.crop_year"
                                  :class="
                                    error.crop_year != '' && error.crop_year
                                      ? 'is-invalid'
                                      : ''
                                  "
                                />
                              </div>
                              <p
                                class="invalid-feedback"
                                v-if="error.crop_year"
                                v-for="message in error.crop_year"
                              >
                                {{ message }}
                              </p>
                            </div>
                          </div>

                          <div class="col">
                            <div class="form-group">
                              <label for="quantity">Quantity (TON)</label>
                              <div
                                class="input-group"
                                v-bind:class="
                                  error.quantity != '' && error.quantity
                                    ? 'is-invalid'
                                    : ''
                                "
                              >
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-weight-hanging"></i>
                                  </div>
                                </div>
                                <input
                                  @input="calcTotalPrice"
                                  type="number"
                                  name="quantity"
                                  id="quantity"
                                  placeholder="Quantity"
                                  class="form-control"
                                  min="1"
                                  v-model="current_item.quantity"
                                  :class="
                                    error.quantity != '' && error.quantity
                                      ? 'is-invalid'
                                      : ''
                                  "
                                />
                              </div>
                              <p
                                class="invalid-feedback"
                                v-if="error.quantity"
                                v-for="message in error.quantity"
                              >
                                {{ message }}
                              </p>
                            </div>
                          </div>

                          <div class="col">
                            <div class="form-group">
                              <label for="unit_price">Unit Price (US$)</label>
                              <div
                                class="input-group"
                                v-bind:class="
                                  error.unit_price != '' && error.unit_price
                                    ? 'is-invalid'
                                    : ''
                                "
                              >
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-money-bill"></i>
                                  </div>
                                </div>
                                <input
                                  @input="calcTotalPrice"
                                  type="number"
                                  name="unit_price"
                                  id="unit_price"
                                  placeholder="Unit Price"
                                  class="form-control"
                                  min="1"
                                  v-model="current_item.unit_price"
                                  :class="
                                    error.unit_price != '' && error.unit_price
                                      ? 'is-invalid'
                                      : ''
                                  "
                                />
                              </div>
                              <p
                                class="invalid-feedback"
                                v-if="error.unit_price"
                                v-for="message in error.unit_price"
                              >
                                {{ message }}
                              </p>
                            </div>
                          </div>

                          <div class="col">
                            <div class="form-group">
                              <label for="total_price">Total Price (US$)</label>
                              <div
                                class="input-group"
                                v-bind:class="
                                  error.total_price != '' && error.total_price
                                    ? 'is-invalid'
                                    : ''
                                "
                              >
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-money-bill"></i>
                                  </div>
                                </div>
                                <input
                                  type="number"
                                  name="total_price"
                                  id="total_price"
                                  placeholder="Total Price"
                                  class="form-control"
                                  min="1"
                                  v-model="current_item.total_price"
                                  :class="
                                    error.total_price != '' && error.total_price
                                      ? 'is-invalid'
                                      : ''
                                  "
                                />
                              </div>
                              <p
                                class="invalid-feedback"
                                v-if="error.total_price"
                                v-for="message in error.total_price"
                              >
                                {{ message }}
                              </p>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="value">Total Price in Words</label>
                              <div
                                class="input-group"
                                v-bind:class="
                                  error.value != '' && error.value
                                    ? 'is-invalid'
                                    : ''
                                "
                              >
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-money-bill"></i>
                                  </div>
                                </div>
                                <input
                                  type="text"
                                  name="value"
                                  id="value"
                                  placeholder="Value in words"
                                  class="form-control"
                                  v-model="current_item.value"
                                  :class="
                                    error.value != '' && error.value
                                      ? 'is-invalid'
                                      : ''
                                  "
                                />
                              </div>
                              <p
                                class="invalid-feedback"
                                v-if="error.value"
                                v-for="message in error.value"
                              >
                                {{ message }}
                              </p>
                            </div>
                          </div>

                          <div class="col">
                            <div class="form-group">
                              <label for="value">Advance Payment</label>
                              <div
                                class="input-group"
                                v-bind:class="
                                  error.advance_payment != '' &&
                                  error.advance_payment
                                    ? 'is-invalid'
                                    : ''
                                "
                              >
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-money-bill"></i>
                                  </div>
                                </div>
                                <input
                                  type="number"
                                  name="advance_payment"
                                  id="advance_payment"
                                  placeholder="0"
                                  class="form-control"
                                  min="1"
                                  v-model="current_item.advance_payment"
                                  :class="
                                    error.advance_payment != '' &&
                                    error.advance_payment
                                      ? 'is-invalid'
                                      : ''
                                  "
                                />
                              </div>
                              <p
                                class="invalid-feedback"
                                v-if="error.advance_payment"
                                v-for="message in error.advance_payment"
                              >
                                {{ message }}
                              </p>
                            </div>
                          </div>

                          <div class="col">
                            <div class="form-group">
                              <label for="value">Container</label>
                              <div
                                class="input-group"
                                v-bind:class="
                                  error.container != '' && error.container
                                    ? 'is-invalid'
                                    : ''
                                "
                              >
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-weight-hanging"></i>
                                  </div>
                                </div>
                                <input
                                  type="number"
                                  name="container"
                                  id="container"
                                  placeholder="0"
                                  class="form-control"
                                  min="1"
                                  v-model="current_item.container"
                                  :class="
                                    error.container != '' && error.container
                                      ? 'is-invalid'
                                      : ''
                                  "
                                />
                              </div>
                              <p
                                class="invalid-feedback"
                                v-if="error.container"
                                v-for="message in error.container"
                              >
                                {{ message }}
                              </p>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="total_bags"
                                >Total Quantity (Bags)</label
                              >
                              <div
                                class="input-group"
                                v-bind:class="
                                  error.total_bags != '' && error.total_bags
                                    ? 'is-invalid'
                                    : ''
                                "
                              >
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-sort-amount-up"></i>
                                  </div>
                                </div>
                                <input
                                  type="number"
                                  name="total_bags"
                                  id="total_bags"
                                  placeholder="Total bags"
                                  class="form-control"
                                  min="1"
                                  v-model="current_item.total_bags"
                                  :class="
                                    error.total_bags != '' && error.total_bags
                                      ? 'is-invalid'
                                      : ''
                                  "
                                />
                              </div>
                              <p
                                class="invalid-feedback"
                                v-if="error.total_bags"
                                v-for="message in error.total_bags"
                              >
                                {{ message }}
                              </p>
                            </div>
                          </div>

                          <div class="col">
                            <div class="form-group">
                              <label for="net_weight">Net Weight</label>
                              <div
                                class="input-group"
                                v-bind:class="
                                  error.net_weight != '' && error.net_weight
                                    ? 'is-invalid'
                                    : ''
                                "
                              >
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-weight-hanging"></i>
                                  </div>
                                </div>
                                <input
                                  type="number"
                                  name="net_weight"
                                  id="net_weight"
                                  placeholder="Net Weight"
                                  class="form-control"
                                  min="1"
                                  v-model="current_item.net_weight"
                                  :class="
                                    error.net_weight != '' && error.net_weight
                                      ? 'is-invalid'
                                      : ''
                                  "
                                />
                              </div>
                              <p
                                class="invalid-feedback"
                                v-if="error.net_weight"
                                v-for="message in error.net_weight"
                              >
                                {{ message }}
                              </p>
                            </div>
                          </div>

                          <div class="col">
                            <div class="form-group">
                              <label for="gross_weight">Gross Weight</label>
                              <div
                                class="input-group"
                                v-bind:class="
                                  error.gross_weight != '' && error.gross_weight
                                    ? 'is-invalid'
                                    : ''
                                "
                              >
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-weight-hanging"></i>
                                  </div>
                                </div>
                                <input
                                  type="number"
                                  name="gross_weight"
                                  id="gross_weight"
                                  placeholder="Gross Weight"
                                  class="form-control"
                                  min="1"
                                  v-model="current_item.gross_weight"
                                  :class="
                                    error.gross_weight != '' &&
                                    error.gross_weight
                                      ? 'is-invalid'
                                      : ''
                                  "
                                />
                              </div>
                              <p
                                class="invalid-feedback"
                                v-if="error.gross_weight"
                                v-for="message in error.gross_weight"
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

              </div>
            </div>

            <div class="row">
              <div class="col">
                <p
                  class="text-center"
                  v-if="!orderContractsCount"
                  :class="
                    error.contract_provisions != '' && error.contract_provisions
                      ? 'is-invalid'
                      : ''
                  "
                >
                  <strong
                    >No contract_provisions were added to this order.</strong
                  >
                </p>
                <div
                  class="invalid-feedback"
                  v-if="error.contract_provisions"
                  v-for="message in error.contract_provisions"
                >
                  {{ message }}
                </div>

                <table
                  class="table table-striped table-responsive-sm"
                  v-if="orderContractsCount > 0"
                >
                  <tr>
                    <th>Contrato Identifier</th>
                    <th style="width: 120px" class="text-right">Action</th>
                  </tr>
                  <tr v-for="(contract, index) in order.contract_provisions">
                    <td>{{ contract.provider_contract.identifier }}</td>
                    <td class="action-column text-right">
                      <button
                        class="btn btn-sm btn-danger"
                        @click="DeleteContract(index)"
                      >
                        delete
                      </button>

                      <div
                        class="modal fade"
                        :id="'modal-edit-contract-' + index"
                      >
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Edit Contract</h4>
                              <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-hidden="true"
                              >
                                &times;
                              </button>
                            </div>

                            <div class="modal-footer">
                              <button
                                type="button"
                                class="btn btn-default"
                                data-dismiss="modal"
                                @click="current_provider_contract = {}"
                              >
                                Close
                              </button>
                              <button
                                type="button"
                                class="btn btn-primary"
                                @click.prevent="StoreContract(index)"
                              >
                                Save
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                </table>

                <p class="text-right">
                  <button
                    class="btn btn-success"
                    data-toggle="modal"
                    href="#modal-new-contract"
                  >
                    Add new contract
                  </button>
                </p>

                <div class="modal fade" id="modal-new-contract">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">New Contract</h4>
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
                            <label for="contract">Select a contract</label>

                            <multiselect
                              v-model="current_provider_contract.provider_contract_id"
                              track-by="id"
                              :options="contracts"
                              :custom-label="customLabel"
                              :show-labels="false"
                              :allow-empty="false"
                              :class="error.contract_id != '' && error.contract_id ? 'invalid' : ''"
                              >
                            </multiselect>
                                  
                            <p
                              :class="error.contract_id ? 'invalid-feedback' : ''"
                              v-if="error.contract_id"
                              v-for="message in error.contract_id"
                              >
                                {{ message }}
                            </p>
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
                          @click.prevent="StoreNewContract"
                        >
                          Add
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="form-group">
                  <a
                    :href="order.contract"
                    target="_blank"
                    class="btn btn-primary"
                  >
                    Open Contract
                  </a>
                </div>
              </div>

              <div class="col">
                <div class="form-group">
                  <a
                    :href="order.proforma"
                    target="_blank"
                    class="btn btn-primary"
                  >
                    Open Proforma
                  </a>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="form-group">
                  <p>Is Advance Swift Required?</p>
                  <input
                    type="radio"
                    name="swift_advance"
                    id="swift_advance_1"
                    v-model="order.swift_advance"
                    value="1"
                  />&nbsp;<label for="swift_advance_1">Yes</label><br />
                  <input
                    type="radio"
                    name="swift_advance"
                    id="swift_advance_0"
                    v-model="order.swift_advance"
                    value="0"
                  />&nbsp;<label for="swift_advance_0">No</label>
                </div>

                <input type="hidden" class="form-control is-invalid" />
                <p
                  class="invalid-feedback"
                  v-if="error.swift_advance"
                  v-for="message in error.swift_advance"
                >
                  {{ message }}
                </p>
              </div>
              <div class="col">
                <label for="">Signature</label>
                <div
                  class="input-group"
                  v-bind:class="
                    error.signature != '' && error.signature ? 'is-invalid' : ''
                  "
                >
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-university"></i>
                    </div>
                  </div>
                  <select
                    name=""
                    id=""
                    class="form-control"
                    v-model="order.signature_id"
                    v-bind:class="
                      error.signature_id != '' && error.signature_id
                        ? 'is-invalid'
                        : ''
                    "
                  >
                    <option value="">Chose an option</option>
                    <option
                      :value="signature_item.id"
                      v-for="signature_item in signatures"
                    >
                      {{ signature_item.text }}
                    </option>
                  </select>
                </div>
                <input type="hidden" class="form-control is-invalid" />
                <p
                  class="invalid-feedback"
                  v-if="error.signature_id"
                  v-for="message in error.signature_id"
                >
                  {{ message }}
                </p>
              </div>
            </div>
          </div>

          <div class="card-footer text-right">
            <router-link :to="{ name: 'Orders' }" class="btn btn-danger">Cancel</router-link>
            <input type="button" :value="saving ? 'Saving...' : 'Save'" @click="SaveOrder" class="btn btn-success" :disabled="saving"/>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>


<style>
.invalid-feedback {
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
import PopcornBank from '../components/PopcornBank.vue';

export default {
  data() {
    return {
      saving: false,
      loading: true,
      action: "add",

      importers: [],
      exporters:[],
      editors: [],
      order: {
        importer_id: null,
        items: [],
        bank:[],
        document: [],
        specifications: [],
        contract_provisions: [],
        container_type: null,
        incoterm: '',
      },
      newSpecification: {
        description: '',
        error: null
      },
      newDocument: {
        description: '',
        error: null
      },
      error: {},
      current_item: {},
      current_provider_contract: { id: null },
      documentsList: [],
      specificationsList: [],
      banks: [],
      contracts: [],
      signatures: {},

      containerTypes: [
        {
          id: 0,
          name: 'Dry 20'
        },
        {
          id: 1,
          name: 'Dry 40'
        },
        {
          id: 2,
          name: 'Dry 40 High'
        }
      ],

      transportationTypes: [
        {
          id: 0,
          name: 'Multimodal'
        },
        {
          id: 1,
          name: 'Maritime/Inland'
        } 
      ],

      incotermsTypes: [
      {
          id: 0,
          name: 'EXW',
          description: 'Ex Works',
          transportationType: 0
        },
        {
          id: 1,
          name: 'FCA',
          description: 'Free Carrier',
          transportationType: 0
        },
        {
          id: 2,
          name: 'CPT',
          description: 'Carriage Paid To',
          transportationType: 0
        },
        {
          id: 3,
          name: 'CIP',
          description: 'Carriage And Insurance Paid To',
          transportationType: 0
        },
        {
          id: 4,
          name: 'DAP',
          description: 'Delivered At Place',
          transportationType: 0
        },
        {
          id: 5,
          name: 'DPU',
          description: 'Delivered At Place Unloaded',
          transportationType: 0
        },
        {
          id: 6,
          name: 'DDP',
          description: 'Delivered Duty Paid',
          transportationType: 0
        },
        {
          id: 7,
          name: 'FAS',
          description: 'Free Alongside Ship',
          transportationType: 1
        },
        {
          id: 8,
          name: 'FOB',
          description: 'Free On Board',
          transportationType: 1
        },
        {
          id: 9,
          name: 'CFR',
          description: 'Cost And Freight',
          transportationType: 1
        },
        {
          id: 10,
          name: 'CIF',
          description: 'Cost Insurance And Freight',
          transportationType: 1
        }
      ],
      filteredIncoterms: null,
      selectedTransportation: null,
      selectedIncoterm: null,
      containerTypeValue: [],
      incotermMapping: {
        'CIF - ID: 3ef932f3-c2d1-424b-9857-a7eb10433715/ Gross Weight - 76,190.00 KGS': 'CIF',
        'CIF (FDA REGISTRATION - 10137311510)': 'CIF',
        'CIF- ID:3ef932f3-c2d1-424b-9857-a7eb10433715': 'CIF',
        'CIF-DAT': 'CIF',
        'CNF': 'CNF',
        'CRF': 'CRF',
        'DAP': 'DAP',
        'Dat': 'DPU',
        'DAT  FREIGHT PREPAID': 'DPU',
        'DAT (INSURANCE)': 'DPU',
        'DAT/ FREIGHT PREPAID': 'DPU',
        'DATE': 'DPU',
        'EXW': 'EXW',
        'FOB': 'FOB',
        'FOB ( BUT I CAN HIRE THE FREIGHT)': 'FOB',
        'FOB $760 - CFR $ 850 - $90 PER TON FREIGHT': 'FOB',
        'FOB/ FREIGHT PREPAID': 'FOB',
        'CFR': 'CFR',
        'CIF': 'CIF',
        'DPU':'DPU',
      },
    };
  },

  components: {
    PopcornBank
  },

  computed: {

    orderItemsCount() {
      if (this.order.items == undefined) {
        return 0;
      }

      return this.order.items.length;
    },

    orderContractsCount() {
      if (this.order.contract_provisions == undefined) {
        return 0;
      }

      return this.order.contract_provisions.length;
    },
  },

  methods: {

    updateIncoterms() {
      const newIncoterm = this.incotermMapping[this.order.incoterm];
      this.filteredIncoterms = this.incotermsTypes.filter(type => type.transportationType === this.order.transportion.id);
      this.order.incoterm = this.incotermsTypes.filter(type => type.name === newIncoterm)[0];
    },

    ConvertNumberToWords(amount) {
      var words = new Array();
      words[0] = '';
      words[1] = 'One';
      words[2] = 'Two';
      words[3] = 'Three';
      words[4] = 'Four';
      words[5] = 'Five';
      words[6] = 'Six';
      words[7] = 'Seven';
      words[8] = 'Eight';
      words[9] = 'Nine';
      words[10] = 'Ten';
      words[11] = 'Eleven';
      words[12] = 'Twelve';
      words[13] = 'Thirteen';
      words[14] = 'Fourteen';
      words[15] = 'Fifteen';
      words[16] = 'Sixteen';
      words[17] = 'Seventeen';
      words[18] = 'Eighteen';
      words[19] = 'Nineteen';
      words[20] = 'Twenty';
      words[30] = 'Thirty';
      words[40] = 'Forty';
      words[50] = 'Fifty';
      words[60] = 'Sixty';
      words[70] = 'Seventy';
      words[80] = 'Eighty';
      words[90] = 'Ninety';
      amount = amount.toString();
      var atemp = amount.split(".");
      var number = atemp[0].split(",").join("");
      var n_length = number.length;
      var words_string = "";

      if (n_length <= 9) {
          var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
          var received_n_array = new Array();

          for (var i = 0; i < n_length; i++) {
              received_n_array[i] = number.substr(i, 1);
          }

          for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
              n_array[i] = received_n_array[j];
          }

          for (var i = 0, j = 1; i < 9; i++, j++) {
              if (i == 0 || i == 2 || i == 4 || i == 7) {
                  if (n_array[i] == 1) {
                      n_array[j] = 10 + parseInt(n_array[j]);
                      n_array[i] = 0;
                  }
              }
          }

          let value = null;
          for (var i = 0; i < 9; i++) {
              if (i == 0 || i == 2 || i == 4 || i == 7) {
                  value = n_array[i] * 10;
              } else {
                  value = n_array[i];
              }

              if (value != 0) {
                  words_string += words[value] + " ";
              }

              if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
                  words_string += "Crores ";
              }

              if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
                  words_string += "Lakhs ";
              }

              if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
                  words_string += "Thousand ";
              }

              if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
                  words_string += "Hundred and ";
              } else if (i == 6 && value != 0) {
                  words_string += "Hundred ";
              }
          }

          words_string = words_string.split("  ").join(" ");
        }

      this.$data.current_item.value = words_string + "dollars";

    },

    customLabel ({ name }) {
      return `${name}`
    },

    customIncotermsLabel ({ name, description }) {
      const labelName = `${name}`;
      const labelDescription = labelName && `(${description})`;

      return labelName + labelDescription
    },

    callChildStoreFunction() {
      this.$refs.popcornBank.StoreBank(this.$data.action);
    },

    updateLoading(value) {
      this.loading = value;
    },
    
    handleActionSuccess(message) {
      this.getBanks();
      $('#modal-new-bank').modal('hide');
      this.$router.push("/banks");
      this.saving = false;
    },

    handleActionError(message) {
      this.saving = false;
    },

    StoreNewSpecification: function (){
      this.$data.newSpecification.error = {}
      this.$http.post('/api/specifications', this.$data.newSpecification)
      .then((response) => {
        if (response.body.errors) {
          this.$data.newSpecification.error = response.body.errors
          return
        }

      this.getSpecifications();

      $('#modal-new-specification').modal('hide')
      this.$toaster.success(response.body.success);
      })
    },

    StoreNewDocument: function () {
      this.$data.newDocument.error = {}

      this.$http.post('/api/order-document', this.$data.newDocument)
      .then((response) => {
        if (response.body.errors) {
          this.$data.newDocument.error = response.body.errors
          return
        }

        this.getDocuments();

        $('#modal-new-document').modal('hide')
        this.$toaster.success(e.body.success)
      })
    },
    
    FormatNumber: function (value) {
      let value_fixed = parseFloat(value).toFixed(2);
      let [value_decimal, value_fraction] = value_fixed.split(/\./);

      value_decimal = value_decimal.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");

      value_fixed = value_decimal;
      return value_fixed;
    },
    FormatNumberDecimal: function (value) {
      let value_fixed = parseFloat(value).toFixed(2);
      let [value_decimal, value_fraction] = value_fixed.split(/\./);

      value_decimal = value_decimal.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");

      value_fixed = value_decimal + "." + value_fraction;
      return value_fixed;
    },
    FormatPrice: function (value) {
      let value_fixed = parseFloat(value).toFixed(2);
      let [value_decimal, value_fraction] = value_fixed.split(/\./);

      value_decimal = value_decimal.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");

      value_fixed = value_decimal + "." + value_fraction;
      return value_fixed;
    },

    SaveOrder: function () {
      this.saving = true;
      this.$data.error = {};

      const order = {...this.$data.order};
      order.container_type = order.container_type?.id ?? null;
      order.incoterm = this.order.incoterm.name;
      order.transportion = this.order.transportion.name;
      
      this.$http.put("/api/order/" + this.$route.params.id, order).then(function (response) {
        this.$toaster.success(response.body.success);
        location.reload(true);
      })
      .catch(error => {
            this.$data.error = error.body.errors;
          return;
      });

      this.saving = false;
    },

    StoreNewItem: function () {
      this.$data.order.items.push(this.$data.current_item);
      this.$data.current_item = {};

      $("#modal-new-item").modal("hide");
    },

    StoreItem: function (index) {
      this.$data.order.items[index] = this.$data.current_item;
      this.$data.current_item = {};

      $(".modal").modal("hide");
    },

    DeleteItem: function (index) {
      this.$data.order.items.splice(index, 1);
    },
    
    RegisterContract: function (file) {
      if (!file) {
        this.$data.order.contract = null;
        return;
      }
      this.$data.order.contract = file.url;
    },

    RegisterProforma: function (file) {
      if (!file) {
        this.$data.order.proforma = null;
        return;
      }
      this.$data.order.proforma = file.url;
    },
    formatDate: function (date) {
      return date;
    },
    format: function (value) {
      return "R$ " + value;
    },
    calcTotalPrice: function () {
      this.current_item.total_price = 0;

      if (this.current_item.quantity > 0 && this.current_item.unit_price.length > 0 ){
        this.current_item.total_price =
        this.current_item.quantity * this.current_item.unit_price;
      }

      this.ConvertNumberToWords(this.current_item.total_price);
    },

    StoreNewContract: function () {
      this.$data.error = {};
      const provider_contract_id = {'provider_contract_id': this.$data.current_provider_contract.provider_contract_id.id}

      this.$http.post(`/api/orders/${this.$route.params.id}/contract-provisions`, provider_contract_id)
        .then((result) => {
          this.order.contract_provisions.push(result.body);
        })
        .catch((error) => console.error(error));

      $("#modal-new-contract").modal("hide");
      this.$data.current_provider_contract = {};
    },

    StoreContract: function (index) {
      const contract = this.order.contract_provisions[index];
      this.$http
        .put(
          `/api/orders/${this.$route.params.id}/contract-provisions/${contract.id}`,
          this.current_provider_contract
        )
        .then((result) => {
          this.$set(this.order.contract_provisions, index, result.body);
        })
        .catch((error) => console.error(error));

      $(".modal").modal("hide");

      this.$data.current_provider_contract = {};
    },

    DeleteContract: function (index) {
      const contract = this.order.contract_provisions[index];
      this.$http
        .delete(
          `/api/orders/${this.$route.params.id}/contract-provisions/${contract.id}`
        )
        .then((result) => {
          this.order.contract_provisions.splice(index, 1);
        })
        .catch((error) => console.log(error));
    },

    getDocuments: function (){
      this.$http.get("/api/order-document").then((e) => {
        this.$data.loading = false;
        this.$data.documentsList = e.body;
      });
    },

    getSpecifications: function (){
      this.$http.get("/api/specifications").then((e) => {
        this.$data.loading = false;
        this.$data.specificationsList = e.body;
      });
    },

    getBanks: function () {
      this.$http.get("/api/banks").then((e) => {
        this.$data.loading = false;
        this.$data.banks = e.body;

        let bankData = []

        e.body.forEach(bank => {
          bankData.push({
            id:bank.id,
            text:bank.beneficiary_company +' ('+ bank.beneficiary_name +')',
            name: bank.beneficiary_company +' ('+ bank.beneficiary_name +')'
          })
        });

        this.$data.banks = bankData;
      });
    },

  },

  created: function () {
    this.$data.loading = true;
    this.$http.get("/api/users/admins").then((e) => {
      let arrayData = []

      e.body.forEach(element => {
        arrayData.push({
          id:element.id,
          text:element.name,
          name:element.name
        })
      });

      this.$data.exporters = arrayData;
    });

    this.$http.get("/api/users/clients").then((e) => {
      this.$data.loading = false;
      if (!e.body.length) {
        return;
      }

      let arrayData = []

      e.body.forEach(element => {
        arrayData.push({
          id:element.id,
          text:element.name,
          name:element.name
        })
      });

      this.$data.importers = arrayData;

      this.$data.importers.unshift({ id: "", text: "" });
      this.$data.exporters.unshift({ id: "", text: "" });

      this.$http.get("/api/order/" + this.$route.params.id).then((response) => {
        const orderData = response.body;
        this.$data.order = orderData

        const documents = orderData.orders_document_order.map(document => document.document_order_id);

        this.$data.order.document = documents;
        this.order.bank = orderData.banks_id;
        this.order.specifications = orderData.specifications.map(specification => specification.specifications_id);
        this.$data.order.container_type = this.containerTypes.filter(type => type.id === orderData.container_type)[0];
        
        const transportionValidate = /^maritim\d*$/i;
        const transportion = !transportionValidate.test(orderData.transportion) ? 'Maritime/Inland' : orderData.transportion;
        this.order.transportion = this.transportationTypes.filter(type => type.name === transportion)[0];

        this.order.incoterm = orderData.incoterm;
        this.updateIncoterms();
      });
    });

    this.$http.get("/api/users-by-role").then((e) => {
      this.$data.loading = false;
      this.$data.editors = e.body;
    });

    this.getDocuments();
    this.getSpecifications();
    this.getBanks();

    this.$http.get("/api/signatures").then((e) => {
      this.$data.signatures = e.body.signatures;
    });

    this.$http
      .get("/api/providers/contracts")
      .then((result) => {
        this.loading = false;

        const contractsData = result.body.map((contract) => {
          const data = {
            id: contract.id,
            name: contract.identifier,
          }

          return data;
        });

        this.$data.contracts = contractsData;

      })
      .catch((error) => console.error(error));
  },

};
</script>
