<template>
  <div class="container-fluid">
    <div id="ui-view">
      <div class="panel panel-success">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">Banks</div>
              <div class="col text-right">
                <router-link :to="{ name: 'New Bank' }" class="btn btn-success btn-sm">Add new bank</router-link>
              </div>
            </div>
          </div>
          <div class="card-body">
            <p class="text-center" v-if="!objLst.length">No records found</p>

            <div class="card-columns">
              <div class="card" v-if="objLst.length" v-for="(bank, index) in objLst">
                <div class="card-body p-2">
                  <div class="d-flex">
                    <div>
                      <div class="text-muted text-truncate m-0 " style="font-size: smaller;">Beneficiary company</div>
                      <div class="pointer" style="font-weight: bold;" v-on:click="copyText(bank.beneficiary_company)">
                        {{ bank.beneficiary_company }}
                      </div>
                    </div>

                    <div class="ml-auto">
                      <div class="dropdown">
                        <button class="btn btn-secondary btn-lt dropdown-toggle" type="button"
                            data-toggle="dropdown" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu">

                          <router-link :to="{ name: 'Edit Bank', params: { id: bank.id } }" class="dropdown-item">
                            Edit
                          </router-link>


                          <button class="dropdown-item" type="button" @click="deleteBank(bank.id)">
                            Delete
                          </button>

                        </div>
                      </div>
                    </div>

                  </div>

                  <div class="row px-3 pt-2">
                    <div class="col px-0 pr-md-2">

                      <div class="col card" v-if="bank.beneficiary_name">
                        <div class="text-muted text-truncate m-0" style="font-size: smaller;">
                          Beneficiary bank
                        </div>
                        <div class="font-weight-normal pointer" v-on:click="copyText(bank.beneficiary_name)">
                          {{ bank.beneficiary_name }}
                        </div>
                      </div>

                      <div class="col card" v-if="bank.beneficiary_swift">
                        <div class="text-muted text-truncate m-0" style="font-size: smaller;">
                          Beneficiary Swift-code
                        </div>
                        <p class="font-weight-normal mb-0 pointer" v-on:click="copyText(bank.beneficiary_swift)">
                          {{ bank.beneficiary_swift }}
                        </p>
                      </div>

                      <div class="col card" v-if="bank.beneficiary_iban">
                        <div class="text-muted text-truncate m-0" style="font-size: smaller;">
                          Beneficiary IBAN
                        </div>
                        <div class="font-weight-normal pointer" v-on:click="copyText(bank.beneficiary_iban)">
                          {{ bank.beneficiary_iban }}
                        </div>
                      </div>

                      <div class="col card" v-if="bank.beneficiary_branch">
                        <div class="text-muted text-truncate m-0" style="font-size: smaller;">
                          Beneficiary BRANCH
                        </div>
                        <div class="font-weight-normal pointer" v-on:click="copyText(bank.beneficiary_branch)">
                          {{ bank.beneficiary_branch }}
                        </div>
                      </div>

                      <div class="col card" v-if="bank.beneficiary_clearing">
                        <div class="text-muted text-truncate m-0" style="font-size: smaller;">
                          Beneficiary Clearing Code
                        </div>
                        <div class="font-weight-normal pointer" v-on:click="copyText(bank.beneficiary_clearing)">
                          {{ bank.beneficiary_clearing }}
                        </div>
                      </div>

                      <div class="col card" v-if="bank.beneficiary_chips">
                        <div class="text-muted text-truncate m-0" style="font-size: smaller;">
                          Beneficiary CHIPS UID
                        </div>
                        <div class="font-weight-normal pointer" v-on:click="copyText(bank.beneficiary_chips)">
                          {{ bank.beneficiary_chips }}
                        </div>
                      </div>

                      <div class="col card" v-if="bank.beneficiary_agency">
                        <div class="text-muted text-truncate m-0" style="font-size: smaller;">
                          Beneficiary Agency Number
                        </div>
                        <div class="font-weight-normal pointer" v-on:click="copyText(bank.beneficiary_agency)">
                          {{ bank.beneficiary_agency }}
                        </div>
                      </div>

                      <div class="col card" v-if="bank.beneficiary_account">
                        <div class="text-muted text-truncate m-0" style="font-size: smaller;">
                          Beneficiary Account Number
                        </div>
                        <div class="font-weight-normal pointer" v-on:click="copyText(bank.beneficiary_account)">
                          {{ bank.beneficiary_account }}
                        </div>
                      </div>

                    </div>

                    <div class="intermediary col pt-2">

                      <div class="col card" v-if="bank.intermediary_company">
                        <div class="text-muted text-truncate m-0" style="font-size: smaller;">
                          Intermediary company
                        </div>
                        <div class="font-weight-normal pointer" v-on:click="copyText(bank.intermediary_company)">
                          {{ bank.intermediary_company }}
                        </div>
                      </div>

                      <div class="col card" v-if="bank.intermediary_name">
                        <div class="text-muted text-truncate m-0" style="font-size: smaller;">
                          Intermediary bank
                        </div>
                        <div class="font-weight-normal pointer" v-on:click="copyText(bank.intermediary_name)">
                          {{ bank.intermediary_name }}
                        </div>
                      </div>
                      
                      <div class="col card" v-if="bank.intermediary_swift">
                        <div class="text-muted text-truncate m-0" style="font-size: smaller;">
                          Intermediary Swift-code
                        </div>
                        <p class="font-weight-normal mb-0 pointer" v-on:click="copyText(bank.intermediary_swift)">
                          {{ bank.intermediary_swift }}
                        </p>
                      </div>
                      
                      <div class="col card" v-if="bank.intermediary_iban">
                        <div class="text-muted text-truncate m-0" style="font-size: smaller;">
                          Intermediary IBAN
                        </div>
                        <div class="font-weight-normal pointer" v-on:click="copyText(bank.intermediary_iban)">
                          {{ bank.intermediary_iban }}
                        </div>
                      </div>
                      
                      <div class="col card" v-if="bank.intermediary_branch">
                        <div class="text-muted text-truncate m-0" style="font-size: smaller;">
                          Intermediary BRANCH
                        </div>
                        <div class="font-weight-normal pointer" v-on:click="copyText(bank.intermediary_branch)">
                          {{ bank.intermediary_branch }}
                        </div>
                      </div>
                      
                      <div class="col card" v-if="bank.intermediary_clearing">
                        <div class="text-muted text-truncate m-0" style="font-size: smaller;">
                          Intermediary Clearing Code
                        </div>
                        <div class="font-weight-normal pointer" v-on:click="copyText(bank.intermediary_clearing)">
                          {{ bank.intermediary_clearing }}
                        </div>
                      </div>
                      
                      <div class="col card" v-if="bank.intermediary_chips">
                        <div class="text-muted text-truncate m-0" style="font-size: smaller;">
                          Intermediary CHIPS UID
                        </div>
                        <div class="font-weight-normal pointer" v-on:click="copyText(bank.intermediary_chips)">
                          {{ bank.intermediary_chips }}
                        </div>
                      </div>
                      
                      <div class="col card" v-if="bank.intermediary_agency">
                        <div class="text-muted text-truncate m-0" style="font-size: smaller;">
                          Intermediary Agency Number
                        </div>
                        <div class="font-weight-normal pointer" v-on:click="copyText(bank.intermediary_agency)">
                          {{ bank.intermediary_agency }}
                        </div>
                      </div>
                      
                      <div class="col card" v-if="bank.intermediary_account">
                        <div class="text-muted text-truncate m-0" style="font-size: smaller;">
                          Intermediary Account Number
                        </div>
                        <div class="font-weight-normal pointer" v-on:click="copyText(bank.intermediary_account)">
                          {{ bank.intermediary_account }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
  .intermediary {
    background-color: rgb(238, 238, 238);
    border-radius: 5px;
  }

  .intermediary .card{
    background-color: rgb(238, 238, 238);
  }


  .pointer {
    cursor: pointer;
  }
  .card-body {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
  }
  .card-columns {
    display: inline-block;
    column-count: 1 !important;
  }

  @media screen and (min-width: 600px) {
    .card-columns {
      column-count: 1 !important;
    }
  }

  @media screen and (min-width: 992px) {
    .card-columns {
      column-count: 1 !important;
    }
  }

  @media screen and (min-width: 1200px) {
    .card-columns {
      column-count: 1 !important;
    }
  }

  @media screen and (min-width: 1600px) {
    .card-columns {
      column-count: 1 !important;
    }
  }

</style>

<script>
export default {
  data() {
    return {
      objLst: [],
      page: [],
    }
  },
  methods: {
    copyText: async function (text){
      try {
      await navigator.clipboard.writeText(text);
      this.$toaster.success(text + ' copied to clipboard');
      } catch (err) {
        this.$toaster.success('Failed to copy: ', err);
      }
    },

    deleteBank: async function (bankId){
      await this.$http.delete('/api/banks/' + bankId).then(e => {
        this.getBanks();
        this.$toaster.success('bank deleted successfully');
      })
    },

    getBanks: function (){
      this.$http.get('/api/banks').then(e => {
        this.$data.objLst = this.$data.page = e.body;
        this.$data.objLst = this.$data.page = e.body.sort((a, b) => {
          if (!a.loading_deadline) return -1;
          if (!b.loading_deadline) return 1;
          return this.DateToTimestamp(a.loading_deadline) - this.DateToTimestamp(b.loading_deadline);
        });
      })
    }
  },
  mounted: function () {
    this.getBanks();
  }
}

</script>
