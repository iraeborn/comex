<template>
  <div class="container-fluid">
    <div id="ui-view">
      <div class="panel panel-success">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">Contracts</div>

              <div class="col-2 text-right">
                <div class="input-group">
                  <input
                    type="text"
                    placeholder="Filter"
                    class="form-control form-control-sm"
                    v-model="filter"
                  />
                  
                  <div class="input-group-append">
                    <button
                      type="button"
                      class="btn btn-danger btn-sm"
                      @click="filter = ''"
                    >
                      X
                    </button>
                  </div>
                </div>
              </div>

              <div class="col-2 text-right">
                <select class="form-control form-control-sm" v-model="filterType">
                  <option value="supplier">Filter by Supplier</option>
                  <option value="service_type">Filter by Service Type</option>
                  <option value="service_location">Filter by Location</option>
                </select>
              </div>

              <div class="col-2 text-right">
                <router-link
                  :to="{ name: 'New Contract' }"
                  class="btn btn-success btn-sm btn-block">
                    Add new Contract
                </router-link>
              </div>
            </div>
          </div>
          <div class="card-body">
            <p class="text-center" v-if="!arrayContracts.length">No records found</p>
            <div v-if="arrayContracts.length">            
              <table class="table table-sm">
                <tr>
                  <th>Supplier</th>
                  <th>Identifier</th>
                  <th>Service Type</th>
                  <th>Location</th>
                  <th>Negotiated Terms</th>
                  <th>Contract Status</th>
                  <th>Expirated At</th>
                  <th>&nbsp;</th>
                </tr>
                <tbody v-for="(obj) in arrayContracts">
                  <tr v-if="obj.id">
                      <td>{{ obj.provider.name }}</td>
                      <td>{{ obj.identifier }}</td>
                      <td>{{ obj.service ? obj.service.name : '' }}</td>
                      <td>{{ obj.service_location }}</td>
                      <td>{{ obj.negotiated_terms }}</td>
                      <td>{{ contractStatus(obj.is_active) }}</td>
                      <td>{{ formatDate(obj.expirated_at) }}</td>
                      <td class="text-right buttons">
                          <div class="dropdown dropleft">
                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                    data-toggle="dropdown" aria-expanded="false">
                                </button>
                                <div class="dropdown-menu">
                                  <router-link :to="{ name: 'Edit Contract', params: { id: obj.id } }"
                                    class="dropdown-item">
                                      Edit
                                  </router-link>
                                  <button class="dropdown-item pendence" @click="confirmDeleteContract(obj.id)">
                                    Delete
                                  </button>
                                </div>
                            </div>
                      </td>
                  </tr>
                </tbody>
              </table>

                <div class="d-flex">
                  <div class="pill-center" v-if="totalPages > 1">
                    <ul class="pagination">

                      <li class="page-item" v-if="currentPage > 1">
                        <a class="page-link" @click="prevPage">Previous</a>
                      </li>

                      <li class="page-item" :class="page == currentPage ? 'active' : ''" v-for="page in totalPages" :key="page">
                        <a class="page-link" @click="setPage(page)">{{ page }}</a>
                      </li>

                      <li class="page-item" v-if="currentPage + 1 < totalPages">
                        <a class="page-link" @click="nextPage">Next</a>
                      </li>

                    </ul>
                  </div>

                  <div class="ml-auto">
                    <select v-model="perPage" name="perPage" id="perPage" class="form-control">
                      <option value="25">25</option>
                      <option value="50">50</option>
                      <option value="100">100</option>
                      <option value="150">150</option>
                      <option value="200">200</option>
                    </select>
                  </div>
                </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="confirm-delete">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Deletion confirmation</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col">Are you sure?</div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" @click="cancelDeleteContract">No</button>
            <button type="button" class="btn btn-primary" @click="deleteContract">Yes</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  

</template>

<style scoped>
.table .table {
  background-color: #fef101;
}

.btn-warning, .btn-warning:hover , .btn-warning:active {
  color: white;
}

td.buttons {
  white-space: nowrap;
}
</style>

<script>

import datesFormatter from './../../mixins/datesFormatter';
import utilidade from './../../mixins/utilidade';

export default {

  mixins: [ datesFormatter,utilidade ],

  data() {
      return {
          objLst: [],
          page: [],
          filter:null,
          filterType:'supplier',
          contractIdSelected: null,

          items: [],
          currentPage: 1,
          totalPages: null,
          perPage: 25
      }
  },

  computed: {
    arrayContracts() {
      return this.items.filter((item) => {

        if(!this.filter)return true;
        let filter = this.removerAcentos(this.filter.toLowerCase()).trim();

        return(
          (this.filterType == 'supplier' && item.provider && item.provider.name && !!this.removerAcentos(item.provider.name.toLowerCase()).match(filter)) ||
          (this.filterType == 'service_type' && item.service_type && !!this.removerAcentos(item.service_type.toLowerCase()).match(filter)) ||
          (this.filterType == 'service_location' && item.service_location && !!this.removerAcentos(item.service_location.toLowerCase()).match(filter))
        );
      });
    },
  },
  methods: {

      confirmDeleteContract(contractId){
        this.contractIdSelected = contractId
        $('#confirm-delete').modal('show')
      },

      cancelDeleteContract(){
        this.contractIdSelected = null
        $('#confirm-delete').modal('hide')
      },

      async deleteContract() {
        try {
          const response = await this.$http.delete('/api/providers/' + this.contractIdSelected + '/contracts');
          
          if (response.body.success) {
            $('#confirm-delete').modal('hide')
            await this.getContracts();
            this.$toaster.success(response.body.success);
          }
        } catch (error) {
          this.$toaster.error('An error occurred while deleting the contract.');
        }
      },

      contractStatus: function (status) {

        if ( status == 1) {
            return "Active";
        } else {
            return "Inactive";
        }

      },

      async fetchData(page = 1) {
        try {
          const response = await this.$http.get(`/api/providers/contracts?page=${page}`,  {
                params: {
                  perPage: this.perPage
                }
                });
          this.items = response.body.data;
          this.currentPage = response.body.current_page;
          this.totalPages = response.body.last_page;

        } catch (error) {
          console.error('Error fetching data:', error);
        }
      },

      setPage(number) {
          this.fetchData(number);
      },

      prevPage() {
        if (this.currentPage > 1) {
          this.fetchData(this.currentPage - 1);
        }
      },

      nextPage() {
        if (this.currentPage < this.totalPages) {
          this.fetchData(this.currentPage + 1);
        }
      },

      handlePerPage(newPerPage) {
        this.perPage = newPerPage;
        this.fetchData(this.currentPage);
      },
    },
    watch: {
      perPage(newVal) {
        this.handlePerPage(newVal);
      }
    },
    mounted: function () {
      this.fetchData();
    }
}

</script>
