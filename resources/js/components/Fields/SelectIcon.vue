<template>
    <div class="form-group">
      <div class="input-group" :class="componentError != '' && componentError ? 'is-invalid' : ''">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i :class="componentIcon"></i>
          </div>
        </div>
        <select class="form-control" :id="componentName" :name="componentName" v-model="componentValue">
          <slot></slot>
        </select>

      </div>
      <div class="invalid-feedback" v-if="componentError" v-for="message in componentError">{{ message }}</div>
    </div>
</template>

<script>
export default {
    props: ['value', 'name', 'icon', 'error'],
    name: 'select-icon',
    watch: {
        componentValue: function (newValue, oldValue) {
            this.$emit('input', newValue)
        },
        value: function (newValue, oldValue) {
            this.$data.componentValue = newValue
        },
        label: function (newValue, oldValue) {
            this.$data.componentLabel = newValue
        },
        name: function (newValue, oldValue) {
            this.$data.componentName = newValue
        },
        icon: function (newValue, oldValue) {
            this.$data.componentIcon = newValue
        },
        type: function (newValue, oldValue) {
            this.$data.componentType = newValue
        },
        error: function (newValue, oldValue) {
            this.$data.componentError = newValue
        },
    },
    mounted: function () {
        this.$data.componentValue = this.$props.value;
    },
    data() {
        return {
            componentValue: null,
            componentLabel: null,
            componentName: null,
            componentIcon: 'fas fa-calculator',
            componentType: 'text',
            componentError: null,
        }
    }
}
</script>