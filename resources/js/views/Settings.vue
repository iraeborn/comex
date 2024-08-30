<template>
    <div class="container-fluid">

            <div id="ui-view">

                <div class="panel panel-success">
                    
<div class="card">
  <div class="card-header">
    <div class="row">
        <div class="col">
            Settings
        </div>
    </div>
  </div>
  <div class="card-body">

    <div v-if="loading">Loading system settings!</div>
    
    <ul class="nav nav-tabs" v-if="!loading && settings">
        <li class="nav-item">
            <div class="nav-link active" data-toggle="tab" href="#transaction_types">Transaction types</div>
        </li>
        <!-- <li class="nav-item">
            <div class="nav-link" data-toggle="tab" href="#smtp">SMTP</div>
        </li> -->
    </ul>

    <div class="tab-content" v-if="!loading && settings">
        <div id="transaction_types" class="tab-pane active">
            <popcorn-transaction></popcorn-transaction>
        </div>
        <!-- <div id="smtp" class="tab-pane">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="">E-mail from</label>
                        <input type="text" class="form-control" v-model="settings.mail.username" placeholder="E-mail from">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">Name from</label>
                        <input type="text" class="form-control" v-model="settings.mail.name" placeholder="Name from">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="">SMTP</label>
                        <input type="text" class="form-control" v-model="settings.mail.host" placeholder="SMTP">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="">Port</label>
                        <input type="text" class="form-control" v-model="settings.mail.port" placeholder="Port">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="">SMTP password</label>
                        <input type="password" class="form-control" v-model="settings.mail.password" placeholder="SMTP password">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">SMTP Authentication</label><br>
                                <div class="form-check form-check-inline mr-1">
                                    <input class="form-check-input" id="authentication1" v-model="settings.mail.authentication" type="radio" value="0" name="authentication">
                                    <label class="form-check-label" for="authentication1">No</label>
                                </div>
                                <div class="form-check form-check-inline mr-1">
                                    <input class="form-check-input" id="authentication2" v-model="settings.mail.authentication" type="radio" value="1" name="authentication">
                                    <label class="form-check-label" for="authentication2">Yes</label>
                                </div>
                            </div>
                        </div>

                        <div class="col col-form-label">
                            <label for="">SMTP encryption</label><br>
                            <div class="form-check form-check-inline mr-1">
                                <input class="form-check-input" v-model="settings.mail.encryption" id="encryption1" type="radio" value="none" name="encryption">
                                <label class="form-check-label" for="encryption1">None</label>
                            </div>
                            <div class="form-check form-check-inline mr-1">
                                <input class="form-check-input" v-model="settings.mail.encryption" id="encryption2" type="radio" value="ssl" name="encryption">
                                <label class="form-check-label" for="encryption2">SSL/TLS</label>
                            </div>
                            <div class="form-check form-check-inline mr-1">
                                <input class="form-check-input" v-model="settings.mail.encryption" id="encryption3" type="radio" value="starttls" name="encryption">
                                <label class="form-check-label" for="encryption3">STARTTLS</label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col text-right">
                    <input type="button" class="btn btn-primary" value="Send test" @click="SendTest">
                    <input type="button" class="btn btn-success" value="Save" @click="Store">
                </div>
            </div>
        </div> -->
    </div>

  </div>

  <div class="card-footer text-right">
    &nbsp;
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
            settings: false,
            loading: false,
        }
    },
    methods: {
        formatDate: function (date) {
            return date
        },
        Store: function () {
            this.$http.put('/api/settings', this.$data.settings).then(e => {
                this.$toaster.success(e.body.success)
            })
        },
        SendTest: async function () {
            let data = await this.$http.get('/api/email-test?d=2732629@gmail.com');
        }
    },
    mounted: function () {
        this.$data.loading = true
        this.$http.get('/api/settings').then( e => {
            this.$data.settings = e.body
            this.$data.loading = false
        })
    }
}

</script>