<template>
    <div class="modal fade" id="provider-modal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">

              <div class="row">
                <div class="col-12">
                  <h4 class="modal-title">{{firstCapitalLetter(this.type)}}</h4>
                </div>
                <div class="col-12">
                  <p class="m-0">Select agency and contract for order {{ this.data.number }}</p>
                </div>
              </div>
              
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
              
              <div class="container text-center" v-if="loading.body">
                <div class="row align-items-center">
                  <div class="col p-5">
                    <b-spinner small></b-spinner>
                  </div>
                </div>
              </div>
              
              <div class="row" v-if="!loading.body">
                <div class="col-12">
                  
                  <label for="fumigation-agency">{{firstCapitalLetter(this.type)}} Agency</label>
                  <div class="form-group reto">

                    <div :class="error.providers != '' && error.providers ? 'invalid' : ''">
                        <multiselect
                          v-model="providers"
                          placeholder="Select a user"
                          track-by="id"
                          :options="agencyUsers"
                          :custom-label="customLabel"
                          :show-labels="false"
                          :allow-empty="true"
                          >
                        </multiselect>
                    </div>

                    <p
                      :class="error.providers ? 'invalid-feedback' : ''"
                      v-if="error.providers"
                      v-for="message in error.providers"
                      >
                        {{ message }}
                    </p>

                  </div>

                </div>
                
                <div class="col-12 col-sm-10" v-if="contracts || contract">
                  
                  <label for="fumigation-contract">{{firstCapitalLetter(this.type)}} Contract</label>
                  <div class="form-group reto">
  
                    <div :class="error.contract != '' && error.contract ? 'invalid' : ''">
                          <multiselect
                            v-model="contracts"
                            placeholder="Select a contract"
                            track-by="id"
                            :options="agencyContract"
                            :custom-label="customLabel"
                            :show-labels="false"
                            :allow-empty="false"
                            >
                          </multiselect>
                        </div>
                        
                        <p
                          :class="error.contract ? 'invalid-feedback' : ''"
                          v-if="error.contract"
                          v-for="message in error.contract"
                          >
                            {{ message }}
                        </p>
  
                  </div>

                </div>

                <div class="col-12 col-sm-2 d-flex align-items-end pl-sm-0">
                  <button class="btn btn-success" style="width:100%; margin-bottom: 0.3rem !important;" data-toggle="modal" href="#modal-new-bank" disabled>
                    New
                  </button>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-default"
                @click="closeModal"
              >
                Close
              </button>
              <button
                type="button"
                class="btn btn-primary"
                @click="() => !loading.save && save(index)"
              >
                <b-spinner small v-if="loading.save"></b-spinner>
                {{ !loading.save ? 'Save' : 'Saving' }}
              </button>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
export default {
name: "providers-modal",
  data (){
    return {
      loading: {
        body: false,
        save: false
      },
      isSave: false,
      providers: [],
      contracts: [],
      allContracts: [],
      agencyContract:[],
      agencyUsers: [],
      currentType: null,
      currentAgentUser: null,
    }
  },
  props: {
    type: {
      type: String,
      default: 'modal',
      required: false
    },
    index: {
      type: Number,
      default: 0,
      required: false
    },
    data: {
      type: Object,
      default: {},
      required: false
    },
    error: {
      type: Object,
      default: {},
      required: false
    }
  },

  created: async function () {
    this.loading.body = true;
    await this.defineCurrentType(this.type)
    await this.getUsersByRole();
    await this.getContracts();
    await this.findContractProvision();
  },

  watch: {
    'type': {
      handler: async function(){
        this.loading.body = true;
        await this.defineCurrentType(this.type)
        await this.getUsersByRole();
        await this.getContracts();
        await this.findContractProvision();
      }
    },
    'providers': {
      handler(newVal, oldVal) {
        this.agencyContract = this.allContracts.filter(item => item.provider_id == newVal.id);

        if(newVal.id !== oldVal.id && this.contracts.id !== undefined && this.agencyContract.length == 0){
          this.agencyContract = [];
          this.contracts = [];
        }

      }
    },
    deep: true
  },

  methods: {
    closeModal(){

      if(!this.providers.length){
        this.providers = [];
      }
      if(!this.contracts.length){
        this.contracts = [];
      }

      this.$emit('callback', {status: 200, data: null});
    },

    defineCurrentType: async function (agencyType){

      const agencyTypes = {
        fumigation: 'fumigation_id',
        insurance: 'weight_id',
        inspection: 'inspection_id',
        forwarding: 'forwarding_agent_id',
        railway: 'railway_agent_id',
        terminal: 'terminal_agent_id',
        broker: 'broker_id',
        shipping: 'shipping_id'
      };

      const agencyUsers = {
        fumigation: 'fumigation_agency_id',
        insurance: 'insurance_agency_id',
        inspection: 'inspection_agency_id',
        forwarding: 'forwarding_agent_id',
        railway: 'railway_agent_id',
        terminal: 'terminal_agent_id',
        broker: 'broker_id',
        shipping: 'shipping_line_id'
      };

      const currentType = agencyType.split('_')[0];
      this.currentType = agencyTypes[currentType];
      this.currentAgentUser = agencyUsers[currentType];

    },

    findContractProvision: async function() {
      const typeId = this.data[this.currentType];
      
      const foundContract = this.data.contract_provisions && this.data.contract_provisions.find(
        provision => provision.provider_id === typeId
        );

      this.providers = typeId !== null ? this.agencyUsers.filter(item => item.id == typeId)[0] : [];
      this.contracts = foundContract !== undefined ? this.allContracts.filter(item => item.id == foundContract.provider_contract_id)[0] : [];

      this.loading.body = false;
    },

    save: async function (){
      this.loading.save = true;

      try {
        const {status, data} = await this.$http.post(`/api/provider/${this.data.id}`, {
          provider_type: this.currentType,
          contract_id: this.contracts.id,
          provider_id: this.providers.id
        });
        this.loading.save = false;
        const dataCallback = {status, data};
        this.$emit('callback', dataCallback);

      } catch (error){
        //status 422, message: this.contract.has.already.been.linked.to.this.order
        console.log(error);
        throw error;
      }
    },

    getUsersByRole: async function(){
      try {
        const response = await this.$http.get("/api/users-by-role");
        // this.loading = false;
        const editors = response.body;
  
        const agencyUsersData = editors[this.currentAgentUser].map((user) => ({
          id: user.id,
          name: user.name
        }));
        
        const firstOption = {
          id: null,
          name: 'Select a user'
        }

        this.agencyUsers = [firstOption, ...agencyUsersData];

      } catch (error) {
        throw error;
      }
    },

    getContracts: async function(){
      try {
        const response = await this.$http.get("/api/providers/contracts");
        // this.loading = false;

        const contractsData = response.body.map((contract) => {
          const data = {
            id: contract.id,
            name: contract.identifier,
            provider_id: contract.provider_id
          }

          return data;
        });

        this.allContracts = contractsData;

      } catch (error){
        throw error;
      }
    },

    customLabel ({ name }) {
      return `${name}`
    },

    firstCapitalLetter(data) {
      const split = data.split('_');
      return split.map(part => part.charAt(0).toUpperCase() + part.slice(1)).join(' ');
    },

  },
};
</script>
