<template>
    <div>
        <div class="row">
            <div class="col-12">
                <p><strong>Container tracking</strong></p>
                <a target="_blank" :href="GetContainerUrl(container)" v-if="traking_site" class="btn btn-primary btn-block" v-for="(container, index) in container_data">{{ container.name }}</a>
                
            </div>
        </div>
        
        <div v-if="!dhl_tracking_code">DHL tracking Code not informed!</div>

        <div class="row" style="margin-top: 20px;" v-if="dhl_tracking_code">
            <div class="col" v-if="dhl_data">
                <p><strong>Document tracking</strong></p>
                <div class="alert alert-danger" v-if="dhl_data.type == 'Client error'">{{ dhl_data.title }}</div>
                <div v-if="dhl_data.type != 'Client error'">
                    
                    <table class="table table-sm table-amarelo">
                        <tr>
                            <th>Sevice</th>
                            <td>{{ dhl_data.shipments[0].service }}</td>
                        </tr>
                        <tr>
                            <th>Origin</th>
                            <td>{{ dhl_data.shipments[0].origin.address.addressLocality }}</td>
                        </tr>
                        <tr>
                            <th>Destination</th>
                            <td>{{ dhl_data.shipments[0].destination.address.addressLocality }}</td>
                        </tr>
                        <!-- <tr>
                            <th>Status</th>
                            <td>{{ dhl_data.shipments[0].status }}</td>
                        </tr> -->
                    </table>

                    <table class="table table-sm table-amarelo">
                        <tr>
                            <th>Date Time</th>
                            <th>Address</th>
                            <th>&nbsp;</th>
                        </tr>

                        <tr v-for="(event, index) in dhl_data.shipments[0].events">
                            <td>{{ FormatDateTime(event.timestamp) }}</td>
                            <td>{{ event.location.address.addressLocality }}</td>
                            <td>{{ event.description }}</td>
                        </tr>

                    </table>
                    
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'popcorn-traking',
    props: ['value', 'container_data'],
    data() {
        return {
            traking_site: null,
            traking_links: null,
            dhl_tracking_code: null,
            dhl_data: null,
            order: null,
        }
    },
    mounted: async function () {
        let traking_links = await this.$http.get('/api/traking-links');
        this.$data.traking_links = traking_links.body;

        let order = await this.$http.get('/api/order/' + this.$route.params.id);
        this.$data.order = order.body;
        this.$data.dhl_tracking_code = this.$data.order.dhl_tracking_number;

        let dhl_data = await this.$http.get('/api/tracking/' + this.$data.dhl_tracking_code);
        this.$data.dhl_data = dhl_data.body;

        let traking_site = this.$data.traking_links.filter(function (e) {
            if (e.id == order.body.tracking_link_id) return true;
            return false;
        })[0];

        this.$data.traking_site = traking_site;
    },
    methods: {
        Save: async function () {
            let params = {
                traking_link_id: this.$data.traking_site.id,
                dhl_tracking_code: this.$data.dhl_tracking_code,
            }

            let save = this.$http.post('/api/traking-links', params);
        },
        GetContainerUrl: function (container) {
            if(!this.$data.traking_site) return null;
            return this.$data.traking_site.link.replace(/%s/, container.name);
        },
        FormatDateTime: function (date) {
            //2019-05-24T11:25:00
            let [year, month, day, hour, minutes, seconds] = date.split(/[-:T]/);

            return `${month}/${day}/${year} ${hour}:${minutes}`;
        },
    }
}
</script>