<template>
    <div class="modal fade" id="modal-product">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">{{ this.type }} Item</h4>
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
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="description"
                      >Description of Goods</label
                    >
                    <div
                      class="input-group"
                      v-bind:class="
                        error.description != '' &&
                        error.description
                          ? 'is-invalid'
                          : ''
                      "
                    >
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-pen"></i>
                        </div>
                      </div>
                      <input
                        type="text"
                        name="description"
                        id="description"
                        placeholder="Item description"
                        class="form-control"
                        v-model="localItem.description"
                        @input="validateField('description')"
                        :class="
                          error.description != '' &&
                          error.description
                            ? 'is-invalid'
                            : ''
                        "
                      />
                    </div>
                    <p
                      class="invalid-feedback"
                      v-if="error.description"
                      v-for="message in error.description"
                    >
                      {{ message }}
                    </p>
                  </div>
                </div>
    
                <div class="col-3">
                  <div class="form-group">
                    <label for="hs_code"
                      >HS Code</label
                    >
                    <div
                      class="input-group"
                      v-bind:class="
                        error.hs_code != '' &&
                        error.hs_code
                          ? 'is-invalid'
                          : ''
                      "
                    >
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-pen"></i>
                        </div>
                      </div>
                      <input
                        type="text"
                        name="hs_code"
                        id="hs_code"
                        class="form-control"
                        v-model="localItem.hs_code"
                        @input="validateField('hs_code')"
                        :class="
                          error.hs_code != '' &&
                          error.hs_code
                            ? 'is-invalid'
                            : ''
                        "
                      />
                    </div>
                    <p
                      class="invalid-feedback"
                      v-if="error.hs_code"
                      v-for="message in error.hs_code"
                    >
                      {{ message }}
                    </p>
                  </div>
                </div>
                
              </div>
    
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="description"
                      >Botanical name</label
                    >
                    <div
                      class="input-group"
                      v-bind:class="
                        error.description != '' &&
                        error.description
                          ? 'is-invalid'
                          : ''
                      "
                    >
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-pen"></i>
                        </div>
                      </div>
                      <input
                        type="text"
                        name="botanical_name"
                        id="botanical_name"
                        placeholder="Item botanical name"
                        class="form-control"
                        v-model="localItem.botanical_name"
                        @input="validateField('botanical_name')"
                        :class="
                          error.botanical_name != '' &&
                          error.botanical_name
                            ? 'is-invalid'
                            : ''
                        "
                      />
                    </div>
                    <p
                      class="invalid-feedback"
                      v-if="error.botanical_name"
                      v-for="message in error.botanical_name"
                    >
                      {{ message }}
                    </p>
                  </div>
                </div>
              </div>
    
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <iconinput
                      label="Crop Year:"
                      type="month"
                      v-model="localItem.crop_year"
                      id="crop_year"
                      name="crop_year"
                      icon="fas fa-calendar"
                      @input="validateField('crop_year')"
                      :error="error.crop_year"
                    />
                  </div>
                </div>
    
                <div class="col">
                  <div class="form-group">
                    <label for="quantity">Quantity (TON)</label>
                    <div
                      class="input-group"
                      v-bind:class="
                        error.quantity != '' && error.quantity
                          ? 'is-invalid'
                          : ''
                      "
                    >
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-weight-hanging"></i>
                        </div>
                      </div>
                      <input
                        @input="() => {
                          calcTotalPrice();
                          validateField('quantity');
                          }"
                        type="number"
                        name="quantity"
                        id="quantity"
                        placeholder="Quantity"
                        class="form-control"
                        min="1"
                        v-model="localItem.quantity"
                        :class="
                          error.quantity != '' && error.quantity
                            ? 'is-invalid'
                            : ''
                        "
                      />
                    </div>
                    <p
                      class="invalid-feedback"
                      v-if="error.quantity"
                      v-for="message in error.quantity"
                    >
                      {{ message }}
                    </p>
                  </div>
                </div>
    
                <div class="col">
                  <div class="form-group">
                    <label for="unit_price"
                      >Unit Price (US$)</label
                    >
                    <div
                      class="input-group"
                      v-bind:class="
                        error.unit_price != '' &&
                        error.unit_price
                          ? 'is-invalid'
                          : ''
                      "
                    >
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-money-bill"></i>
                        </div>
                      </div>
                      <input
                        @input="() => {
                          calcTotalPrice();
                          validateField('quantity');
                          }"
                        type="number"
                        name="unit_price"
                        id="unit_price"
                        placeholder="Unit Price"
                        class="form-control"
                        min="1"
                        v-model="localItem.unit_price"
                        :class="
                          error.unit_price != '' &&
                          error.unit_price
                            ? 'is-invalid'
                            : ''
                        "
                      />
                    </div>
                    <p
                      class="invalid-feedback"
                      v-if="error.unit_price"
                      v-for="message in error.unit_price"
                    >
                      {{ message }}
                    </p>
                  </div>
                </div>
    
                <div class="col">
                  <div class="form-group">
                    <label for="total_price"
                      >Total Price (US$)</label
                    >
                    <div
                      class="input-group"
                      v-bind:class="
                        error.total_price != '' &&
                        error.total_price
                          ? 'is-invalid'
                          : ''
                      "
                    >
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-money-bill"></i>
                        </div>
                      </div>
                      <input
                        readonly
                        type="number"
                        name="total_price"
                        id="total_price"
                        placeholder="Total Price"
                        class="form-control"
                        min="1"
                        v-model="localItem.total_price"
                        @input="validateField('total_price')"
                        :class="
                          error.total_price != '' &&
                          error.total_price
                            ? 'is-invalid'
                            : ''
                        "
                      />
                    </div>
                    <p
                      class="invalid-feedback"
                      v-if="error.total_price"
                      v-for="message in error.total_price"
                    >
                      {{ message }}
                    </p>
                  </div>
                </div>
              </div>
    
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="value"
                      >Total Price in Words</label
                    >
                    <div
                      class="input-group"
                      v-bind:class="
                        error.value != '' && error.value
                          ? 'is-invalid'
                          : ''
                      "
                    >
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-money-bill"></i>
                        </div>
                      </div>
                      <input
                        type="text"
                        name="value"
                        id="value"
                        placeholder="Value in words"
                        class="form-control"
                        v-model="localItem.value"
                        @input="validateField('value')"
                        :class="
                          error.value != '' && error.value
                            ? 'is-invalid'
                            : ''
                        "
                      />
                    </div>
                    <p
                      class="invalid-feedback"
                      v-if="error.value"
                      v-for="message in error.value"
                    >
                      {{ message }}
                    </p>
                  </div>
                </div>
    
                <div class="col">
                  <div class="form-group">
                    <label for="value">Advance Payment</label>
                    <div
                      class="input-group"
                      v-bind:class="
                        error.advance_payment != '' &&
                        error.advance_payment
                          ? 'is-invalid'
                          : ''
                      "
                    >
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-money-bill"></i>
                        </div>
                      </div>
                      <input
                        type="number"
                        name="advance_payment"
                        id="advance_payment"
                        placeholder="0"
                        class="form-control"
                        min="1"
                        v-model="localItem.advance_payment"
                        @input="validateField('advance_payment')"
                        :class="
                          error.advance_payment != '' &&
                          error.advance_payment
                            ? 'is-invalid'
                            : ''
                        "
                      />
                    </div>
                    <p
                      class="invalid-feedback"
                      v-if="error.advance_payment"
                      v-for="message in error.advance_payment"
                    >
                      {{ message }}
                    </p>
                  </div>
                </div>
    
                <div class="col">
                  <div class="form-group">
                    <label for="value">Container</label>
                    <div
                      class="input-group"
                      v-bind:class="
                        error.container != '' && error.container
                          ? 'is-invalid'
                          : ''
                      "
                    >
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-weight-hanging"></i>
                        </div>
                      </div>
                      <input
                        type="number"
                        name="container"
                        id="container"
                        placeholder="0"
                        class="form-control"
                        min="1"
                        v-model="localItem.container"
                        @input="validateField('container')"
                        :class="
                          error.container != '' &&
                          error.container
                            ? 'is-invalid'
                            : ''
                        "
                      />
                    </div>
                    <p
                      class="invalid-feedback"
                      v-if="error.container"
                      v-for="message in error.container"
                    >
                      {{ message }}
                    </p>
                  </div>
                </div>
              </div>
    
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="total_bags"
                      >Total Quantity (Bags)</label
                    >
                    <div
                      class="input-group"
                      v-bind:class="
                        error.total_bags != '' &&
                        error.total_bags
                          ? 'is-invalid'
                          : ''
                      "
                    >
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-sort-amount-up"></i>
                        </div>
                      </div>
                      <input
                        type="number"
                        name="total_bags"
                        id="total_bags"
                        placeholder="Total bags"
                        class="form-control"
                        min="1"
                        v-model="localItem.total_bags"
                        @input="validateField('total_bags')"
                        :class="
                          error.total_bags != '' &&
                          error.total_bags
                            ? 'is-invalid'
                            : ''
                        "
                      />
                    </div>
                    <p
                      class="invalid-feedback"
                      v-if="error.total_bags"
                      v-for="message in error.total_bags"
                    >
                      {{ message }}
                    </p>
                  </div>
                </div>
    
                <div class="col">
                  <div class="form-group">
                    <label for="net_weight">Net Weight</label>
                    <div
                      class="input-group"
                      v-bind:class="
                        error.net_weight != '' &&
                        error.net_weight
                          ? 'is-invalid'
                          : ''
                      "
                    >
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-weight-hanging"></i>
                        </div>
                      </div>
                      <input
                        type="number"
                        name="net_weight"
                        id="net_weight"
                        placeholder="Net Weight"
                        class="form-control"
                        min="1"
                        v-model="localItem.net_weight"
                        @input="validateField('net_weight')"
                        :class="
                          error.net_weight != '' &&
                          error.net_weight
                            ? 'is-invalid'
                            : ''
                        "
                      />
                    </div>
                    <p
                      class="invalid-feedback"
                      v-if="error.net_weight"
                      v-for="message in error.net_weight"
                    >
                      {{ message }}
                    </p>
                  </div>
                </div>
    
                <div class="col">
                  <div class="form-group">
                    <label for="gross_weight"
                      >Gross Weight</label
                    >
                    <div
                      class="input-group"
                      v-bind:class="
                        error.gross_weight != '' &&
                        error.gross_weight
                          ? 'is-invalid'
                          : ''
                      "
                    >
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-weight-hanging"></i>
                        </div>
                      </div>
                      <input
                        type="number"
                        name="gross_weight"
                        id="gross_weight"
                        placeholder="Gross Weight"
                        class="form-control"
                        min="1"
                        v-model="localItem.gross_weight"
                        @input="validateField('gross_weight')"
                        :class="
                          error.gross_weight != '' &&
                          error.gross_weight
                            ? 'is-invalid'
                            : ''
                        "
                      />
                    </div>
                    <p
                      class="invalid-feedback"
                      v-if="error.gross_weight"
                      v-for="message in error.gross_weight"
                    >
                      {{ message }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-default"
                data-dismiss="modal"
              >
                Close
              </button>
              <button
                type="button"
                class="btn btn-primary"
                @click="StoreItem(index)"
              >
                Save
              </button>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
export default {
name: "products-modal",
  data (){
    return {
      error: {},
      localItem: {},
      type: null
    }
  },
  props: {
    index: {
      type: Number,
      default: undefined,
      required: false
    },
    item: {
      type: Object,
      default: {},
      required: false
    }
  },
  created() {
    if (this.item && Object.keys(this.item).length > 0) {
      this.localItem = { ...this.item };
    }else{
      this.localItem = {
        crop_year: this.todayYearMonth()
      };
    }
  },
  watch: {
    item: {
      handler(newValue) {
        if (this.item && Object.keys(this.item).length > 0) {
          this.type = 'Edit';
          const year = newValue.crop_year.length === 4 ? newValue.crop_year + '-01' : newValue.crop_year;
          this.localItem = { ...newValue, crop_year: year };
          this.localItem = { ...this.item };
        }else{
          this.localItem = {
            crop_year: this.todayYearMonth()
          };
          this.type = 'New';
        }
        this.error = {};
      },
      immediate: true
    }
  },
  methods: {
    todayYearMonth() {
      const date = new Date();
      const year = date.getFullYear();
      const month = (date.getMonth() + 1).toString().padStart(2, '0');

      return `${year}-${month}`;
    },

    validateField: function (fieldName) {
      if (!this.localItem[fieldName]) {
        this.error[fieldName] = ['Required field'];
      } else {
        this.error[fieldName] = [];
      }
    },

    StoreItem: function () {
      let errors = [];
      for (const field in this.error) {
        if (this.error[field].length > 0) {
          errors.push(`Erro no campo ${field}: ${this.error[field].join(', ')}`);
        }
      }
      
      if(errors.length > 0){
        return;
      }
      const isEdit = this.index !== undefined;

      this.$emit("callback", this.localItem, this.index, isEdit);
      $("#modal-product").modal("hide");
    },

    calcTotalPrice: function () {
      this.localItem.total_price = 0;
      if (this.localItem.quantity > 0 && this.localItem.unit_price > 0 )
      {
        this.localItem.total_price = this.localItem.quantity * this.localItem.unit_price;
        this.ConvertNumberToWords(this.localItem.total_price);
      }
    },

    ConvertNumberToWords: function  (amount) {
      var words = new Array();
      words[0] = '';
      words[1] = 'One';
      words[2] = 'Two';
      words[3] = 'Three';
      words[4] = 'Four';
      words[5] = 'Five';
      words[6] = 'Six';
      words[7] = 'Seven';
      words[8] = 'Eight';
      words[9] = 'Nine';
      words[10] = 'Ten';
      words[11] = 'Eleven';
      words[12] = 'Twelve';
      words[13] = 'Thirteen';
      words[14] = 'Fourteen';
      words[15] = 'Fifteen';
      words[16] = 'Sixteen';
      words[17] = 'Seventeen';
      words[18] = 'Eighteen';
      words[19] = 'Nineteen';
      words[20] = 'Twenty';
      words[30] = 'Thirty';
      words[40] = 'Forty';
      words[50] = 'Fifty';
      words[60] = 'Sixty';
      words[70] = 'Seventy';
      words[80] = 'Eighty';
      words[90] = 'Ninety';
      amount = amount.toString();
      var atemp = amount.split(".");
      var number = atemp[0].split(",").join("");
      var n_length = number.length;
      var words_string = "";

      if (n_length <= 9) {
          var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
          var received_n_array = new Array();

          for (var i = 0; i < n_length; i++) {
              received_n_array[i] = number.substr(i, 1);
          }

          for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
              n_array[i] = received_n_array[j];
          }

          for (var i = 0, j = 1; i < 9; i++, j++) {
              if (i == 0 || i == 2 || i == 4 || i == 7) {
                  if (n_array[i] == 1) {
                      n_array[j] = 10 + parseInt(n_array[j]);
                      n_array[i] = 0;
                  }
              }
          }

          let value = null;
          for (var i = 0; i < 9; i++) {
              if (i == 0 || i == 2 || i == 4 || i == 7) {
                  value = n_array[i] * 10;
              } else {
                  value = n_array[i];
              }

              if (value != 0) {
                  words_string += words[value] + " ";
              }

              if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
                  words_string += "Crores ";
              }

              if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
                  words_string += "Lakhs ";
              }

              if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
                  words_string += "Thousand ";
              }

              if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
                  words_string += "Hundred and ";
              } else if (i == 6 && value != 0) {
                  words_string += "Hundred ";
              }
          }

          words_string = words_string.split("  ").join(" ");
        }

      this.$data.localItem.value = words_string + "dollars";

    },
  },
};
</script>
