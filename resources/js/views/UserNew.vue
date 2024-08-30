<template>
  <div class="container-fluid">
    <div id="ui-view">
      <div class="panel panel-success">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">New user</div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="name">Name</label>
                  <div
                    class="input-group"
                    v-bind:class="
                      error.name != '' && error.name ? 'is-invalid' : ''
                    "
                  >
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-user"></i>
                      </div>
                    </div>
                    <input
                      type="text"
                      name="name"
                      v-model="user.name"
                      class="form-control"
                      placeholder="Name"
                      v-bind:class="
                        error.name != '' && error.name ? 'is-invalid' : ''
                      "
                    />
                  </div>
                  <div
                    class="invalid-feedback"
                    v-if="error.name"
                    v-for="message in error.name"
                  >
                    {{ message }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="username">Username</label>
                  <div
                    class="input-group"
                    v-bind:class="
                      error.username != '' && error.username ? 'is-invalid' : ''
                    "
                  >
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-user"></i>
                      </div>
                    </div>
                    <input
                      type="text"
                      name="username"
                      id="username"
                      v-model="user.username"
                      class="form-control"
                      placeholder="Username"
                      v-bind:class="
                        error.username != '' && error.username
                          ? 'is-invalid'
                          : ''
                      "
                    />
                  </div>
                  <div
                    class="invalid-feedback"
                    v-if="error.username"
                    v-for="message in error.username"
                  >
                    {{ message }}
                  </div>
                </div>
              </div>

              <div class="col">
                <div class="form-group">
                  <label for="email">E-mail</label>
                  <div
                    class="input-group"
                    v-bind:class="
                      error.email != '' && error.email ? 'is-invalid' : ''
                    "
                  >
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-at"></i>
                      </div>
                    </div>
                    <input
                      type="text"
                      name="email"
                      id="email"
                      v-model="user.email"
                      class="form-control"
                      placeholder="E-mail"
                      v-bind:class="
                        error.email != '' && error.email ? 'is-invalid' : ''
                      "
                    />
                  </div>
                  <div class="invalid-feedback" v-if="error.email">
                    {{ error.email.join("") }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="role">
                    Group
                    <button
                      v-if="current_user.group_id == 1"
                      type="button"
                      class="btn btn-success btn-sm"
                      data-toggle="modal"
                      :href="'#modal-port-add'"
                    >
                      Add
                    </button>
                  </label>

                  <popcorn-group-add modal="modal-port-add" v-if="!$data.loading && current_user.group_id == 1" :getGroups="getGroups" />

                  <div
                    class="input-group"
                    v-bind:class="
                      error.group_id != '' && error.group_id ? 'is-invalid' : ''
                    "
                  >
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                    <select
                      name="role"
                      id="role"
                      v-model="user.group_id"
                      class="form-control"
                      placeholder="Group"
                      :disabled="current_user.group_id != 1"
                      v-bind:class="
                        error.group_id != '' && error.group_id ? 'is-invalid' : ''
                      "
                    >
                      <option :value="group.id" v-for="group in groups">
                        {{ group.name }}
                      </option>
                    </select>
                  </div>
                  <div class="invalid-feedback" v-if="error.group_id">
                    {{ error.group_id.join("") }}
                  </div>
                </div>
              </div>

              <div class="col-6">
                <div class="form-group">
                  <label for="role">Role</label>

                  <div
                    class="input-group"
                    v-bind:class="
                      error.role != '' && error.role ? 'is-invalid' : ''
                    "
                  >
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-user-tag"></i>
                      </div>
                    </div>
                    <select
                      name="role"
                      id="role"
                      v-model="user.role"
                      class="form-control"
                      placeholder="E-mail"
                      v-bind:class="
                        error.role != '' && error.role ? 'is-invalid' : ''
                      "
                    >
                      <option :value="role.id" v-for="role in roles">
                        {{ role.name }}
                      </option>
                    </select>
                  </div>
                  <div class="invalid-feedback" v-if="error.role">
                    {{ error.role.join("") }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="email">CNPJ</label>
                  <div
                    class="input-group"
                    v-bind:class="
                      error.cnpj != '' && error.cnpj ? 'is-invalid' : ''
                    "
                  >
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-building"></i>
                      </div>
                    </div>
                    <input
                      type="text"
                      name="cnpj"
                      id="cnpj"
                      v-model="user.cnpj"
                      class="form-control"
                      placeholder="CNPJ"
                      v-bind:class="
                        error.cnpj != '' && error.cnpj ? 'is-invalid' : ''
                      "
                    />
                  </div>
                  <div class="invalid-feedback" v-if="error.cnpj">
                    {{ error.cnpj.join("") }}
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="email">State Registration</label>
                  <div
                    class="input-group"
                    v-bind:class="
                      error.state_registration != '' && error.state_registration
                        ? 'is-invalid'
                        : ''
                    "
                  >
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-building"></i>
                      </div>
                    </div>
                    <input
                      type="text"
                      name="state_registration"
                      id="state_registration"
                      v-model="user.state_registration"
                      class="form-control"
                      placeholder="State Registration"
                      v-bind:class="
                        error.state_registration != '' &&
                        error.state_registration
                          ? 'is-invalid'
                          : ''
                      "
                    />
                  </div>
                  <div class="invalid-feedback" v-if="error.state_registration">
                    {{ error.state_registration.join("") }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-3">
                <div class="form-group">
                  <p>Is there tax substitution?</p>
                  <input
                    type="radio"
                    name="tax_substitution"
                    id="tax_substitution_1"
                    v-model="user.tax_substitution"
                    value="1"
                  />&nbsp;<label for="tax_substitution_1">Yes</label><br />
                  <input
                    type="radio"
                    name="tax_substitution"
                    id="tax_substitution_0"
                    v-model="user.tax_substitution"
                    value="0"
                  />&nbsp;<label for="tax_substitution_0">No</label>
                </div>

                <input type="hidden" class="form-control is-invalid" />
                <p
                  class="invalid-feedback"
                  v-if="error.tax_substitution"
                  v-for="message in error.tax_substitution"
                >
                  {{ message }}
                </p>
              </div>
               <div class="col-3">
                <div class="form-group">
                  <label for="profile_picture">Profile picture</label>
                  <popcorn-upload
                    name="profile_picture"
                    allowed_pattern="image"
                    v-model="user.profile_picture" 
                    @upload="RegisterImage"
                  ></popcorn-upload>
                </div>
              </div>
            </div>

            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                <a
                  class="nav-link active"
                  id="address-tab"
                  data-toggle="tab"
                  href="#address"
                  role="tab"
                  aria-controls="address"
                  aria-selected="false"
                  >Address</a
                >
              </li>
              <li class="nav-item">
                <a
                  class="nav-link"
                  id="contact-tab"
                  data-toggle="tab"
                  href="#contact"
                  role="tab"
                  aria-controls="contact"
                  aria-selected="false"
                  >Contact</a
                >
              </li>
              <li class="nav-item">
                <a
                  class="nav-link"
                  id="bank-tab"
                  data-toggle="tab"
                  href="#bank"
                  role="tab"
                  aria-controls="bank"
                  aria-selected="false"
                  >Bank</a
                >
              </li>
              <!-- <li class="nav-item">
                <a
                  class="nav-link"
                  id="children-tab"
                  data-toggle="tab"
                  href="#children"
                  role="tab"
                  aria-controls="children"
                  aria-selected="false"
                  >Children</a
                >
              </li> -->
            </ul>
            <div class="tab-content" id="myTabContent">
              <div
                class="tab-pane fade show active"
                id="address"
                role="tabpanel"
                aria-labelledby="address-tab"
              >
                <div class="row">
                  <div class="col">
                    <label for="name">Country</label>
                    <div class="form-group reto">
                      <div class="caixa-icone">
                        <i class="fas fa-globe-americas"></i>
                      </div>
                      <vue-select2
                        class="vue-select1 form-control"
                        name="select1"
                        :options="countries"
                        :model.sync="user.country"
                        v-model="user.country"
                        v-bind:class="
                          error.country != '' && error.country
                            ? 'is-invalid select2-hidden-accessible'
                            : ''
                        "
                      ></vue-select2>
                      <input
                        type="hidden"
                        v-bind:class="
                          error.country != '' && error.country
                            ? 'is-invalid'
                            : ''
                        "
                        class="form-control"
                      />
                      <div class="invalid-feedback" v-if="error.country">
                        {{ error.country.join("") }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="zip">Zip</label>

                      <div
                        class="input-group"
                        v-bind:class="
                          error.zip != '' && error.zip ? 'is-invalid' : ''
                        "
                      >
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="fas fa-envelope"></i>
                          </div>
                        </div>
                        <input
                          type="text"
                          name="zip"
                          v-model="user.zip"
                          class="form-control"
                          placeholder="Zip"
                          v-bind:class="
                            error.zip != '' && error.zip ? 'is-invalid' : ''
                          "
                        />
                      </div>
                      <div class="invalid-feedback" v-if="error.zip">
                        {{ error.zip.join("") }}
                      </div>
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group">
                      <label for="address_1">Address 1</label>
                      <div
                        class="input-group"
                        v-bind:class="
                          error.address_1 != '' && error.address_1
                            ? 'is-invalid'
                            : ''
                        "
                      >
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="fas fa-map-marker-alt"></i>
                          </div>
                        </div>
                        <input
                          type="text"
                          name="address_1"
                          v-model="user.address_1"
                          class="form-control"
                          placeholder="Address 1"
                          v-bind:class="
                            error.address_1 != '' && error.address_1
                              ? 'is-invalid'
                              : ''
                          "
                        />
                      </div>
                      <div class="invalid-feedback" v-if="error.address_1">
                        {{ error.address_1.join("") }}
                      </div>
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group">
                      <label for="address_2">Address 2</label>
                      <div
                        class="input-group"
                        v-bind:class="
                          error.address_2 != '' && error.address_2
                            ? 'is-invalid'
                            : ''
                        "
                      >
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="fas fa-map-marker-alt"></i>
                          </div>
                        </div>
                        <input
                          type="text"
                          name="address_2"
                          v-model="user.address_2"
                          class="form-control"
                          placeholder="Address 2"
                          v-bind:class="
                            error.address_2 != '' && error.address_2
                              ? 'is-invalid'
                              : ''
                          "
                        />
                      </div>
                      <div class="invalid-feedback" v-if="error.address_2">
                        {{ error.address_2.join("") }}
                      </div>
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group">
                      <label for="number">Number</label>
                      <div
                        class="input-group"
                        v-bind:class="
                          error.number != '' && error.number ? 'is-invalid' : ''
                        "
                      >
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="fas fa-map-marker-alt"></i>
                          </div>
                        </div>
                        <input
                          type="text"
                          name="number"
                          v-model="user.number"
                          class="form-control"
                          placeholder="Number"
                          v-bind:class="
                            error.number != '' && error.number
                              ? 'is-invalid'
                              : ''
                          "
                        />
                      </div>
                      <div class="invalid-feedback" v-if="error.number">
                        {{ error.number.join("") }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="state">State</label>
                      <div
                        class="input-group"
                        v-bind:class="
                          error.state != '' && error.state ? 'is-invalid' : ''
                        "
                      >
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="fas fa-university"></i>
                          </div>
                        </div>
                        <input
                          type="text"
                          name="state"
                          v-model="user.state"
                          class="form-control"
                          placeholder="State"
                          v-bind:class="
                            error.state != '' && error.state ? 'is-invalid' : ''
                          "
                        />
                      </div>
                      <div class="invalid-feedback" v-if="error.state">
                        {{ error.state.join("") }}
                      </div>
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group">
                      <label for="city">City</label>
                      <div
                        class="input-group"
                        v-bind:class="
                          error.city != '' && error.city ? 'is-invalid' : ''
                        "
                      >
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="fas fa-city"></i>
                          </div>
                        </div>
                        <input
                          type="text"
                          name="city"
                          v-model="user.city"
                          class="form-control"
                          placeholder="City"
                          v-bind:class="
                            error.city != '' && error.city ? 'is-invalid' : ''
                          "
                        />
                      </div>
                      <div class="invalid-feedback" v-if="error.city">
                        {{ error.city.join("") }}
                      </div>
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group">
                      <label for="neighborhood">District</label>
                      <div
                        class="input-group"
                        v-bind:class="
                          error.neighborhood != '' && error.neighborhood
                            ? 'is-invalid'
                            : ''
                        "
                      >
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="fas fa-building"></i>
                          </div>
                        </div>
                        <input
                          type="text"
                          name="neighborhood"
                          v-model="user.neighborhood"
                          class="form-control"
                          placeholder="District"
                          v-bind:class="
                            error.neighborhood != '' && error.neighborhood
                              ? 'is-invalid'
                              : ''
                          "
                        />
                      </div>
                      <div class="invalid-feedback" v-if="error.neighborhood">
                        {{ error.neighborhood.join("") }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div
                class="tab-pane fade"
                id="contact"
                role="tabpanel"
                aria-labelledby="contact-tab"
              >
                <div class="row" v-for="(contact, index) in user.contacts">
                  <div class="col-3">
                    <div class="form-group">
                      <label for="contact_type_id">Contact type</label>
                      <div
                        class="input-group"
                        v-bind:class="
                          error['contacts.' + index + '.contact_type_id'] !=
                            '' &&
                          error['contacts.' + index + '.contact_type_id']
                            ? 'is-invalid'
                            : ''
                        "
                      >
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="fas fa-id-card"></i>
                          </div>
                        </div>
                        <select
                          name="contact_type_id"
                          v-model="contact.contact_type"
                          @change="
                            contact.contact_type_id = contact.contact_type.id
                          "
                          class="form-control"
                          placeholder="Contact type"
                          v-bind:class="
                            error['contacts.' + index + '.contact_type_id'] !=
                              '' &&
                            error['contacts.' + index + '.contact_type_id']
                              ? 'is-invalid'
                              : ''
                          "
                        >
                          <option value="">Contact Type</option>
                          <option :value="ct" v-for="ct in contact_types">
                            {{ ct.name }}
                          </option>
                        </select>
                      </div>
                      <div
                        class="invalid-feedback"
                        v-if="error['contacts.' + index + '.contact_type_id']"
                      >
                        {{
                          error["contacts." + index + ".contact_type_id"].join(
                            ""
                          )
                        }}
                      </div>
                    </div>
                  </div>

                  <div class="col-7">
                    <div class="form-group">
                      <label for="value">Contact</label>
                      <div
                        class="input-group"
                        v-bind:class="
                          error['contacts.' + index + '.value'] != '' &&
                          error['contacts.' + index + '.value']
                            ? 'is-invalid'
                            : ''
                        "
                      >
                        <div
                          class="input-group-prepend"
                          v-if="contact.contact_type"
                        >
                          <div class="input-group-text">
                            <i :class="contact.contact_type.icon"></i>
                          </div>
                        </div>
                        <input
                          type="text"
                          name="value"
                          v-model="contact.value"
                          class="form-control"
                          placeholder="Contact"
                          v-bind:class="
                            error['contacts.' + index + '.value'] != '' &&
                            error['contacts.' + index + '.value']
                              ? 'is-invalid'
                              : ''
                          "
                        />
                      </div>
                      <div
                        class="invalid-feedback"
                        v-if="error['contacts.' + index + '.value']"
                      >
                        {{ error["contacts." + index + ".value"].join("") }}
                      </div>
                    </div>
                  </div>

                  <div class="col-2 align-bottom">
                    <label for="">&nbsp;</label><br />
                    <button
                      type="text"
                      @click="RemoveContact(index)"
                      class="btn btn-danger btn-block"
                    >
                      <i class="trash"></i>Remove
                    </button>
                  </div>
                </div>

                <div class="row">
                  <div class="col text-right">
                    <input
                      type="button"
                      class="btn btn-success"
                      value="Add new contact"
                      @click="AddNewContact()"
                    />
                  </div>
                </div>
              </div>

              <div
                class="tab-pane fade"
                id="bank"
                role="tabpanel"
                aria-labelledby="bank-tab"
              >
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="beneficiary_bank">Bank</label>
                      <div
                        class="input-group"
                        v-bind:class="
                          error.beneficiary_bank != '' && error.beneficiary_bank
                            ? 'is-invalid'
                            : ''
                        "
                      >
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="fas fa-university"></i>
                          </div>
                        </div>
                        <input
                          type="text"
                          name="beneficiary_bank"
                          id="beneficiary_bank"
                          v-model="user.banks.beneficiary_bank"
                          class="form-control"
                          placeholder="Bank"
                          v-bind:class="
                            error.beneficiary_bank != '' &&
                            error.beneficiary_bank
                              ? 'is-invalid'
                              : ''
                          "
                        />
                      </div>
                      <div
                        class="invalid-feedback"
                        v-if="error.beneficiary_bank"
                      >
                        {{ error.beneficiary_bank.join("") }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="account_nr">Account</label>
                      <div
                        class="input-group"
                        v-bind:class="
                          error.account_nr_one != '' && error.account_nr_one
                            ? 'is-invalid'
                            : ''
                        "
                      >
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="fas fa-sort-amount-down"></i>
                          </div>
                        </div>
                        <input
                          type="text"
                          name="account_nr_one"
                          id="account_nr_one"
                          v-model="user.banks.account_nr_one"
                          class="form-control"
                          placeholder="Account"
                          v-bind:class="
                            error.account_nr_one != '' && error.account_nr_one
                              ? 'is-invalid'
                              : ''
                          "
                        />
                      </div>
                      <div class="invalid-feedback" v-if="error.account_nr_one">
                        {{ error.account_nr_one.join("") }}
                      </div>
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group">
                      <label for="branch_number">Agency</label>
                      <div
                        class="input-group"
                        v-bind:class="
                          error.branch_number != '' && error.branch_number
                            ? 'is-invalid'
                            : ''
                        "
                      >
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="fas fa-building"></i>
                          </div>
                        </div>
                        <input
                          type="text"
                          name="branch_number"
                          id="branch_number"
                          v-model="user.banks.branch_number"
                          class="form-control"
                          placeholder="Agency"
                          v-bind:class="
                            error.branch_number != '' && error.branch_number
                              ? 'is-invalid'
                              : ''
                          "
                        />
                      </div>
                      <div class="invalid-feedback" v-if="error.branch_number">
                        {{ error.branch_number.join("") }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="tab-pane fade"
                id="children"
                role="tabpanel"
                aria-labelledby="children-tab"
              >
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="email">Add New User</label>
                      <div
                        class="input-group"
                        v-bind:class="
                          error.children != '' && error.children
                            ? 'is-invalid'
                            : ''
                        "
                      >
                        <div class="caixa-icone">
                          <i class="fas fa-user"></i>
                        </div>

                        <vue-select2
                          icon="fas fa-user"
                          class="vue-select1 form-control"
                          name="select1"
                          :options="users"
                          :model.sync="children"
                          v-model="children"
                          v-bind:class="
                            error.children != '' && error.children
                              ? 'is-invalid select2-hidden-children'
                              : ''
                          "
                        >
                          <option value="">Select Use</option>
                        </vue-select2>
                      </div>
                      <div class="invalid-feedback" v-if="error.email">
                        {{ error.email.join("") }}
                      </div>

                      <label
                        for="email"
                        class="pt-3"
                        v-if="user.children && user.children.length"
                        >Users</label
                      >

                      <table class="table pt-2" width="100%">
                        <tbody>
                          <template v-for="(userList, i) in users">
                            <template v-for="(child, j) in user.children">
                              <tr v-if="child == userList.id" :key="i + j">
                                <td>{{ userList.name }}</td>
                                <td>
                                  <button
                                    class="btn btn-danger"
                                    @click="RemoveUser(j)"
                                  >
                                    Delete
                                  </button>
                                </td>
                              </tr>
                            </template>
                          </template>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer text-right">
            <router-link :to="{ name: 'Users' }" class="btn btn-danger"
              >Cancel</router-link
            >
            <input
              type="button"
              value="Save"
              @click="Store()"
              class="btn btn-success"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- <popcorn-upload></popcorn-upload> -->
  </div>
</template>

<style>
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

.caixa-icone ~ span.select2-container {
  width: calc(100% - 40.25px) !important;
  float: right;
}
</style>

<script>
import PopcornGroupAdd from "../components/PopcornGroupAdd";

export default {
  props: ["roles","current_user"],
  components:{PopcornGroupAdd},
  data() {
    return {
      contact_types: [
        { id: 1, name: "E-mail comercial", icon: "fas fa-at" },
        { id: 2, name: "E-mail document", icon: "fas fa-at" },
        { id: 3, name: "Phone comercial", icon: "fas fa-phone" },
        { id: 4, name: "Phone document", icon: "fas fa-phone" },
      ],
      users: [],
      groups: [],
      children: null,
      user: {
        name: null,
        username: null,
        email: null,
        password: null,
        neighborhood: null,
        city: null,
        state: null,
        zip: null,
        number: null,
        address_1: null,
        address_2: null,
        profile_picture: null,
        country: null,
        state_registration: null,
        tax_substitution: null,
        contacts: [
          {
            contact_type_id: null,
            contact_type: null,
            value: null,
          },
        ],
        children: [],
        banks: {
          beneficiary_bank: null,
          account_nr_one: null,
          branch_number: null,
        },
      },
      error: {},
    };
  },
  mounted: function () {
    // this.$http.get("/api/users/admins").then((e) => {
    //   let arrayData = [];

    //   e.body.forEach((element) => {
    //     arrayData.push({
    //       id: element.id,
    //       text: element.name,
    //       name: element.name,
    //     });
    //   });

    //   this.$data.users = arrayData;
    //   this.$data.users.unshift({ id: "", text: "" });
    // });

    this.$data.user.group_id = this.current_user.group_id
    this.getGroups();
  },

  watch: {
    children() {
      if (this.children) {
        if (!this.$data.user.children) {
          this.$data.user.children = [];
        }

        if (this.$data.user.children.indexOf(this.children) == -1) {
          this.$data.user.children.push(this.children);
        }

        this.$data.users.unshift({ id: "", text: "" });
        this.children = null;
      }
    },
  },

  computed: {
    countries() {
      return [
        "Afghanistan",
        "Albania",
        "Algeria",
        "Andorra",
        "Angola",
        "Antigua and Barbuda",
        "Argentina",
        "Armenia",
        "Aruba",
        "Australia",
        "Austria",
        "Azerbaijan",
        "Bahamas, The",
        "Bahrain",
        "Bangladesh",
        "Barbados",
        "Belarus",
        "Belgium",
        "Belize",
        "Benin",
        "Bhutan",
        "Bolivia",
        "Bosnia and Herzegovina",
        "Botswana",
        "Brazil",
        "Brunei",
        "Bulgaria",
        "Burkina Faso",
        "Burma",
        "Burundi",
        "Cambodia",
        "Cameroon",
        "Canada",
        "Cabo Verde",
        "Central African Republic",
        "Chad",
        "Chile",
        "China",
        "Colombia",
        "Comoros",
        "Congo, Democratic Republic of the",
        "Congo, Republic of the",
        "Costa Rica",
        "Cote d'Ivoire",
        "Croatia",
        "Cuba",
        "Curacao",
        "Cyprus",
        "Czechia",
        "Denmark",
        "Djibouti",
        "Dominica",
        "Dominican Republic",
        "Ecuador",
        "Egypt",
        "El Salvador",
        "Equatorial Guinea",
        "Eritrea",
        "Estonia",
        "Eswatini",
        "Ethiopia",
        "Fiji",
        "Finland",
        "France",
        "Gabon",
        "Gambia, The",
        "Georgia",
        "Germany",
        "Ghana",
        "Greece",
        "Grenada",
        "Guatemala",
        "Guinea",
        "Guinea-Bissau",
        "Guyana",
        "Haiti",
        "Holy See",
        "Honduras",
        "Hong Kong",
        "Hungary",
        "Iceland",
        "India",
        "Indonesia",
        "Iran",
        "Iraq",
        "Ireland",
        "Israel",
        "Italy",
        "Jamaica",
        "Japan",
        "Jordan",
        "Kazakhstan",
        "Kenya",
        "Kiribati",
        "Korea, North",
        "Korea, South",
        "Kosovo",
        "Kuwait",
        "Kyrgyzstan",
        "Laos",
        "Latvia",
        "Lebanon",
        "Lesotho",
        "Liberia",
        "Libya",
        "Liechtenstein",
        "Lithuania",
        "Luxembourg",
        "Macau",
        "Macedonia",
        "Madagascar",
        "Malawi",
        "Malaysia",
        "Maldives",
        "Mali",
        "Malta",
        "Marshall Islands",
        "Mauritania",
        "Mauritius",
        "Mexico",
        "Micronesia",
        "Moldova",
        "Monaco",
        "Mongolia",
        "Montenegro",
        "Morocco",
        "Mozambique",
        "Namibia",
        "Nauru",
        "Nepal",
        "Netherlands",
        "New Zealand",
        "Nicaragua",
        "Niger",
        "Nigeria",
        "North Korea",
        "Norway",
        "Oman",
        "Pakistan",
        "Palau",
        "Palestinian Territories",
        "Panama",
        "Papua New Guinea",
        "Paraguay",
        "Peru",
        "Philippines",
        "Poland",
        "Portugal",
        "Qatar",
        "Romania",
        "Russia",
        "Rwanda",
        "Saint Kitts and Nevis",
        "Saint Lucia",
        "Saint Vincent and the Grenadines",
        "Samoa",
        "San Marino",
        "Sao Tome and Principe",
        "Saudi Arabia",
        "Senegal",
        "Serbia",
        "Seychelles",
        "Sierra Leone",
        "Singapore",
        "Sint Maarten",
        "Slovakia",
        "Slovenia",
        "Solomon Islands",
        "Somalia",
        "South Africa",
        "South Korea",
        "South Sudan",
        "Spain",
        "Sri Lanka",
        "Sudan",
        "Suriname",
        "Swaziland",
        "Sweden",
        "Switzerland",
        "Syria",
        "Taiwan",
        "Tajikistan",
        "Tanzania",
        "Thailand",
        "Timor-Leste",
        "Togo",
        "Tonga",
        "Trinidad and Tobago",
        "Tunisia",
        "Turkey",
        "Turkmenistan",
        "Tuvalu",
        "Uganda",
        "Ukraine",
        "United Arab Emirates",
        "United Kingdom",
        "United States",
        "Uruguay",
        "Uzbekistan",
        "Vanuatu",
        "Venezuela",
        "Vietnam",
        "Yemen",
        "Zambia",
        "Zimbabwe",
      ].map(function (x) {
        return x.toUpperCase();
      });
    },
  },

  methods: {

    getGroups(id) {
      this.$http.get("/api/groups").then((e) => {
        this.$data.groups = [];
        e.body.forEach((element) => {
          this.$data.groups.push(element);

          if (element.id == id) {
            this.$data.user.group_id = id;
          }
        });
      });
    },

    Store: async function () {
      this.$data.error = {};
      this.$http.post("/api/user", this.$data.user).then(function (e) {
        if (!e.body.success) {
          this.$data.error = e.body.errors;
          return;
        }

        $("body").append(
          $(
            '<div style="height:1px; overflow: hidden;"><iframe src="' +
              e.body.link +
              '"></iframe></div>'
          )
        );
        //$('body').css({'visibility':'hidden;'}).append($('<iframe src="' + e.body.link + '"></iframe>'));
        this.$toaster.success(e.body.success);
        this.$router.push("/users");
      });
    },
    AddNewContact: function () {
      this.$data.user.contacts.push({});
    },
    RemoveUser(index) {
      this.user.children.splice(index, 1);
    },
    RemoveContact: function (index) {
      this.$data.user.contacts.splice(index, 1);
    },
    RegisterImage: function (e) {
      this.$data.user.profile_picture = e.url;
    },
  },
};
</script>
