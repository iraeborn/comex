<template>
  <div class="container-fluid">
    <div id="ui-view">
      <div class="panel panel-success">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">{{ firstCapitalLetter(this.currentPath) }} order</div>
            </div>
          </div>

          <div class="card-body text-center" v-if="loading.page">
            Loading data...
          </div>

          <div class="card-body text-center" v-if="!importers && !loading.page">
            There is no registered importer, to start register an importer
            <router-link :to="{ name: 'New user' }">here</router-link>
          </div>

          <div class="card-body" v-if="importers && !loading.page">
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="name">Order number</label>
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
                
                <label for="importer">Importer</label>
                <div class="form-group reto">

                  <div v-bind:class="error.importer_id != '' && error.importer_id ? 'is-invalid' : ''">
                        <multiselect
                          v-model="order.importer_id"
                          placeholder="Select a user importer"
                          track-by="id"
                          :options="importers"
                          :custom-label="customLabel"
                          :show-labels="false"
                          :allow-empty="true"
                          >
                        </multiselect>
                      </div>
                      
                      <p
                        :class="error.importer_id ? 'invalid-feedback' : ''"
                        v-if="error.importer_id"
                        v-for="message in error.importer_id"
                        >
                          {{ message }}
                      </p>

                </div>
              </div>

              <div class="col">
                <label for="exporter">Exporter</label>
                <div class="form-group reto">

                  <div :class="error.exporter_id != '' && error.exporter_id ? 'is-invalid' : ''">
                        <multiselect
                          v-model="order.exporter_id"
                          placeholder="Select a user exporter"
                          track-by="id"
                          :options="exporters"
                          :custom-label="customLabel"
                          :show-labels="false"
                          :allow-empty="false"
                          >
                        </multiselect>
                      </div>
                      
                      <p
                        :class="error.exporter_id ? 'invalid-feedback' : ''"
                        v-if="error.exporter_id"
                        v-for="message in error.exporter_id"
                        >
                          {{ message }}
                      </p>

                </div>
              </div>

              <div class="col">
                <label for="broker">Broker</label>
                <div class="form-group reto">

                  <div :class="error.broker_id != '' && error.broker_id ? 'is-invalid' : ''">
                        <multiselect
                          v-model="order.broker_id"
                          placeholder="Select a user broker"
                          track-by="id"
                          :options="brokers"
                          :custom-label="customLabel"
                          :show-labels="false"
                          :allow-empty="false"
                          >
                        </multiselect>
                      </div>
                      
                      <p
                        :class="error.broker_id ? 'invalid-feedback' : ''"
                        v-if="error.broker_id"
                        v-for="message in error.broker_id"
                        >
                          {{ message }}
                      </p>

                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <label for="notify">Notify</label>
                <div class="form-group reto">

                  <div :class="error.notify_id != '' && error.notify_id ? 'is-invalid' : ''">
                    <multiselect
                      v-model="order.notify_id"
                      placeholder="Select a user to notify"
                      track-by="id"
                      :options="importers"
                      :custom-label="customLabel"
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
                <label for="name">Consignee</label>
                <div class="form-group reto">

                  <div :class="error.consignee_id != '' && error.consignee_id ? 'is-invalid' : ''">
                    <multiselect
                      v-model="order.consignee_id"
                      placeholder="Select a user to consignee"
                      track-by="id"
                      :options="importers"
                      :custom-label="customLabel"
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
                      @callback="callbackBank"
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
                <label for="transportion">Transportion</label>

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
                <label for="incoterm">Incoterm</label>

                <multiselect
                  ref="incoterm"
                  v-model="order.incoterm"
                  track-by="id"
                  placeholder="Select one"
                  :disabled="incotermIsDissabled"
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
                <label for="container_type">Shipping forecast:</label>
                  <div class="d-flex" style="align-items: center;">
                    <multiselect
                      ref="shipmentFirst"
                      v-model="shipmentFirst"
                      track-by="id"
                      placeholder="Select"
                      @select="handleShipping($event, false)"
                      :options="shippingForecastOptions"
                      :custom-label="customLabel"
                      :show-labels="false"
                      :allow-empty="false"
                      :class="error.shipmentFirst != '' && error.shipmentFirst ? 'invalid' : ''"
                      >
                    </multiselect>
                    /
                    <multiselect
                      ref="shipmentSecond"
                      v-model="shipmentSecond"
                      track-by="id"
                      placeholder="Select"
                      @select="handleShipping($event, true)"
                      :options="shippingForecastOptions"
                      :custom-label="customLabel"
                      :show-labels="false"
                      :allow-empty="false"
                      :class="error.shipmentSecond != '' && error.shipmentSecond ? 'invalid' : ''"
                      >
                    </multiselect>
                  </div>
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
                    multiple name="specification" id="specification" class="form-control selectpicker" v-model="order.specifications"
                    v-bind:class="error.specifications != '' && error.specifications ? 'is-invalid' : ''">
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
                <p v-if="!order.items.length" :class="error.items != '' && error.items ? 'is-invalid text-center' : 'text-center'">
                  <strong>No items were added to this order.</strong>
                  <p class="invalid-feedback" v-if="error.items" v-for="message in error.items">
                    {{ message }}
                  </p>
                </p>

                <table
                  class="table table-striped table-responsive-sm"
                  v-if="order.items.length > 0"
                >
                  <tr>
                    <th>Description</th>
                    <th>HS Code</th>
                    <th>Year</th>
                    <th>Quantity</th>
                    <th>CTR</th>
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
                      <div class="dropdown dropleft">
                          <button class="btn btn-secondary dropdown-toggle" type="button"
                              data-toggle="dropdown" aria-expanded="false">
                          </button>
                          <div class="dropdown-menu">
                              <button class="dropdown-item" @click="openProductModal(item, index)">
                                edit
                              </button>
                              <button class="dropdown-item pendence" @click="DeleteItem(index)">
                                delete
                              </button>

                          </div>
                      </div>

                    </td>

                  </tr>
                  
                </table>

                <p class="text-right">
                  <button
                    class="btn btn-success"
                    data-toggle="modal"
                    @click="openProductModal()"
                  >
                    Add new item
                  </button>
                </p>

              </div>

              <products-modal
                v-if="productModalIsOpen"
                :index="modalIndex"
                :item="modalItem"
                :error="modalError"
                @callback="handleStoreItem"
              ></products-modal>

            </div>

            <div class="row" v-if="this.$data.currentPath == 'edit'">
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
                  <p>Is Advanced Swift Required?</p>
                  <input
                    type="radio"
                    name="swift_advance"
                    id="swift_advance_1"
                    v-model="order.swift_advance"
                    value="1"
                  />&nbsp;<label for="swift_advance_1">Yes</label>
                  
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

                <label for="name">Signature</label>
                <div class="form-group reto">

                  <div :class="error.signature_id != '' && error.signature_id ? 'invalid' : ''">
                    <multiselect
                      v-model="order.signature_id"
                      placeholder="Select a user to consignee"
                      track-by="id"
                      :options="signatures"
                      :custom-label="customLabel"
                      :show-labels="false"
                      :allow-empty="false"
                      >
                    </multiselect>
                  </div>

                  <div class="invalid-feedback" v-if="error.signature_id" v-for="message in error.signature_id">
                    {{ message }}
                  </div>

                </div>
              </div>
            </div>
          </div>

          <div class="card-footer text-right">
            <router-link :to="{ name: 'Orders' }" class="btn btn-danger">Cancel</router-link>
            <!-- <input type="button" :value="saving ? 'Saving...' : 'Save'" @click="OrderAction" class="btn btn-success" :disabled="saving"/> -->

            <button
              type="button"
              class="btn btn-primary"
              @click="() => !loading.save && OrderAction()"
            >
              <b-spinner small v-if="loading.save"></b-spinner>
              {{ !loading.save ? 'Save' : 'Saving' }}
            </button>

          </div>
        </div>
      </div>
    </div>

  </div>
</template>


<style>
.is-invalid .multiselect__tags{
  border-color: #f86c6b;
}

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

.dropdown-submenu {
  position: relative;
}

.dropdown-submenu>.dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: 0px;
  margin-left: -1px;
  -webkit-border-radius: 6px;
  -moz-border-radius: 6px;
  border-radius: 6px;
}

.dropdown-submenu:hover>.dropdown-menu {
  display: block;
}

.dropdown-submenu>#sub-toogle:after {
  display: block;
  content: " ";
  float: right;
  width: 0;
  height: 0;
  border-color: transparent;
  border-style: solid;
  border-width: 5px 0 5px 5px;
  border-left-color: #4e4e4e;
  margin-top: 5px;
  margin-right: -10px;
}

.dropdown-submenu:hover>#sub-toogle:after {
  border-left-color: #4e4e4e;
}

.dropdown-submenu.pull-left {
  float: none;
}

.dropdown-submenu.pull-left>.dropdown-menu {
  left: -100%;
  margin-left: 10px;
  -webkit-border-radius: 6px 0 6px 6px;
  -moz-border-radius: 6px 0 6px 6px;
  border-radius: 6px 0 6px 6px;
}
</style>

<script>
import PopcornBank from '../components/PopcornBank.vue';
import moment from 'moment';

export default {
  data() {
    return {
      key: Date.now(),
      saving: false,
      loading: {
        page: false,
        bank: false,
        save: false
      },
      action: "add",

      importers: [],
      exporters:[],
      brokers: [],
      editors: [],
      signatures: [],
      order: {
        exporter_id: [],
        importer_id: [],
        broker_id: [],
        banks_id: [],
        signature_id: [],
        incoterm: [],
        transportion: [],
        container_type: [],
        items: [],
        bank:[],
        document: [],
        specifications: [],
        contract_provisions: [],
        invoce_advance_value:'',
        invoce_advance_status: '',
        invoce_balance: '',
        invoce_total: '',
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

      shippingForecastOptions:[],
      shipmentFirst: null,
      shipmentSecond: null,
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
      filteredIncoterms: [],
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
      currentPath: null,
      incotermIsDissabled: true,
      modalIndex: {},
      modalItem: {},
      modalError: {},
      productModalIsOpen: false,
      actionProductModaltype: null,
    };
  },

  components: {
    PopcornBank
  },

  methods: {
    allMonths(){
      const shippingForecastOptions = [];
      for (let i = 0; i < 12; i++) {
          const monthObj = {
              id: i + 1,
              name: moment().month(i).format('MMMM')
          };
          shippingForecastOptions.push(monthObj);
      }
      this.shippingForecastOptions = shippingForecastOptions
    },

    orderContractsCount() {
      if (this.order.contract_provisions == undefined) {
        return 0;
      }

      return this.order.contract_provisions.length;
    },

    handleStoreItem(item, index, isEdit) {

      if (isEdit) {
        this.order.items.splice(index, 1, item);
      } else {
        this.order.items.push(item);
      }
    },

    openProductModal(item, index) {
      this.modalIndex = index;
      this.modalItem = item ? JSON.parse(JSON.stringify(item)) : {};
      this.productModalIsOpen = true;

      this.$nextTick(() => {
        $('#modal-product').modal('show');
      });

    },

    whatsIsPath() {
      const path = this.$route.path;

      if (path.includes('/order/edit')) {
        this.currentPath = 'edit';
      } else if (path.includes('/order/copy')) {
        this.currentPath = 'copy';
      } else if (path.includes('/order/new')) {
        this.currentPath = 'new';
        // this.order = {};
        this.order.specifications = [];
        this.order.document = [];
      } else {
        this.currentPath = null;
      }
    },

    firstCapitalLetter(str) {
      return str.charAt(0).toUpperCase() + str.slice(1);
    },

    updateIncoterms() {
      const hasTransportion = this.order.transportion !== undefined;

      const newIncoterm = this.incotermMapping[this.order.incoterm];

      this.filteredIncoterms = hasTransportion ? this.incotermsTypes.filter(type => type.transportationType === this.order.transportion.id) : [];

      if(this.currentPath == 'edit' || this.currentPath == 'copy'){
        this.order.incoterm = this.incotermsTypes.filter(type => type.name === newIncoterm)[0];
      }

      if(hasTransportion){
          this.incotermIsDissabled = false;
      }

    },

    handleShipping(value, isSecond = false){
      if (isSecond) {
        this.shipmentSecond = value;
      } else {
        this.shipmentFirst = value;
      }
      if (this.shipmentFirst && this.shipmentSecond) {
      this.order.shipment = this.shipmentFirst.name + '/' + this.shipmentSecond.name;
    } else {
      this.order.shipment = '';
    }
    },

    customLabel ({ name }) {
      return `${name}`
    },

    customBankLabel ({ companyName, bankName }) {
      const labelCompanyName = companyName || '';
      const labelBankName = bankName || '';

      if (companyName && bankName) {
        return `${labelCompanyName} / ${labelBankName}`;
      } else {
        return labelCompanyName || labelBankName;
      }
    },

    customIncotermsLabel ({ name, description }) {
      const labelName = `${name}`;
      const labelDescription = labelName && `(${description})`;

      return labelName + labelDescription
    },

    callChildStoreFunction() {
      this.$refs.popcornBank.StoreBank(this.$data.action);
    },

    callbackBank({status}) {
      if(status == 'success'){
        this.$toaster.success('New bank add sucessfully');
      }

      if(status == 'error'){
        this.$toaster.error('New bank add sucessfully');
      }

      this.getBanks();
      $('#modal-new-bank').modal('hide');
      this.loading.bank = false;
    },
    
    handleActionError(message) {
      this.saving = false;
    },

    StoreNewSpecification: function (){
      this.newSpecification.error = {}
      this.$http.post('/api/specifications', this.newSpecification)
      .then((response) => {
        if (response.body.errors) {
          this.newSpecification.error = response.body.errors
          return
        }

      this.getSpecifications();

      $('#modal-new-specification').modal('hide')
      this.$toaster.success(response.body.success);
      })
    },

    StoreNewDocument: function () {
      this.newDocument.error = {}

      this.$http.post('/api/order-document', this.newDocument)
      .then((response) => {

        if (response.body.errors) {
          this.newDocument.error = response.body.errors
          return
        }

        this.getDocuments();

        $('#modal-new-document').modal('hide')
        this.$toaster.success(response.body.success)
      })
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

    OrderAction: function (){
      if(this.currentPath == 'edit'){
        this.SaveOrder();
        return;
      }

      this.NewOrder()
    },

    NewOrder: function () {
      const isCopy = this.currentPath == 'copy';

      const order = {...this.$data.order};
      order.broker_id = order.broker_id?.id ?? null;
      order.incoterm = this.order.incoterm.name;
      order.transportion = this.order.transportion.name;
      order.signature_id = order.signature_id?.id;
     

      if (!isCopy) {
        order.notify_id = order.notify_id?.id ?? null;
        order.importer_id = order.importer_id?.id ?? null;
        order.exporter_id = order.exporter_id?.id ?? null;
        order.container_type = order.container_type?.id ?? null;
        order.consignee_id = order.consignee_id?.id ?? null;
        order.banks_id = order.banks_id?.id ?? null;
      }
      
      if (isCopy) {

        order.importer_id = order.importer_id.id;
        order.exporter_id = order.exporter_id.id;
        order.banks_id = order.banks_id.id;
        order.consignee_id = order.consignee_id.id;
        order.container_type = order.container_type.id;
        order.notify_id = order.notify_id.id;
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
          item.hs_code = item.hs_code.toString();
          return item;
        });

      }
  
      this.saving = true;
      this.error = {};

      this.$http.post("/api/order", order)
      .then(function (response) {
        this.$toaster.success(response.body.original.success);
        this.$router.push("/orders");
      })
      .catch(error => {
          if (error.body.errors) {
            this.$toaster.error('Review required fields');
            this.error = error.body.errors;
          return;
        }
      });

      this.saving = false;
    },

    SaveOrder: async function () {

      this.loading.save = true;
      this.$data.error = {};

      const order = {...this.$data.order};
      order.container_type = order.container_type?.id;
      order.importer_id = order.importer_id?.id;
      order.broker_id = order.broker_id?.id;
      order.banks_id = order.banks_id?.id;
      order.consignee_id = order.consignee_id?.id;
      order.notify_id = order.notify_id?.id;
      order.exporter_id = order.exporter_id?.id;
      order.signature_id = order.signature_id?.id;
      order.incoterm = this.order.incoterm?.name;
      order.transportion = this.order.transportion?.name;
      order.label = order.label;

      try {
        const response = await this.$http.put("/api/order/" + this.$route.params.id, order);
        this.$toaster.success(response.body.success);
        this.$router.push("/orders");
      } catch (error) {
        this.$data.error = error.body.errors;
        this.loading.save = false;
        return;
      }      
    },

    DeleteItem: function (index) {
      this.order.items.splice(index, 1);
    },

    StoreNewContract: function () {
      this.error = {};
      const provider_contract_id = {'provider_contract_id': this.$data.current_provider_contract.provider_contract_id.id}

      this.$http.post(`/api/orders/${this.$route.params.id}/contract-provisions`, provider_contract_id)
        .then((result) => {
          this.order.contract_provisions.push(result.body);
        })
        .catch((error) => {
          throw error
        });

      $("#modal-new-contract").modal("hide");
      this.$data.current_provider_contract = {};
    },

    UpdateContract: function (index) {
      const contract = this.order.contract_provisions[index];
      this.$http
        .put(
          `/api/orders/${this.$route.params.id}/contract-provisions/${contract.id}`,
          this.current_provider_contract
        )
        .then((result) => {
          this.$set(this.order.contract_provisions, index, result.body);
        })
        .catch((error) => {
          throw error;
        });

      $(".modal").modal("hide");

      this.current_provider_contract = {};
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
        .catch((error) => {
          throw error;
        });
    },

    getDocuments: function (){
      this.$http.get("/api/order-document").then((response) => {
        // this.$data.loading = false;
        const documents = response.body;
        this.documentsList = documents;
      });
    },

    getSpecifications: function (){
      this.$http.get("/api/specifications").then((response) => {
        // this.$data.loading = false;
        const specifications = response.body;
        this.specificationsList = specifications;
      });
    },

    getBanks: async function() {
      try{
        const response = await this.$http.get("/api/banks");
        // this.$data.loading = false;
        const banks = response.body;
        let bankData = banks ? banks.map((bank) => ({
          id: bank.id,
          bankName: bank.intermediary_name,
          companyName: bank.intermediary_company
        })) : [];

        const firstOption = {
          id: null,
          bankName: '',
          companyName: 'Select a bank'
        }

        this.banksValue = firstOption;
        this.banks = [firstOption, ...bankData];

      } catch (error) {
        throw error;
      };

      },

    getOrders: async function(){
      this.loading.page = true;

      try{
        const response = await this.$http.get("/api/order/" + this.$route.params.id);
        const orderData = response.body;
        this.order = orderData

        if(this.currentPath == 'copy'){
          this.order.number = '';
        }

        const documents = orderData.orders_document_order.map(document => document.document_order_id);
        this.order.document = documents;

        if(orderData.banks_id != undefined){
          this.order.banks_id = this.banks.filter(bank => bank.id === orderData.banks_id)[0];
        }

        this.order.specifications = orderData.specifications.map(specification => specification.specifications_id);
        this.order.container_type = this.containerTypes.filter(type => type.id === orderData.container_type)[0];

        const transportionValidate = /^maritim\d*$/i;
        const transportion = !transportionValidate.test(orderData.transportion) ? 'Maritime/Inland' : orderData.transportion;
        this.order.transportion = this.transportationTypes.filter(type => type.name === transportion)[0];

        if (this.order.shipment.includes('-')) {
          const [year, month] = this.order.shipment.split('-').map(item => parseInt(item));
          const monthName = moment().month(month - 1).format('MMMM');
          this.shipmentFirst = this.shippingForecastOptions.find(option => option.name === monthName);
          this.shipmentSecond = { name: monthName };
        } else {
          const [second, first] = this.order.shipment.split('/').map(item => item.trim());
          this.shipmentFirst = this.shippingForecastOptions.find(option => option.name === first);
          this.shipmentSecond = this.shippingForecastOptions.find(option => option.name === second);
        }

        this.loading.page = false;
      } catch (error) {
        throw error;
      };
    },

    getUsersAdmin: async function(){
      try{
        const response = await this.$http.get("/api/users/admins");

        // this.$data.loading = false;
        const users = response.body;

        const usersData = users ? users.map((user) => ({
          id: user.id,
          name: user.name
        })) : [];
  
        const firstOption = {
          id: null,
          name: 'Select a user'
        }

        this.exporters = usersData;
        const usersExporters = [firstOption, ...usersData];

        if((this.currentPath == 'edit' || this.currentPath == 'copy') && this.order.exporter_id != undefined){
          this.order.exporter_id = usersExporters.filter(user => user.id == this.order.exporter_id)[0];
        }
        
      } catch (error) {
        throw error;
      };
    },

    getUsersExporters: async function(){
      try{
        const response = await this.$http.get("/api/users/exporters");
        const users = response.body;

        const usersData = users ? users.map((user) => ({
          id: user.id,
          name: user.name
        })) : [];
  
        const firstOption = {
          id: null,
          name: 'Select a user'
        }

        this.exporters = usersData;
        const usersExporters = [firstOption, ...usersData];

        if((this.currentPath == 'edit' || this.currentPath == 'copy') && this.order.exporter_id != undefined){
          this.order.exporter_id = usersExporters.filter(user => user.id == this.order.exporter_id)[0];
        }
        
      } catch (error) {
        throw error;
      };
    },

    getUsersClient: async function (){
      try {
        const response = await this.$http.get("/api/users/clients");
        // this.$data.loading = false;
        const users = response.body;

        const usersData = users ? users.map((user) => ({
          id: user.id,
          name: user.name
        })) : [];

        const firstOption = {
          id: null,
          name: 'Select a user'
        }

        this.importers = usersData;
        const usersImporters = [firstOption, ...usersData];

        if(this.currentPath == 'edit' || this.currentPath == 'copy'){
            this.order.importer_id = usersImporters.filter(user => user.id === this.order.importer_id)[0];
            this.order.notify_id = usersImporters.filter(user => user.id === this.order.notify_id)[0];
            this.order.consignee_id = usersImporters.filter(user => user.id === this.order.consignee_id)[0];
        }

      } catch (error) {
        throw error;
      };
    },

    getUsersByRole: async function(){

      try {
        const response = await this.$http.get("/api/users-by-role");
        // this.$data.loading = false;
        
        const editors = response.body;
        this.editors = editors;
        
        const brokersUsersData = editors.broker_id ? editors.broker_id.map((user) => ({
          id: user.id,
          name: user.name
        })) : [];
        
        const firstOption = {
          id: null,
          name: 'Select a user'
        }

        this.brokers = brokersUsersData;
        const usersBrokers = [firstOption, ...brokersUsersData];
        
        if(this.currentPath == 'edit' || this.currentPath == 'copy'){
          this.order.broker_id = this.order.broker_id != undefined ? usersBrokers.filter(user => user.id === this.order.broker_id)[0] : [];
        }

      } catch (error) {
        throw error;
      }
    },

    getSignatures: async function(){
      try{
        const response = await this.$http.get("/api/signatures");
        // this.$data.loading = false;

        const signature = response.body;
        const signaturesData = signature ? signature.map((user) => ({
          id: user.id,
          name: user.text
        })) : [];

        
        const firstOption = {
          id: null,
          name: 'Select a signature'
        }

        const usersSignatures = [firstOption, ...signaturesData];
        this.signatures = usersSignatures;

        
        if(this.currentPath == 'edit' || this.currentPath == 'copy'){
          this.order.signature_id = this.order.signature_id != undefined ? usersSignatures.filter(user => user.id === this.order.signature_id)[0] : [];
        }
        

      } catch(error) {
        throw error;
      };
    },

    getContracts: function(){
      this.$http.get("/api/providers/contracts").then((result) => {
        // this.loading = false;
        const contracts = result.body
        const contractsData = contracts ? contracts.map((contract) => {
          return {
            id: contract.id,
            name: contract.identifier,
          }
        }) : [];
                
        this.contracts = contractsData;

      })
      .catch((error) => {
        throw error;
      });
    }

  },

  created: async function () {

    // this.orderItemsCount();
    this.orderContractsCount();
    this.whatsIsPath();

    this.allMonths();

    try {
      
      await this.getBanks();

      if(this.currentPath == 'edit' || this.currentPath == 'copy'){
        await this.getOrders();
      }

      await this.getUsersExporters();
      await this.getUsersClient();
      await this.getUsersByRole();
      await this.getSignatures();

      this.updateIncoterms();

    } catch (error) {
      console.error('Erro:', error);
      throw error;
    }

    this.getContracts();
    this.getDocuments();
    this.getSpecifications();
    
  },
  watch: {
    $route() {
      this.whatsIsPath()
      this.$forceUpdate();
    },
  },

};
</script>
