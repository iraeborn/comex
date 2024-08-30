<template>
  <div class="modal fade modal-primary text-left" :id="modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New Group</h4>
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
                id="name:"
                name="name"
                autocomplete="off"
                icon="fas fa-clipboard"
                :error="error.name"
                label="Name:"
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
  name: "popcorn-group-add",
  props: {
    modal: {
      type: String,
      required: true,
      default: function () {
        return new Date().getTime().toString();
      },
    },
    getGroups: {
      type: Function,
      required: false,
      default: null
    }
  },
  data() {
    return {
      name:'',
      formData:{
        name:'null',
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
      this.$http.post("/api/groups", this.$data.formData).then((e) => {

        if (e.body.errors) {
          self.$toaster.error(e.body.errors);
        } 
        else {
          $("#"+self.modal).modal("hide");
          self.$toaster.success(e.body.success);

          if(this.getGroups){
            this.getGroups(e.body.group_id);
          }
        }
      });
    },
  },
};
</script>
