<template>
  <div class="modal fade modal-primary text-left" :id="modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New Port</h4>
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
              <iconinput
                type="text"
                v-model="formData.name"
                id="name"
                name="name"
                icon="fas fa-clipboard"
                :error="error.name"
                label="Name:"
              />
            </div>
          </div>

          <div class="row">
            <div class="col">
              <iconinput
                label="Code:"
                type="text"
                v-model="formData.code"
                id="code"
                name="code"
                icon="fas fa-clipboard"
                :error="error.code"
              />
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            Cancel
          </button>
          <button
            type="button"
            class="btn btn-success"
            @click="Save()"
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
  name: "popcorn-port-add",
  props: {
    modal: {
      type: String,
      required: true,
      default: function () {
        return new Date().getTime().toString();
      },
    },
    getPorts: {
      type: Function,
      required: false,
      default: null
    }
  },
  data() {
    return {
      formData:{
        name:null,
        code:null,
      },
      error:{}
    };
  },
  watch: {},
  mounted: function () {},
  methods: {
    Save: function (selected_bills) {
      
      if(!this.formData.name){
        this.$toaster.error(this.error.name);
        return;
      }
      $("#"+this.modal).modal("show");

      let self = this
      this.$http.put("/api/ports/", this.$data.formData).then((e) => {
        if (e.body.errors) {
          

          self.$toaster.error(e.body.errors);
        } 
        else {
          $("#"+self.modal).modal("hide");
          self.$toaster.success(e.body.success);

          if(this.getPorts){
            this.getPorts(e.body.port_id);
          }
        }
      });
    },
  },
};
</script>
