<template>
    <div>
      <b-button class="helpButton" :id="buttonId">{{ buttonLabel }}</b-button>
      
      <b-tooltip ref="tooltip" :target="buttonId">
        {{ tooltipContent }}
      </b-tooltip>
    </div>
</template>
  
<script>
  export default {
    name: "help-tips",
    props: {
      buttonId: {
        type: String,
        required: true
      },
      tooltipContent: {
        type: String,
        required: true
      },
      buttonLabel: {
        type: String,
        default: "?"
      }
    },
  };
</script>
  
<style>
    .tooltip {
        opacity: inherit;
        font-size: 13px;
    }
    .bs-tooltip-top .arrow::before {
    border-top-color: #333;
    }
    .tooltip-inner {
        color: #fff;
        background-color: #333;
    }
    .helpButton {
        height: 15px;
        width: 15px;
        border-radius: 10px;
        margin: 1px 5px 0 0;
        padding: 0;
        font-size: 10px;
        float: left;
    }
</style>
  