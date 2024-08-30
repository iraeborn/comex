<template>
  <div class="container-fluid">
    <div id="ui-view">
      <div class="panel panel-success">
        <div class="card">
          <div class="card-header">
           <div class="row">
              <div class="col">Groups</div>

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
                <router-link
                  :to="{ name: 'New Group' }"
                  class="btn btn-success btn-sm"
                >Add new Group</router-link>
              </div>
            </div>
          </div>
          <div class="card-body">
            <p class="text-center" v-if="!arrayData.length">
              No records found
            </p>
            <table class="table table-sm" v-if="arrayData.length">
              <tr>
                <th>Name</th>
                <th>&nbsp;</th>
              </tr>
              <tbody v-for="(obj, index) in arrayData" :key="index">
                <tr v-if="obj.id">
                  <td>{{ obj.name }}</td>
                  <td class="text-right">
                    <router-link
                      :to="{ name: 'Edit Group', params: { id: obj.id } }"
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
                            <p>Deleting the group {{ obj.name }}.</p>
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
              :items="arrayData"
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
import utilidade from "../mixins/utilidade";
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
    this.getData();
  },
  computed: {
    arrayData() {
      return this.$data.objLst.filter((item) => {

        if(!this.filter)return true;

        let filter = this.removerAcentos(this.filter.toLowerCase()).trim();

        return (
          !!this.removerAcentos(item.name.toLowerCase()).match(filter)
        );
      });
    },
  },
  methods: {
    Exclude: function (driver,index) {
      var self = this;

      this.$http.delete("/api/groups/" + driver.id).then(function (e) {
        $("#modal-" + index).modal("hide");

        if (e.body.success) {
          this.getData();
          self.$toaster.success(e.body.success);
          return;
        }

        self.$toaster.error(e.body.error);
      });
    },
    getData(search = "") {
      this.$http.get("/api/groups?search=" + search).then((e) => {
        this.$data.objLst =  e.body;
      });
    },
  },
};
</script>
