<template>
  <div>
    <div class="row">
      <div class="col-6">
        <div class="form-group">
          <label for="draft_bl">BL
            <div class="badge badge-primary"
              v-if="original_document.draft_bl_status == 1 && !!original_document.draft_bl">Waiting for approval</div>
            <div class="badge badge-success" v-if="original_document.draft_bl_status == 2">Accepted</div>
            <div class="badge badge-danger" v-if="original_document.draft_bl_status == 3">Rejected</div>
            <div class="alert alert-danger" v-if="original_document.draft_bl_status == 3">Reason:
              {{ original_document.draft_bl_reason }}</div>
          </label>
          <popcorn-upload sufix="od" name="od_draft_bl" :allowed_pattern="/^application\/pdf$/"
            v-model="original_document.draft_bl" :file="original_document.draft_bl" @upload="RegisterDraftBl"
            :class="error.draft_bl != '' && error.draft_bl ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.draft_bl" v-for="message in error.draft_bl">{{ message }}</p>
        </div>

      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="draft_comercial">COMERCIAL INVOICE
            <div class="badge badge-primary"
              v-if="original_document.draft_comercial_status == 1 && !!original_document.draft_comercial">Waiting for
              approval</div>
            <div class="badge badge-success" v-if="original_document.draft_comercial_status == 2">Accepted</div>
            <div class="badge badge-danger" v-if="original_document.draft_comercial_status == 3">Rejected</div>
            <div class="alert alert-danger" v-if="original_document.draft_comercial_status == 3">Reason:
              {{ original_document.draft_comercial_reason }}</div>
          </label>
          <popcorn-upload sufix="od" name="od_draft_comercial" :allowed_pattern="/^application\/pdf$/"
            v-model="original_document.draft_comercial" :file="original_document.draft_comercial"
            @upload="RegisterDraftComercial"
            class="error.draft_comercial != '' && error.draft_comercial ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.draft_comercial" v-for="message in error.draft_comercial">{{ message }}
          </p>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="packing_list">PACKING LIST</label>
          <div class="badge badge-primary"
            v-if="original_document.packing_list_status == 1 && !!original_document.packing_list">Waiting for approval
          </div>
          <div class="badge badge-success" v-if="original_document.packing_list_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="original_document.packing_list_status == 3">Rejected</div>
          <div class="alert alert-danger" v-if="original_document.packing_list_status == 3">Reason:
            {{ original_document.packing_list_reason }}</div>
          <popcorn-upload sufix="od" name="od_packing_list" :allowed_pattern="/^application\/pdf$/"
            v-model="original_document.packing_list" :file="original_document.packing_list" @upload="RegisterPackingList"
            class="error.packing_list != '' && error.packing_list ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.packing_list" v-for="message in error.packing_list">{{ message }}</p>
        </div>

      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="certificate_origin">CERTIFICATE OF ORIGIN</label>
          <div class="badge badge-primary"
            v-if="original_document.certificate_origin_status == 1 && !!original_document.certificate_origin">Waiting for
            approval</div>
          <div class="badge badge-success" v-if="original_document.certificate_origin_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="original_document.certificate_origin_status == 3">Rejected</div>
          <div class="alert alert-danger" v-if="original_document.certificate_origin_status == 3">Reason:
            {{ original_document.certificate_origin_reason }}</div>
          <popcorn-upload sufix="od" name="od_certificate_origin" :allowed_pattern="/^application\/pdf$/"
            v-model="original_document.certificate_origin" :file="original_document.certificate_origin"
            @upload="RegisterCertificateOrigin"
            class="error.certificate_origin != '' && error.certificate_origin ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.certificate_origin" v-for="message in error.certificate_origin">
            {{ message }}</p>
        </div>

      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="certificate_fumigation">FUMIGATION CERTIFICATE</label>
          <div class="badge badge-primary"
            v-if="original_document.certificate_fumigation_status == 1 && !!original_document.certificate_fumigation">
            Waiting for approval</div>
          <div class="badge badge-success" v-if="original_document.certificate_fumigation_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="original_document.certificate_fumigation_status == 3">Rejected</div>
          <div class="alert alert-danger" v-if="original_document.certificate_fumigation_status == 3">Reason:
            {{ original_document.certificate_fumigation_reason }}</div>
          <popcorn-upload sufix="od" name="od_certificate_fumigation" :allowed_pattern="/^application\/pdf$/"
            v-model="original_document.certificate_fumigation" :file="original_document.certificate_fumigation"
            @upload="RegisterCertificateFumigation"
            class="error.certificate_fumigation != '' && error.certificate_fumigation ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.certificate_fumigation" v-for="message in error.certificate_fumigation">
            {{ message }}</p>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="certificate_quality">QUALITY CERTIFICATE</label>
          <div class="badge badge-primary"
            v-if="original_document.certificate_quality_status == 1 && !!original_document.certificate_quality">Waiting
            for approval</div>
          <div class="badge badge-success" v-if="original_document.certificate_quality_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="original_document.certificate_quality_status == 3">Rejected</div>
          <div class="alert alert-danger" v-if="original_document.certificate_quality_status == 3">Reason:
            {{ original_document.certificate_quality_reason }}</div>
          <popcorn-upload sufix="od" name="od_certificate_quality" :allowed_pattern="/^application\/pdf$/"
            v-model="original_document.certificate_quality" :file="original_document.certificate_quality"
            @upload="RegisterCertificateQuality"
            class="error.certificate_quality != '' && error.certificate_quality ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.certificate_quality" v-for="message in error.certificate_quality">
            {{ message }}</p>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="certificate_weight">WEIGHT CERTIFICATE</label>
          <div class="badge badge-primary"
            v-if="original_document.certificate_weight_status == 1 && !!original_document.certificate_weight">Waiting for
            approval</div>
          <div class="badge badge-success" v-if="original_document.certificate_weight_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="original_document.certificate_weight_status == 3">Rejected</div>
          <div class="alert alert-danger" v-if="original_document.certificate_weight_status == 3">Reason:
            {{ original_document.certificate_weight_reason }}</div>
          <popcorn-upload sufix="od" name="od_certificate_weight" :allowed_pattern="/^application\/pdf$/"
            v-model="original_document.certificate_weight" :file="original_document.certificate_weight"
            @upload="RegisterCertificateWeight"
            class="error.certificate_weight != '' && error.certificate_weight ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.certificate_weight" v-for="message in error.certificate_weight">
            {{ message }}</p>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="certificate_seguro">CERTIFICATE OF INSURANCE</label>
          <div class="badge badge-primary"
            v-if="original_document.certificate_seguro_status == 1 && !!original_document.certificate_seguro">Waiting for
            approval</div>
          <div class="badge badge-success" v-if="original_document.certificate_seguro_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="original_document.certificate_seguro_status == 3">Rejected</div>
          <div class="alert alert-danger" v-if="original_document.certificate_seguro_status == 3">Reason:
            {{ original_document.certificate_seguro_reason }}</div>
          <popcorn-upload sufix="od" name="od_certificate_seguro" :allowed_pattern="/^application\/pdf$/"
            v-model="original_document.certificate_seguro" :file="original_document.certificate_seguro"
            @upload="RegisterCertificateSeguro"
            class="error.certificate_seguro != '' && error.certificate_seguro ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.certificate_seguro" v-for="message in error.certificate_seguro">
            {{ message }}</p>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="certificate_phyto">PHYTO CERTIFICATE</label>
          <div class="badge badge-primary"
            v-if="original_document.certificate_phyto_status == 1 && !!original_document.certificate_phyto">Waiting for
            approval</div>
          <div class="badge badge-success" v-if="original_document.certificate_phyto_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="original_document.certificate_phyto_status == 3">Rejected</div>
          <div class="alert alert-danger" v-if="original_document.certificate_phyto_status == 3">Reason:
            {{ original_document.certificate_phyto_reason }}</div>
          <popcorn-upload sufix="od" name="od_certificate_phyto" :allowed_pattern="/^application\/pdf$/"
            v-model="original_document.certificate_phyto" :file="original_document.certificate_phyto"
            @upload="RegisterCertificatePhyto"
            class="error.certificate_phyto != '' && error.certificate_phyto ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.certificate_phyto" v-for="message in error.certificate_phyto">
            {{ message }}</p>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="report">STUFFING REPORT</label>
          <div class="badge badge-primary" v-if="original_document.report_status == 1 && !!original_document.report">
            Waiting for approval</div>
          <div class="badge badge-success" v-if="original_document.report_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="original_document.report_status == 3">Rejected</div>
          <div class="alert alert-danger" v-if="original_document.report_status == 3">Reason:
            {{ original_document.report_reason }}</div>
          <popcorn-upload sufix="od" name="od_report" :allowed_pattern="/^application\/pdf$/"
            v-model="original_document.report" :file="original_document.report" @upload="RegisterReport"
            class="error.report != '' && error.report ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.report" v-for="message in error.report">{{ message }}</p>
        </div>

      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="report">HEALTH CERTIFICATE</label>
          <div class="badge badge-primary"
            v-if="original_document.health_certificate_status == 1 && !!original_document.health_certificate">Waiting for
            approval</div>
          <div class="badge badge-success" v-if="original_document.health_certificate_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="original_document.health_certificate_status == 3">Rejected</div>
          <div class="alert alert-danger" v-if="original_document.health_certificate_status == 3">Reason:
            {{ original_document.health_certificate_reason }}</div>
          <popcorn-upload sufix="od" name="od_health_certificate" :allowed_pattern="/^application\/pdf$/"
            v-model="original_document.health_certificate" :file="original_document.health_certificate"
            @upload="RegisterHealthCertificate"
            class="error.health_certificate != '' && error.health_certificate ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.health_certificate" v-for="message in error.health_certificate">
            {{ message }}</p>
        </div>

      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="report">NON-GMO CERTIFICATE</label>
          <div class="badge badge-primary"
            v-if="original_document.non_gmo_certificate_status == 1 && !!original_document.non_gmo_certificate">Waiting
            for approval</div>
          <div class="badge badge-success" v-if="original_document.non_gmo_certificate_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="original_document.non_gmo_certificate_status == 3">Rejected</div>
          <div class="alert alert-danger" v-if="original_document.non_gmo_certificate_status == 3">Reason:
            {{ original_document.non_gmo_certificate_reason }}</div>
          <popcorn-upload sufix="od" name="od_non_gmo_certificate" :allowed_pattern="/^application\/pdf$/"
            v-model="original_document.non_gmo_certificate" :file="original_document.non_gmo_certificate"
            @upload="RegisterNonGmoCertificate"
            class="error.non_gmo_certificate != '' && error.non_gmo_certificate ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.non_gmo_certificate" v-for="message in error.non_gmo_certificate">
            {{ message }}</p>
        </div>

      </div>
    </div>

    <div class="row">
      <div class="col">
        <label for="other">OTHER DOCUMENTS</label>
        <button class="btn btn-success btn-sm float-right" @click="original_document.others.push(null)">Add another
          document</button>
      </div>
    </div>
    <div class="row" v-for="(other, index) in original_document.others">
      <div class="col-6">
        <div class="form-group">
          <popcorn-upload-new sufix="od" :name="'od_other_' + index" :allowed_pattern="/^application\/pdf$/"
            v-model="original_document.others[index]"
            class="error.other != '' && error.other ? 'is-invalid' : ''"></popcorn-upload-new>
          <p class="invalid-feedback" v-if="error.other" v-for="message in error.other">{{ message }}</p>
        </div>

      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: 'popcorn-original-documents',
  props: ["sending_emails"],
  data() {
    return {
      loading: true,
      original_document: {},
      error: {}
    }
  },
  mounted: function () {
    this.$http.get('/api/original-documents/' + this.$route.params.id).then(e => {
      if (!e.body.others) e.body.others = [];
      this.original_document = e.body
    })
  },
  methods: {
    formatDate: function (val) {
      return val
    },
    RegisterDraftBl: function (val) {
      this.$data.original_document.draft_bl = val
      this.$data.original_document.draft_bl_status = 1
    },
    RegisterDraftComercial: function (val) {
      this.$data.original_document.draft_comercial = val
      this.$data.original_document.draft_comercial_status = 1
    },
    RegisterPackingList: function (val) {
      this.$data.original_document.packing_list = val
      this.$data.original_document.packing_list_status = 1
    },
    RegisterCertificateOrigin: function (val) {
      this.$data.original_document.certificate_origin = val
      this.$data.original_document.certificate_origin_status = 1
    },
    RegisterCertificateFumigation: function (val) {
      this.$data.original_document.certificate_fumigation = val
      this.$data.original_document.certificate_fumigation_status = 1
    },
    RegisterCertificateQuality: function (val) {
      this.$data.original_document.certificate_quality = val
      this.$data.original_document.certificate_quality_status = 1
    },
    RegisterCertificateWeight: function (val) {
      this.$data.original_document.certificate_weight = val
      this.$data.original_document.certificate_weight_status = 1
    },
    RegisterCertificateSeguro: function (val) {
      this.$data.original_document.certificate_seguro = val
      this.$data.original_document.certificate_seguro_status = 1
    },
    RegisterHealthCertificate: function (val) {
      this.$data.original_document.health_certificate = val
      this.$data.original_document.health_certificate_status = 1
    },
    RegisterNonGmoCertificate: function (val) {
      this.$data.original_document.non_gmo_certificate = val
      this.$data.original_document.non_gmo_certificate_status = 1
    },
    RegisterCertificatePhyto: function (val, sendback) {
      this.$data.original_document.certificate_phyto = val
      this.$data.original_document.certificate_phyto_status = 1

      if (!sendback) {
        if (typeof (val) == 'object' && val !== null) {
          this.$emit('phyto', val.url);
        } else {
          this.$emit('phyto', val);
        }
      }
    },
    RegisterReport: function (val) {
      this.$data.original_document.report = val
      this.$data.original_document.report_status = 1
    },
    RegisterOther: function (val) {
      this.$data.original_document.others[index] = val.url
    },
    Save: function () {
      this.$data.original_document.sending_emails = this.$props.sending_emails;
      // this.$http.put('/api/original-documents/' + this.$route.params.id, this.$data.original_document).then( e => {
      //   if (e.body.success) {
      //     //this.$toaster.success(e.body.success)
      //   }
      // })

    }
  }
}
</script>