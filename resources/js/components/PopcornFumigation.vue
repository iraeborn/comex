<template>
    <div>
        <div class="row">
            <div class="col booking">
                <strong class="title">Fumigation Agency</strong>
                <br>
                <p>{{ fumigation.name }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label for="Treatment">Treatment</label>
                <input-icon :id="'Treatment'" v-model="fumigation.treatment" icon="fas fa-pills" />
            </div>
            <div class="col">
                <label for="Dosage">Dosage</label>
                <input-icon :id="'Dosage'" v-model="fumigation.dosage" icon="fas fa-pills" />
            </div>
            <div class="col">
                <label for="exposition_time">Exposition time</label>
                <!-- <input-icon v-model="fumigation.exposition_time" icon="fas fa-clock" /> -->
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-clock"></i></div>
                    </div>
                    <select class="form-control" id="exposition_time" v-model="fumigation.exposition_time">
                        <option value="24 hours">24 hours</option>
                        <option value="96 hours">96 hours</option>
                        <option value="120 hours">120 hours</option>
                    </select>
                </div>
            </div>
            <div class="col">
                <label for="">Temperature local</label>
                <input-icon v-model="fumigation.temperature_local" icon="fas fa-thermometer-half" />
            </div>
        </div>
        <div class="row">
            <div class="col">
                <dateinput v-model="fumigation.date_of_fumigation_certificate" date_title="Date of fumigation" time_title="Time of fumigation"></dateinput>
            </div>
            <div class="col">
                <dateinput v-model="fumigation.date_of_conclusion" date_title="Date of conclusion" time_title="Time of conclusion"></dateinput>
            </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group" id="place_of_treatment">
              <label for="place_of_treatment">Place of Treatment:</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">
                    <i class="fas fa-house"></i>
                  </div>
                </div>
                <textarea class="form-control" name="place_of_treatment" id="place_of_treatment" placeholder="Place of Treatment" v-model="fumigation.place_of_treatment"></textarea>
              </div>
            </div>
          </div>
        </div>
        
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


<style scoped>
.booking strong, .booking p {
    width: 160px;
}

.booking {
    margin-bottom: 7px;
}
</style>

<script>
import dateinput from './Fields/DateInput'

export default {
    props: ['value'],
    name: 'popcorn-fumigation',
    components: {
      dateinput
    },
    watch: {
        value: function (newValue, oldValue) {
            this.$data.fumigation = newValue;
        }
    },
    mounted() {
        this.$data.fumigation = this.$props.value;
    },
    data() {
        return {
            fumigation: {
                "id":null,
                "created_at":null,
                "updated_at":null,
                "deleted_at":null,
                "order_id":null,
                "treatment_id":null,
                "date_of_fumigation_certificate":null,
                "date_of_conclusion":null,
                "treatment":null,
                "exposition_time":null,
                "temperature_local":null,
                "name":null,
                "draft_fumigation_certificate":null,
            },
        }
    },
    methods: {
        Save: function () {
            this.$http.put('/api/fumigation/' + this.$route.params.id, this.$data.fumigation).then( e => {
                if (e.body.error) {
                    this.$toaster.danger(e.body.error);
                }
            });
        }
    }
}
</script>