<template>
    <div>
        <div class="row">
            <div class="col">
                
            </div>
        </div>

        <div class="row">
            <div class="col">
                <p class="text-center" v-if="transaction_types.length == 0">No transaction founded!</p>

                <table class="table table-sm" v-if="transaction_types.length > 0">
                    <tr>
                        <th>Name</th>
                        <th>Display to importer</th>
                        <th>&nbsp;</th>
                    </tr>
                    <tr v-for="(transaction_type, index) in transaction_types">
                        <td>
                            <p v-if="!transaction_type.is_editing">{{transaction_type.name}}</p>
                            <input type="text" class="form-control form-control-sm" v-model="transaction_type.name" v-if="transaction_type.is_editing">
                        </td>
                        <td style="width: 15%">
                            <div v-if="!transaction_type.is_editing">
                                <span class="badge badge-lg badge-success" v-if="transaction_type.display_to_importer">Yes</span>
                                <span class="badge badge-lg badge-danger" v-if="!transaction_type.display_to_importer">No</span>
                            </div>
                            <!-- <input type="checkbox" v-model="transaction_type.display_to_importer" v-if="transaction_type.is_editing"> -->
                            <label class="switch switch-pill switch-primary" v-if="transaction_type.is_editing">
                                <input type="checkbox" class="switch-input" v-model="transaction_type.display_to_importer">
                                <span class="switch-slider"></span>
                            </label>
                        </td>
                        <td style="width: 15%" class="text-right">
                            <div v-if="!transaction_type.is_editing">
                                <button @click="Edit(index)" class="btn btn-sm btn-success">Edit</button>
                                <button @click="Delete(index)" class="btn btn-sm btn-danger">Delete</button>
                            </div>
                            <div v-if="transaction_type.is_editing">
                                <button @click="Cancel(index)" class="btn btn-sm btn-danger">Cancel</button>
                                <button @click="Save(index)" class="btn btn-sm btn-success">Save</button>
                            </div>
                        </td>
                    </tr>
                </table>

                <button class="btn btn-success btn-sm" @click="Add">Add new transaction type</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "popcorn-transaction",
    data() {
        return {
            transaction_types: [],
            transaction_types_original: [],
        }
    },
    mounted: function () {
        this.LoadApiData();
    },
    methods: {
        LoadApiData: async function () {
            let transaction_types = await this.$http.get('/api/transaction-types');
            transaction_types.body = transaction_types.body.map(transaction_type => {
                transaction_type.is_editing = false;
                return transaction_type;
            });

            this.$data.transaction_types = transaction_types.body;
            this.$data.transaction_types_original = transaction_types.body.map(transaction_type => {
                return Object.assign({}, transaction_type);
            });
        },
        Add: async function () {
            // let has_new = false;
            let has_new = this.$data.transaction_types.filter(transaction_type => {
                if(transaction_type.id === null) return true;
                return false;
            });

            if(has_new.length > 0) return;

            this.$data.transaction_types.push({
                id: null,
                name: "",
                display_to_importer: 0,
                is_editing: true
            });
        },
        Edit: async function (index) {
            this.$data.transaction_types_original[index] = Object.assign({}, this.$data.transaction_types[index]);
            this.$data.transaction_types[index].is_editing = true;
        },
        Delete: async function (index) {
            let delete_transaction_types = await this.$http.delete('/api/transaction-type/' + this.$data.transaction_types[index].id);
            if(delete_transaction_types.body.success) {
                this.$data.transaction_types.splice(index, 1);
                return;
            }

            this.$toaster.error("Cannot delete this transaction");
        },
        Cancel: async function (index) {
            if(!this.$data.transaction_types[index].id) {
                this.$data.transaction_types.splice(index, 1);
                return;
            }
            this.$data.transaction_types[index].name = this.$data.transaction_types_original[index].name;
            this.$data.transaction_types[index].display_to_importer = this.$data.transaction_types_original[index].display_to_importer;
            this.$data.transaction_types[index].is_editing = false;
        },
        Save: async function (index) {
            let id = this.$data.transaction_types[index].id;
            let saved_transaction_types = await this.$http.put('/api/transaction-type/' + (id||''), this.$data.transaction_types[index]);
            if (!id)
                this.$data.transaction_types[index].id = saved_transaction_types.body.id;
            this.$data.transaction_types[index].is_editing = false;
            this.$toaster.success("Transaction type saved!");
        },
    }
}
</script>