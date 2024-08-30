<template>
    <div class="container-fluid nav-left">
        <div id="ui-view">
            <div class="panel panel-success">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                Report ({{ this.$data.totalData }})
                            </div>
                            <div class="d-flex">
                                <div class="btn-group dropstart">
                                    <button
                                        class="btn btn-secondary btn-sm mr-2"
                                        type="button"
                                        @click="AjustContentHeight"
                                    >
                                        {{
                                            (!filterIsHidden
                                                ? "Open "
                                                : "Close ") + "filters"
                                        }}
                                    </button>
                                    <button
                                        class="btn btn-secondary btn-sm dropdown-toggle"
                                        type="button"
                                        data-toggle="dropdown"
                                        aria-expanded="false"
                                    >
                                        Columns
                                    </button>
                                    <div
                                        class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start"
                                    >
                                        <div class="m-0 p-2">
                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="po"
                                                    :checked="columns.po"
                                                    @click="
                                                        columns.po = !columns.po
                                                    "
                                                    disabled
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="po"
                                                >
                                                    PO
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="importerName"
                                                    :checked="
                                                        columns.importerName
                                                    "
                                                    @click="
                                                        columns.importerName =
                                                            !columns.importerName
                                                    "
                                                    disabled
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="importerName"
                                                >
                                                    Importer Name
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="accessKey"
                                                    :checked="columns.accessKey"
                                                    @click="
                                                        () =>
                                                            ShowHideColumns(
                                                                'accessKey'
                                                            )
                                                    "
                                                    :disabled="
                                                        disableCheckbox(
                                                            'accessKey'
                                                        )
                                                    "
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="accessKey"
                                                >
                                                    Access Key
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="country"
                                                    :checked="columns.country"
                                                    @click="
                                                        () =>
                                                            ShowHideColumns(
                                                                'country'
                                                            )
                                                    "
                                                    :disabled="
                                                        disableCheckbox(
                                                            'country'
                                                        )
                                                    "
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="country"
                                                >
                                                    Country
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="date"
                                                    :checked="columns.date"
                                                    @click="
                                                        () =>
                                                            ShowHideColumns(
                                                                'date'
                                                            )
                                                    "
                                                    :disabled="
                                                        disableCheckbox('date')
                                                    "
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="date"
                                                >
                                                    Date
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="weight"
                                                    :checked="columns.weight"
                                                    @click="
                                                        () =>
                                                            ShowHideColumns(
                                                                'weight'
                                                            )
                                                    "
                                                    :disabled="
                                                        disableCheckbox(
                                                            'weight'
                                                        )
                                                    "
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="weight"
                                                >
                                                    Weight (Kg)
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="bank"
                                                    :checked="columns.bank"
                                                    @click="
                                                        () =>
                                                            ShowHideColumns(
                                                                'bank'
                                                            )
                                                    "
                                                    :disabled="
                                                        disableCheckbox('bank')
                                                    "
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="bank"
                                                >
                                                    Bank
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="description"
                                                    :checked="
                                                        columns.description
                                                    "
                                                    @click="
                                                        () =>
                                                            ShowHideColumns(
                                                                'description'
                                                            )
                                                    "
                                                    :disabled="
                                                        disableCheckbox(
                                                            'description'
                                                        )
                                                    "
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="description"
                                                >
                                                    Description
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="transactionType"
                                                    :checked="
                                                        columns.transactionType
                                                    "
                                                    @click="
                                                        () =>
                                                            ShowHideColumns(
                                                                'transactionType'
                                                            )
                                                    "
                                                    :disabled="
                                                        disableCheckbox(
                                                            'transactionType'
                                                        )
                                                    "
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="transactionType"
                                                >
                                                    Transaction type
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="value"
                                                    :checked="columns.value"
                                                    @click="
                                                        () =>
                                                            ShowHideColumns(
                                                                'value'
                                                            )
                                                    "
                                                    :disabled="
                                                        disableCheckbox('value')
                                                    "
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="value"
                                                >
                                                    Value
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="dollar"
                                                    :checked="columns.dollar"
                                                    @click="
                                                        () =>
                                                            ShowHideColumns(
                                                                'dollar'
                                                            )
                                                    "
                                                    :disabled="
                                                        disableCheckbox(
                                                            'dollar'
                                                        )
                                                    "
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="dollar"
                                                >
                                                    Dollar Value
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="converted"
                                                    :checked="columns.converted"
                                                    @click="
                                                        () =>
                                                            ShowHideColumns(
                                                                'converted'
                                                            )
                                                    "
                                                    :disabled="
                                                        disableCheckbox(
                                                            'converted'
                                                        )
                                                    "
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="converted"
                                                >
                                                    Converted value
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="due_code"
                                                    :checked="columns.due_code"
                                                    @click="
                                                        () =>
                                                            ShowHideColumns(
                                                                'due_code'
                                                            )
                                                    "
                                                    :disabled="
                                                        disableCheckbox(
                                                            'due_code'
                                                        )
                                                    "
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="due_code"
                                                >
                                                    Due code
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="reference"
                                                    :checked="columns.reference"
                                                    @click="
                                                        () =>
                                                            ShowHideColumns(
                                                                'reference'
                                                            )
                                                    "
                                                    :disabled="
                                                        disableCheckbox(
                                                            'reference'
                                                        )
                                                    "
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="reference"
                                                >
                                                    Reference
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="cambio_contract"
                                                    :checked="
                                                        columns.cambio_contract
                                                    "
                                                    @click="
                                                        () =>
                                                            ShowHideColumns(
                                                                'cambio_contract'
                                                            )
                                                    "
                                                    :disabled="
                                                        disableCheckbox(
                                                            'cambio_contract'
                                                        )
                                                    "
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="cambio_contract"
                                                >
                                                    Exchang contract
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="filters" v-if="filterIsHidden">
                            <div class="row">
                                <div class="col-8">
                                    <label>Importer</label>
                                    <multiselect
                                        v-model="importersOptionsValue"
                                        placeholder="Select a user importer"
                                        track-by="id"
                                        :options="importersOptions"
                                        :custom-label="customLabel"
                                        :show-labels="false"
                                        :allow-empty="true"
                                    >
                                    </multiselect>
                                </div>

                                <div class="col">
                                    <label>Transaction type</label>
                                    <multiselect
                                        v-model="transactionTypesOptionsValue"
                                        :options="transactionTypesOptions"
                                        :multiple="true"
                                        track-by="id"
                                        :custom-label="customLabel"
                                        :show-labels="false"
                                        :allow-empty="true"
                                    >
                                    </multiselect>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <label for="initial_date"
                                        >Initial Date</label
                                    >
                                    <input
                                        type="date"
                                        class="form-control"
                                        v-model="initial_date"
                                        :disabled="dateDisabled"
                                    />
                                </div>
                                <div class="col">
                                    <label for="final_date">Final Date</label>
                                    <input
                                        type="date"
                                        class="form-control"
                                        v-model="final_date"
                                        :disabled="dateDisabled"
                                    />
                                </div>
                                <div class="col-2">
                                    <div class="row align-items-center h-50">
                                        <div class="col align-self-center">
                                            <label
                                                class="w-100"
                                                for="daysTypesOptions"
                                                >Select the last</label
                                            >
                                            <multiselect
                                                v-model="daysTypesOptionsValue"
                                                :options="daysTypesOptions"
                                                :custom-label="customLabel"
                                                :allow-empty="false"
                                                @select="LastDaysSelector"
                                            >
                                            </multiselect>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="terms">Terms</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="terms"
                                        placeholder="PO, NFE, DU-E, Description"
                                    />
                                </div>

                                <div class="col">
                                    <label for="apply">&nbsp;</label>
                                    <button
                                        class="btn btn-success btn-block"
                                        @click="getReport()"
                                    >
                                        Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div
                            class="row"
                            :style="{
                                'margin-top': filterIsHidden ? '20px' : '0',
                            }"
                        >
                            <div class="col">
                                <div class="row table-line m-0 p-0 table-line-title">
                                    <div class="col-1 p-1" v-if="columns.po">
                                        PO
                                    </div>
                                    <div
                                        class="col-1 p-1"
                                        v-if="columns.accessKey"
                                    >
                                        Access Key
                                    </div>
                                    <div
                                        class="col p-1"
                                        v-if="columns.importerName"
                                    >
                                        Importer name
                                    </div>
                                    <div class="col p-1" v-if="columns.country">
                                        Country
                                    </div>
                                    <div class="col-1 p-1" v-if="columns.date">
                                        Date
                                    </div>
                                    <div
                                        class="col-1 p-1"
                                        v-if="columns.weight"
                                    >
                                        Weight(Kg)
                                    </div>
                                    <div
                                        class="col-1 p-1"
                                        v-if="columns.transactionType"
                                    >
                                        Transaction Type
                                    </div>
                                    <div class="col-1 p-1" v-if="columns.bank">
                                        Bank
                                    </div>
                                    <div
                                        class="col-1 p-1"
                                        v-if="columns.description"
                                    >
                                        Description
                                    </div>
                                    <div class="col-2 p-1" v-if="columns.value">
                                        Value
                                    </div>
                                    <div
                                        class="col-2 p-1"
                                        v-if="columns.dollar"
                                    >
                                        Dollar value
                                    </div>
                                    <div
                                        class="col-2 p-1"
                                        v-if="columns.converted"
                                    >
                                        Converted value
                                    </div>
                                    <div
                                        class="col-1 p-1"
                                        v-if="columns.due_code"
                                    >
                                        Due code
                                    </div>
                                    <div
                                        class="col-1 p-1"
                                        v-if="columns.reference"
                                    >
                                        Reference
                                    </div>
                                    <div
                                        class="col-1 p-1"
                                        v-if="columns.cambio_contract"
                                    >
                                        Exchange contract
                                    </div>
                                </div>

                                <div
                                    v-for="(
                                        transaction, index
                                    ) in reports_filtered"
                                    :key="transaction.id"
                                >
                                    <div
                                        :class="
                                            'row table-line m-0 p-0 ' +
                                            (!transaction.isHidden
                                                ? 'table-line-highlight'
                                                : '')
                                        "
                                        @click="
                                            transaction.isHidden =
                                                !transaction.isHidden
                                        "
                                    >
                                        <div
                                            class="col-1 p-1"
                                            v-if="columns.po"
                                        >
                                            {{ transaction.order.number }}
                                        </div>
                                        <div
                                            class="col-1 p-1"
                                            v-if="columns.accessKey"
                                        >
                                            {{ transaction.order.nf }}
                                        </div>
                                        <div
                                            class="col p-1 ellipsis"
                                            v-if="columns.importerName"
                                        >
                                            {{ transaction.owner.name }}
                                        </div>
                                        <div
                                            class="col p-1"
                                            v-if="columns.country"
                                        >
                                            {{
                                                transaction.owner &&
                                                transaction.owner.country
                                                    ? transaction.owner.country
                                                    : ""
                                            }}
                                        </div>

                                        <div
                                            class="col-1 p-1"
                                            v-if="columns.date"
                                        >
                                            {{
                                                transactionDate(
                                                    transaction.created_at
                                                )
                                            }}
                                        </div>
                                        <div
                                            class="col-1 p-1"
                                            v-if="columns.weight"
                                        >
                                            {{
                                                transaction.order
                                                    ? transaction.qty_ton
                                                    : 0
                                            }}
                                        </div>
                                        <div
                                            class="col-1 p-1"
                                            v-if="columns.transactionType"
                                        >
                                            {{
                                                transaction.transaction_type
                                                    .name
                                            }}
                                        </div>
                                        <div
                                            class="col-1 p-1 ellipsis"
                                            v-if="columns.bank"
                                        >
                                            {{ transaction.bank }}
                                        </div>
                                        <div
                                            class="col-1 p-1 ellipsis"
                                            v-if="columns.description"
                                        >
                                            {{ transaction.description }}
                                        </div>

                                        <div
                                            class="col-2 p-1"
                                            v-if="columns.value"
                                        >
                                            $
                                            {{
                                                transaction.value
                                                    ? FormatNumber(
                                                          transaction.value || 0
                                                      )
                                                    : 0
                                            }}
                                        </div>
                                        <div
                                            class="col-2 p-1"
                                            v-if="columns.dollar"
                                        >
                                            $
                                            {{
                                                transaction.dollar_value
                                                    ? FormatNumber(
                                                          transaction.dollar_value ||
                                                              0,
                                                          4
                                                      )
                                                    : 0
                                            }}
                                        </div>
                                        <div
                                            class="col-2 p-1"
                                            v-if="columns.converted"
                                        >
                                            R$
                                            {{
                                                transaction.converted_value
                                                    ? FormatNumber(
                                                          transaction.converted_value ||
                                                              0
                                                      )
                                                    : 0
                                            }}
                                        </div>
                                        <div
                                            class="col-1 p-1"
                                            v-if="
                                                columns.due_code &&
                                                transaction.order.mapa
                                            "
                                        >
                                            {{
                                                transaction.order.mapa.due_code
                                                    ? transaction.order.mapa
                                                          .due_code
                                                    : ""
                                            }}
                                        </div>
                                        <div
                                            class="col-1 p-1"
                                            v-if="columns.reference"
                                        >
                                            {{
                                                transaction.reference
                                                    ? transaction.reference
                                                    : ""
                                            }}
                                        </div>
                                        <div
                                            class="col-1 p-1"
                                            v-if="columns.cambio_contract"
                                        >
                                            {{
                                                transaction.cambio_contract
                                                    ? transaction.cambio_contract
                                                    : ""
                                            }}
                                        </div>
                                    </div>
                                    <div
                                        class="row sub-table m-0 p-0"
                                        v-if="!transaction.isHidden"
                                    >
                                        <div class="col">
                                            <div class="row p-2">
                                                <div class="col-6">
                                                    <div class="row m-0">
                                                        <div
                                                            class="col col-sm-4 p-1"
                                                            v-if="!columns.po"
                                                        >
                                                            <strong>PO</strong>
                                                        </div>
                                                        <div
                                                            class="col col-sm-8 p-1"
                                                            v-if="!columns.po"
                                                        >
                                                            {{
                                                                transaction
                                                                    .order
                                                                    .number
                                                            }}
                                                        </div>

                                                        <div
                                                            class="col col-sm-4 p-1"
                                                            v-if="
                                                                !columns.accessKey
                                                            "
                                                        >
                                                            <strong
                                                                >Access
                                                                Key</strong
                                                            >
                                                        </div>
                                                        <div
                                                            class="col col-sm-8 p-1"
                                                            v-if="
                                                                !columns.accessKey
                                                            "
                                                        >
                                                            {{
                                                                transaction
                                                                    .order.nf
                                                            }}
                                                        </div>

                                                        <div
                                                            class="col col-sm-4 p-1"
                                                            v-if="
                                                                !columns.importerName
                                                            "
                                                        >
                                                            <strong
                                                                >Importer
                                                                Name</strong
                                                            >
                                                        </div>
                                                        <div
                                                            class="col col-sm-8 p-1"
                                                            v-if="
                                                                !columns.importerName
                                                            "
                                                        >
                                                            {{
                                                                transaction
                                                                    .owner.name
                                                            }}
                                                        </div>

                                                        <div
                                                            class="col col-sm-4 p-1"
                                                            v-if="
                                                                !columns.country
                                                            "
                                                        >
                                                            <strong
                                                                >Country</strong
                                                            >
                                                        </div>
                                                        <div
                                                            class="col col-sm-8 p-1"
                                                            v-if="
                                                                !columns.country
                                                            "
                                                        >
                                                            {{
                                                                transaction.owner &&
                                                                transaction
                                                                    .owner
                                                                    .country
                                                                    ? transaction
                                                                          .owner
                                                                          .country
                                                                    : ""
                                                            }}
                                                        </div>

                                                        <div
                                                            class="col col-sm-4 p-1"
                                                            v-if="!columns.date"
                                                        >
                                                            <strong
                                                                >Date</strong
                                                            >
                                                        </div>
                                                        <div
                                                            class="col col-sm-8 p-1"
                                                            v-if="!columns.date"
                                                        >
                                                            {{
                                                                transactionDate(
                                                                    transaction.created_at
                                                                )
                                                            }}
                                                        </div>
                                                        <div
                                                            class="col col-sm-4 p-1"
                                                            v-if="
                                                                !columns.weight
                                                            "
                                                        >
                                                            <strong
                                                                >Weight(Kg)</strong
                                                            >
                                                        </div>
                                                        <div
                                                            class="col col-sm-8 p-1"
                                                            v-if="
                                                                !columns.weight
                                                            "
                                                        >
                                                            {{
                                                                transaction.order
                                                                    ? transaction.qty_ton
                                                                    : 0
                                                            }}
                                                        </div>
                                                        <div
                                                            class="col col-sm-4 p-1"
                                                            v-if="!columns.bank"
                                                        >
                                                            <strong
                                                                >Bank</strong
                                                            >
                                                        </div>
                                                        <div
                                                            class="col col-sm-8 p-1"
                                                            v-if="!columns.bank"
                                                        >
                                                            {{
                                                                transaction.bank
                                                            }}
                                                        </div>

                                                        <div
                                                            class="col col-sm-4 p-1"
                                                            v-if="
                                                                !columns.description
                                                            "
                                                        >
                                                            <strong
                                                                >Description</strong
                                                            >
                                                        </div>
                                                        <div
                                                            class="col col-sm-8 p-1"
                                                            v-if="
                                                                !columns.description
                                                            "
                                                        >
                                                            {{
                                                                transaction.description
                                                            }}
                                                        </div>

                                                        <div
                                                            class="col col-sm-4 p-1"
                                                            v-if="
                                                                !columns.transactionType
                                                            "
                                                        >
                                                            <strong
                                                                >Transaction
                                                                type</strong
                                                            >
                                                        </div>
                                                        <div
                                                            class="col col-sm-8 p-1"
                                                            v-if="
                                                                !columns.transactionType
                                                            "
                                                        >
                                                            {{
                                                                transaction
                                                                    .transaction_type
                                                                    .name
                                                            }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="row m-0">
                                                        <div
                                                            class="col col-sm-4 p-1"
                                                            v-if="
                                                                transaction.transaction_type_id !=
                                                                    2 &&
                                                                transaction.transaction_type_id !=
                                                                    5
                                                            "
                                                        >
                                                            Value NFE
                                                        </div>
                                                        <div
                                                            class="col col-sm-8 p-1"
                                                            v-if="
                                                                transaction.transaction_type_id !=
                                                                    2 &&
                                                                transaction.transaction_type_id !=
                                                                    5
                                                            "
                                                        >
                                                            R$
                                                            {{
                                                                FormatNumber(
                                                                    NFEValue(
                                                                        transaction.value,
                                                                        transaction.main_dollar_value
                                                                    )
                                                                )
                                                            }}
                                                        </div>

                                                        <div
                                                            class="col col-sm-4 p-1"
                                                            v-if="
                                                                !columns.value
                                                            "
                                                        >
                                                            <strong
                                                                >Value</strong
                                                            >
                                                        </div>
                                                        <div
                                                            class="col col-sm-8 p-1"
                                                            v-if="
                                                                !columns.value
                                                            "
                                                        >
                                                            $
                                                            {{
                                                                FormatNumber(
                                                                    transaction.value ||
                                                                        0
                                                                )
                                                            }}
                                                        </div>

                                                        <div
                                                            class="col col-sm-4 p-1"
                                                            v-if="
                                                                !columns.dollar
                                                            "
                                                        >
                                                            <strong
                                                                >Dollar
                                                                value</strong
                                                            >
                                                        </div>
                                                        <div
                                                            class="col col-sm-8 p-1"
                                                            v-if="
                                                                !columns.dollar
                                                            "
                                                        >
                                                            $
                                                            {{
                                                                FormatNumber(
                                                                    transaction.dollar_value ||
                                                                        0,
                                                                    4
                                                                )
                                                            }}
                                                        </div>

                                                        <div
                                                            class="col col-sm-4 p-1"
                                                            v-if="
                                                                !columns.converted
                                                            "
                                                        >
                                                            <strong
                                                                >Converted
                                                                value</strong
                                                            >
                                                        </div>
                                                        <div
                                                            class="col col-sm-8 p-1"
                                                            v-if="
                                                                !columns.converted
                                                            "
                                                        >
                                                            R$
                                                            {{
                                                                FormatNumber(
                                                                    transaction.converted_value ||
                                                                        0
                                                                )
                                                            }}
                                                        </div>

                                                        <div
                                                            class="col col-sm-4 p-1"
                                                        >
                                                            <strong
                                                                >Exchange
                                                                variation</strong
                                                            >
                                                        </div>
                                                        <div
                                                            class="col col-sm-8 p-1"
                                                        >
                                                            R$
                                                            {{
                                                                FormatNumber(
                                                                    ExchangeVariation(
                                                                        transaction.value,
                                                                        transaction.dollar_value,
                                                                        transaction.main_dollar_value
                                                                    )
                                                                )
                                                            }}
                                                        </div>

                                                        <div
                                                            class="col col-sm-4 p-1"
                                                        >
                                                            <strong
                                                                >Exchange
                                                                contract</strong
                                                            >
                                                        </div>
                                                        <div
                                                            class="col col-sm-8 p-1"
                                                        >
                                                            {{
                                                                transaction.cambio_contract
                                                            }}
                                                        </div>

                                                        <div
                                                            class="col col-sm-4 p-1"
                                                            v-if="
                                                                !columns.due_code
                                                            "
                                                        >
                                                            <strong
                                                                >Due
                                                                code</strong
                                                            >
                                                        </div>
                                                        <div
                                                            class="col col-sm-8 p-1"
                                                            v-if="
                                                                !columns.due_code
                                                            "
                                                        >
                                                            {{
                                                                transaction
                                                                    .order.mapa
                                                                    ? transaction
                                                                          .order
                                                                          .mapa
                                                                          .due_code
                                                                    : ""
                                                            }}
                                                        </div>

                                                        <div
                                                            class="col col-sm-4 p-1"
                                                            v-if="
                                                                !columns.reference
                                                            "
                                                        >
                                                            <strong
                                                                >Reference</strong
                                                            >
                                                        </div>
                                                        <div
                                                            class="col col-sm-8 p-1"
                                                            v-if="
                                                                !columns.reference
                                                            "
                                                        >
                                                            {{
                                                                transaction.reference
                                                            }}
                                                        </div>

                                                        <div
                                                            class="col col-sm-4 p-1"
                                                            v-if="
                                                                !columns.cambio_contract
                                                            "
                                                        >
                                                            <strong
                                                                >Echange
                                                                contract</strong
                                                            >
                                                        </div>
                                                        <div
                                                            class="col col-sm-8 p-1"
                                                            v-if="
                                                                !columns.cambio_contract
                                                            "
                                                        >
                                                            {{
                                                                transaction.cambio_contract
                                                            }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="row p-2">
                                    <div class="col-6">
                                        <div class="row m-0">
                                            <div class="col col-sm-4 p-1">
                                                <strong>Total NFE</strong>
                                            </div>
                                            <div class="col col-sm-8 p-1">
                                                R$
                                                {{
                                                    FormatNumber(
                                                        SumNFE(reports_filtered)
                                                    )
                                                }}
                                            </div>

                                            <div class="col col-sm-4 p-1">
                                                <strong>Total value</strong>
                                            </div>
                                            <div class="col col-sm-8 p-1">
                                                $
                                                {{
                                                    FormatNumber(
                                                        SumValue(
                                                            reports_filtered
                                                        )
                                                    )
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row m-0">
                                            <div class="col col-sm-4 p-1">
                                                <strong>Total converted</strong>
                                            </div>
                                            <div class="col col-sm-8 p-1">
                                                R$
                                                {{
                                                    FormatNumber(
                                                        SumConverted(
                                                            reports_filtered
                                                        )
                                                    )
                                                }}
                                            </div>

                                            <div class="col col-sm-4 p-1">
                                                <strong>Total variation</strong>
                                            </div>
                                            <div class="col col-sm-8 p-1">
                                                R$
                                                {{
                                                    FormatNumber(
                                                        SumVariation(
                                                            reports_filtered
                                                        )
                                                    )
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer" v-if="reports_filtered.length">
                        <button
                            class="btn btn-success btn-block"
                            @click="generatePdfReport()"
                        >
                            Export PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div v-if="alert">
          <div class="alert alert-danger"><strong>Rejected</strong></div>
    </div> -->
    </div>
</template>

<style scoped>
.content-scroll {
    height: calc(100vh - 570px);
    overflow-y: scroll;
}

.fixed {
    position: fixed;
    right: 0;
    top: 50%;
    width: 8em;
    margin-top: -2.5em;
}

.red-color {
    color: #c00;
}

thead th {
    min-width: 80px;
    word-break: normal;
}

tr td:nth-child(3),
tr td:nth-child(5) {
    word-break: break-all;
}

.collapsed {
    display: none;
}



/* .table div {
    border: 1px solid rgb(202, 202, 202);
} */

/* .gray {
    background-color: #f1f1f1;
} */

/* .sub-table > div {
    border: 1px solid rgb(202, 202, 202);
    background-color: rgb(250, 250, 250);
}

.sub-table:nth-child(odd) {
    background-color: rgb(241, 241, 241);
} */

.ellipsis {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>

<script>
import reportValues from "./../mixins/reportValues";
import moment from "moment";

export default {
    mixins: [reportValues],

    data() {
        return {
            alert: null,
            isCheckboxDisabled: false,
            dateDisabled: false,
            columns: {
                po: true,
                importerName: true,
                accessKey: false,
                country: false,
                date: false,
                weight: false,
                bank: false,
                description: false,
                transactionType: false,
                value: true,
                dollar: true,
                converted: true,
                due_code: false,
                reference: false,
                cambio_contract: false,
            },
            totalData: null,
            filterIsHidden: true,
            display: true,
            reports_original: null,
            reports_filtered: null,
            transaction_type: null,
            initial_date: this.ConstructDate(60),
            final_date: this.ConstructDate(),
            terms: null,
            transaction_types: null,
            importersOptions: [],
            importersOptionsValue: [],
            transactionTypesOptions: [],
            transactionTypesOptionsValue: [],
            lastDays: 30,
            daysTypesOptions: [
                { days: 7, name: "7 days" },
                { days: 15, name: "15 days" },
                { days: 30, name: "30 days" },
                { days: 60, name: "60 days" },
                { days: 0, name: "All dates" },
            ],
            daysTypesOptionsValue: [{ days: 60, name: "60 days" }],
        };
    },
    methods: {
        forceFileDownload(response, name) {
            let filename = name + ".pdf";
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement("a");
            link.href = url;
            link.setAttribute("download", filename);
            document.body.appendChild(link);
            link.click();
        },

        SumNFE: function (reports) {
            let map =
                reports &&
                reports.map((report) => {
                    return (
                        report.value !== null &&
                        this.NFEValue(report.value, report.main_dollar_value)
                    );
                });

            if (!map) return 0;

            return map.reduce((acc, value) => {
                return acc + value;
            });
        },
        SumValue: function (reports) {
            if (reports) {
                let map = reports.map((report) => {
                    return report.value;
                });

                if (!map) return 0;

                return map.reduce((acc, value) => {
                    return acc + value;
                });
            }
        },
        SumDollar: function (reports) {
            let map = reports.map((report) => {
                return report.dollar_value;
            });

            if (!map) return 0;

            return map.reduce((acc, value) => {
                return acc + value;
            });
        },
        SumConverted: function (reports) {
            if (reports) {
                let map = reports.map((report) => {
                    return report.converted_value;
                });

                if (!map) return 0;

                return map.reduce((acc, value) => {
                    return acc + value;
                });
            }
        },
        SumVariation: function (reports) {
            if (reports) {
                let map = reports.map((report) => {
                    if (report.transaction_type_id === 3) {
                        return this.ExchangeVariation(
                            report.value,
                            report.dollar_value,
                            report.main_dollar_value
                        );
                    }
                    return 0;
                });

                if (!map) return 0;

                return map.reduce((acc, value) => {
                    return acc + value;
                });
            }
        },

        FormatNumber: function (value, fixed = 2) {
            if (value) {
                let value_fixed = value.toFixed(fixed);
                let [value_decimal, value_fraction] = value_fixed.split(/\./);

                value_decimal = value_decimal.replace(
                    /(\d)(?=(\d\d\d)+(?!\d))/g,
                    "$1."
                );

                value_fixed = value_decimal + "," + value_fraction;
                return value_fixed;
            }

            if (value) {
                return 0;
            }
        },
        FormatDate: function (date) {
            if (!date) return "Not informed";
            let [year, month, day, hour, minutes, seconds] =
                date.split(/[- :]/g);

            return month + "/" + day + "/" + year;
        },
        transactionDate(transaction) {
            return new Date(transaction).toLocaleDateString();
        },
        ToogleInformation: function (event) {
            return (this.display = !this.display);
        },
        ConstructDate: function (dias = 0) {
            let inicial = new Date()
                .toISOString()
                .replace(/T.*/, "")
                .split("-")
                .join("-");
            const partes = inicial.split("-");
            const ano = partes[0];
            const mes = partes[1] - 1;
            const dia = partes[2];

            inicial = new Date(ano, mes, dia);
            let final = new Date(inicial);

            final.setDate(final.getDate() - dias);

            const dd = ("0" + final.getDate()).slice(-2);
            const mm = ("0" + (final.getMonth() + 1)).slice(-2);
            const y = final.getFullYear();

            return y + "-" + mm + "-" + dd;
        },

        LastDaysSelector: function () {
            const lastDays = this.daysTypesOptionsValue.days;

            if (lastDays !== 0) {
                this.dateDisabled = false;
                this.initial_date = this.ConstructDate(lastDays);
                this.final_date = this.ConstructDate();
            } else {
                this.dateDisabled = true;
            }
        },

        AjustContentHeight: function () {
            this.$data.filterIsHidden = !this.$data.filterIsHidden;
            const heightContent = !this.$data.filterIsHidden ? 400 : 570;
            const element = document.getElementById("content");
            element.setAttribute(
                "style",
                `height:calc(100vh - ${heightContent}px)`
            );
        },

        ShowHideColumns: function (column) {
            let count = this.countTrueValues() + 1;
            let columns = this.$data.columns;

            if (count == 8 && !columns[column]) {
                this.$toaster.error("Maximum columns displayed is 8");
            }
            columns[column] = !columns[column];
        },

        countTrueValues: function () {
            let count = 0;
            let columns = this.$data.columns;

            for (const key in columns) {
                if (columns.hasOwnProperty(key) && columns[key] === true) {
                    count++;
                }
            }

            return count;
        },
        disableCheckbox: function (column) {
            let count = this.countTrueValues();
            return count >= 8 && !this.$data.columns[column];
        },

        customLabel({ name }) {
            return `${name}`;
        },

        getUsersClient: async function () {
            try {
                const response = await this.$http.get("/api/users/clients");
                const users = response.body;

                const usersData = users
                    ? users.map((user) => ({
                          id: user.id,
                          name: user.name,
                      }))
                    : [];

                const firstOption = {
                    id: null,
                    name: "Select a user",
                };

                this.importers = usersData;
                this.$data.importersOptions = [firstOption, ...usersData];
            } catch (error) {
                throw error;
            }
        },

        getTransactionTypes: async function () {
            await this.$http
                .get("/api/transaction-types")
                .then((response) => {
                    const data = response.body;
                    const types =
                        data &&
                        data.map((type) => ({
                            id: type.id,
                            name: type.name,
                        }));

                    this.transactionTypesOptions = types;
                })
                .catch(() => console.log("error occured"));
        },

        generatePdfReport: async function () {
            const url = "/api/reportPdf";
            const name = "relatorio-transactions";

            await this.$http
                .get(url, {
                    params: {
                        columns: this.$data.columns,
                        initial_date: this.initial_date,
                        final_date: this.final_date,
                        importer_id: this.importersOptionsValue["id"],
                        transaction_types:
                            this.transactionTypesOptionsValue.map(
                                (item) => item.id
                            ),
                        searchByDate:
                            this.daysTypesOptionsValue["days"] == "0"
                                ? false
                                : true,
                        terms: this.terms,
                    },
                    responseType: "arraybuffer",
                })
                .then((response) => {
                    this.forceFileDownload(response, name);
                })
                .catch(() => console.log("error occured"));
        },

        getReport: function () {
            this.$http
                .get("/api/report", {
                    params: {
                        initial_date: this.initial_date,
                        final_date: this.final_date,
                        importer_id: this.importersOptionsValue["id"],
                        transaction_types:
                            this.transactionTypesOptionsValue.map(
                                (item) => item.id
                            ),
                        searchByDate:
                            this.daysTypesOptionsValue["days"] == "0"
                                ? false
                                : true,
                        terms: this.terms,
                    },
                })
                .then((response) => {
                    const data = response.body.map((item) => ({
                        isHidden: true,
                        ...item,
                    }));
                    this.reports_filtered = data;
                })
                .catch(() => console.log("error occured"));
        },
    },
    mounted: async function () {
        this.getReport();
        this.getUsersClient();
        this.getTransactionTypes();
    },
};
</script>
