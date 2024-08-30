<template>
  <div>
    <div class="modal fade" :id="'modal-extra-container' + container.id">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Container {{ container.name }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            {{order_bills}}
            <table class="table table-sm">
              <tr>
                <th>
                  Register in this container
                </th>
                <th>
                  Associated container
                </th>
                <th>
                  Bill Number
                </th>
                <th>
                  Bill Key
                </th>
              </tr>

              <tr v-for="(bill, index) in order_bills">
                <td>
                  <input type="checkbox" v-model="selected" :value="bill.id">
                </td>
                <td>
                  {{bill}}<br>
                  {{ GetContainerNameById(bill.container_id) }}
                  
                </td>
                <td>
                  {{ bill.number }}
                </td>
                <td>
                  {{ bill.key }}
                </td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" @click="Save">Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.table .table {
  background-color: initial !important;
}
</style>

<script>
export default {
  props: ['value', 'containers_list'],
  name: 'popcorn-modal-container-extra-field',
  data () {
    return {
      container: {},
      containers: [],
      order_bills: [],
      selected: []
    }
  },
  mounted: function () {
    this.$data.containers = JSON.parse(JSON.stringify(this.$props.containers_list))
    this.$data.container = JSON.parse(JSON.stringify(this.$props.value))
    if(typeof this.$data.container.extra_fields == "undefined")
      this.$data.container.extra_fields = []

    this.ReloadBills()
  },
  watch: {
    value: function (newValue, oldValue) {
        if(newValue) {
            this.$data.container = JSON.parse(JSON.stringify(newValue))
            return
        }

        this.$data.container = null
    },
    containers_list: function (newValue, oldValue) {
      if (newValue) {
        this.$data.containers = JSON.parse(JSON.stringify(newValue))
      }
    }
  },
  methods: {
    ReloadBills: function () {
      this.$http.get('/api/bill/' + this.$route.params.id).then( e => {
        this.$data.order_bills = e.body
        this.$data.selected = e.body.map(e => {
          if (e.container_id == this.$props.value.id)
            return e.id
          return null
        }).filter(e => {
          return e !== null
        })
      })

    },
    AddExtraField: function () {
      if (typeof this.$data.container.extra_fields == 'undefined') this.$data.container.extra_fields = []
      this.$data.container.extra_fields.push({
        name: null,
        value: null,
      });

      this.$forceUpdate()
    },
    Remove: function (index) {
      this.$data.container.extra_fields.splice(index, 1)
      this.$forceUpdate()
    },
    Rollback: function ( index ) {
      $('.modal').modal('hide')

      if(typeof this.$props.value.extra_fields == "undefined") {
        this.$data.container.extra_fields = []
        return
      }

      this.$data.container.extra_fields = JSON.parse(JSON.stringify(this.$props.value.extra_fields))
      this.$forceUpdate()
    },
    GetContainerNameById: function (container_id) {
      if (container_id == this.$data.container.id) {
        return this.$data.container.name
      }

      for (var i in this.$data.containers) {
        if (this.$data.containers[i].id == container_id) return this.$data.containers[i].name
      }

    return 'Not associated'
    },
    Save: function () {
      this.$emit('select', this.$data.selected, this.$data.container.id)
      $('.modal').modal('hide')
    }
  }
}
</script>