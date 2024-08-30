<template>
  <div>
    <div v-if="loading">
      <p class="text-center">Loading draft data...</p>
    </div>
    <div v-if="!loading && draft">
      <div class="row">
        <div class="col">
          <p>Dear customer, we are sending conference documents</p>
        </div>
      </div>
              
        <div class="col-6" v-if="draft.draft_bl_access">
          <popcorn-draft-for-aprove :link="draft.draft_bl" v-model="draft.draft_bl_status" label="DRAFT BL" @statusChange="SetStatus">
            <textarea name="draft_bl" id="" cols="30" rows="10" class="form-control" v-model="draft.draft_bl_reason"></textarea>
          </popcorn-draft-for-aprove>

        </div>
        
        <div class="col-6" v-if="draft.draft_comercial_access">
          <popcorn-draft-for-aprove :link="draft.draft_comercial" v-model="draft.draft_comercial_status" label="DRAFT COMERCIAL INVOICE"  @statusChange="SetStatus">
            <textarea name="draft_bl" id="" cols="30" rows="10" class="form-control" v-model="draft.draft_comercial_reason"></textarea>
          </popcorn-draft-for-aprove>

        </div>
        
        <div class="col-6" v-if="draft.packing_list_access">
          <popcorn-draft-for-aprove :link="draft.packing_list" v-model="draft.packing_list_status" label="PACKING LIST"  @statusChange="SetStatus">
            <textarea name="draft_bl" id="" cols="30" rows="10" class="form-control" v-model="draft.packing_list_reason"></textarea>
          </popcorn-draft-for-aprove>

        </div>
        
        <div class="col-6" v-if="draft.certificate_origin_access">
          <popcorn-draft-for-aprove :link="draft.certificate_origin" v-model="draft.certificate_origin_status" label="CERTIFICATE OF ORIGIN"  @statusChange="SetStatus">
            <textarea name="draft_bl" id="" cols="30" rows="10" class="form-control" v-model="draft.certificate_origin_reason"></textarea>
          </popcorn-draft-for-aprove>

        </div>
        
        <div class="col-6" v-if="draft.certificate_fumigation_access">
          <popcorn-draft-for-aprove :link="draft.certificate_fumigation" v-model="draft.certificate_fumigation_status" label="FUMIGATION CERTIFICATE"  @statusChange="SetStatus">
            <textarea name="draft_bl" id="" cols="30" rows="10" class="form-control" v-model="draft.certificate_fumigation_reason"></textarea>
          </popcorn-draft-for-aprove>

        </div>
        
        <div class="col-6" v-if="draft.certificate_quality_access">
          <popcorn-draft-for-aprove :link="draft.certificate_quality" v-model="draft.certificate_quality_status" label="QUALITY CERTIFICATE"  @statusChange="SetStatus">
            <textarea name="draft_bl" id="" cols="30" rows="10" class="form-control" v-model="draft.certificate_quality_reason"></textarea>
          </popcorn-draft-for-aprove>

        </div>
        
        <div class="col-6" v-if="draft.certificate_weight_access">
          <popcorn-draft-for-aprove :link="draft.certificate_weight" v-model="draft.certificate_weight_status" label="WEIGHT CERTIFICATE"  @statusChange="SetStatus">
            <textarea name="draft_bl" id="" cols="30" rows="10" class="form-control" v-model="draft.certificate_weight_reason"></textarea>
          </popcorn-draft-for-aprove>

        </div>
        
        <div class="col-6" v-if="draft.certificate_seguro_access">
          <popcorn-draft-for-aprove :link="draft.certificate_seguro" v-model="draft.certificate_seguro_status" label="CERTIFICATE OF INSURANCE"  @statusChange="SetStatus">
            <textarea name="draft_bl" id="" cols="30" rows="10" class="form-control" v-model="draft.certificate_seguro_reason"></textarea>
          </popcorn-draft-for-aprove>

        </div>
        
        <div class="col-6" v-if="draft.certificate_phyto_access">
          <popcorn-draft-for-aprove :link="draft.certificate_phyto" v-model="draft.certificate_phyto_status" label="PHYTO CERTIFICATE"  @statusChange="SetStatus">
            <textarea name="draft_bl" id="" cols="30" rows="10" class="form-control" v-model="draft.certificate_phyto_reason"></textarea>
          </popcorn-draft-for-aprove>

        </div>
        
        <div class="col-6" v-if="draft.report_status_access">
          <popcorn-draft-for-aprove :link="draft.report" v-model="draft.report_status" label="STUFFING REPORT"  @statusChange="SetStatus">
            <textarea name="draft_bl" id="" cols="30" rows="10" class="form-control" v-model="draft.report_reason"></textarea>
          </popcorn-draft-for-aprove>


        </div>
        
        <div class="col-6" v-if="draft.health_certificate_access">
          <popcorn-draft-for-aprove :link="draft.health_certificate" v-model="draft.health_certificate_status" label="HEALTH CERTIFICATE"  @statusChange="SetStatus">
            <textarea name="draft_bl" id="" cols="30" rows="10" class="form-control" v-model="draft.health_certificate_reason"></textarea>
          </popcorn-draft-for-aprove>


        </div>
      </div>

      <div class="row">
        <div class="col">
          <label for="other-documents">Other documents</label>
        </div>
      </div>

      <div class="row" v-for="(other, index) in draft.others">
        <div class="col">

          <popcorn-draft-for-aprove :link="draft.others[index]" v-model="draft.others_status[index]" label=""  @statusChange="SetStatus">
            <textarea :name="'other_' + index" id="other-documents" cols="30" rows="10" class="form-control" v-model="draft.others_reason[index]"></textarea>
          </popcorn-draft-for-aprove>          
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'popcorn-draft-documents-view',
  data () {
    return {
      draft: null,
      loading: true,
    }
  },
  mounted: function () {
    this.$http.get('/api/draft/' + this.$route.params.id).then(e => {
      if(!e.body.others_reason) e.body.others_reason = [];
      this.$data.draft = e.body
      this.$data.loading = false
    })
  },
  methods: {
    SetStatus:  function () {
      this.$http.put('/api/draft/status', this.$data.draft).then ( e => {
        if (e.body.success) this.$toaster.success(e.body.success)
      })
    }
  }
}
</script>