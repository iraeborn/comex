<template>
    <div class="row">
        <div class="col">
            <div class="form-group">
              <label for="date">{{ date_title }}</label>
              <input-icon icon="fas fa-calendar" type="date" v-model="dateValue" />
            </div>
        </div>

        <div class="col">
            <div class="form-group">
              <label for="time">{{ time_title }}</label>
              <input-icon icon="fas fa-clock" type="time" v-model="timeValue" />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['value', 'date_title', 'time_title'],
    name: 'dateinput',
    watch: {
        dateValue: function (newValue, oldValue) {
            let value = newValue + " " + this.$data.timeValue;
            if (!value.match(/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}$/))
                value += ":00";
            this.$emit('input', value);
        },
        timeValue: function (newValue, oldValue) {
            let value = this.$data.dateValue + " " + newValue;
            if (!value.match(/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}$/))
                value += ":00";
            this.$emit('input', value);
        },
        value: function (newValue, oldValue) {
            
            if (!newValue) {
                return;
            }
            
            if (!newValue.match(/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}$/)) {
                return;
            }

            let [date, time] = newValue.split(/\s/);

            this.$data.dateValue = date;
            this.$data.timeValue = time;
        }
    },
    data() {
        return {
            dateValue: null,
            timeValue: null,
        }
    }
}
</script>