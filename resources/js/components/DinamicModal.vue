<template>
    <div class="modal fade text-left" id="dinamic-modal" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ modalTitle }}</h4>
            <button type="button" class="close" @click="close" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">

            <component
              v-if="contentComponent"
              :is="contentComponent"
              v-bind="componentProps"
              @update-data="updateData"
            ></component>

            <div v-else v-html="contentHtml"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" @click="close">Cancel</button>
            <button type="button" class="btn btn-success" @click="save">{{ saveButtonLabel ?? 'Save'}}</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "dinamic-modal",
    props: {
      modalId: String,
      modalTitle: String,
      saveButtonLabel: String,
      contentComponent: [Object, Function],
      componentProps: Object,
      contentHtml: String,
      saveAction: Function,
    },
    methods: {
      close(){
          this.$emit('callback', {status: 200, data: {isOpen: false}});
        },
      save() {
        if (this.saveAction) {
          this.saveAction(this.componentProps.orderId);
        }
        this.close();
      },
      updateData(payload) {
        this.$emit('update-data', payload);
      }
    },
  };
  </script>
  