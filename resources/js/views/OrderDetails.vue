  <template>
  <div class="container-fluid">
    <div id="ui-view">
      <div class="panel panel-success">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">Details Orders</div>
              <div class="col-2">
                <label for="">Initial Date</label>
                <input
                  type="date"
                  class="form-control"
                  v-model="initial_date"
                />
              </div>
              <div class="col-2">
                <label for="">Final Date</label>
                <input type="date" class="form-control" v-model="final_date" />
              </div>

              <div :class="'col-' + (this.$data.filters.length + 1)">
                <label>
                  Filters
                  <a href="#" @click="addFilter()">
                    <i class="icon-plus" title="Add filter"></i>
                  </a>
                </label>
                <div class="row" id="filters">
                  <div class="col" v-for="(filter, index) in filters">
                    <div class="filter" v-if="filters.length > 1">
                      <i
                        class="icon-close text-danger"
                        @click="removeFilter(index)"
                      ></i>
                      <input
                        type="text"
                        class="form-control"
                        v-model="filters[index]"
                        placeholder="Filter"
                      />
                    </div>
                    <input
                      type="text"
                      class="form-control"
                      v-else
                      v-model="filters[index]"
                      placeholder="Filter"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <p class="text-center" v-if="!filteredDetails.length">
                No records found
              </p>
              <table class="table table-sm" v-else>
                <tr>
                  <th>PO</th>
                  <th>Importer</th>
                  <th>Exporter</th>
                  <th>Loading Location</th>
                  <th>Start Truck Loading Date</th>
                  <th>End Truck Loading Date</th>
                  <th>Packing</th>
                  <th>Label</th>
                  <th>Quantity cont.</th>
                  <th>Cont. Type</th>
                  <th>Product</th>
                  <th>Cross Docking Terminal</th>
                  <th>Stuffing Start Date</th>
                  <th>Stuffing End Date</th>
                  <th>Total Order</th>
                  <th>Shipped Total</th>
                  <th>Balance</th>
                  <th>Quality Observations</th>
                  <th>&nbsp;</th>
                </tr>
                <tr v-for="(order, index) in filteredDetails">
                  <th>
                    {{ order.number }}
                  </th>
                  <td style="font-size: 10px">
                    {{ order.owner ? order.owner.name : ""
                    }}{{
                      order.owner && order.owner.country
                        ? " - " + order.owner.country.toUpperCase()
                        : ""
                    }}
                  </td>
                  <td style="font-size: 10px">
                    {{ order.exporter ? order.exporter.name : ""
                    }}{{
                      order.exporter && order.exporter.country
                        ? " - " + order.exporter.country.toUpperCase()
                        : ""
                    }}
                  </td>
                  <td style="font-size: 10px">
                    {{ order.loadings ? order.loadings.loading_location : "" }}
                  </td>
                  <td>
                    {{
                      order.loadings
                        ? FormatDate(order.loadings.start_truck_loading_date)
                        : ""
                    }}
                  </td>
                  <td>
                    {{
                      order.loadings
                        ? FormatDate(order.loadings.end_truck_loading_date)
                        : ""
                    }}
                  </td>
                  <td>
                    {{ order.full_packing }}
                  </td>
                  <td>
                    {{ parseInt(order.label) == 1 ? "Yes" : "No" }}
                  </td>
                  <td>
                    {{
                      order.container_stuffing
                        ? order.container_stuffing.qtd_container
                        : ""
                    }}
                  </td>
                  <td>
                    {{
                      order.container_stuffing
                        ? order.container_stuffing.container_type
                        : ""
                    }}
                  </td>
                  <td style="font-size: 10px">
                    {{ order.items[0] ? order.items[0].description : "" }}
                  </td>
                  <td style="font-size: 10px">
                    {{
                      order.container_stuffing
                        ? order.container_stuffing.terminal
                        : ""
                    }}
                  </td>
                  <td>
                    {{
                      order.container_stuffing
                        ? FormatDate(
                            order.container_stuffing.stuffing_starting_date
                          )
                        : ""
                    }}
                  </td>
                  <td>
                    {{
                      order.container_stuffing
                        ? FormatDate(
                            order.container_stuffing.stuffing_ending_date
                          )
                        : ""
                    }}
                  </td>
                  <td>{{ order.container_stuffing.quantity_total }} KG</td>
                  <td>{{ order.total_shipped }} KG</td>
                  <td>{{ order.balance }} KG</td>
                  <td style="font-size: 10px">
                    <span v-for="spec in order.specifications">
                      {{
                        spec.specification
                          ? spec.specification.description
                          : ""
                      }}<br />
                    </span>
                  </td>
                  <td>
                    <a
                      :href="'/order/info/' + order.id"
                      target="_blank"
                      class="btn btn-primary"
                      >Order Information</a
                    >
                    <a
                      :href="'/order/edit/' + order.id"
                      target="_blank"
                      class="btn btn-success"
                      >Order</a
                    >
                    <button
                      type="button"
                      class="btn btn-danger btn-sm btn-block"
                      data-toggle="modal"
                      :href="'#modal-' + index"
                    >
                      Delete
                    </button>
                  </td>

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
                            @click="Exclude(order, index)"
                          >
                            Confirm exclusion
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </tr>
              </table>
            </div>
          </div>
          <div class="card-footer">
            <button class="btn btn-success btn-block" @click="generateReport()">
              Export PDF
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.filter {
  position: relative;
}

.filter .icon-close {
  position: absolute;
  padding: 10px;
  cursor: pointer;
  right: 0px;
}

.filter input {
  padding-right: 30px;
}
</style>

<script>
import queryString from "query-string";
import moment from "moment";

export default {
  data() {
    return {
      details: [],
      filteredDetails: [],
      initial_date: "",
      final_date: "",
      filters: [""],
      filterTimeout: null,
    };
  },
  watch: {
    initial_date: function () {
      this.$data.filteredDetails = this.FilterDetails();
    },
    final_date: function () {
      this.$data.filteredDetails = this.FilterDetails();
    },
    filters: function () {
      var that = this;
      clearTimeout(this.$data.filterTimeout);

      this.$data.filterTimeout = setTimeout(function () {
        that.$data.filteredDetails = that.FilterDetails();
      }, 500);
    },
    details: function () {
      this.$data.filteredDetails = this.FilterDetails();
    },
  },
  mounted: function () {
    this.getOrdersDetail();
  },
  methods: {
    getOrdersDetail() {
      this.$http.get("/api/orders/details").then((e) => {
        if (typeof e.body == "object" && e.body !== null) {
          e.body = Object.values(e.body);
        }

        this.$data.details = this.sortDetails(e.body);
      });
    },

    Exclude: function (order, index) {
      var self = this;

      this.$http.delete("/api/order/" + order.id).then(function (e) {
        $("#modal-" + index).modal("hide");

        if (e.body.success) {
          self.$toaster.success(e.body.success);
          self.getOrdersDetail();
          return;
        }

        self.$toaster.error(e.body.error);
      });
    },

    FilterByInitialDate: function (details, initial_date) {
      if (!initial_date) return details;
      return details.filter((detail) => {
        let date = moment(detail.stuffing_starting_date, "DD/MM/YYYY");
        let init_date = moment(initial_date);

        return date.isSameOrAfter(init_date, "day");
      });
    },
    FilterByFinalDate: function (details, final_date) {
      if (!final_date) return details;
      return details.filter((detail) => {
        let date = moment(detail.stuffing_starting_date, "DD/MM/YYYY");
        let end_date = moment(final_date);

        return date.isSameOrBefore(end_date, "day");
      });
    },
    FilterByTerm: function (details, term) {
      if (!term) return details;
      term = term.toLowerCase();

      return details.filter((detail) => {
        return (
          (detail.number
            ? detail.number.toLowerCase().includes(term)
            : false) ||
          (detail.owner && detail.owner.name
            ? detail.owner.name.toLowerCase().includes(term)
            : false) ||
          (detail.items && detail.items[0] && detail.items[0].description
            ? detail.items[0].description.toLowerCase().includes(term)
            : false) ||
          (detail.container_stuffing && detail.container_stuffing.terminal
            ? detail.container_stuffing.terminal.toLowerCase().includes(term)
            : false) ||
          (detail.loadings && detail.loadings.loading_location
            ? detail.loadings.loading_location.toLowerCase().includes(term)
            : false) ||
          (detail.group && detail.group.name
            ? detail.group.name.toLowerCase().includes(term)
            : false) ||
          (detail.exporter && detail.exporter.name
            ? detail.exporter.name.toLowerCase().includes(term)
            : false)
        );
      });
    },
    FilterDetails: function () {
      var details = this.$data.details;
      var that = this;
      details = this.FilterByInitialDate(details, this.$data.initial_date);
      details = this.FilterByFinalDate(details, this.$data.final_date);
      this.$data.filters.forEach(function (filter) {
        details = that.FilterByTerm(details, filter);
      });

      return details;
    },
    sortDetails: function (details) {
      details = details.map(function (detail) {
        let date = "";

        if (
          detail.hasOwnProperty("stuffing_starting_date") &&
          detail.stuffing_starting_date
        ) {
          date = detail.stuffing_starting_date;
        } else {
          date = detail.created_at;
        }

        detail.date = date;

        return detail;
      });

      details = details.sort(function (a, b) {
        let dateA = moment(a.date, "DD/MM/YYYY");
        let dateB = moment(b.date, "DD/MM/YYYY");

        if (dateA.isAfter(dateB)) {
          return 1;
        } else if (dateB.isAfter(dateA)) {
          return -1;
        }

        return 0;
      });

      return details;
    },
    FormatDate: function (date) {
      if (!date) return "";
      var d = new Date(date);
      var month = (d.getUTCMonth() + 1).toString();
      var day = d.getUTCDate().toString();
      var year = d.getUTCFullYear().toString();

      month = month.length < 2 ? "0" + month : month;
      day = day.length < 2 ? "0" + day : day;

      return month + "/" + day + "/" + year;
    },
    forceFileDownload(response, name) {
      let filename = name + ".pdf";
      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement("a");
      link.href = url;
      link.setAttribute("download", filename);
      document.body.appendChild(link);
      link.click();
    },

    generateFile: function (url, name) {
      this.$http({
        method: "get",
        url: url,
        responseType: "arraybuffer",
      })
        .then((response) => {
          this.forceFileDownload(response, name);
        })
        .catch(() => console.log("error occured"));
    },

    generateReport: function () {
      let params = {
        initial_date: this.$data.initial_date,
        final_date: this.$data.final_date,
        filters: this.$data.filters,
      };
      let url =
        "/api/reports/truck_loading/export?" +
        queryString.stringify(params, { arrayFormat: "bracket" });

      this.generateFile(url, "relatorio-truck_loading");
    },

    addFilter() {
      this.$data.filters.push("");
    },

    removeFilter(index) {
      this.$data.filters.splice(index, 1);
    },
  },
};
</script>
