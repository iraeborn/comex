<template>
    <footer class="app-footer">
        <div>
            <!-- <span>© {{ year }} {{ this.$root.appName }}.</span> -->
            <span>© {{ year }} 'COMEX - System.'</span>

        </div>
    </footer>

</template>

<script>
import moment from 'moment'

export default {
  name: 'popcorn-footer',
  data: function () {
    return {
        year: moment().year()
    }
  },
}
</script>