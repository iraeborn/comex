<template>
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="monthYear">{{ monthYearTitle }}</label>
          <input-icon
            icon="fas fa-calendar"
            type="month"
            v-model="monthYearValue"
            :name="monthYearName"
            :id="monthYearId"
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
    props: ['value', 'monthYearTitle', 'monthYearName', 'monthYearId'],
    name: 'monthYearInput',
    watch: {
      monthYearValue: function (newValue, oldValue) {
        this.$emit('input', newValue);
      },
      value: function (newValue, oldValue) {
        if (!newValue) {
          return;
        }
  
        this.$data.monthYearValue = newValue;
      },
    },
    data() {
      return {
        monthYearValue: null,
      };
    },
  };
  </script>
  