<template>
  <div class="container-fluid">
    <div id="ui-view">
      <div class="panel panel-success">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">New order</div>
            </div>
          </div>
          <div class="card-body text-center" v-if="loading">
            Loading data...
          </div>

          <div class="card-body" v-if="!loading">
            <div class="row">
              <div class="col-3">
                <div class="form-group">
                  <label for="name">Order number</label>
                  <div class="input-group" v-bind:class="error.name != '' && error.name ? 'is-invalid' : ''
                    ">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-calculator"></i>
                      </div>
                    </div>
                    <input type="text" name="number" id="number" placeholder="Item number" class="form-control"
                      v-model="order.number" :class="error.number != '' && error.number ? 'is-invalid' : ''
                        " />
                  </div>
                  <div class="invalid-feedback" v-if="error.number" v-for="message in error.number">
                    {{ message }}
                  </div>
                </div>
              </div>
              
              <div class="col-3">
                <label for="importer">Importer</label>
                <div class="form-group reto">

                  <div :class="error.importer != '' && error.importer ? 'invalid' : ''">
                        <multiselect
                          v-model="order.importer_id"
                          placeholder="Select a user importer"
                          track-by="id"
                          :options="usersImporters"
                          :custom-label="customUserImporterLabel"
                          :show-labels="false"
                          :allow-empty="false"
                          >
                        </multiselect>
                      </div>
                      
                      <p
                        :class="error.importer ? 'invalid-feedback' : ''"
                        v-if="error.importer"
                        v-for="message in error.importer"
                        >
                          {{ message }}
                      </p>

                </div>
              </div>

              <div class="col-3">
                <div class="form-group">
                  <label for="exporter">Exporter</label>
                  <div class="form-group reto">

                    <div :class="error.exporter != '' && error.exporter ? 'invalid' : ''">
                      <multiselect
                        v-model="order.exporter_id"
                        placeholder="Select a user exporter"
                        track-by="id"
                        :options="usersExporter"
                        :custom-label="customUserExporterLabel"
                        :show-labels="false"
                        :allow-empty="false"
                        >
                      </multiselect>
                    </div>

                    <p
                        :class="error.exporter ? 'invalid-feedback' : ''"
                        v-if="error.exporter"
                        v-for="message in error.exporter"
                        >
                          {{ message }}
                      </p>

                  </div>
                </div>
              </div>

              <div class="col-3">
                <label for="broker">Broker</label>
                <div class="form-group reto">

                  <div :class="error.broker != '' && error.broker ? 'invalid' : ''">
                    <multiselect
                      v-model="order.broker_id"
                      placeholder="Select a user broker"
                      track-by="id"
                      :options="usersBroker"
                      :custom-label="customUserExporterLabel"
                      :show-labels="false"
                      :allow-empty="false"
                      >
                    </multiselect>
                  </div>

                  <p
                    :class="error.broker ? 'invalid-feedback' : ''"
                    v-if="error.broker"
                    v-for="message in error.broker"
                    >
                      {{ message }}
                  </p>

                </div>
                
              </div>

            </div>

            <div class="row">
              <div class="col">
                <label for="fumigation-agency">Fumigation Agency</label>
                <div class="input-group" v-bind:class="error.name != '' && error.name ? 'is-invalid' : ''
                  ">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-bug"></i>
                    </div>
                  </div>
                  <select name="" id="" class="form-control" v-model="order.fumigation_id" v-bind:class="error.fumigation_id != '' && error.fumigation_id
                    ? 'is-invalid'
                    : ''
                    ">
                    <option value="">Chose an option</option>
                    <option :value="editor.id" v-for="editor in editors.fumigation_agency_id">
                      {{ editor.name }}
                    </option>
                  </select>
                  <div class="invalid-feedback" v-if="error.fumigation_id" v-for="message in error.fumigation_id">
                    {{ message }}
                  </div>
                </div>
              </div>
              <div class="col">
                <label for="insurance-agency">Insurance Agency</label>
                <div class="input-group" v-bind:class="error.name != '' && error.name ? 'is-invalid' : ''
                  ">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-shield-alt"></i>
                    </div>
                  </div>
                  <select name="" id="" class="form-control" v-model="order.weight_id" v-bind:class="error.weight_id != '' && error.weight_id
                    ? 'is-invalid'
                    : ''
                    ">
                    <option value="">Chose an option</option>
                    <option :value="editor.id" v-for="editor in editors.insurance_agency_id">
                      {{ editor.name }}
                    </option>
                  </select>
                  <div class="invalid-feedback" v-if="error.weight_id" v-for="message in error.weight_id">
                    {{ message }}
                  </div>
                </div>
              </div>
              <div class="col">
                <label for="inspection-agency">Inspection Agency</label>
                <div class="input-group" v-bind:class="error.name != '' && error.name ? 'is-invalid' : ''
                  ">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-certificate"></i>
                    </div>
                  </div>
                  <select name="" id="" class="form-control" v-model="order.inspection_id" v-bind:class="error.inspection_id != '' && error.inspection_id
                    ? 'is-invalid'
                    : ''
                    ">
                    <option value="">Chose an option</option>
                    <option :value="editor.id" v-for="editor in editors.inspection_agency_id">
                      {{ editor.name }}
                    </option>
                  </select>
                  <div class="invalid-feedback" v-if="error.inspection_id" v-for="message in error.inspection_id">
                    {{ message }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row margin-bottom">
              <div class="col">
                <label for="forwarding-agent">Forwarding Agent</label>
                <div class="input-group" v-bind:class="error.name != '' && error.name ? 'is-invalid' : ''
                  ">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-forward"></i>
                    </div>
                  </div>
                  <select name="" id="" class="form-control" v-model="order.forwarding_agent_id" v-bind:class="error.forwarding_agent_id != '' &&
                    error.forwarding_agent_id
                    ? 'is-invalid'
                    : ''
                    ">
                    <option value="">Chose an option</option>
                    <option :value="editor.id" v-for="editor in editors.forwarding_agent_id">
                      {{ editor.name }}
                    </option>
                  </select>
                  <div class="invalid-feedback" v-if="error.forwarding_agent_id"
                    v-for="message in error.forwarding_agent_id">
                    {{ message }}
                  </div>
                </div>
              </div>
              <div class="col">
                <label for="terminal-agent">Terminal Agent</label>
                <div class="input-group" v-bind:class="error.terminal_agent_id != '' && error.terminal_agent_id
                  ? 'is-invalid'
                  : ''
                  ">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-forward"></i>
                    </div>
                  </div>
                  <select name="" id="" class="form-control" v-model="order.terminal_agent_id" v-bind:class="error.terminal_agent_id != '' && error.terminal_agent_id
                    ? 'is-invalid'
                    : ''
                    ">
                    <option value="">Chose an option</option>
                    <option :value="editor.id" v-for="editor in editors.terminal_agent_id">
                      {{ editor.name }}
                    </option>
                  </select>
                  <div class="invalid-feedback" v-if="error.terminal_agent_id" v-for="message in error.terminal_agent_id">
                    {{ message }}
                  </div>
                </div>
              </div>

              <div class="col">
                <label for="railway-agent">Railway Agent</label>
                
                <div class="input-group" v-bind:class="error.railway_agent_id != '' && error.railway_agent_id
                  ? 'is-invalid'
                  : ''
                  ">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-forward"></i>
                    </div>
                  </div>
                  <select name="" id="" class="form-control" v-model="order.railway_agent_id" v-bind:class="error.railway_agent_id != '' && error.railway_agent_id
                    ? 'is-invalid'
                    : ''
                    ">
                    <option value="">Chose an option</option>
                    <option :value="editor.id" v-for="editor in editors.railway_agent_id">
                      {{ editor.name }}
                    </option>
                  </select>
                </div>

              </div>

            </div>

            <div class="row">
              <div class="col">
                <label for="notify">Notify</label>
                <div class="form-group reto">

                  <div :class="error.notify_id != '' && error.notify_id ? 'invalid' : ''">
                    <multiselect
                      v-model="order.notify_id"
                      placeholder="Select a user to notify"
                      track-by="id"
                      :options="usersNotify"
                      :custom-label="customUserNotifyLabel"
                      :show-labels="false"
                      :allow-empty="false"
                      >
                    </multiselect>
                  </div>

                  <div class="invalid-feedback" v-if="error.notify_id" v-for="message in error.notify_id">
                    {{ message }}
                  </div>

                </div>
              </div>
              <div class="col">
                <label for="consignee">Consignee</label>
                <div class="form-group reto">

                  <div :class="error.consignee_id != '' && error.consignee_id ? 'invalid' : ''">
                    <multiselect
                      v-model="order.consignee_id"
                      placeholder="Select consignee"
                      track-by="id"
                      :options="consignees"
                      :custom-label="customConsigneeLabel"
                      :show-labels="false"
                      :allow-empty="false"
                      >
                    </multiselect>
                  </div>

                  <div class="invalid-feedback" v-if="error.consignee_id" v-for="message in error.consignee_id">
                    {{ message }}
                  </div>

                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="packing">Packing</label>
                  <div class="input-group" v-bind:class="error.name != '' && error.name ? 'is-invalid' : ''
                    ">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-archive"></i>
                      </div>
                    </div>
                    <input type="text" name="full_packing" id="full_packing" placeholder="Packing" class="form-control"
                      v-model="order.full_packing" :class="error.full_packing != '' && error.full_packing
                        ? 'is-invalid'
                        : ''
                        " />
                  </div>
                  <div class="invalid-feedback" v-if="error.full_packing" v-for="message in error.full_packing">
                    {{ message }}
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <p for="name">Label?</p>
                  <input type="radio" name="label" id="label_1" v-model="order.label" value="1" />&nbsp;
                  <label for="label_1">Yes</label>&emsp;
                  <input type="radio" name="label" id="label_0" v-model="order.label" value="0" />&nbsp;
                  <label for="label_0">No</label>
                </div>
                <input type="hidden" class="form-control is-invalid" />
                <p class="invalid-feedback" v-if="error.label" v-for="message in error.label">
                  {{ message }}
                </p>
              </div>

              <div class="col">
                <div class="form-group">
                  <label for="unit_comission">Comission</label>
                  <div class="input-group" v-bind:class="error.name != '' && error.name ? 'is-invalid' : ''
                    ">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-money-bill"></i>
                      </div>
                    </div>
                    <input type="text" name="unit_comission" id="unit_comission" placeholder="Comission" maxlength="50"
                      class="form-control" min="1" v-model="order.unit_comission" :class="error.unit_comission != '' && error.unit_comission
                        ? 'is-invalid'
                        : ''
                        " />
                    <div class="invalid-feedback" v-if="error.unit_comission" v-for="message in error.unit_comission">
                      {{ message }}
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-6">
                <div class="form-group">
                  <label for="payment-conditions">Payment Conditions</label>
                  <div class="input-group" v-bind:class="error.name != '' && error.name ? 'is-invalid' : ''
                    ">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-credit-card"></i>
                      </div>
                    </div>
                    <input type="text" name="payment_conditions" id="payment_conditions" placeholder="Payment Conditions"
                      class="form-control" v-model="order.payment_conditions" :class="error.payment_conditions != '' &&
                        error.payment_conditions
                        ? 'is-invalid'
                        : ''
                        " />
                  </div>
                  <div class="invalid-feedback" v-if="error.payment_conditions"
                    v-for="message in error.payment_conditions">
                    {{ message }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="port_origin">Port Origin</label>
                  <div class="input-group" v-bind:class="error.name != '' && error.name ? 'is-invalid' : ''
                    ">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-location-arrow"></i>
                      </div>
                    </div>
                    <input type="text" name="port_origin" id="port_origin" placeholder="Port Origin" class="form-control"
                      v-model="order.port_origin" :class="error.port_origin != '' && error.port_origin
                        ? 'is-invalid'
                        : ''
                        " />
                  </div>
                  <div class="invalid-feedback" v-if="error.port_origin" v-for="message in error.port_origin">
                    {{ message }}
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="port_destiny">Port Destiny</label>
                  <div class="input-group" v-bind:class="error.name != '' && error.name ? 'is-invalid' : ''
                    ">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-map-marker"></i>
                      </div>
                    </div>
                    <input type="text" name="port_destiny" id="port_destiny" placeholder="Port Destiny"
                      class="form-control" v-model="order.port_destiny" :class="error.port_destiny != '' && error.port_destiny
                        ? 'is-invalid'
                        : ''
                        " />
                  </div>
                  <div class="invalid-feedback" v-if="error.port_destiny" v-for="message in error.port_destiny">
                    {{ message }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row pb-2">
              <div class="col">
                <label for="banks">Bank Data</label>
                <div class="d-flex">

                  <div class="flex-grow-1">
                    <div class="form-group reto">
                      <div :class="error.banks_id != '' && error.banks_id ? 'invalid' : ''">
                        <multiselect
                          v-model="order.banks_id"
                          placeholder="Select bank"
                          track-by="id"
                          :options="banks"
                          :custom-label="customBankLabel"
                          :show-labels="false"
                          :allow-empty="false"
                          >
                        </multiselect>
                      </div>
                      
                      <p
                        :class="error.banks_id ? 'invalid-feedback' : ''"
                        v-if="error.banks_id"
                        v-for="message in error.banks_id"
                        >
                          {{ message }}
                      </p>
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
                      @click="callChildStoreFunction"
                    >
                      Save
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="row pb-2">

              <div class="col">
                <div class="form-group">
                  <label for="incoterm">Incoterm</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                    <input type="text" class="form-control" v-model="order.incoterm" name="incoterm" 
                    :class="error.incoterm != '' && error.incoterm ? 'is-invalid' : ''"/>
                  </div>
                  <p
                    :class="error.incoterm ? 'invalid-feedback' : ''"
                    v-if="error.incoterm"
                    v-for="message in error.incoterm"
                    >
                      {{ message }}
                  </p>
                </div>
              </div>

              <div class="col">
                <div class="form-group">
                  <label for="shipment">Shipment</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                    <input type="text" class="form-control" v-model="order.shipment" name="shipment" 
                    :class="error.shipment != '' && error.shipment ? 'is-invalid' : ''"/>
                  </div>
                  <p
                    :class="error.shipment ? 'invalid-feedback' : ''"
                    v-if="error.shipment"
                    v-for="message in error.shipment"
                    >
                      {{ message }}
                  </p>
                </div>
              </div>

              <div class="col">
                <div class="form-group">
                  <label for="transportion">Transportion</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                    <input type="text" class="form-control" v-model="order.transportion" name="transportion" 
                    :class="error.transportion != '' && error.transportion ? 'is-invalid' : ''"/>
                  </div>
                  <p
                    :class="error.transportion ? 'invalid-feedback' : ''"
                    v-if="error.transportion"
                    v-for="message in error.transportion"
                    >
                      {{ message }}
                  </p>
                </div>
              </div>

              <div class="col">
                <div class="form-group">
                  <label for="hs_code">HS Code</label>
                  <input-icon type="text" icon="fas fa-box" v-model="order.hs_code" name="hs_code" :error="error.hs_code"
                    id="hs_code" />
                </div>
              </div>

              <div class="col">
                <label for="container_type">Container Type</label>

                <multiselect
                  v-model="order.container_type"
                  track-by="id"
                  :options="containerTypes"
                  :custom-label="customContainerTypeLabel"
                  :show-labels="false"
                  :allow-empty="false"
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
                  <label for="specifications" class="m-0">Specifications</label>
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
                
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-forward"></i>
                    </div>
                  </div>

                  <select multiple name="" id="" class="form-control"
                    v-model="order.specifications"
                    v-bind:class="
                      error.specifications != '' && error.specifications
                      ? 'is-invalid' : ''"
                    >
                    <option value="">Chose an option</option>
                    <option :value="specification.id" v-for="specification in specificationsLst">
                      {{ specification.description }}
                    </option>
                  </select>

                </div>

                <div class="invalid-feedback" v-if="error.specifications" v-for="message in error.specifications">
                  {{ message }}
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
                                        <input type="text" class="form-control" v-model="specificationDescription" name="description" 
                                        :class="error.description != '' && error.description ? 'is-invalid' : ''"/>
                                    </div>
                                </div>
                                <p
                                    :class="error.description ? 'invalid-feedback' : ''"
                                    v-if="error.description"
                                    v-for="message in error.description"
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
                          @click="() => this.$data.error = {}"
                        >
                          Close
                        </button>
                        <button
                          type="button"
                          class="btn btn-primary"
                          @click="StoreNewSpecification"
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

                <div class="d-flex pb-2 align-items-center">
                  <label for="documents" class="m-0">Documents</label>
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
                  <select multiple name="" id="" class="form-control selectpicker" v-model="order.document"
                    v-bind:class="error.document != '' &&
                      error.document
                      ? 'is-invalid'
                      : ''
                      ">
                    <option value="">Chose an option</option>
                    <option :value="document.id" v-for="document in documents">
                      {{ document.description }}
                    </option>
                  </select>
                </div>

                <div class="invalid-feedback" v-if="error.specifications" v-for="message in error.specifications">
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
                                    <label for="qtd_container">Description</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-box"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" v-model="documentDescription" name="description" 
                                        :class="error.description != '' && error.description ? 'is-invalid' : ''"/>
                                    </div>
                                </div>
                                <p
                                    :class="error.description ? 'invalid-feedback' : ''"
                                    v-if="error.description"
                                    v-for="message in error.description"
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
                          @click="() => this.$data.error = {}"
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

            <div class="row mt-2 custom-table-responsive">
              <div class="col">

                <div class="text-center" v-if="order.items.length == 0">
                  <strong>No items were added to this order.</strong>
                </div>

                <table class="table table-striped" v-if="order.items.length > 0">

                  <thead>
                    <tr>
                      <th scope="col">Description</th>
                      <th scope="col">Year</th>
                      <th scope="col">Quantity</th>
                      <th scope="col">Container</th>
                      <th scope="col">Unit price</th>
                      <th scope="col">Total price</th>
                      <th scope="col">Advance Payment</th>
                      <th scope="col">Total bags</th>
                      <th scope="col">Net Weight</th>
                      <th scope="col">Gross Weight</th>
                      <th scope="col" style="width: 120px" class="text-right">Action</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr v-for="(item, index) in order.items">
                      <td data-label="Description">{{ item.description }}</td>
                      <td data-label="Year">{{ item.crop_year }}</td>
                      <td data-label="Quantity">{{ FormatNumberDecimal(item.quantity) }} TONS</td>
                      <td data-label="Container">{{ item.container }}</td>
                      <td data-label="Unit price">US$ {{ FormatPrice(item.unit_price) }}</td>
                      <td data-label="Total price">US$ {{ FormatPrice(item.total_price) }}</td>
                      <td data-label="Advance Payment">US$ {{ FormatPrice(item.advance_payment) }}</td>
                      <td data-label="Total bags">{{ FormatNumberDecimal(item.total_bags) }}</td>
                      <td data-label="Net Weight">{{ FormatNumberDecimal(item.net_weight) }} KGS</td>
                      <td data-label="Gross Weight">{{ FormatNumberDecimal(item.gross_weight) }} KGS</td>
                      <td data-label="Action" class="action-column text-right">
                        <button class="btn btn-sm btn-success" data-toggle="modal" :href="'#modal-edit-item-' + index"
                          @click="current_item = JSON.parse(JSON.stringify(item))">
                          edit
                        </button>
                        <button class="btn btn-sm btn-danger" @click="DeleteItem(index)">
                          delete
                        </button>
  
                        <div class="modal fade" :id="'modal-edit-item-' + index">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title">Edit Item</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                  &times;
                                </button>
                              </div>
                              <div class="modal-body">
                                <div class="row">
                                  <div class="col">
                                    <div class="form-group">
                                      <label for="description-goods">Description of Goods</label>
                                      <div class="input-group" v-bind:class="error.description != '' &&
                                        error.description
                                        ? 'is-invalid'
                                        : ''
                                        ">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">
                                            <i class="fas fa-pen"></i>
                                          </div>
                                        </div>
                                        <input type="text" name="description" id="description"
                                          placeholder="Item description" class="form-control"
                                          v-model="current_item.description" :class="error.description != '' &&
                                            error.description
                                            ? 'is-invalid'
                                            : ''
                                            " />
                                      </div>
                                      <p class="invalid-feedback" v-if="error.description"
                                        v-for="message in error.description">
                                        {{ message }}
                                      </p>
                                    </div>
                                  </div>
                                </div>
  
                                <div class="row">
                                  <div class="col">
                                    <div class="form-group">
                                      <label for="botanical_name">Botanical name</label>
                                      <div class="input-group" v-bind:class="error.description != '' &&
                                        error.description
                                        ? 'is-invalid'
                                        : ''
                                        ">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">
                                            <i class="fas fa-pen"></i>
                                          </div>
                                        </div>
                                        <input type="text" name="botanical_name" id="botanical_name"
                                          placeholder="Item botanical name" class="form-control"
                                          v-model="current_item.botanical_name" :class="error.botanical_name != '' &&
                                            error.botanical_name
                                            ? 'is-invalid'
                                            : ''
                                            " />
                                      </div>
                                      <p class="invalid-feedback" v-if="error.botanical_name"
                                        v-for="message in error.botanical_name">
                                        {{ message }}
                                      </p>
                                    </div>
                                  </div>
                                </div>
  
                                <div class="row">
                                  <div class="col">
                                    <div class="form-group">
                                      <label for="crop_year">Crop Year</label>
                                      <div class="input-group" v-bind:class="error.crop_year != '' && error.crop_year
                                        ? 'is-invalid'
                                        : ''
                                        ">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">
                                            <i class="fas fa-calendar"></i>
                                          </div>
                                        </div>
                                        <input type="number" name="crop_year" id="crop_year" placeholder="Crop Year"
                                          class="form-control" min="2017" v-model="current_item.crop_year" :class="error.crop_year != '' &&
                                            error.crop_year
                                            ? 'is-invalid'
                                            : ''
                                            " />
                                      </div>
                                      <p class="invalid-feedback" v-if="error.crop_year" v-for="message in error.crop_year">
                                        {{ message }}
                                      </p>
                                    </div>
                                  </div>
  
                                  <div class="col">
                                    <div class="form-group">
                                      <label for="quantity">Quantity (TON)</label>
                                      <div class="input-group" v-bind:class="error.quantity != '' && error.quantity
                                        ? 'is-invalid'
                                        : ''
                                        ">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">
                                            <i class="fas fa-weight-hanging"></i>
                                          </div>
                                        </div>
                                        <input @input="calcTotalPrice" type="number" name="quantity" id="quantity"
                                          placeholder="Quantity" class="form-control" min="1"
                                          v-model="current_item.quantity" :class="error.quantity != '' && error.quantity
                                            ? 'is-invalid'
                                            : ''
                                            " />
                                      </div>
                                      <p class="invalid-feedback" v-if="error.quantity" v-for="message in error.quantity">
                                        {{ message }}
                                      </p>
                                    </div>
                                  </div>
  
                                  <div class="col">
                                    <div class="form-group">
                                      <label for="unit_price">Unit Price (US$)</label>
                                      <div class="input-group" v-bind:class="error.unit_price != '' &&
                                        error.unit_price
                                        ? 'is-invalid'
                                        : ''
                                        ">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">
                                            <i class="fas fa-money-bill"></i>
                                          </div>
                                        </div>
                                        <input @input="calcTotalPrice" type="number" name="unit_price" id="unit_price"
                                          placeholder="Unit Price" class="form-control" min="1"
                                          v-model="current_item.unit_price" :class="error.unit_price != '' &&
                                            error.unit_price
                                            ? 'is-invalid'
                                            : ''
                                            " />
                                      </div>
                                      <p class="invalid-feedback" v-if="error.unit_price"
                                        v-for="message in error.unit_price">
                                        {{ message }}
                                      </p>
                                    </div>
                                  </div>
  
                                  <div class="col">
                                    <div class="form-group">
                                      <label for="total_price">Total Price (US$)</label>
                                      <div class="input-group" v-bind:class="error.total_price != '' &&
                                        error.total_price
                                        ? 'is-invalid'
                                        : ''
                                        ">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">
                                            <i class="fas fa-money-bill"></i>
                                          </div>
                                        </div>
                                        <input readonly type="number" name="total_price" id="total_price"
                                          placeholder="Total Price" class="form-control" min="1"
                                          v-model="current_item.total_price" :class="error.total_price != '' &&
                                            error.total_price
                                            ? 'is-invalid'
                                            : ''
                                            " />
                                      </div>
                                      <p class="invalid-feedback" v-if="error.total_price"
                                        v-for="message in error.total_price">
                                        {{ message }}
                                      </p>
                                    </div>
                                  </div>
                                </div>
  
                                <div class="row">
                                  <div class="col">
                                    <div class="form-group">
                                      <label for="value">Total Price in Words</label>
                                      <div class="input-group" v-bind:class="error.value != '' && error.value
                                        ? 'is-invalid'
                                        : ''
                                        ">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">
                                            <i class="fas fa-money-bill"></i>
                                          </div>
                                        </div>
                                        <input type="text" name="value" id="value" placeholder="Value in words"
                                          class="form-control" v-model="current_item.value" :class="error.value != '' && error.value
                                            ? 'is-invalid'
                                            : ''
                                            " />
                                      </div>
                                      <p class="invalid-feedback" v-if="error.value" v-for="message in error.value">
                                        {{ message }}
                                      </p>
                                    </div>
                                  </div>
  
                                  <div class="col">
                                    <div class="form-group">
                                      <label for="value">Advance Payment</label>
                                      <div class="input-group" v-bind:class="error.advance_payment != '' &&
                                        error.advance_payment
                                        ? 'is-invalid'
                                        : ''
                                        ">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">
                                            <i class="fas fa-money-bill"></i>
                                          </div>
                                        </div>
                                        <input type="number" name="advance_payment" id="advance_payment" placeholder="0"
                                          class="form-control" min="1" v-model="current_item.advance_payment" :class="error.advance_payment != '' &&
                                            error.advance_payment
                                            ? 'is-invalid'
                                            : ''
                                            " />
                                      </div>
                                      <p class="invalid-feedback" v-if="error.advance_payment"
                                        v-for="message in error.advance_payment">
                                        {{ message }}
                                      </p>
                                    </div>
                                  </div>
  
                                  <div class="col">
                                    <div class="form-group">
                                      <label for="value">Container</label>
                                      <div class="input-group" v-bind:class="error.container != '' && error.container
                                        ? 'is-invalid'
                                        : ''
                                        ">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">
                                            <i class="fas fa-weight-hanging"></i>
                                          </div>
                                        </div>
                                        <input type="number" name="container" id="container" placeholder="0"
                                          class="form-control" min="1" v-model="current_item.container" :class="error.container != '' &&
                                            error.container
                                            ? 'is-invalid'
                                            : ''
                                            " />
                                      </div>
                                      <p class="invalid-feedback" v-if="error.container" v-for="message in error.container">
                                        {{ message }}
                                      </p>
                                    </div>
                                  </div>
                                </div>
  
                                <div class="row">
                                  <div class="col">
                                    <div class="form-group">
                                      <label for="total_bags">Total Quantity (Bags)</label>
                                      <div class="input-group" v-bind:class="error.total_bags != '' &&
                                        error.total_bags
                                        ? 'is-invalid'
                                        : ''
                                        ">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">
                                            <i class="fas fa-sort-amount-up"></i>
                                          </div>
                                        </div>
                                        <input @input="calcTotalPrice" type="number" name="total_bags" id="total_bags"
                                          placeholder="Total bags" class="form-control" min="1"
                                          v-model="current_item.total_bags" :class="error.total_bags != '' &&
                                            error.total_bags
                                            ? 'is-invalid'
                                            : ''
                                            " />
                                      </div>
                                      <p class="invalid-feedback" v-if="error.total_bags"
                                        v-for="message in error.total_bags">
                                        {{ message }}
                                      </p>
                                    </div>
                                  </div>
  
                                  <div class="col">
                                    <div class="form-group">
                                      <label for="net_weight">Net Weight</label>
                                      <div class="input-group" v-bind:class="error.net_weight != '' &&
                                        error.net_weight
                                        ? 'is-invalid'
                                        : ''
                                        ">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">
                                            <i class="fas fa-weight-hanging"></i>
                                          </div>
                                        </div>
                                        <input type="number" name="net_weight" id="net_weight" placeholder="Net Weight"
                                          class="form-control" min="1" v-model="current_item.net_weight" :class="error.net_weight != '' &&
                                            error.net_weight
                                            ? 'is-invalid'
                                            : ''
                                            " />
                                      </div>
                                      <p class="invalid-feedback" v-if="error.net_weight"
                                        v-for="message in error.net_weight">
                                        {{ message }}
                                      </p>
                                    </div>
                                  </div>
  
                                  <div class="col">
                                    <div class="form-group">
                                      <label for="gross_weight">Gross Weight</label>
                                      <div class="input-group" v-bind:class="error.gross_weight != '' &&
                                        error.gross_weight
                                        ? 'is-invalid'
                                        : ''
                                        ">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">
                                            <i class="fas fa-weight-hanging"></i>
                                          </div>
                                        </div>
                                        <input type="number" name="gross_weight" id="gross_weight"
                                          placeholder="Gross Weight" class="form-control" min="1"
                                          v-model="current_item.gross_weight" :class="error.gross_weight != '' &&
                                            error.gross_weight
                                            ? 'is-invalid'
                                            : ''
                                            " />
                                      </div>
                                      <p class="invalid-feedback" v-if="error.gross_weight"
                                        v-for="message in error.gross_weight">
                                        {{ message }}
                                      </p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"
                                  @click="current_item = {}">
                                  Close
                                </button>
                                <button type="button" class="btn btn-primary" @click.prevent="StoreItem(index)">
                                  Save
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>

                <div class="text-right">
                  <button class="btn btn-success" data-toggle="modal" href="#modal-new-item"  :style="error.items != '' && error.items ? 'border-color: #f86c6b;' : ''">
                    Add new item
                  </button>
                  <div class="invalid-feedback" v-if="error.items" v-for="message in error.items">
                  {{ message }}
                  </div>
                </div>
                <div class="modal fade" id="modal-new-item">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">New Item</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                          &times;
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="description">Description of Goods</label>
                              <div class="input-group" v-bind:class="error.description != '' && error.description
                                ? 'is-invalid'
                                : ''
                                ">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-pen"></i>
                                  </div>
                                </div>
                                <input type="text" name="description" id="description" placeholder="Item description"
                                  class="form-control" v-model="current_item.description" :class="error.description != '' && error.description
                                    ? 'is-invalid'
                                    : ''
                                    " />
                              </div>
                              <p class="invalid-feedback" v-if="error.description" v-for="message in error.description">
                                {{ message }}
                              </p>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="description">Botanical name</label>
                              <div class="input-group" v-bind:class="error.description != '' && error.description
                                ? 'is-invalid'
                                : ''
                                ">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-pen"></i>
                                  </div>
                                </div>
                                <input type="text" name="botanical_name" id="botanical_name"
                                  placeholder="Item botanical name" class="form-control"
                                  v-model="current_item.botanical_name" :class="error.botanical_name != '' &&
                                    error.botanical_name
                                    ? 'is-invalid'
                                    : ''
                                    " />
                              </div>
                              <p class="invalid-feedback" v-if="error.botanical_name"
                                v-for="message in error.botanical_name">
                                {{ message }}
                              </p>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="crop_year">Crop Year</label>
                              <div class="input-group" v-bind:class="error.crop_year != '' && error.crop_year
                                ? 'is-invalid'
                                : ''
                                ">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-calendar"></i>
                                  </div>
                                </div>
                                <input type="number" name="crop_year" id="crop_year" placeholder="Crop Year"
                                  class="form-control" min="2017" v-model="current_item.crop_year" :class="error.crop_year != '' && error.crop_year
                                    ? 'is-invalid'
                                    : ''
                                    " />
                              </div>
                              <p class="invalid-feedback" v-if="error.crop_year" v-for="message in error.crop_year">
                                {{ message }}
                              </p>
                            </div>
                          </div>

                          <div class="col">
                            <div class="form-group">
                              <label for="quantity">Quantity (TON)</label>
                              <div class="input-group" v-bind:class="error.quantity != '' && error.quantity
                                ? 'is-invalid'
                                : ''
                                ">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-weight-hanging"></i>
                                  </div>
                                </div>
                                <input @input="calcTotalPrice" type="number" name="quantity" id="quantity"
                                  placeholder="Quantity" class="form-control" min="1" v-model="current_item.quantity"
                                  :class="error.quantity != '' && error.quantity
                                    ? 'is-invalid'
                                    : ''
                                    " />
                              </div>
                              <p class="invalid-feedback" v-if="error.quantity" v-for="message in error.quantity">
                                {{ message }}
                              </p>
                            </div>
                          </div>

                          <div class="col">
                            <div class="form-group">
                              <label for="unit_price">Unit Price (US$)</label>
                              <div class="input-group" v-bind:class="error.unit_price != '' && error.unit_price
                                ? 'is-invalid'
                                : ''
                                ">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-money-bill"></i>
                                  </div>
                                </div>
                                <input @input="calcTotalPrice" type="number" name="unit_price" id="unit_price"
                                  placeholder="Unit Price" class="form-control" min="1" v-model="current_item.unit_price"
                                  :class="error.unit_price != '' && error.unit_price
                                    ? 'is-invalid'
                                    : ''
                                    " />
                              </div>
                              <p class="invalid-feedback" v-if="error.unit_price" v-for="message in error.unit_price">
                                {{ message }}
                              </p>
                            </div>
                          </div>

                          <div class="col">
                            <div class="form-group">
                              <label for="total_price">Total Price (US$)</label>
                              <div class="input-group" v-bind:class="error.total_price != '' && error.total_price
                                ? 'is-invalid'
                                : ''
                                ">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-money-bill"></i>
                                  </div>
                                </div>
                                <input readonly type="number" name="total_price" id="total_price" placeholder="0"
                                  class="form-control" min="1" v-model="current_item.total_price" :class="error.total_price != '' && error.total_price
                                    ? 'is-invalid'
                                    : ''
                                    " />
                              </div>
                              <p class="invalid-feedback" v-if="error.total_price" v-for="message in error.total_price">
                                {{ message }}
                              </p>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="value">Total Price in Words</label>
                              <div class="input-group" v-bind:class="error.value != '' && error.value
                                ? 'is-invalid'
                                : ''
                                ">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-money-bill"></i>
                                  </div>
                                </div>
                                <input type="text" name="value" id="value" placeholder="Value in words"
                                  class="form-control" v-model="current_item.value" :class="error.value != '' && error.value
                                    ? 'is-invalid'
                                    : ''
                                    " />
                              </div>
                              <p class="invalid-feedback" v-if="error.value" v-for="message in error.value">
                                {{ message }}
                              </p>
                            </div>
                          </div>
                          <div class="col">
                            <div class="form-group">
                              <label for="value">Advance Payment</label>
                              <div class="input-group" v-bind:class="error.advance_payment != '' &&
                                error.advance_payment
                                ? 'is-invalid'
                                : ''
                                ">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-money-bill"></i>
                                  </div>
                                </div>
                                <input type="number" name="advance_payment" id="advance_payment" placeholder="0"
                                  class="form-control" min="1" v-model="current_item.advance_payment" :class="error.advance_payment != '' &&
                                    error.advance_payment
                                    ? 'is-invalid'
                                    : ''
                                    " />
                              </div>
                              <p class="invalid-feedback" v-if="error.advance_payment"
                                v-for="message in error.advance_payment">
                                {{ message }}
                              </p>
                            </div>
                          </div>

                          <div class="col">
                            <div class="form-group">
                              <label for="value">Container</label>
                              <div class="input-group" v-bind:class="error.container != '' && error.container
                                ? 'is-invalid'
                                : ''
                                ">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-weight-hanging"></i>
                                  </div>
                                </div>
                                <input type="number" name="container" id="container" placeholder="0" class="form-control"
                                  min="1" v-model="current_item.container" :class="error.container != '' && error.container
                                    ? 'is-invalid'
                                    : ''
                                    " />
                              </div>
                              <p class="invalid-feedback" v-if="error.container" v-for="message in error.container">
                                {{ message }}
                              </p>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="total_bags">Total Quantity (Bags)</label>
                              <div class="input-group" v-bind:class="error.total_bags != '' && error.total_bags
                                ? 'is-invalid'
                                : ''
                                ">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-sort-amount-up"></i>
                                  </div>
                                </div>
                                <input type="number" name="total_bags" id="total_bags" placeholder="Total bags"
                                  class="form-control" min="1" v-model="current_item.total_bags" :class="error.total_bags != '' && error.total_bags
                                    ? 'is-invalid'
                                    : ''
                                    " />
                              </div>
                              <p class="invalid-feedback" v-if="error.total_bags" v-for="message in error.total_bags">
                                {{ message }}
                              </p>
                            </div>
                          </div>

                          <div class="col">
                            <div class="form-group">
                              <label for="net_weight">Net Weight</label>
                              <div class="input-group" v-bind:class="error.net_weight != '' && error.net_weight
                                ? 'is-invalid'
                                : ''
                                ">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-weight-hanging"></i>
                                  </div>
                                </div>
                                <input type="number" name="net_weight" id="net_weight" placeholder="Net Weight"
                                  class="form-control" min="1" v-model="current_item.net_weight" :class="error.net_weight != '' && error.net_weight
                                    ? 'is-invalid'
                                    : ''
                                    " />
                              </div>
                              <p class="invalid-feedback" v-if="error.net_weight" v-for="message in error.net_weight">
                                {{ message }}
                              </p>
                            </div>
                          </div>

                          <div class="col">
                            <div class="form-group">
                              <label for="gross_weight">Gross Weight</label>
                              <div class="input-group" v-bind:class="error.gross_weight != '' && error.gross_weight
                                ? 'is-invalid'
                                : ''
                                ">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-weight-hanging"></i>
                                  </div>
                                </div>
                                <input type="number" name="gross_weight" id="gross_weight" placeholder="Gross Weight"
                                  class="form-control" min="1" v-model="current_item.gross_weight" :class="error.gross_weight != '' &&
                                    error.gross_weight
                                    ? 'is-invalid'
                                    : ''
                                    " />
                              </div>
                              <p class="invalid-feedback" v-if="error.gross_weight" v-for="message in error.gross_weight">
                                {{ message }}
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                          Close
                        </button>
                        <button type="button" class="btn btn-primary" @click.prevent="StoreNewItem">
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
                <p class="text-center" v-if="order.contract_provisions.length == 0" :class="error.contract_provisions != '' && error.contract_provisions
                  ? 'is-invalid'
                  : ''
                  ">
                  <strong>No contract_provisions were added to this order.</strong>
                </p>
                <div class="invalid-feedback" v-if="error.contract_provisions"
                  v-for="message in error.contract_provisions">
                  {{ message }}
                </div>

                <table class="table table-striped table-responsive-sm" v-if="order.contract_provisions.length > 0">
                  <tr>
                    <th>Contrato Identifier</th>
                    <th style="width: 120px" class="text-right">Action</th>
                  </tr>
                  <tr v-for="(contract, index) in order.contract_provisions">
                    <td>{{ contract.provider_contract.identifier }}</td>
                    <td class="action-column text-right">

                      <button class="btn btn-sm btn-danger" @click="DeleteContract(index)">
                        delete
                      </button>

                      <div class="modal fade" :id="'modal-edit-contract-' + index">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Edit Contract</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                &times;
                              </button>
                            </div>

                            <div class="modal-body">
                              <div class="row">
                                <div class="col">
                                  <label for="contract"></label>
                                  <div class="input-group" v-bind:class="error.contract_id != '' &&
                                    error.contract_id
                                    ? 'is-invalid'
                                    : ''
                                    ">
                                    <div class="input-group-prepend">
                                      <div class="input-group-text">
                                        <i class="fas fa-certificate"></i>
                                      </div>
                                    </div>

                                    <vue-select2 icon="fas fa-certificate"
                                      class="vue-select1 form-control select2-hidden-accessible" name="select1"
                                      :options="contracts" :model.sync="current_provider_contract.provider_contract_id"
                                      v-model="current_provider_contract.provider_contract_id"
                                      placeholder="Select Contract">
                                    </vue-select2>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal"
                                @click="current_provider_contract = {}">
                                Close
                              </button>
                              <button type="button" class="btn btn-primary" @click.prevent="StoreContract(index)">
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
                  <button class="btn btn-success" data-toggle="modal" href="#modal-new-contract">
                    Add new contract
                  </button>
                </p>
                <div class="modal fade" id="modal-new-contract">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">New Contract</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                          &times;
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                          <div class="col">
                            <label for="contract">Select a contract</label>

                            <multiselect
                              v-model="contracts_value"
                              track-by="id"
                              :options="contracts"
                              :custom-label="customLabel"
                              :show-labels="false"
                              :allow-empty="false"
                              @select="SelectProviderContract"
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
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                          Close
                        </button>
                        <button type="button" class="btn btn-primary" @click.prevent="StoreNewContract">
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
                <div class="form-group" style="display: inline-block">
                  <p>Is Swift Advances required?</p>
                  <input type="radio" name="swift_advance" id="swift_advance_1" v-model="order.swift_advance"
                    value="1" />&nbsp;<label for="swift_advance_1">Yes</label><br />
                  <input type="radio" name="swift_advance" id="swift_advance_0" v-model="order.swift_advance"
                    value="0" />&nbsp;<label for="swift_advance_0">No</label>
                </div>

                <input type="hidden" class="form-control is-invalid" />
                <p class="invalid-feedback" v-if="error.swift_advance" v-for="message in error.swift_advance">
                  {{ message }}
                </p>
              </div>

              <div class="col">
                <label for="">Signature</label>
                <div class="input-group" v-bind:class="error.signature != '' && error.signature ? 'is-invalid' : ''
                  ">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-university"></i>
                    </div>
                  </div>
                  <select name="" id="" class="form-control" v-model="order.signature_id" v-bind:class="error.signature_id != '' && error.signature_id
                    ? 'is-invalid'
                    : ''
                    ">
                    <option value="">Chose an option</option>
                    <option :value="signature_item.id" v-for="signature_item in signatures">
                      {{ signature_item.text }}
                    </option>
                  </select>
                </div>
                <input type="hidden" class="form-control is-invalid" />
                <p class="invalid-feedback" v-if="error.signature_id" v-for="message in error.signature_id">
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

@media screen and (max-width: 1250px) {
  .custom-table-responsive .action-column {
    width: 100% !important;
  }

  .custom-table-responsive table {
    border: 0;
  }

  .custom-table-responsive table caption {
    font-size: 1.3em;
  }

  .custom-table-responsive table thead {
    border: none;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
  }

  .custom-table-responsive table tr {
    border-bottom: 3px solid #ddd;
    display: block;
    margin-bottom: .625em;
  }

  .custom-table-responsive table td {
    border-bottom: 1px solid #ddd;
    display: block;
    text-align: right;
  }

  .custom-table-responsive table td::before {
    content: attr(data-label);
    float: left;
    font-weight: bold;
  }

  .custom-table-responsive table td:last-child {
    border-bottom: 0;
  }
}

.input-group .multiselect__tags {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.multiselect__single {
  white-space: nowrap;
  overflow: hidden;
}

/* .multiselect__tags {
    display: inline-block !important;
    width: inherit;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
} */

.is-invalid {
    display: flex !important;
}

.invalid-feedback {
    display: block;
}

.row.margin-bottom {
  margin-bottom: 20px;
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

.caixa-icone~.select2-container {
  width: calc(100% - 39.25px) !important;
}

.invalid .multiselect__tags {
    border-color: #f04124!important;
}

.multiselect__tags {
  padding: 0px 40px 0 8px !important;
  min-height: 35px !important;
}

.multiselect__placeholder {
  margin-bottom: 2px !important;
  padding-top: 7px !important;
}

.multiselect__select {
    height: 34px !important;
}

.multiselect__input, .multiselect__single {
    margin-bottom: 0px !important;
    line-height: 34px !important;
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
        contracts_value: null,

        editors: {
          broker_id: [],
          fumigation_agency_id: [],
          insurance_agency_id: [],
          inspection_agency_id: [],
          forwarding_agent_id: [],
          terminal_agent_id: [],
          railway_agent_id: [],
        },
        order: {
          orders_document_order: [],
          unit_comission: null,
          hs_code: null,
          invoce_balance: null,
          invoce_total: null,
          invoce_advance_value: null,
          invoce_advance_status: null,
          fumigation_agency_id: null,
          insurance_agency_id: null,
          inspection_agency_id: null,
          forwarding_agent_id: null,
          terminal_agent_id: null,
          railway_agent_id: null,
          items: [],
          provider_contracts: [],
          contract_provisions: [],
          fumigation_id: "",
          quality_id: "",
          seguro_id: "",
          weight_id: "",
          inspection_id: "",
          forwarding_agent_id: "",
          specifications: [],
          document: [],
          signature_id: null,
          exporter_id: null,
          importer_id: null,
          broker_id: null,
          notify_id: null,
          container_type: null,
          consignee_id: null,
          banks_id: null,
        },
        error: {},
        current_item: {
          value: null
        },
        current_provider_contract: {},
        
        signatures: {},
        documents: [],
        specifications: [],

        specificationDescription: null,
        documentDescription: null,

        usersImporters: [],
        userImporterValue: [],

        usersExporter: [],
        userExporterValue: [],

        usersBroker: [],
        userBrokerValue: [],

        consignees: [],
        consigneeValue: [],

        userNotifyValue: [],
        usersNotify: [],

        banks: [],
        banksValue: [],

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
        containerTypeValue: [],

        specificationsLst: [],
        contracts: [],
      };
    },
    computed: {
      isInvalid () {
        return this.isTouched && this.value.length === 0
      }
    },
    components: {
      PopcornBank
    },

    methods: {

      customContainerTypeLabel ({ name }) {
        return `${name}`
      },

      customLabel ({ name }) {
        return `${name}`
      },

      getUsersAdmins: async function () {
        await this.$http.get("/api/users/admins").then((response) => {

          let usersData = response.body.map((user) => ({
            id: user.id,
            name: user.name
          }));

          let firstOption = {
            id: null,
            name: 'Select a user'
          }

        // if(this.$data.order.exporter_id){
        //  firstOption = usersData.filter(user => user.id === this.$data.order.exporter_id);
        // }

        this.$data.userExporterValue = firstOption;
        this.$data.usersExporter = [firstOption, ...usersData];

        })
      },

      customUserExporterLabel ({ name }) {
        return `${name}`
      },

      getUsersClients: async function () {
        await this.$http.get("/api/users/clients").then((response) => {
          this.$data.loading = false;

          if (!response.body.length) {
            return;
          }

          let usersData = response.body.map((user) => ({
            id: user.id,
            name: user.name
          }));

          const firstOption = {
            id: null,
            name: 'Select a user'
          }

          this.$data.userImporterValue = firstOption;
          this.$data.usersImporters = [firstOption, ...usersData];

          this.$data.consigneeValue = firstOption;
          this.$data.consignees = [firstOption, ...usersData];

          this.$data.userNotifyValue = firstOption;
          this.$data.usersNotify = [firstOption, ...usersData];

          if (this.$route.query.copy_id) {

            this.$http.get("/api/order/" + this.$route.query.copy_id).then((response) => {
              const orderData = response.body;
              this.$data.order = orderData;

              this.order.contract_provisions = [];
              this.$data.order.importer_id = usersData.filter(user => user.id === orderData.importer_id);
              this.$data.order.broker_id = this.usersBroker.filter(user => user.id === orderData.broker_id);
              this.$data.order.exporter_id = this.usersExporter.filter(user => user.id === orderData.exporter_id);
              this.$data.order.notify_id = usersData.filter(user => user.id === orderData.notify_id);
              this.$data.order.consignee_id = usersData.filter(user => user.id === orderData.consignee_id);
              this.$data.order.banks_id = this.banks.filter(bank => bank.id === orderData.banks_id);
              this.$data.order.container_type = this.containerTypes.filter(container => container.id === orderData.container_type);

              const document_order_id = orderData.orders_document_order.map(
                (e) => {
                  return e.document_order_id;
                });

              this.$data.order.items = orderData.items;
              this.$data.order.orders_document_order = document_order_id;
              this.$data.order.document = document_order_id;

              this.order.specifications = orderData.specifications.map((e) => {
                return e.specifications_id;
              });

              this.containerTypeValue = this.containerTypes.filter(type => type.id === orderData.container_type)
            });
          }
        });
      },

      customUserNotifyLabel ({ name }) {
        return `${name}`
      },
      
      customConsigneeLabel ({ name }) {
        return `${name}`
      },
      
      customUserImporterLabel ({ name }) {
        return `${name}`
      },

      getBanks: function () {
        this.$http.get("/api/banks").then((response) => {
          this.$data.loading = false;

          let bankData = response.body.map((bank) => ({
            id: bank.id,
            bankName: bank.intermediary_name,
            companyName: bank.intermediary_company
          }));

          const firstOption = {
            id: null,
            bankName: '',
            companyName: 'Select a bank'
          }

          this.$data.banksValue = firstOption;
          this.$data.banks = [firstOption, ...bankData];
        });
      },

      customBankLabel ({ companyName, bankName }) {
        const labelCompanyName = companyName && `${companyName}`;
        const labelBankName = bankName && `(${bankName})`;

        return labelCompanyName + labelBankName
      },

      getUsersByRole: function (){
        this.$http.get("/api/users-by-role").then((response) => {
          this.$data.loading = false;

          this.$data.editors = response.body;

          let usersBroker = response.body.broker_id.map((user) => ({
            id: user.id,
            name: user.name,
          }));

          const firstOption = {
            id: null,
            name: 'Select a user'
          }
          
          this.$data.userBrokerValue = firstOption;
          this.$data.usersBroker = [firstOption, ...usersBroker];
        });
      },

      onUserBrokerChange (value) {
        if (value.id === null){
          this.$data.order.broker_id = null
        }else{
          this.$data.order.broker_id = value.id
        }
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
        this.saving = false;
      },

      handleActionError(message) {
        this.saving = false;
      },

      StoreNewSpecification: function (){
        this.$data.error = {}

        this.$http.post('/api/specifications', {'description': this.$data.specificationDescription})
        .then((response) => {
          if (response.body.errors) {
            this.$data.error = response.body.errors
            return
          }

        this.getSpecifications();

        $('#modal-new-specification').modal('hide')
        this.$toaster.success(e.body.success);
        })
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

      StoreNewDocument: function () {
        this.$data.error = {}
        this.$http.post('/api/order-document', {'description': this.$data.documentDescription})
        .then((response) => {
          if (response.body.errors) {
            this.$data.error = response.body.errors
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

      SaveOrder: function () {
        const order = {...this.$data.order};
        
        order.broker_id = order.broker_id?.id ?? null;

        if (!this.$route.query.copy_id) {
          order.notify_id = order.notify_id?.id ?? null;
          order.importer_id = order.importer_id?.id ?? null;
          order.exporter_id = order.exporter_id?.id ?? null;
          order.container_type = order.container_type?.id ?? null;
          order.consignee_id = order.consignee_id?.id ?? null;
          order.banks_id = order.banks_id?.id ?? null;
        }
        
        if (this.$route.query.copy_id) {
          order.importer_id = order.importer_id[0].id;
          order.exporter_id = order.exporter_id[0].id;
          order.banks_id = order.banks_id[0].id;
          order.consignee_id = order.consignee_id[0].id;
          order.container_type = order.container_type[0].id;
          order.notify_id = order.notify_id[0].id;
          order.hs_code = parseInt(order.hs_code);
          order.swift_advance = toString(order.swift_advance);
          order.label = toString(order.label);

          order.items = order.items.map(item => {
            item.quantity = item.quantity.toString();
            item.crop_year = item.crop_year.toString();
            item.unit_price = item.unit_price.toString();
            item.advance_payment = item.advance_payment.toString();
            item.total_bags = item.total_bags.toString();
            item.net_weight = item.net_weight.toString();
            item.gross_weight = item.gross_weight.toString();
            return item;
          });

        }
    
        this.saving = true;
        this.$data.error = {};

        this.$http.post("/api/order", order)
        .then(function (response) {
          this.$toaster.success(response.body.original.success);
          this.$router.push("/order");
        })
        .catch(error => {
            if (error.body.errors) {
              this.$toaster.error('Review required fields');
              this.$data.error = error.body.errors;
            return;
          }
        });

        this.saving = false;
      },

      StoreNewItem: function () {
        this.$data.error = {};
        this.$http.post("/api/item", this.$data.current_item).then(function (e) {
          if (e.body.errors) {
            this.$data.error = e.body.errors;
            return;
          }

          this.$data.order.items.push(this.$data.current_item);

          $("#modal-new-item").modal("hide");
          this.$data.current_item = {};
        });
      },
      StoreItem: function (index) {
        this.$data.error = {};
        this.$http.post("/api/item", this.$data.current_item).then(function (e) {
          if (e.body.errors) {
            this.$data.error = e.body.errors;
            return;
          }

          $(".modal").modal("hide");
          this.$data.order.items[index] = this.$data.current_item;
          this.$data.current_item = {};
        });
      },

      DeleteItem: function (index) {
        this.$data.order.items.splice(index, 1);
      },

      SelectProviderContract: function (event) {
        this.provider_contract = event;
      },

      StoreNewContract: function () {
        this.$data.error = {};
        const provider_contract_id = this.provider_contract.id;

        this.$http.get(`/api/providers/${provider_contract_id}/contracts`)
          .then((result) => {
            const contract = {
              provider_contract_id: result.body.id,
              provider_contract: result.body,
            };

            this.$data.order.contract_provisions.push(contract);
          })
          .catch((error) => {
            console.error(error)
          });

        $("#modal-new-contract").modal("hide");
        this.provider_contract = null;
      },

      StoreContract: function (index) {
        $(".modal").modal("hide");

        this.$http
          .get(
            `/api/providers/${this.current_provider_contract.provider_contract_id}/contracts`
          )
          .then((result) => {
            const contract = {
              provider_contract_id: result.body.id,
              provider_contract: result.body,
            };

            this.$set(this.order.contract_provisions, index, contract);
          })
          .catch((error) => console.error(error));

        this.$data.current_provider_contract = {};
      },

      DeleteContract: function (index) {
        this.order.contract_provisions.splice(index, 1);
      },

      RegisterContract: function (file) {
        this.$data.order.contract = file.url;
      },
      RegisterProforma: function (file) {
        this.$data.order.proforma = file.url;
      },
      FormatPrice: function (value) {
        let value_fixed = parseFloat(value).toFixed(3);
        let [value_decimal, value_fraction] = value_fixed.split(/\./);

        value_decimal = value_decimal.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");

        value_fixed = value_decimal + "." + value_fraction;
        return value_fixed;
      },
      formatDate: function (date) {
        return date;
      },
      format: function (value) {
        return "R$ " + value;
      },
      calcTotalPrice: function () {
        this.current_item.total_price = 0;

        if ( this.current_item.quantity > 0 && this.current_item.unit_price.length > 0 ){
          this.current_item.total_price = this.current_item.quantity * this.current_item.unit_price;
        }

        this.ConvertNumberToWords(this.current_item.total_price);
      },

      getDocuments: function (){
        this.$http.get("/api/order-document").then((e) => {
          this.$data.loading = false;
          this.$data.documents = e.body;
        });
      },

      getSpecifications: function (){
        this.$http.get("/api/specifications").then((e) => {
          this.$data.loading = false;
          this.$data.specificationsLst = e.body;
        });
      },

    },
    mounted: async function () {
      this.$data.loading = true;

      await this.getUsersAdmins();
      this.getBanks();
      this.getUsersClients();
      this.getUsersByRole();
      this.getDocuments();
      this.getSpecifications();

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

      this.$http.get("/api/signatures").then((e) => {
        this.$data.signatures = e.body.signatures;
      });
    },
  };
</script>
