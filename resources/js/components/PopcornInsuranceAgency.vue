<template>
    <div>
        <div v-if="!insurance_agency">
            No data founded
        </div>
        <div v-if="insurance_agency">
            <div class="row">
                <div class="col booking">
                    <strong class="title">Insurance Agency</strong><br>
                    <p>{{ insurance_agency.name }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <label for="draft_comercial">DRAFT COMERCIAL INVOICE</label>
                    <!-- <p><a :href="insurance_agency.draft_documents.draft_comercial" class="btn btn-primary" download>Download</a></p> -->
                    <popcorn-upload-new v-model="insurance_agency.draft_documents.draft_comercial" name="draft_comercial" @input="ChangeDraft"></popcorn-upload-new>
                </div>
                <div class="col-6">
                    <label for="proforma">PROFORMA</label>
                    <!-- <p><a :href="insurance_agency.order.proforma" class="btn btn-primary" download>Download</a></p> -->
                    <popcorn-upload-new v-model="insurance_agency.order.proforma" name="proforma"></popcorn-upload-new>
                </div>
                <div class="col-6">
                    <label for="proforma">CERTIFICATE OF INSURANCE</label>
                    <p v-if="insurance_agency.draft_documents.certificate_seguro"><a :href="insurance_agency.draft_documents.certificate_seguro" class="btn btn-primary" download>Download</a></p>
                    <div v-if="!insurance_agency.draft_documents.certificate_seguro" class="alert alert-danger">Waiting for document</div>
                </div>
            </div>
            
            <div class="row">
                <div class="col">
                    <dr-history v-if="insurance_agency.id" :url="'/api/insuranceObservation/' + insurance_agency.id"></dr-history>
                </div>
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
    name: "popcorn-insurance-agency",
    data () {
        return {
            error: {},
            insurance_agency: null
        }
    },
    watch: {
        value: function (newValue, oldValue) {
            this.$data.insurance_agency = newValue;
        },
        insurance_agency: function (newValue, oldValue) {
            this.$emit('input', newValue);
        },
    },
    mounted: function () {
        this.$data.insurance_agency = this.$props.value;
        console.log("insuranceObservation: ", this.$props.value)
        //this.LoadInsuranceAgencyData();
    },
    methods: {
        ChangeDraft: function () {
            this.$emit('change', this.$data.insurance_agency.draft_documents.draft_comercial);
        },
        Save: function () {
            this.$http.post('/api/insurance/' + this.$route.params.id, this.$data.insurance_agency).then( e => {
                if (e.body.error) {
                    this.toaster.danger('There are problems in Insurance Agency');
                }
            })
        },
        Show: function (value) {
            return value;
        }
    }
}
</script>