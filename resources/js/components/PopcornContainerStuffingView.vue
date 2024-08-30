<template>
  <div>
    <table class="table table-sm table-amarelo">
      <tr>
        <td colspan="3" style="width: 25%"><strong for="terminal">Cross Docking Terminal</strong></td>
        <td colspan="3" style="width: 25%"><strong for="terminal">Depot Pick up Empty Container</strong></td>
        <td colspan="3" style="width: 25%"><strong for="terminal">Quantity Container</strong></td>
        <td colspan="3" style="width: 25%"><strong>Container type</strong></td>
      </tr>
      <tr>
        <td colspan="3"><p>{{ container_stuffing.terminal || "Not informed" }}</p></td>
        <td colspan="3"><p>{{ container_stuffing.depot_pick || "Not informed" }}</p></td>
        <td colspan="3"><p>{{ container_stuffing.qtd_container || "Not informed" }}</p></td>
        <td colspan="3">{{ container_stuffing.container_type || "Not informed" }}</td>
      </tr>
      <tr><td class="spacer" colspan="12"></td></tr>
      <tr>
        <td colspan="6"><strong>Quantity per container (BAGS)</strong></td>
        <td colspan="6"><strong for="number_of_bags">Quantity total (BAGS)</strong></td>
      </tr>
      <tr>
        <td colspan="6">{{ container_stuffing.quantity_per_container || "Not informed" }}</td>
        <td colspan="6">{{ container_stuffing.number_of_bags }}</td>
      </tr>
      <tr><td class="spacer" colspan="12"></td></tr>
      <tr>
        <td colspan="6"><strong for="weight">Quantity per container (KGS)</strong></td>
        <td colspan="6"><strong>Quantity total (KGS)</strong></td>
      </tr>
      <tr>
        <td colspan="6">{{ container_stuffing.weight }}</td>
        <td colspan="6">{{ container_stuffing.quantity_total || "Not informed" }}</td>
      </tr>
      <tr><td class="spacer" colspan="12"></td></tr>
      <tr>
        <td colspan="3"><strong for="empty_release_for_outbound_date">Empty Release for Outbound Date</strong></td>
        <td colspan="3"><strong for="stuffing_starting_date">Stuffing Starting Date</strong></td>
        <td colspan="3"><strong for="stuffing_ending_date">Stuffing Ending Date</strong></td>
        <td colspan="3"><strong for="containers_return_date">Containers Return Date</strong></td>
      </tr>
      <tr>
        <td colspan="3">{{ DateFormat(container_stuffing.empty_release_for_outbound_date) }}</td>
        <td colspan="3">{{ DateFormat(container_stuffing.stuffing_starting_date) }}</td>
        <td colspan="3">{{ DateFormat(container_stuffing.stuffing_ending_date) }}</td>
        <td colspan="3">{{ DateFormat(container_stuffing.containers_return_date) }}</td>
      </tr>
      <tr><td class="spacer" colspan="12"></td></tr>
      <tr>
        <td colspan="6"><strong for="terminal_observations">Terminal Observations</strong></td>
        <td colspan="6"><strong for="terminal_user">Terminal User</strong></td>
      </tr>
      <tr>
        <td colspan="6">{{ container_stuffing.terminal_observations || "Not informed" }}</td>
        <td colspan="6">{{ container_stuffing.terminal_user || "Not informed" }}</td>
      </tr>
  
    </table>
  

    <div class="row">
      <div class="col">
        <strong>Per Container</strong>
      </div>
    </div>

    <div class="row" v-if="container_stuffing">
      <div class="col">
        <containers-display v-model="container_stuffing.containers" :shipping_id="shipping.id"></containers-display>
      </div>
    </div>


    <!-- <div class="row">
      <div class="col" v-if="container_stuffing">
        <p v-if="!container_stuffing.containers">No containers were added</p>
        <table class="table table-sm table-containers" v-if="container_stuffing.containers">
          <tr>
            <th>Container</th>
            <th>Tare</th>
            <th>Seal</th>
            <th style="width: 80px;">&nbsp;</th>
          </tr>
          <tbody v-for="(container, index) in container_stuffing.containers">
            <tr>
              <td>
                {{ container.name }}
              </td>
              <td>
                {{ container.tare }}
              </td>
              <td>
                {{ container.seal }}
              </td>
              <td>
                <button class="btn btn-primary btn-sm" data-toggle="collapse" :data-target="'#collapse' + container.id" aria-expanded="false" aria-controls="collapseExample">View bills</button>
              </td>
            </tr>
            <tr>
              <td colspan="4" class="collapse" :id="'collapse' + container.id">
                <div class="row">
                  <div class="col">
                    <table class="table table-sm">
                      <tr>
                        <th>Bill number</th>
                        <th>key</th>
                      </tr>
                      <tr v-for="bill in container.bills">
                        <td>{{ bill.number }}</td>
                        <td>{{ bill.key }}</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div> -->


  </div>
</template>

<style scoped>
p {
  margin-bottom: 0;
}

.spacer {
  background-color: #fff;
}

.table-containers > th {
  border-top: 5px solid #fef101;
}

.table-containers > td {
  border-top: 3px solid #fef101;
}
</style>

<script>
export default {
    name: 'popcorn-container-stuffing-view',
    props: ['value'],
    data () {
      return {
        shipping: [],
        container_stuffing: {
          containers: null,
          selected: []
        },
        bill_list: [],
        error: {},
      }
    },
    watch: {
      value: async function ( new_value, old_value ) {
        if(!this.$props.value) return;
        this.$data.container_stuffing = JSON.parse(JSON.stringify(new_value))
  
        this.$data.shipping = (await this.$http.get('/api/shipping/' + this.$route.params.id)).body;
  
        this.$data.container_stuffing.containers = this.$data.shipping.containers;
  
        if(this.$data.container_stuffing)
          this.$data.original_containers = this.$data.container_stuffing.containers;
      },
    },
    async mounted() {
      if(!this.$props.value) return;
      this.$data.container_stuffing = JSON.parse(JSON.stringify(this.$props.value))

      this.$data.shipping = (await this.$http.get('/api/shipping/' + this.$route.params.id)).body;

      this.$data.container_stuffing.containers = this.$data.shipping.containers;

      if(this.$data.container_stuffing)
        this.$data.original_containers = this.$data.container_stuffing.containers

      this.$forceUpdate()
    },
    methods: {
        ForceUpdate: function () {
          this.$forceUpdate()
        },
        DateFormat: function (date) {
          if (!date) return "Not informed";
          let [year, month, day] = date.split(/-/);
          return month + "/" + day + "/" + year;
        }
    }
}
</script>