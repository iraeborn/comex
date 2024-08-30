<template>
  <div :class="classProp">
    <button class="btn btn-success btn-sm" @click="RegisterStartBalance">Register Initial Balance</button>

    <div class="modal fade" id="modal-register-initial-balance">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Register Initial Balance</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <iconinput icon="fas fa-calendar" label="Expiration Date" type="date" v-model="expiry_date" />
            <iconinput icon="fas fa-money-bill-wave" label="Initial Balance" type="number" v-model="balance" />
            <iconinput icon="fas fa-money-bill-wave" label="Dollar Value" type="number" v-model="dollar" />
            <iconinput icon="fas fa-file-invoice" label="NF" type="text" v-model="nf" />
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

<script>
export default {
  name: "register-initial-balance",
  props: ['value', 'class'],
  data() {
    return {
      expiry_date: null,
      balance: 0,
      nf: null,
      dollar: 0,
    }
  },
  watch: {
    value: function (newValue, oldValue) {
      this.$data.balance = newValue;
    }
  },
  methods: {
    RegisterStartBalance: async function () {
      $('#modal-register-initial-balance').modal('show');
    },
    Save: async function () {
      let balance = this.$data.balance;
      let expiry_date = this.$data.expiry_date;
      let nf = this.$data.nf;
      let dollar_value = this.$data.dollar;
      let type = 1;
      let save_information = await this.$http.post('/api/balance/' + this.$route.params.id, {balance, type, expiry_date, nf, dollar_value});

      if (save_information.body.success) {
        this.$toaster.success('Saved!');
        $('.modal').modal('hide');
        return;
      }

      this.$toaster.error(save_information.body.message);
    }
  }
}
</script>