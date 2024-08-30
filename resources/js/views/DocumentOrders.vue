<template>
  <div class="container-fluid">
    <div id="ui-view">
      <div class="panel panel-success">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">Documents</div>

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
                  :to="{ name: 'New Document Order' }"
                  class="btn btn-success btn-sm"
                  >Add new document</router-link
                >
              </div>
            </div>
          </div>
          <div class="card-body">
            <p class="text-center" v-if="!arrayDocuments.length">No records found</p>
            <table class="table table-sm" v-if="arrayDocuments.length">
              <tr>
                <th>Description</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
              </tr>
              <tbody v-for="(obj, index) in arrayDocuments" :key="index">
                <tr v-if="obj.id">
                  <td>{{ obj.description }}</td>
                  <td class="text-right">
                    <router-link
                      :to="{
                        name: 'Edit Document Order',
                        params: { id: obj.id },
                      }"
                      class="btn btn-success btn-sm"
                      >Edit</router-link
                    >
                  </td>

                  <td>
                    <button
                      type="button"
                      class="btn btn-warning btn-sm btn-block"
                      @click="Copy(obj)"
                    >
                      Copy
                    </button>
                  </td>

                  <td>
                    <button
                      type="button"
                      class="btn btn-danger btn-sm btn-block"
                      data-toggle="modal"
                      :href="'#modal-' + index"
                    >
                      Delete
                    </button>
                  </td>
                </tr>

                <div class="modal fade text-left" :id="'modal-' + index">
                    <div class="modal-dialog modal-danger">
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
                          <p>
                            Deleting the request will no longer be possible to
                            see your data in the panel and is an irreversible
                            action.
                          </p>
                          <p>Are you sure you want to continue?</p>
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
                            @click="Exclude(obj, index)"
                          >
                            Confirm exclusion
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
              </tbody>
            </table>
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
  mixins:[utilidade],
  data() {
    return {
      objLst: [],
      page: [],
      filter:null
    };
  },
  mounted: function () {
    this.getOrdersDocuments();
  },
  computed: {
    arrayDocuments() {
      return this.$data.objLst.filter((item) => {
        if (!this.filter) return true;

        let filter = this.removerAcentos(this.filter.toLowerCase()).trim();

        return (
          !!this.removerAcentos(item.description.toLowerCase()).match(filter)
        );
      });
    },
  },
  methods: {
    getOrdersDocuments() {
      this.$http.get("/api/order-document").then((e) => {
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

    Copy: function (document) {
      var self = this;

      this.$http.get("/api/order-document-copy/" + document.id).then(function (e) {
        if (e.body.success) {
          self.$toaster.success(e.body.success);
          self.getOrdersDocuments();
          return;
        }

        self.$toaster.error(e.body.error);
      });
    },

    Exclude: function (document, index) {
      var self = this;

      this.$http.delete("/api/order-document/" + document.id).then(function (e) {
        $("#modal-" + index).modal("hide");

        if (e.body.success) {
          self.$toaster.success(e.body.success);
          self.getOrdersDocuments();
          return;
        }

        self.$toaster.error(e.body.error);
      });
    },
  },
};
</script>
