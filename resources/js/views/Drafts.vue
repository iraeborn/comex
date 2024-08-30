<template>
    <div class="container-fluid">

            <div id="ui-view">

                <div class="panel panel-success">
                    
<div class="card">
  <div class="card-header">
    <div class="row">
        <div class="col">
            Orders
        </div>
    </div>
  </div>
  <div class="card-body">
    <p class="text-center" v-if="!orders.length">No records found</p>
    <table class="table table-sm" v-if="orders.length">
        <tr>
            <th>Importer name</th>
            <th>Order date</th>
            <th>Draft status</th>
            <th>&nbsp;</th>
        </tr>
        <tr v-for="(order, index) in page_orders">
            <td>{{ order.owner.name }}</td>
            <td>{{ FormatDate(order.created_at) }}</td>
            <td>\{\{ order.draft.status.name }}</td>
            <td class="text-right">
                <router-link :to="{ name: 'Edit draft document', params: { id: order.id } }" class="btn btn-success btn-sm">Edit</router-link>
                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" @click="Delete">Delete</button>

            </td>
        </tr>
    </table>

    <popcorn-pagination v-model="page_orders" :items="orders"></popcorn-pagination>
  </div>
</div>



                </div>

            </div>
    </div>

</template>

<script>
export default {
    data() {
        return {
            orders: [],
            page_orders: [],
            status: [],
            error: [],
            document_status: [
                {name: 'In analysis', id: '1'},
                {name: 'Rejected',    id: '2'},
                {name: 'Approved',     id: '3'}
            ],
        }
    },
    methods: {
        FormatDate: function (date) {
            var d = new Date(date)
            var month = (d.getUTCMonth() + 1).toString()
            var day = d.getUTCDate().toString()
            var year = d.getUTCFullYear().toString()
            var hours = d.getUTCHours().toString()
            var minutes = d.getUTCMinutes().toString()
            var seconds = d.getUTCSeconds().toString()

            month   = month.length < 2   ? "0" + month : month
            day     = day.length < 2     ? "0" + day : day
            hours   = hours.length < 2   ? "0" + hours : hours
            minutes = minutes.length < 2 ? "0" + minutes : minutes
            seconds = seconds.length < 2 ? "0" + seconds : seconds

            return month + "/" + day + "/" + year + " " + hours + ":" + minutes
        },
        Delete: function ( id ) {
            var index
            for (index in this.$data.orders) {
                if (this.$data.orders[index].id != id) continue
                var order = this.$data.orders[index]
                break
            }

            var self = this

            this.$http.delete('/api/draft/' + order.id).then(function (e) {
                $('#modal-' + index).modal('hide')

                if (e.body.success) {
                    self.$data.orders.splice(index,1)
                    self.$toaster.success(e.body.success)
                    return;
                }

                self.$toaster.error(e.body.error)
            })
        },
    },
    mounted: function () {
        this.$http.get('/api/drafts').then( e => {
            this.$data.orders = this.$data.page_orders = e.body
        })
    }
}

</script>