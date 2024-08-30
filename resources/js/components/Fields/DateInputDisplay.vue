<template>
    <div>
        <strong>{{ date_title }}</strong>
        <p>{{FormatDate(value)}}</p>
    </div>
</template>

<script>
export default {
    props: ['value', 'date_title', 'time_title'],
    name: 'dateinput',
    data() {
        return {}
    },
    methods: {
        FormatDate: function (value) {
            if(!value) return '';
            let [year, month, day, time] = value.split(/[- ]+/);

            return month + "/" + day + "/" + year + " " + time;
        }
    }
}
</script>