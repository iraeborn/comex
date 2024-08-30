<template>
    <div class="form-group">
      <label :for="componentName">{{componentLabel}}</label>
      <div class="input-group" v-bind:class="componentError != '' && componentError ? 'is-invalid' : ''">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i :class="componentIcon"></i>
          </div>
        </div>
        <input
            :type="componentType"
            :name="componentName"
            :id="componentName"
            :placeholder="componentLabel"
            :readonly="readonly"
            class="form-control"
            v-model="componentValue"
            :class="componentError != '' && componentError ? 'is-invalid' : ''"
            @change="$emit('change', componentValue)"
        >
      </div>
      <div class="invalid-feedback" v-if="componentError" v-for="message in componentError">{{ message }}</div>
    </div>
</template>

<script>
export default {
    props: ['value', 'label', 'name', 'icon', 'type', 'error', 'readonly'],
    name: 'iconinput',
    watch: {
        label: function (newValue, oldValue) {
            this.$data.componentLabel = newValue;
        },
        name: function (newValue, oldValue) {
            this.$data.componentName = newValue;
        },
        icon: function (newValue, oldValue) {
            this.$data.componentIcon = newValue;
        },
        type: function (newValue, oldValue) {
            this.$data.componentType = newValue;
        },
        value: function (newValue, oldValue) {
            this.$data.componentValue = newValue;
        },
        error: function (newValue, oldValue) {
            this.$data.componentError = newValue;
        },
        componentValue: function (newValue, oldValue) {
            //this.$data.componentValue = newValue
            this.$emit('input', newValue);
        }
    },
    mounted: function () {
        this.$data.componentValue = this.$props.value;
        this.$data.componentIcon = this.$props.icon;
        this.$data.componentLabel = this.$props.label;
        this.$data.componentName = this.$props.name;
        this.$data.componentType = this.$props.type;
        this.$data.componentError = this.$props.error;
    },
    data() {
        return {
            componentLabel: null,
            componentName: null,
            componentIcon: 'fas fa-calculator',
            componentType: 'text',
            componentValue: null,
            componentError: null,
        }
    }
}
</script>