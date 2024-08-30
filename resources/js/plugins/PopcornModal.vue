<template>
    <div class="modal fade" id="modal-id" :class="class_name">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ title }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body" v-html="text">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// we must import our Modal plugin instance
// because it contains reference to our Eventbus
import Modal from './Modal.js';
export default {
  data() {
    return {
      title: '',
      text: '',
      class_name: '',
      // adding callback function variable
      onConfirm: {}
    }
  },
  methods: {
    hide() {
      $('#modal-id').modal('hide')
    },
    confirm() {
      // we must check if this.onConfirm is function
      if(typeof this.onConfirm === 'function') {
        // run passed function and then close the modal
        this.onConfirm()
        this.hide()
      } else {
        // we only close the modal
        this.hide()
      }
    },
    show(params) {
      this.title = params.title
      this.text = params.text
      this.class_name = params.class_name
      // setting callback function
      this.onConfirm = params.onConfirm
      $('.modal').modal('hide')
      $('#modal-id').modal('show')
    }
  },
  beforeMount() {
    // here we need to listen for emited events
    // we declared those events inside our plugin
    Modal.EventBus.$on('show', (params) => {
      this.show(params)
    })
  }
}
</script>