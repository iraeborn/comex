<template>
    <div class="container-fluid">
        <div id="ui-view"></div>
        <div class="panel panel-success">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            Edit Bank
                        </div>
                    </div>
                </div>

                <div class="card-body text-center" v-if="loading">
                    Loading data...
                </div>

                    <PopcornBank
                        :parent-action="action"
                        :loading="loading"
                        :redirect="redirect"
                        :store-function="callChildStoreFunction"
                        @callback="callbackBank"
                        ref="popcornBank"
                    />

                <div class="card-footer text-right">
                    <router-link :to="{ name: 'Banks' }" class="btn btn-danger">Cancel</router-link>
                    <input type="button" :value="saving ? 'Saving...' : 'Save'" @click="callChildStoreFunction()" class="btn btn-success" :disabled="saving">
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import PopcornBank from '../components/PopcornBank.vue';

export default {
    data() {
        return {
            saving: false,
            loading: true,
            action: "change",
            redirect: true
        }
    },
    components: {
        PopcornBank
    },
    methods: {
        callChildStoreFunction() {
            this.saving = true;
            this.$refs.popcornBank.StoreBank(this.$data.action);
        },

        callbackBank({status}) {

            if(status == 'success'){
                this.$toaster.success('New bank add sucessfully');
            }

            if(status == 'error'){
                this.$toaster.error('New bank add sucessfully');
            }

            // this.getBanks();
            $('#modal-new-bank').modal('hide');
            this.loading = false;
            this.saving = false;
        },
    },
}
</script>
