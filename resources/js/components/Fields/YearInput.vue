<template>
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label :for="yearTitle">{{ yearTitle }}</label>
          <input-icon
            icon="fas fa-calendar"
            type="year"
            v-model="yearValue"
            :name="yearName"
            :id="yearId"
          />
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import InputIcon from '@/components/iconInput.vue';
  
  export default {
    components: {
      InputIcon,
    },
    props: ['value', 'yearTitle', 'yearName', 'yearId'],
    name: 'YearInput',
    watch: {
      yearValue: function (newValue, oldValue) {
        this.$emit('input', newValue);
      },
      value: function (newValue, oldValue) {
        if (!newValue) {
          return;
        }
  
        this.$data.yearValue = newValue;
      },
    },
    data() {
      return {
        yearValue: null,
      };
    },
  };
  </script>
  