<template>
  <div class="form-group">
    <label for="draft_bl">{{ label }}</label> <a :href="link" download v-if="link">[Download]</a>

    <div v-if="!link">
      <div class="alert alert-danger">{{ this.$root.appName }} did not send this document.</div>
    </div>
    
    <div v-if="link && value == 2">
        <div class="alert alert-success">Accepted</div>
    </div>
    
    <div v-if="link && value == 3">
        <div class="alert alert-danger"><strong>Rejected</strong></div>
    </div>
    
    <div v-if="link && value == 1">
      <button class="btn btn-success" @click="Approve">Approved</button>
      <button class="btn btn-danger" data-toggle="modal" :href="'#modal-' + internal_id">Reject</button>

      <div class="modal fade" :id="'modal-' + internal_id">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Reason</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
              <slot></slot>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" data-dismiss="modal" @click="Reject">Save changes</button>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
export default {
    props: ['value', 'label', 'link'],
    name: 'popcorn-draft-for-aprove',
    data () {
        return {
            internal_id: null,
        }
    },
    mounted: function () {
        this.$data.internal_id = this.$props.label.toLowerCase().replace(/[^a-z]+/g, '')
    },
    methods: {
        Approve: function () {
            this.$emit('input', 2)
            this.$emit('statusChange')
        },
        Reject: function () {
            this.$emit('input', 3)
            this.$emit('statusChange')
        }
    }
}
</script>