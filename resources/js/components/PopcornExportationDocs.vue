<template>

    <div>
        <div class="row p-2 justify-content-md-center">

            <div class="p-3 col-12 col-md-6">
                <div class="d-flex text-center w-100"> DRAFT BILL </div>
                <button class="btn btn-success btn-sm float-right w-100" @click=generateDraftBill > Generate </button>
            </div>

            <div class="p-3 col-12 col-md-6">
                <div class="d-flex text-center w-100"> LOAD UNITIZATION </div>
                <button class="btn btn-success btn-sm float-right w-100" @click=generateLoadUnitization > Generate </button>
            </div>

            <div class="p-3 col-12 col-md-6">
                <div class="d-flex text-center w-100"> COMMERCIAL INVOICE </div>
                <button class="btn btn-success btn-sm float-right w-100" @click=generateCommercialInvoice > Generate </button>
            </div>

            <div class="p-3 col-12 col-md-6">
                <div class="d-flex text-center w-100"> PACKING LIST </div>
                <button class="btn btn-success btn-sm float-right w-100" @click=generatePackingList > Generate </button>
            </div>

            <div class="p-3 col-12 col-md-6">
                <div class="d-flex text-center w-100"> FUMIGATION DECLARATION </div>
                <button class="btn btn-success btn-sm float-right w-100" @click=generateFumigationDeclaration > Generate </button>
            </div>

            <div class="p-3 col-12 col-md-6">
                <div class="d-flex text-center w-100"> PHYTO CERTIFICATE </div>
                <button class="btn btn-success btn-sm float-right w-100" @click=generatePhytoCertificate > Generate </button>
            </div>

            <div class="p-3 col-12 col-md-6">
                <div class="d-flex text-center w-100"> BL DRAFT </div>
                <button class="btn btn-success btn-sm float-right w-100" @click=generateBLDraft > Generate </button>
            </div>

        </div>
    </div>

</template>

<script>
export default {
    name: 'popcorn-exportation-docs',
    props: ['order'],
    data() {
        return {
        }
    },

    methods: {

        forceFileDownload(response, type){

            let filename = type + '-' + this.order.number + '.pdf';
            const url = window.URL.createObjectURL(new Blob([response.data]))
            const link = document.createElement('a')
            link.href = url
            link.setAttribute('download', filename) //or any other extension
            document.body.appendChild(link)
            link.click()
        },

        generateFile: function (url, type) {

            this.$http({
                method: 'get',
                url: url,
                responseType: 'arraybuffer'
            })
            .then(response => {
                this.forceFileDownload(response, type)  
            })
            .catch(() => console.log('error occured'));
        },

        generateDraftBill: function () {
            this.generateFile('/order/' + this.$route.params.id + '/draft-bill', 'draft-bill');
        },

        generateLoadUnitization: function () {
            this.generateFile('/order/' + this.$route.params.id + '/load-unitization', 'load-unitization');
        },

        generateCommercialInvoice: function () {
            this.generateFile('/order/' + this.$route.params.id + '/commercial-invoice', 'commercial-invoice');
        },

        generatePackingList: function () {
            this.generateFile('/order/' + this.$route.params.id + '/packing-list', 'packing-list');
        },

        generateFumigationDeclaration: function () {
            this.generateFile('/order/' + this.$route.params.id + '/fumigation-declaration', 'fumigation-declaration');
        },

        generatePhytoCertificate: function () {
            this.generateFile('/order/' + this.$route.params.id + '/phyto-certificate', 'phyto-certificate');
        },

        generateBLDraft: function () {
            this.generateFile('/order/' + this.$route.params.id + '/bl-draft', 'bl-draft');
        },

    }

}
</script>