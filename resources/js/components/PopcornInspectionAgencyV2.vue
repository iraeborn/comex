<template>
    <div>
        <div class="row">
            <div class="col-6">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="inspection_start_date">Inspection Start Date</label>
                            <input-icon icon="fas fa-calendar" type="date" v-model="inspection_agency.inspection_start_date" id="inspection_start_date" name="inspection_start_date" :class="error.inspection_start_date != '' && error.inspection_start_date ? 'is-invalid' : ''" />
                            <div class="invalid-feedback" v-if="error.inspection_start_date" v-for="message in error.inspection_start_date">{{ message }}</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="inspection_start_time">Inspection Start Time</label>
                            <input-icon icon="fas fa-clock" type="time" v-model="inspection_agency.inspection_start_time" id="inspection_start_time" name="inspection_start_time" :class="error.inspection_start_time != '' && error.inspection_start_time ? 'is-invalid' : ''" />
                            <div class="invalid-feedback" v-if="error.inspection_start_time" v-for="message in error.inspection_start_time">{{ message }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="inspection_start_date">Inspection End Date</label>
                            <input-icon icon="fas fa-calendar" type="date" v-model="inspection_agency.inspection_end_date" id="inspection_end_date" name="inspection_end_date" :class="error.inspection_end_date != '' && error.inspection_end_date ? 'is-invalid' : ''" />
                            <div class="invalid-feedback" v-if="error.inspection_end_date" v-for="message in error.inspection_end_date">{{ message }}</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="inspection_end_time">Inspection End Time</label>
                            <input-icon icon="fas fa-clock" type="time" v-model="inspection_agency.inspection_end_time" id="inspection_end_time" name="inspection_end_time" :class="error.inspection_end_time != '' && error.inspection_end_time ? 'is-invalid' : ''" />
                            <div class="invalid-feedback" v-if="error.inspection_end_time" v-for="message in error.inspection_end_time">{{ message }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-4">
                <label for="">Weight Certificate (Draft)</label><br>
                <popcorn-upload-new name="weight_certificate_draft" :allowed_pattern="/^application\/pdf$/" v-model="inspection_agency.weight_certificate_draft"></popcorn-upload-new>
            </div>
            <div class="col-4">
                <label for="">Quality Certificate (Draft)</label>
                <popcorn-upload-new name="quality_certificate_draft" :allowed_pattern="/^application\/pdf$/" v-model="inspection_agency.quality_certificate_draft"></popcorn-upload-new>
            </div>
            <div class="col-4">
                <label for="">Report (Draft)</label>
                <popcorn-upload-new name="report_draft" :allowed_pattern="/^application\/pdf$/" v-model="inspection_agency.report_draft"></popcorn-upload-new>
            </div>
            <div class="col-4">
                <label for="">Health Certificate (Draft)</label>
                <popcorn-upload-new name="health_certificate_draft" :allowed_pattern="/^application\/pdf$/" v-model="inspection_agency.health_certificate_draft"></popcorn-upload-new>
            </div>
            <div class="col-4">
                <label for="">Non-GMO Certificate (Draft)</label>
                <popcorn-upload-new name="non_gmo_certificate_draft" :allowed_pattern="/^application\/pdf$/" v-model="inspection_agency.non_gmo_certificate_draft"></popcorn-upload-new>
            </div>
            <div class="col-4"></div>
            <div class="col-4">
                <label for="">Weight Certificate (Original)</label>
                <popcorn-upload-new name="weight_certificate_original" :allowed_pattern="/^application\/pdf$/" v-model="inspection_agency.weight_certificate_original"></popcorn-upload-new>
            </div>
            <div class="col-4">
                <label for="">Quality Certificate (Original)</label>
                <popcorn-upload-new name="quality_certificate_original" :allowed_pattern="/^application\/pdf$/" v-model="inspection_agency.quality_certificate_original"></popcorn-upload-new>
            </div>
            <div class="col-4">
                <label for="">Report (Original)</label>
                <popcorn-upload-new name="report_original" :allowed_pattern="/^application\/pdf$/" v-model="inspection_agency.report_original"></popcorn-upload-new>
            </div>
            <div class="col-4">
                <label for="">Health Certificate (Original)</label>
                <popcorn-upload-new name="health_certificate_original" :allowed_pattern="/^application\/pdf$/" v-model="inspection_agency.health_certificate_original"></popcorn-upload-new>
            </div>
            <div class="col-4">
                <label for="">Non-GMO Certificate (Original)</label>
                <popcorn-upload-new name="non_gmo_certificate_original" :allowed_pattern="/^application\/pdf$/" v-model="inspection_agency.non_gmo_certificate_original"></popcorn-upload-new>
            </div>
            <div class="col-4"></div>
        </div>

        <div class="row" v-if="inspection_agency.id">
            <div class="col">
                <dr-history :url="'/api/inspectionObservation/' + inspection_agency.id"></dr-history>
            </div>
        </div>
    </div>
</template>

<style scoped>
.booking strong, .booking p {
    width: 248px;
}

.booking {
    margin-bottom: 7px;
}

</style>

<script>
export default {
    props: ['value'],
    name: "popcorn-inspection-agency",
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
    methods: {
        Save: function () {
            this.$http.post('/api/inspection/' + this.$route.params.id, this.$data.inspection_agency).then( e => {
                if (e.body.error)
                    this.toaster.danger('There are problems in Inspection Agency')
            })
        }
    }
}
</script>