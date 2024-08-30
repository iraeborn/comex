<template>
  <div>
    <div class="row">
      <div class="col-6">
        <div class="form-group">
          <label for="draft_bl">DRAFT BL
            <div class="badge badge-primary" v-if="draft.draft_bl_status == 1 && !!draft.draft_bl">Waiting for approval</div>
            <div class="badge badge-success" v-if="draft.draft_bl_status == 2">Accepted</div>
            <div class="badge badge-danger" v-if="draft.draft_bl_status == 3">Rejected</div>
          </label>
          <popcorn-upload name="draft_bl" :allowed_pattern="/^application\/pdf$/" v-model="draft.draft_bl" :file="draft.draft_bl" @upload="RegisterDraftBl" :class="error.draft_bl != '' && error.draft_bl ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.draft_bl" v-for="message in error.draft_bl">{{message}}</p>
          <div class="alert alert-danger" v-if="draft.draft_bl_status == 3"><strong>Reason:</strong> {{draft.draft_bl_reason}}</div>
        </div>

      </div>

      <div class="col-6">
        <label class="float-right">Required&emsp;<input type="checkbox" v-model="draft.required.draft_bl" value="true"></label>
        <div class="form-group">
          <p>ALLOW ACCESS</p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.draft_bl" value="2">Importer</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.draft_bl" value="3">Fumigation Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.draft_bl" value="6">Insurance Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.draft_bl" value="7">Inspection Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.draft_bl" value="8">Forwarding Agent</label></p>
        </div>
      </div>

    </div>
    <div class="row separador">

      <div class="col-6">
        <div class="form-group">
          <label for="draft_comercial">DRAFT COMERCIAL INVOICE
            <div class="badge badge-primary" v-if="draft.draft_comercial_status == 1 && !!draft.draft_comercial">Waiting for approval</div>
            <div class="badge badge-success" v-if="draft.draft_comercial_status == 2">Accepted</div>
            <div class="badge badge-danger" v-if="draft.draft_comercial_status == 3">Rejected</div>
          </label>
          <popcorn-upload name="draft_comercial" :allowed_pattern="/^application\/pdf$/" v-model="draft.draft_comercial" :file="draft.draft_comercial" @upload="RegisterDraftComercial" class="error.draft_comercial != '' && error.draft_comercial ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.draft_comercial" v-for="message in error.draft_comercial">{{message}}</p>
          <div class="alert alert-danger" v-if="draft.draft_comercial_status == 3"><strong>Reason:</strong> {{draft.draft_comercial_reason}}</div>
        </div>
      </div>

      <div class="col-6">
        <label class="float-right">Required&emsp;<input type="checkbox" v-model="draft.required.draft_comercial" value="true"></label>
        <div class="form-group">
          <p>ALLOW ACCESS</p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.draft_comercial" value="2">Importer</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.draft_comercial" value="3">Fumigation Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.draft_comercial" value="6">Insurance Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.draft_comercial" value="7">Inspection Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.draft_comercial" value="8">Forwarding Agent</label></p>
        </div>
      </div>

    </div>
    <div class="row separador">

      <div class="col-6">
        <div class="form-group">
          <label for="packing_list">PACKING LIST</label>
          <div class="badge badge-primary" v-if="draft.packing_list_status == 1 && !!draft.packing_list">Waiting for approval</div>
          <div class="badge badge-success" v-if="draft.packing_list_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="draft.packing_list_status == 3">Rejected</div>
          <popcorn-upload name="packing_list" :allowed_pattern="/^application\/pdf$/" v-model="draft.packing_list" :file="draft.packing_list" @upload="RegisterPackingList" class="error.packing_list != '' && error.packing_list ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.packing_list" v-for="message in error.packing_list">{{message}}</p>
          <div class="alert alert-danger" v-if="draft.packing_list_status == 3"><strong>Reason:</strong> {{draft.packing_list_reason}}</div>
        </div>

      </div>

      <div class="col-6">
        <label class="float-right">Required&emsp;<input type="checkbox" v-model="draft.required.packing_list" value="true"></label>
        <div class="form-group">
          <p>ALLOW ACCESS</p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.packing_list" value="2">Importer</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.packing_list" value="3">Fumigation Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.packing_list" value="6">Insurance Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.packing_list" value="7">Inspection Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.packing_list" value="8">Forwarding Agent</label></p>
        </div>
      </div>

    </div>
    <div class="row separador">

      <div class="col-6">
        <div class="form-group">
          <label for="certificate_origin">CERTIFICATE OF ORIGIN</label>
          <div class="badge badge-primary" v-if="draft.certificate_origin_status == 1 && !!draft.certificate_origin">Waiting for approval</div>
          <div class="badge badge-success" v-if="draft.certificate_origin_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="draft.certificate_origin_status == 3">Rejected</div>
          <popcorn-upload name="certificate_origin" :allowed_pattern="/^application\/pdf$/" v-model="draft.certificate_origin" :file="draft.certificate_origin" @upload="RegisterCertificateOrigin" class="error.certificate_origin != '' && error.certificate_origin ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.certificate_origin" v-for="message in error.certificate_origin">{{message}}</p>
          <div class="alert alert-danger" v-if="draft.certificate_origin_status == 3"><strong>Reason:</strong> {{draft.certificate_origin_reason}}</div>
        </div>

      </div>

      <div class="col-6">
        <label class="float-right">Required&emsp;<input type="checkbox" v-model="draft.required.certificate_origin" value="true"></label>
        <div class="form-group">
          <p>ALLOW ACCESS</p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_origin" value="2">Importer</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_origin" value="3">Fumigation Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_origin" value="6">Insurance Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_origin" value="7">Inspection Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_origin" value="8">Forwarding Agent</label></p>
        </div>
      </div>

    </div>
    <div class="row separador">

      <div class="col-6">
        <div class="form-group">
          <label for="certificate_fumigation">FUMIGATION CERTIFICATE</label>
          <div class="badge badge-primary" v-if="draft.certificate_fumigation_status == 1 && !!draft.certificate_fumigation">Waiting for approval</div>
          <div class="badge badge-success" v-if="draft.certificate_fumigation_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="draft.certificate_fumigation_status == 3">Rejected</div>
          <popcorn-upload name="certificate_fumigation" :allowed_pattern="/^application\/pdf$/" v-model="draft.certificate_fumigation" :file="draft.certificate_fumigation" @upload="RegisterCertificateFumigation" class="error.certificate_fumigation != '' && error.certificate_fumigation ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.certificate_fumigation" v-for="message in error.certificate_fumigation">{{message}}</p>
          <div class="alert alert-danger" v-if="draft.certificate_fumigation_status == 3"><strong>Reason:</strong> {{draft.certificate_fumigation_reason}}</div>
        </div>
      </div>

      <div class="col-6">
        <label class="float-right">Required&emsp;<input type="checkbox" v-model="draft.required.certificate_fumigation" value="true"></label>
        <div class="form-group">
          <p>ALLOW ACCESS</p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_fumigation" value="2">Importer</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_fumigation" value="3">Fumigation Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_fumigation" value="6">Insurance Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_fumigation" value="7">Inspection Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_fumigation" value="8">Forwarding Agent</label></p>
        </div>
      </div>

    </div>
    <div class="row separador">

      <div class="col-6">
        <div class="form-group">
          <label for="certificate_quality">QUALITY CERTIFICATE</label>
          <div class="badge badge-primary" v-if="draft.certificate_quality_status == 1 && !!draft.certificate_quality">Waiting for approval</div>
          <div class="badge badge-success" v-if="draft.certificate_quality_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="draft.certificate_quality_status == 3">Rejected</div>
          <popcorn-upload name="certificate_quality" :allowed_pattern="/^application\/pdf$/" v-model="draft.certificate_quality" :file="draft.certificate_quality" @upload="RegisterCertificateQuality" class="error.certificate_quality != '' && error.certificate_quality ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.certificate_quality" v-for="message in error.certificate_quality">{{message}}</p>
          <div class="alert alert-danger" v-if="draft.certificate_quality_status == 3"><strong>Reason:</strong> {{draft.certificate_quality_reason}}</div>
        </div>
      </div>

      <div class="col-6">
        <label class="float-right">Required&emsp;<input type="checkbox" v-model="draft.required.certificate_quality" value="true"></label>
        <div class="form-group">
          <p>ALLOW ACCESS</p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_quality" value="2">Importer</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_quality" value="3">Fumigation Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_quality" value="6">Insurance Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_quality" value="7">Inspection Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_quality" value="8">Forwarding Agent</label></p>
        </div>
      </div>

    </div>
    <div class="row separador">

      <div class="col-6">
        <div class="form-group">
          <label for="certificate_weight">WEIGHT CERTIFICATE</label>
          <div class="badge badge-primary" v-if="draft.certificate_weight_status == 1 && !!draft.certificate_weight">Waiting for approval</div>
          <div class="badge badge-success" v-if="draft.certificate_weight_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="draft.certificate_weight_status == 3">Rejected</div>
          <popcorn-upload name="certificate_weight" :allowed_pattern="/^application\/pdf$/" v-model="draft.certificate_weight" :file="draft.certificate_weight" @upload="RegisterCertificateWeight" class="error.certificate_weight != '' && error.certificate_weight ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.certificate_weight" v-for="message in error.certificate_weight">{{message}}</p>
          <div class="alert alert-danger" v-if="draft.certificate_weight_status == 3"><strong>Reason:</strong> {{draft.certificate_weight_reason}}</div>
        </div>
      </div>

      <div class="col-6">
        <label class="float-right">Required&emsp;<input type="checkbox" v-model="draft.required.certificate_weight" value="true"></label>
        <div class="form-group">
          <p>ALLOW ACCESS</p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_weight" value="2">Importer</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_weight" value="3">Fumigation Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_weight" value="6">Insurance Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_weight" value="7">Inspection Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_weight" value="8">Forwarding Agent</label></p>
        </div>
      </div>

    </div>
    <div class="row separador">

      <div class="col-6">
        <div class="form-group">
          <label for="certificate_seguro">CERTIFICATE OF INSURANCE</label>
          <div class="badge badge-primary" v-if="draft.certificate_seguro_status == 1 && !!draft.certificate_seguro">Waiting for approval</div>
          <div class="badge badge-success" v-if="draft.certificate_seguro_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="draft.certificate_seguro_status == 3">Rejected</div>
          <popcorn-upload name="certificate_seguro" :allowed_pattern="/^application\/pdf$/" v-model="draft.certificate_seguro" :file="draft.certificate_seguro" @upload="RegisterCertificateSeguro" class="error.certificate_seguro != '' && error.certificate_seguro ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.certificate_seguro" v-for="message in error.certificate_seguro">{{message}}</p>
          <div class="alert alert-danger" v-if="draft.certificate_seguro_status == 3"><strong>Reason:</strong> {{draft.certificate_seguro_reason}}</div>
        </div>
      </div>

      <div class="col-6">
        <label class="float-right">Required&emsp;<input type="checkbox" v-model="draft.required.certificate_seguro" value="true"></label>
        <div class="form-group">
          <p>ALLOW ACCESS</p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_seguro" value="2">Importer</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_seguro" value="3">Fumigation Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_seguro" value="6">Insurance Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_seguro" value="7">Inspection Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_seguro" value="8">Forwarding Agent</label></p>
        </div>
      </div>

    </div>
    <div class="row separador">

      <div class="col-6">
        <div class="form-group">
          <label for="certificate_phyto">PHYTO CERTIFICATE</label>
          <div class="badge badge-primary" v-if="draft.certificate_phyto_status == 1 && !!draft.certificate_phyto">Waiting for approval</div>
          <div class="badge badge-success" v-if="draft.certificate_phyto_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="draft.certificate_phyto_status == 3">Rejected</div>
          <popcorn-upload name="certificate_phyto" :allowed_pattern="/^application\/pdf$/" v-model="draft.certificate_phyto" :file="draft.certificate_phyto" @upload="RegisterCertificatePhyto" class="error.certificate_phyto != '' && error.certificate_phyto ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.certificate_phyto" v-for="message in error.certificate_phyto">{{message}}</p>
          <div class="alert alert-danger" v-if="draft.certificate_phyto_status == 3"><strong>Reason:</strong> {{draft.certificate_phyto_reason}}</div>
        </div>
      </div>

      <div class="col-6">
        <label class="float-right">Required&emsp;<input type="checkbox" v-model="draft.required.certificate_phyto" value="true"></label>
        <div class="form-group">
          <p>ALLOW ACCESS</p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_phyto" value="2">Importer</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_phyto" value="3">Fumigation Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_phyto" value="6">Insurance Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_phyto" value="7">Inspection Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.certificate_phyto" value="8">Forwarding Agent</label></p>
        </div>
      </div>

    </div>
    <div class="row separador">

      <div class="col-6">
        <div class="form-group">
          <label for="report">STUFFING REPORT</label>
          <div class="badge badge-primary" v-if="draft.report_status == 1 && !!draft.report">Waiting for approval</div>
          <div class="badge badge-success" v-if="draft.report_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="draft.report_status == 3">Rejected</div>
          <popcorn-upload name="report" :allowed_pattern="/^application\/pdf$/" v-model="draft.report" :file="draft.report" @upload="RegisterReport" class="error.report != '' && error.report ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.report" v-for="message in error.report">{{message}}</p>
          <div class="alert alert-danger" v-if="draft.report_status == 3"><strong>Reason:</strong> {{draft.report_reason}}</div>
        </div>

      </div>

      <div class="col-6">
        <label class="float-right">Required&emsp;<input type="checkbox" v-model="draft.required.report" value="true"></label>
        <div class="form-group">
          <p>ALLOW ACCESS</p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.report_status" value="2">Importer</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.report_status" value="3">Fumigation Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.report_status" value="6">Insurance Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.report_status" value="7">Inspection Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.report_status" value="8">Forwarding Agent</label></p>
        </div>
      </div>

    </div>
    <div class="row separador">

      <div class="col-6">
        <div class="form-group">
          <label for="report">HEALTH_CERTIFICATE</label>
          <div class="badge badge-primary" v-if="draft.health_certificate_status == 1 && !!draft.health_certificate">Waiting for approval</div>
          <div class="badge badge-success" v-if="draft.health_certificate_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="draft.health_certificate_status == 3">Rejected</div>
          <popcorn-upload name="health_certificate" :allowed_pattern="/^application\/pdf$/" v-model="draft.health_certificate" :file="draft.health_certificate" @upload="RegisterHealthCertificate" class="error.health_certificate != '' && error.health_certificate ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.health_certificate" v-for="message in error.health_certificate">{{message}}</p>
          <div class="alert alert-danger" v-if="draft.health_certificate_status == 3"><strong>Reason:</strong> {{draft.health_certificate_reason}}</div>
        </div>

      </div>

      <div class="col-6">
        <label class="float-right">Required&emsp;<input type="checkbox" v-model="draft.required.health_certificate" value="true"></label>
        <div class="form-group">
          <p>ALLOW ACCESS</p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.health_certificate" value="2">Importer</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.health_certificate" value="3">Fumigation Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.health_certificate" value="6">Insurance Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.health_certificate" value="7">Inspection Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.health_certificate" value="8">Forwarding Agent</label></p>
        </div>
      </div>

    </div>

    <div class="row separador">

      <div class="col-6">
        <div class="form-group">
          <label for="report">NON_GMO_CERTIFICATE</label>
          <div class="badge badge-primary" v-if="draft.non_gmo_certificate_status == 1 && !!draft.non_gmo_certificate">Waiting for approval</div>
          <div class="badge badge-success" v-if="draft.non_gmo_certificate_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="draft.non_gmo_certificate_status == 3">Rejected</div>
          <popcorn-upload name="non_gmo_certificate" :allowed_pattern="/^application\/pdf$/" v-model="draft.non_gmo_certificate" :file="draft.non_gmo_certificate" @upload="RegisterNonGmoCertificate" class="error.non_gmo_certificate != '' && error.non_gmo_certificate ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.non_gmo_certificate" v-for="message in error.non_gmo_certificate">{{message}}</p>
          <div class="alert alert-danger" v-if="draft.non_gmo_certificate_status == 3"><strong>Reason:</strong> {{draft.non_gmo_certificate_reason}}</div>
        </div>

      </div>

      <div class="col-6">
        <label class="float-right">Required&emsp;<input type="checkbox" v-model="draft.required.non_gmo_certificate" value="true"></label>
        <div class="form-group">
          <p>ALLOW ACCESS</p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.non_gmo_certificate" value="2">Importer</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.non_gmo_certificate" value="3">Fumigation Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.non_gmo_certificate" value="6">Insurance Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.non_gmo_certificate" value="7">Inspection Agency</label></p>
          <p><label><input type="checkbox" v-model="draft.allowAccess.non_gmo_certificate" value="8">Forwarding Agent</label></p>
        </div>
      </div>

    </div>

  <div class="row">
      <div class="col">
        <label for="other">OTHER DOCUMENTS</label>
        <div class="float-right">
          <button class="btn btn-success btn-sm" @click="draft.others.push(null)">Add another document</button><br/><br/>
          <label class="ml-2 mt-2">Required</label>
          <input class="form-control others-required float-left" type="number" v-model="draft.required.others" />
        </div>
      </div>
  </div>
  <div class="row" v-for="(other, index) in draft.others">
    <!-- <div class="col-3">
    </div> -->

    <div class="col-6" v-if="draft.others_status[index] == 3">
      <div class="alert alert-success">Document accepted by customer</div>
    </div>
    <div class="col-6">
      <div class="form-group">
        <popcorn-upload-new :name="'other_' + index" :allowed_pattern="/^application\/pdf$/" v-model="draft.others[index]" class="error.other != '' && error.other ? 'is-invalid' : ''"></popcorn-upload-new>
        <p class="invalid-feedback" v-if="error.other" v-for="message in error.other">{{message}}</p>
      </div>
    </div>
  </div>


    </div>
  </div>
</template>

<style scoped>
.row.separador {
  border-top: 5px solid #fef101;
}
.others-required {
  width: 50px !important;
}
</style>

<script>
export default {
  props: ['value','sending_emails'],
  name: 'popcorn-draft-documents',
  data () {
    return {
      loading: true,
      draft: {},
      error: {}
    }
  },
  watch: {
    value: function (newValue, oldValue) {
      this.$data.draft = newValue;
    },
  },
  mounted: function () {
    this.$data.draft = this.$props.value;
  },
  methods: {
    formatDate: function (val) {
      return val
    },
    RegisterDraftBl: function ( val ) {
      this.$data.draft.draft_bl = val
      this.$data.draft.draft_bl_status = 1
    },
    RegisterDraftComercial: function ( val ) {
      this.$data.draft.draft_comercial = val
      this.$data.draft.draft_comercial_status = 1
    },
    RegisterPackingList: function ( val ) {
      this.$data.draft.packing_list = val
      this.$data.draft.packing_list_status = 1
    },
    RegisterCertificateOrigin: function ( val ) {
      this.$data.draft.certificate_origin = val
      this.$data.draft.certificate_origin_status = 1
    },
    RegisterCertificateFumigation: function ( val ) {
      this.$data.draft.certificate_fumigation = val
      this.$data.draft.certificate_fumigation_status = 1
    },
    RegisterCertificateQuality: function ( val ) {
      this.$data.draft.certificate_quality = val
      this.$data.draft.certificate_quality_status = 1
    },
    RegisterCertificateWeight: function ( val ) {
      this.$data.draft.certificate_weight = val
      this.$data.draft.certificate_weight_status = 1
    },
    RegisterCertificateSeguro: function ( val ) {
      this.$data.draft.certificate_seguro = val
      this.$data.draft.certificate_seguro_status = 1
    },
    RegisterHealthCertificate: function ( val ) {
      this.$data.draft.health_certificate = val
      this.$data.draft.health_certificate_status = 1
    },
    RegisterNonGmoCertificate: function ( val ) {
      this.$data.draft.non_gmo_certificate = val
      this.$data.draft.non_gmo_certificate_status = 1
    },
    RegisterCertificatePhyto: function ( val, sendback ) {
      this.$data.draft.certificate_phyto = val
      this.$data.draft.certificate_phyto_status = 1

      if (!sendback) {
        this.$emit('phyto', val);
      }
    },
    RegisterReport: function ( val ) {
      this.$data.draft.report = val
      this.$data.draft.report_status = 1
    },
    RegisterOther: function ( val, index ) {
      index = index ? index : -1;
      this.$data.draft.others[index] = val.url
    },
    Save: function () {
      this.$data.draft.sending_emails = this.$props.sending_emails;
      
      this.$http.put('/api/draft/' + this.$route.params.id, this.$data.draft).then( e => {
        if (e.body.success) {
          //this.$toaster.success(e.body.success)
        }
      })
    }
  }
}
</script>