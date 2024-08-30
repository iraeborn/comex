<template>
    <div>
        <input type="hidden" :name="name" v-if="data_file" v-model="data_file">
        <input type="file" :name="name + '_field'" :id="name + '_field'" class="popcorn-upload-field" @change="FileSelected">

        <div class="row">
            <div class="col-sm-12">
                
                <div class="row margin-bootm-picture" v-if="data_file && !uploading">
                    <div class="col-sm-12">
                        <div v-if="!IsImage(data_file)">
                            <span class="row">
                                <span class="col-3">
                                    <img src="https://img.icons8.com/metro/1600/document.png" class="img-fluid">
                                </span>

                                <span class="col">
                                    <a :href="data_file.url" target="blank" v-if="!IsImage(data_file)">
                                        Link
                                    </a>
                                </span>
                            </span>
                        </div>
                        <img :src="data_file.url" alt="preview" class="img-fluid" v-if="IsImage(data_file)">
                    </div>

                </div>

                <div class="row" v-if="not_allowed">
                    <div class="col">
                        <p class="text-center alert alert-danger">{{ message }}</p>
                    </div>
                </div>

                <div class="progress" v-if="uploading">
                    <div class="progress-bar" role="progressbar" aria-valuenow="0" :style="{ width: progress + '%' }" aria-valuemin="0" aria-valuemax="100"></div>
                </div> 

            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <button type="button" class="btn btn-primary" @click="ChoseFile(name)" v-if="!data_file">Select File</button>
                    <button type="button" class="btn btn-success" @click="ChoseFile(name)" v-if="data_file">Change File</button>
                    <button type="button" class="btn btn-danger" @click="RemoveFile" v-if="data_file">Remove</button>
                </div>

            </div>

        </div>
    
        <div class="row">
        </div>
    </div>
</template>

<style>
.popcorn-upload-field {
    visibility: hidden;
}

.progress,
.margin-bootm-picture {
    margin-bottom: 20px;
}
</style>

<script>
export default {
  props: ['name', 'allowed_pattern', 'value'],
  name: 'popcorn-upload-new',
  data () {
    return {
        file_name: null,
        ready: false,
        uploading: false,
        progress: 0,
        formData: new FormData(),
        not_allowed: false,
        message: null,
        data_file: null
    }
  },
  mounted: function () {
    if (!this.$props.value) return
    this.$data.data_file = {}
    this.$data.data_file.url = this.$props.value
  },
  watch: {
    value: function (newValue, oldValue) {
        if(newValue) {
            this.$data.data_file = {}
            this.$data.data_file.url = this.$props.value
            return
        }

        this.$data.data_file = null
    }
  },
  methods: {
    IsImage: function (file) {
        return !!file.url.toString().match(/(gif|png|jpe?g)$/)
    },
    FileUpload: function () {
        var self = this.$data

        this.$data.uploading = true
        var self = this
        
        this.$http.post('/api/upload', this.$data.formData, {
            progress(e) {
                if (e.lengthComputable) {
                    self.$data.progress = (e.loaded / e.total * 100).toFixed(2);
                }
            }
        }).then((response) => {
            this.$data.data_file = response.body[0]
            this.$data.ready = false
            this.uploading = false
            this.$data.progress = 0
            this.$emit('input', this.$data.data_file.url)
        }, (response) => {
            this.$data.ready = false
            this.uploading = false
            this.$data.progress = 0
        });

    },
    ChoseFile: function (name) {
        document.getElementById(name + '_field').click()
    },
    FileSelected: function (e) {

        if (e.target.files.length == 0) {
            this.$data.ready = false
            return
        }

        this.$data.ready = true
        var files = e.target.files

        for (var index = 0; index < files.length; index++) {
            var file = files[index]

            if (!file.type.match(this.$props.allowed_pattern)) {
                this.$data.ready = false
                this.$data.not_allowed = true
                this.$data.message = "File type not allowed"
                return
            }

            this.$data.not_allowed = false
            this.$data.formData.append(this.$props.name, file)
        }


        this.FileUpload()
    },
    RemoveFile: function () {
        this.$props.value = null;
        this.$emit('input', this.$props.value)
    }
  }
}
</script>