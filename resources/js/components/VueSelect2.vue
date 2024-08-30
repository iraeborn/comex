<template>
  <select>
    <slot></slot>
  </select>
</template>

<style>
.select2-container {
  width: 100% !important;
  max-width: 100% !important;
}

.selection {
  height: 33px;
}

.select2-container--default .select2-selection--single {
  background-color: #fff;
  border: 1px solid #7ccc6e !important;
  border-radius: 4px;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
  background-color: #c0ebb9 !important;
  color: #2D57A6 !important;
}

.select2-container--open .select2-dropdown--below {
  width: 300px !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
  height: 33px;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
  line-height: 33px !important;
}

.select2-container .select2-selection--single {
  height: 35px !important;
}

.select2-container--default .select2-selection--single {
  height: 35px;
}

.reto .select2-container--default .select2-selection--single {
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
}

</style>

<script>
export default {
  props: ['options', 'value', 'started', 'placeholder'],
  mounted: function () {
    var vm = this

    let config = {
      data: this.options
    }

    if (this.placeholder) {
      if (this.placeholder == "true") {
        config.placeholder = "Select an option";
      } else {
        config.placeholder = this.placeholder;
      }
    }

    $(this.$el)
      // init select2
      .select2(config)
      .val(this.value)
      .trigger('change')
      // emit event on change.
      .on('change', function () {
        vm.$emit('input', this.value);
      });
  },
  watch: {
    value: function (value) {
      // update value
      $(this.$el)
        .val(value)
        .trigger('change');
    },
    options: function (options) {
      let config = { data: options }

      if (this.placeholder) {
        if (this.placeholder == "true") {
          config.placeholder = "Select an option";
        } else {
          config.placeholder = this.placeholder;
        }
      }

      // update options
      $(this.$el).empty().select2(config);
    }
  },
  destroyed: function () {
    $(this.$el).off().select2('destroy');
  }
}
</script>