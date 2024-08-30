<template>
    <div class='form-group'>
        <iconinput 
            :label="label"
            type='text'
            :id="type"
            :name="type"
            icon='fas fa-calendar'
            :value="data.dhl_tracking_number"
            @input="updateData('dhl_tracking_number', $event, data.id)"
        />
    </div>
</template>

<script>
export default {
    name: "dhl-modal",
    props: {
        data: {
            type: Object,
            required: false
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
    methods: {
        updateData(type, value, orderId) {
            this.$emit('update-data', { type, value, orderId });
        }
    }
};
</script>
