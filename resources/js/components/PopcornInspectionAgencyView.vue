<template>
    <div>
        <div class="row">
            <div class="col-6">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <strong for="inspection_start_date">Inspection Start Date</strong>
                            {{ inspection_agency.inspection_start_date }}
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <strong for="inspection_start_time">Inspection Start Time</strong>
                            {{ inspection_agency.inspection_start_time }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <strong for="inspection_start_date">Inspection End Date</strong>
                            {{ inspection_agency.inspection_end_date }}
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <strong for="inspection_end_time">Inspection End Time</strong>
                            {{ inspection_agency.inspection_end_time }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label for="">Weight Certificate (Draft)</label>
            </div>
            <div class="col">
                <label for="">Quality Certificate (Draft)</label>
            </div>
            <div class="col">
                <label for="">Report (Draft)</label>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <a v-if="inspection_agency.weight_certificate_draft" :href="inspection_agency.weight_certificate_draft" class="btn btn-primary btn-block" download>Download</a>
                <p v-if="!inspection_agency.weight_certificate_draft" class="alert alert-danger">File not send to {{ this.$root.appName }}</p>
            </div>
            <div class="col">
                <a v-if="inspection_agency.quality_certificate_draft" :href="inspection_agency.quality_certificate_draft" class="btn btn-primary btn-block" download>Download</a>
                <p v-if="!inspection_agency.quality_certificate_draft" class="alert alert-danger">File not send to {{ this.$root.appName }}</p>
            </div>
            <div class="col">
                <a v-if="inspection_agency.report_draft" :href="inspection_agency.report_draft" class="btn btn-primary btn-block" download>Download</a>
                <p v-if="!inspection_agency.report_draft" class="alert alert-danger">File not send to {{ this.$root.appName }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label for="">Weight Certificate (Original)</label>
            </div>
            <div class="col">
                <label for="">Quality Certificate (Original)</label>
            </div>
            <div class="col">
                <label for="">Report (Original)</label>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <a v-if="inspection_agency.weight_certificate_original" :href="inspection_agency.weight_certificate_original" class="btn btn-primary btn-block" download>Download</a>
                <p v-if="!inspection_agency.weight_certificate_original" class="alert alert-danger">File not send to {{ this.$root.appName }}</p>
            </div>
            <div class="col">
                <a v-if="inspection_agency.quality_certificate_original" :href="inspection_agency.quality_certificate_original" class="btn btn-primary btn-block" download>Download</a>
                <p v-if="!inspection_agency.quality_certificate_original" class="alert alert-danger">File not send to {{ this.$root.appName }}</p>
            </div>
            <div class="col">
                <a v-if="inspection_agency.report_original" :href="inspection_agency.report_original" class="btn btn-primary btn-block" download>Download</a>
                <p v-if="!inspection_agency.report_original" class="alert alert-danger">File not send to {{ this.$root.appName }}</p>
            </div>
        </div>

        <div class="row" v-if="inspection_agency.id">
            <div class="col">
                <dr-history :url="'/api/inspectionObservation/' + inspection_agency.id"></dr-history>
            </div>
        </div>
    </div>
</template>

<style scoped></style>

<script>
export default {
    props: ['value'],
    name: "popcorn-inspection-agency-view",
    data () {
        return {
            error: {},
            inspection_agency: {
                inspection_start_date: null,
                inspection_start_time: null,
                inspection_start_datetime: null,
            }
        }
    },
    watch: {
        value: function ( newValue, oldValue ) {
            this.$data.inspection_agency = newValue
        },
        inspection_agency: {
            handler (val) {
                if (val.inspection_start_date && val.inspection_start_time)
                    this.$data.inspection_agency.inspection_start_datetime = val.inspection_start_date + " " + val.inspection_start_time

                if (!this.$data.inspection_agency.inspection_start_date && val.inspection_start_datetime)
                    this.$data.inspection_agency.inspection_start_date = val.inspection_start_datetime.substr(0, 10)

                if (!this.$data.inspection_agency.inspection_start_time && val.inspection_start_datetime)
                    this.$data.inspection_agency.inspection_start_time = val.inspection_start_datetime.substr(11)


                if (val.inspection_end_date && val.inspection_end_time)
                    this.$data.inspection_agency.inspection_end_datetime = val.inspection_end_date + " " + val.inspection_end_time

                if (!this.$data.inspection_agency.inspection_end_date && val.inspection_end_datetime)
                    this.$data.inspection_agency.inspection_end_date = val.inspection_end_datetime.substr(0, 10)
                
                if (!this.$data.inspection_agency.inspection_end_time && val.inspection_end_datetime)
                    this.$data.inspection_agency.inspection_end_time = val.inspection_end_datetime.substr(11)

            },
            deep: true,
        },
    },
    methods: {}
}
</script>