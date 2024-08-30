<template>
    <div class="container-fluid">
        <div id="ui-view"></div>
        <div class="panel panel-success">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            New Bank
                        </div>
                    </div>
                </div>

                    <PopcornBank
                        :parent-action="action"
                        :loading="loading"
                        :store-function="callChildStoreFunction"
                        :redirect="redirect"
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

<style>
.is-invalid~.invalid-feedback {
    display: block;
}

.action-column {
    width: 121px;
}

.caixa-icone {
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
    background-color: #f0f3f5;
    border: 1px solid #e4e7ea;
    padding: 6px 12px;
    width: auto;
    float: left;
    color: #5c6873;
}

.caixa-icone~.select2-container {
    width: calc(100% - 39.25px) !important;
}

.table-amarelo {
    background-color: #fef101;
}

.table-amarelo th,
.table-amarelo td {
    border: 2px solid #fff;
}
</style>

<script>
import PopcornBank from '../components/PopcornBank.vue';

export default {
    data() {
        return {
            saving: false,
            loading: true,
            action: "add",
            redirect: true
        }
    },
    components: {
        PopcornBank
    },
    methods: {
        callChildStoreFunction() {
            this.$refs.popcornBank.StoreBank(this.$data.action);
        },

        callbackBank({status, redirect}) {

            if(status == 'success'){
                this.$toaster.success('New bank add sucessfully');
            }

            if(status == 'error'){
                this.$toaster.error('New bank add sucessfully');
            }

            if(redirect){
                this.$router.push("/banks");
            }

            // this.getBanks();
            $('#modal-new-bank').modal('hide');
            this.saving = false;
        },
    },
}
</script>
