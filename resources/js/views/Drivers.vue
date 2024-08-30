<template>
  <div class="container-fluid">
    <div id="ui-view">
      <div class="panel panel-success">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">Drivers</div>

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
                <select
                  class="form-control form-control-sm"
                  v-model="filterType"
                >
                  <option value="name">Filter by Name</option>
                  <option value="phone">Filter by Phone</option>
                  <option value="cpf">Filter by CPF</option>
                </select>
              </div>

              <div class="col-2 text-right">
                <router-link
                  :to="{ name: 'New Driver' }"
                  class="btn btn-success btn-sm btn-block"
                  >Add new driver</router-link
                >
              </div>
            </div>
          </div>
          <div class="card-body">
            <p class="text-center" v-if="!arrayDrivers.length">
              No records found
            </p>
            <table class="table table-sm" v-if="arrayDrivers.length">
              <tr>
                <th>name</th>
                <th>phone</th>
                <th>cnh</th>
                <th>rg</th>
                <th>cpf</th>
                <th>born_at</th>
                <th>&nbsp;</th>
              </tr>
              <tbody v-for="(obj, index) in arrayDrivers">
                <tr v-if="obj.id">
                  <td>{{ obj.name }}</td>
                  <td>{{ obj.phone }}</td>
                  <td>{{ obj.cnh }}</td>
                  <td>{{ obj.rg }}</td>
                  <td>{{ obj.cpf }}</td>
                  <td>{{ obj.born_at }}</td>
                  <td class="text-right">
                    <router-link
                      :to="{ name: 'Edit Driver', params: { id: obj.id } }"
                      class="btn btn-success btn-sm"
                      >Edit</router-link
                    >

                    <button
                      type="button"
                      class="btn btn-danger btn-sm"
                      data-toggle="modal"
                      :href="'#modal-' + index"
                    >
                      Delete
                    </button>

                    <div
                      class="modal fade modal-danger text-left"
                      :id="'modal-' + index"
                    >
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h4 class="modal-title">Are you sure?</h4>
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
                            <p>Deleting the driver {{ obj.name }}.</p>
                            <p>
                              This action is irreversible, are you sure you want
                              to continue?
                            </p>
                          </div>
                          <div class="modal-footer">
                            <button
                              type="button"
                              class="btn btn-default"
                              data-dismiss="modal"
                            >
                              Cancel
                            </button>
                            <button
                              type="button"
                              class="btn btn-danger"
                              @click="Exclude(obj,index)"
                            >
                              Confirm exclusion
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <popcorn-pagination
              v-model="page"
              :items="arrayDrivers"
            ></popcorn-pagination>
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
</style>

<script>
import utilidade from "./../mixins/utilidade";
export default {
  mixins: [utilidade],
  data() {
    return {
      objLst: [],
      filter: null,
      filterType: "name",
      page: [],
    };
  },
  mounted: function () {
    this.getDrivers();
  },
  computed: {
    arrayDrivers() {
      return this.$data.objLst.filter((item) => {
        if (!this.filter) return true;

        let filter = this.removerAcentos(this.filter.toLowerCase()).trim();

        return (
          (this.filterType == "name" &&
            !!this.removerAcentos(item.name.toLowerCase()).match(filter)) ||
          (this.filterType == "phone" &&
            item.phone &&
            !!this.removerAcentos(item.phone.toLowerCase()).match(filter)) ||
          (this.filterType == "cpf" &&
            item.cpf &&
            !!this.removerAcentos(item.cpf.toLowerCase()).match(filter))
        );
      });
    },
  },
  methods: {
    Exclude: function (driver,index) {
      var self = this;

      this.$http.delete("/api/drivers/" + driver.id).then(function (e) {
        $("#modal-" + index).modal("hide");

        if (e.body.success) {
          this.getDrivers();
          self.$toaster.success(e.body.success);
          return;
        }

        self.$toaster.error(e.body.error);
      });
    },
    getDrivers(search = "") {
      this.$http.get("/api/drivers?search=" + search).then((e) => {
        this.$data.objLst = this.$data.page = e.body;
        this.$data.objLst = this.$data.page = e.body.sort((a, b) => {
          if (!a.loading_deadline) return -1;
          if (!b.loading_deadline) return 1;
          return (
            this.DateToTimestamp(a.loading_deadline) -
            this.DateToTimestamp(b.loading_deadline)
          );
        });
      });
    },
  },
};
</script>
