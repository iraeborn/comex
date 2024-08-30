<template>
    <div>
        <table class="table table-sm table-amarelo">
            <tr>
                <th>Date of fumigation</th>
                <th>Date of conclusion</th>
            </tr>
            <tr>
                <td>{{ DateTimeFormat(fumigation.date_of_fumigation_certificate) }}</td>
                <td>{{ DateTimeFormat(fumigation.date_of_conclusion) }}</td>
            </tr>
        </table>
        <!-- <div class="row">
            <div class="col">
                <dateinput v-model="fumigation.date_of_fumigation_certificate" date_title="Date of fumigation" time_title="Time of fumigation certificate"></dateinput>
            </div>
            <div class="col">
                <dateinput v-model="fumigation.date_of_conclusion" date_title="Date of conclusion" time_title="Time of conclusion"></dateinput>
            </div>
        </div> -->
        
        <table class="table table-sm table-amarelo">
            <tr>
                <th>Treatment</th>
                <th>Exposition time</th>
                <th>Temperature local</th>
            </tr>
            <tr>
                <td>{{ fumigation.treatment || "Not informed" }}</td>
                <td>{{ fumigation.exposition_time || "Not informed" }}</td>
                <td>{{ fumigation.temperature_local || "Not informed" }}</td>
            </tr>
        </table>

        <div class="row">
            <div class="col-4">
                <p>Draft of Fumigation Certificate</p>
                <a v-if="fumigation.draft_fumigation_certificate" :href="fumigation.draft_fumigation_certificate" class="btn btn-primary btn-block">Download</a>
                <div class="alert alert-danger" v-if="!fumigation.draft_fumigation_certificate">Document not sent</div>
            </div>
            <div class="col-4">
                <p>Original Fumigation Certificate</p>
                <a v-if="fumigation.original_fumigation_certificate" :href="fumigation.original_fumigation_certificate" class="btn btn-primary btn-block">Download</a>
                <div class="alert alert-danger" v-if="!fumigation.original_fumigation_certificate">Document not sent</div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <dr-history :url="'/api/fumigationObservation/' + fumigation.id" v-if="fumigation.id"></dr-history>
            </div>
        </div>
    </div>
</template>

<script>
import dateinput from './Fields/DateInputDisplay'

export default {
    props: ['value'],
    name: 'popcorn-fumigation-view',
    components: {
      dateinput
    },
    watch: {
        value: function (newValue, oldValue) {
            this.$data.fumigation = newValue;
        }
    },
    mounted: async function () {
        let fumigation = await this.$http.get('/api/fumigation/' + this.$route.params.id);
        this.$data.fumigation = fumigation.body;
    },
    data() {
        return {
            fumigation: {},
        }
    },
    methods: {
        DateTimeFormat: function (date) {
            if(!date) return 'Date was not informed';
            let [year, month, day, hour, minutes, seconds] = date.split(/[- :]/gi);
            return day + "/" + month + "/" + year + " " + hour + ":" + minutes;
        }
    },
}
</script>