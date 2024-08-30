<template>
    <div class="form-group">
        <textarea 
            v-model="data.note"
            cols="30"
            rows="5"
            class="form-control"
            placeholder="Note"
            @input="updateData('note', $event.target.value)"
            ></textarea>
    </div>
</template>

<script>
export default {
    name: "note-modal",
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
    methods: {
        updateData(key, value) {
            this.$emit('update-data', { key, value });
        }
    }
};
</script>
