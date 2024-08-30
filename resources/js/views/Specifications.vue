<template>
  <div class="container-fluid">
    <div id="ui-view">
      <div class="panel panel-success">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">Specifications</div>
              <div class="col text-right">
                <router-link
                  :to="{ name: 'New Specification' }"
                  class="btn btn-success btn-sm"
                >Add new specification</router-link>
              </div>
            </div>
          </div>
          <div class="card-body">
            <p class="text-center" v-if="!objLst.length">No records found</p>
            <table class="table table-sm" v-if="objLst.length">
              <tr>
                <th>Description</th>
                <th>&nbsp;</th>
              </tr>
              <tbody v-for="(obj, index) in objLst">
                <tr v-if="obj.id">
                    <td>{{ obj.description }}</td>
                    <td class="text-right">
                        <router-link :to="{ name: 'Edit Specification', params: { id: obj.id } }" class="btn btn-success btn-sm">Edit</router-link>
                    </td>
                </tr>
              </tbody>
            </table>
            <popcorn-pagination v-model="page" :items="objLst"></popcorn-pagination>
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
export default {
    data() {
        return {
            objLst: [],
            page: [],
        }
    },
    mounted: function () {
        this.$http.get('/api/specifications').then( e => {
            this.$data.objLst = this.$data.page = e.body;
            this.$data.objLst = this.$data.page = e.body.sort((a, b) => {
                if(!a.loading_deadline) return -1;
                if(!b.loading_deadline) return 1;
                return this.DateToTimestamp(a.loading_deadline) - this.DateToTimestamp(b.loading_deadline);
            });
        })
    }
}

</script>
