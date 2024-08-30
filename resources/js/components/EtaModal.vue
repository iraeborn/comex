<template>
    <div class="w-100">
        <div class='form-group'>
        <iconinput 
            label='Date:'
            type='date'
            id='eta'
            name='eta'
            icon='fas fa-calendar'
            :value="data.shipping.eta"
            @change="updateData('eta', $event, data.id)"
        />
    </div>
        <div class="mt-2">
            <div v-if="Array.isArray(logs)" class="w-100">
                <div>History logs</div>
                <table class="table table-hover">
                    <tbody>
                        <tr v-for="(data, index) in processedLogs">
                            <td>On <strong>{{ data.created_at }}</strong>, <strong>{{ data.causer_name }}</strong> changed the date to <strong>{{ data.shipping_eta }}</strong>.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-else>{{ logs }}</div>
        </div>
    </div>
</template>

<script>
import moment from 'moment'

export default {
    name: "eta-modal",
    props: {
        data: {
            type: Object,
            required: false
        },
        error: {
            type: Object,
            required: false
        }
    },

    data(){
        return {
            logs: null,
            processedLogs: []
        }
    },

    methods: {
        updateData(key, value, orderId) {
            this.$emit('update-data', { key, value, orderId });
        },

        async getHistoryLogs() {
            try {
                const response = await this.$http.get('/api/order/historyLogs', {
                params: {
                    subject_type: 'shippings',
                    subject_id: this.data.shipping.id
                }
                });
                
                const filteredLogs = response.data.filter(log => {
                const parsedLog = JSON.parse(log.log);
                return parsedLog.hasOwnProperty('shipping.eta');
                });

                this.logs = filteredLogs.length ? filteredLogs : 'No history found';
                
                if (Array.isArray(this.logs)) {
                this.processLogs();
                }
            } catch (error) {
                console.error('Failed to fetch history logs:', error);
                this.logs = 'No history found';
            }
        },

        processLogs() {
            const logs = this.logs.map(data => {
                const parsedLog = JSON.parse(data.log);
                const shippingEta = parsedLog['shipping.eta'];
                
                const processedLog = {
                ...parsedLog,
                created_at: moment(data.created_at).format('DD/MM/YYYY HH:mm'),
                shipping_eta: moment(shippingEta).format('DD/MM/YYYY'),
                causer_name: this.capitalize(parsedLog.causer_name)
                };
                delete processedLog['shipping.eta'];
                
                return processedLog;
            });

            this.processedLogs = logs;
        },

        capitalize(name) {
            return name.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
        }

    },

    watch: {
        'data.isOpen': function(newValue) {
            if (newValue) {
                this.getHistoryLogs();
            }
        }
    },

    mounted() {
        this.getHistoryLogs();
    },
};
</script>
