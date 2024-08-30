<script>
export default {
    methods: {
        async getBanks() {
            try {
                const response = await this.$http.get("/api/banks");
                return response.body;
            } catch (error) {
                console.error("Error fetching banks:", error);
                throw error;
            }
        },
    }
}
</script>