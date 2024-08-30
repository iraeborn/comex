<template>
    <div class="w-100">
        <div class='form-group'>
            <iconinput 
                :label="label"
                type='date'
                :id="type"
                :name="type"
                icon='fas fa-calendar'
                :value="getFieldValue()"
                @change="updateData(type, $event, data.id)"
            />
        </div>
        <div class="mt-2">
            <div v-if="Array.isArray(logs)" class="w-100">
                <div>History logs</div>
                <table class="table table-hover">
                    <tbody>
                        <tr v-for="(data, index) in processedLogs" :key="index">
                            <td>On <strong>{{ data.created_at }}</strong>, <strong>{{ data.causer_name }}</strong> changed the date to <strong>{{ data.date }}</strong>.</td>
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
    name: "date-modal",

    props: {
        data: {
            type: Object,
            required: true
        },
        error: {
            type: Object,
            required: false
        },
        label: {
            type: String,
            default: 'Date:'
        },
        type: {
            type: String,
            required: true
        },
        field: {
            type: String,
            required: true
        }
    },

    data() {
        return {
            logs: null,
            processedLogs: []
        }
    },

    methods: {
        FormatDate: function (date) {
            if (!date) return '';
            var d = new Date(date)
            var month = (d.getUTCMonth() + 1).toString()
            var day = d.getUTCDate().toString()
            var year = d.getUTCFullYear().toString()

            month = month.length < 2 ? "0" + month : month
            day = day.length < 2 ? "0" + day : day

            return month + "/" + day + "/" + year
        },

        updateData(type, value, orderId) {
            this.$emit('update-data', { type, value, orderId });
        },

        getFieldValue() {
            const parts = this.type.split('.');
            let data = this.data;

            if (parts.length === 1) {
                
                const key = this.type;

                if (data.hasOwnProperty(key)) {
                    return data[key];
                } else {
                    return null;
                }
            }

            for (let i = 0; i < parts.length; i++) {
                if (data && data.hasOwnProperty(parts[i])) {
                    data = data[parts[i]];
                } else {
                    return null;
                }
            }

            
            return moment(data).format('YYYY-MM-DD');
        },

        getAttr(typeValue) {
            const parts = typeValue.split('.');
            let result = null;
            if (parts.length > 1) {
                result = parts[1];
            } else {
                result = typeValue;
            }

            return result;
        },

        getClass(typeValue) {
            const parts = typeValue.split('.');

            let result = null;
            if (parts.length > 1) {
                result = parts[0];
            } else {
                result = 'order';
            }
            
            return result;
        },

        getSubject(data, path) {
            const firstKey = this.getClass(path);
            let result = null;

            if (firstKey !== 'order') {
                result = data[firstKey];
            }else{
                result = firstKey;
            }

            return result;
        },

        camelCase(className) {
            return className.toLowerCase().replace(/_([a-z])/g, function (match, letter) {
                return letter.toUpperCase();
            }) + 's';
        },

        async getHistoryLogs() {

            
            try {
                const subject = this.getSubject(this.data, this.type);
                console.log(this.data, this.type, subject)
                if (subject.id === null) {
                    this.logs = 'Invalid path for subject ID';
                    return;
                }
                const response = await this.$http.get('/api/order/historyLogs', {
                    params: {
                        subject_type: this.camelCase(this.getClass(this.type)),
                        subject_id: subject.id
                    }
                });

                const filteredLogs = response.data.filter(data => {
                    const parsedLog = JSON.parse(data.log);
                    return parsedLog.hasOwnProperty(this.type);
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
                const dateField = parsedLog[this.type];

                const processedLog = {
                    ...parsedLog,
                    created_at: moment(data.created_at).format('DD/MM/YYYY HH:mm'),
                    date: moment(dateField).format('DD/MM/YYYY'),
                    causer_name: this.capitalize(parsedLog.causer_name)
                };
                delete processedLog[this.field];

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
    }
};
</script>
