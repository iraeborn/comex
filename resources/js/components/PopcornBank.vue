<template>
    <div class="row p-3">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <div class="flex-grow-1 card-title m-0">Internediary</div>
                        <div class="d-flex">
                            <div class="btn-group dropstart">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Fields</button>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start">
                                    <div class="m-0 p-2">

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="company" checked disabled>
                                            <label class="form-check-label" for="company">
                                                Company
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="name" :checked="fields.intermediary.name"
                                            @click="ShowHideFields('intermediary','name')">
                                            <label class="form-check-label" for="name">
                                                Bank Name
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="swift" :checked="fields.intermediary.swift"
                                            @click="ShowHideFields('intermediary','swift')">
                                            <label class="form-check-label" for="swift">
                                                Swift-code
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="iban" :checked="fields.intermediary.iban"
                                            @click="ShowHideFields('intermediary','iban')">
                                            <label class="form-check-label" for="iban">
                                                IBAN
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="branch" :checked="fields.intermediary.branch"
                                            @click="ShowHideFields('intermediary','branch')">
                                            <label class="form-check-label" for="branch">
                                                BRANCH
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="clearing" :checked="fields.intermediary.clearing"
                                            @click="ShowHideFields('intermediary','clearing')">
                                            <label class="form-check-label" for="clearing">
                                                Clearing Code
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="chips" :checked="fields.intermediary.chips"
                                            @click="ShowHideFields('intermediary','chips')">
                                            <label class="form-check-label" for="chips">
                                                CHIPS UID
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="agency" :checked="fields.intermediary.agency"
                                            @click="ShowHideFields('intermediary','agency')">
                                            <label class="form-check-label" for="agency">
                                                Agency Number
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="account" :checked="fields.intermediary.account"
                                            @click="ShowHideFields('intermediary','account')">
                                            <label class="form-check-label" for="account">
                                                Account Number
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-1">

                    <div class="form-group mb-1">
                        <label class="m-0">Company</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-building"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="intermediary_company" v-model="bankData.intermediary_company"
                            id="intermediary_company" :class="error.intermediary_company != '' && error.intermediary_company ? 'is-invalid' : ''" />
                        </div>
                        <p
                            :class="error.intermediary_company ? 'invalid-feedback' : ''"
                            v-if="error.intermediary_company"
                            v-for="message in error.intermediary_company"
                        >
                            {{ message }}
                        </p>
                    </div>

                    <div class="form-group mb-1" v-if="fields.intermediary.name">
                        <label class="m-0">Bank Name</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-university"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="intermediary_name" v-model="bankData.intermediary_name"/>
                        </div>
                    </div>

                    <div class="form-group mb-1" v-if="fields.intermediary.swift">
                        <label class="m-0">Swift-code</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-globe"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="intermediary_swift" v-model="bankData.intermediary_swift"/>
                        </div>
                    </div>

                    <div class="form-group mb-1" v-if="fields.intermediary.iban">
                        <label class="m-0">IBAN</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-globe"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="intermediary_iban" v-model="bankData.intermediary_iban"/>
                        </div>
                    </div>

                    <div class="form-group mb-1" v-if="fields.intermediary.branch">
                        <label class="m-0">BRANCH</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-globe"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="intermediary_branch" v-model="bankData.intermediary_branch"/>
                        </div>
                    </div>

                    <div class="form-group mb-1" v-if="fields.intermediary.clearing">
                        <label class="m-0">Clearing Code</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-globe"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="intermediary_clearing" v-model="bankData.intermediary_clearing"/>
                        </div>
                    </div>

                    <div class="form-group mb-1" v-if="fields.intermediary.chips">
                        <label class="m-0">CHIPS UID</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-globe"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="intermediary_chips" v-model="bankData.intermediary_chips"/>
                        </div>
                    </div>

                    <div class="form-group mb-1" v-if="fields.intermediary.agency">
                        <label class="m-0">Agency Number</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-globe"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="intermediary_agency" v-model="bankData.intermediary_agency"/>
                        </div>
                    </div>

                    <div class="form-group mb-1" v-if="fields.intermediary.account">
                        <label class="m-0">Account Number</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-globe"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="intermediary_account" v-model="bankData.intermediary_account"/>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <div class="flex-grow-1 card-title m-0">Beneficiary</div>
                        <div class="d-flex">
                            <div class="btn-group dropstart">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Fields</button>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start">
                                    <div class="m-0 p-2">
                                        
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="company" checked disabled>
                                            <label class="form-check-label" for="company">
                                                Company
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="name" :checked="fields.beneficiary.name"
                                            @click="ShowHideFields('beneficiary','name')">
                                            <label class="form-check-label" for="name">
                                                Bank Name
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="swift" :checked="fields.beneficiary.swift"
                                            @click="ShowHideFields('beneficiary','swift')">
                                            <label class="form-check-label" for="swift">
                                                Swift-code
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="iban" :checked="fields.beneficiary.iban"
                                            @click="ShowHideFields('beneficiary','iban')">
                                            <label class="form-check-label" for="iban">
                                                IBAN
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="branch" :checked="fields.beneficiary.branch"
                                            @click="ShowHideFields('beneficiary','branch')">
                                            <label class="form-check-label" for="branch">
                                                BRANCH
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="clearing" :checked="fields.beneficiary.clearing"
                                            @click="ShowHideFields('beneficiary','clearing')">
                                            <label class="form-check-label" for="clearing">
                                                Clearing Code
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="chips" :checked="fields.beneficiary.chips"
                                            @click="ShowHideFields('beneficiary','chips')">
                                            <label class="form-check-label" for="chips">
                                                CHIPS UID
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="agency" :checked="fields.beneficiary.agency"
                                            @click="ShowHideFields('beneficiary','agency')">
                                            <label class="form-check-label" for="agency">
                                                Agency Number
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="account" :checked="fields.beneficiary.account"
                                            @click="ShowHideFields('beneficiary','account')">
                                            <label class="form-check-label" for="account">
                                                Account Number
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="form-group mb-1">
                        <label class="m-0">Company</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-building"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="beneficiary_company" v-model="bankData.beneficiary_company"
                             id="beneficiary_company" :class="error.beneficiary_company != '' && error.beneficiary_company ? 'is-invalid' : ''" />
                        </div>
                        <p
                            :class="error.beneficiary_company ? 'invalid-feedback' : ''"
                            v-if="error.beneficiary_company"
                            v-for="message in error.beneficiary_company"
                        >
                            {{ message }}
                        </p>
                    </div>

                    <div class="form-group mb-1" v-if="fields.beneficiary.name">
                        <label class="m-0">Bank Name</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-university"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="beneficiary_name" v-model="bankData.beneficiary_name"/>
                        </div>
                    </div>

                    <div class="form-group mb-1" v-if="fields.beneficiary.swift">
                        <label class="m-0">Swift-code</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-university"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="beneficiary_swift" v-model="bankData.beneficiary_swift"/>
                        </div>
                    </div>

                    <div class="form-group mb-1" v-if="fields.beneficiary.iban">
                        <label class="m-0">IBAN</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-globe"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="beneficiary_iban" v-model="bankData.beneficiary_iban"/>
                        </div>
                    </div>

                    <div class="form-group mb-1" v-if="fields.beneficiary.branch">
                        <label class="m-0">BRANCH</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-globe"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="beneficiary_branch" v-model="bankData.beneficiary_branch"/>
                        </div>
                    </div>

                    <div class="form-group mb-1" v-if="fields.beneficiary.clearing">
                        <label class="m-0">Clearing Code</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-globe"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="beneficiary_clearing" v-model="bankData.beneficiary_clearing"/>
                        </div>
                    </div>

                    <div class="form-group mb-1" v-if="fields.beneficiary.chips">
                        <label class="m-0">CHIPS UID</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-globe"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="beneficiary_chips" v-model="bankData.beneficiary_chips"/>
                        </div>
                    </div>

                    <div class="form-group mb-1" v-if="fields.beneficiary.agency">
                        <label class="m-0">Agency Number</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-globe"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="beneficiary_agency" v-model="bankData.beneficiary_agency"/>
                        </div>
                    </div>

                    <div class="form-group mb-1" v-if="fields.beneficiary.account">
                        <label class="m-0">Account Number</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-globe"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="beneficiary_account" v-model="bankData.beneficiary_account"/>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<style>
.is-invalid {
    display: block;
}

.invalid-feedback {
    display: block;
    margin: 0;
}

.form-group {
    margin-bottom: 0;
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
export default {
    name: "PopcornBank",
    props: {
        loading: {
            type: Boolean,
            default: true
        },
        redirect: {
            type: Boolean,
            default: false
        },
        parentAction: {
            type: String,
            default: ""
        }
    },
    data() {
        return {
            bankData: [],
            fields: 
            {
                intermediary: {
                    name: true,
                    swift: true,
                    iban: false,
                    branch: false,
                    clearing: false,
                    chips: false,
                    company: true,
                    agency: false,
                    account: false
                },
                beneficiary: {
                    name: true,
                    swift: true,
                    iban: true,
                    branch: true,
                    clearing: true,
                    chips: true,
                    company: true,
                    agency: false,
                    account: false
                }
            },
            error: {},
        }
    },
    methods: {

        ShowHideFields: function (type, field) {
            
            let fieldName = `${type}_${field}`;
            let fieldValue = this.bankData[fieldName];
            let dataField = this.$data.fields[type];

            if(fieldValue?.length && dataField[field]){
                this.$toaster.warning('This action will delete the current information after save!');
            }
            
            dataField[field] = !dataField[field];
        },

        StoreBank: function () {
            let data = {};
            this.$data.error = {}

            for (const type in this.fields) {
                const typeFields = this.fields[type];
                
                for (const field in typeFields) {
                    const fieldName = `${type}_${field}`;

                    if (this.$data.fields[type][field]) {
                        data[fieldName] = this.$data.bankData[fieldName];
                    }else{
                        data[fieldName] = null;
                    }
                }
            }

            if (this.parentAction === "change") {
                this.putBanks(data);
            } else if (this.parentAction === "add") {
                this.postBanks(data);
            }
        },

        putBanks: function (data){
            
            this.$http.put('/api/banks/' + this.$route.params.id, data)
            .then((response) => {
                if(response.body.errors) {
                    this.$data.error = response.body.errors;
                    this.$emit('callback', {status: 'error', redirect: this.redirect});
                    return
                }
                
                this.$emit('callback', {status: 'success', redirect: this.redirect});
            })
        },

        postBanks: function (data){
            this.$http.post('/api/banks', data)
            .then((response) => {
                if(response.body.errors) {
                    this.$data.error = response.body.errors;
                    this.$emit('callback', {status: 'error', redirect: this.redirect});
                    return
                }
                
                this.$emit('callback', {status: 'success', redirect: this.redirect});
            })

        },

        getBanks: async function (){
            await this.$http.get('/api/banks/' + this.$route.params.id).then(e => {
                const bankData = e.body
                this.$data.bankData = bankData;

                for (const type in this.fields) {
                    const typeFields = this.fields[type];

                    for (const field in typeFields) {
                        const fieldName = `${type}_${field}`;

                        if(field === 'company'){
                            continue;
                        }

                        if (bankData[fieldName]) {
                            this.$data.fields[type][field] = true;
                        } else {
                            this.$data.fields[type][field] = false;
                        }
                    }
                }

                this.$emit('callback', {status: 'updated', redirect: this.redirect});
                
            })
        },
    },
    mounted: async function () {
        this.parentAction === "change" && this.getBanks();
    }
}
</script>