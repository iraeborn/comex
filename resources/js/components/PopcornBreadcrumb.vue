<template>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>

        <li class="breadcrumb-item" v-if="Routes()" v-for="route in Routes()">
            <router-link :to="{ name: route }">{{ route }}</router-link>
        </li>

        <li class="breadcrumb-item active">{{ this.$route.name }}</li>

    </ol>
</template>

<script>
export default {
    name: 'popcorn-breadcrumb',
    methods: {
        Routes: function () {
            return this.$route.meta.breadcrumb
        }
    }
}
</script>