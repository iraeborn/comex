<template>
    <div class="container-fluid">
        <div id="ui-view">Dashboard</div>
        <div class="row">
            <div class="col-8">
                <BarChart
                    :label="orders_month"
                    :data="orders_count"
                    :title="'Orders and Containers per Month'"
                />
            </div>
            <div class="col-4">
                <PieChart
                    :label="items_month"
                    :data="items_count"
                    :title="'Top ten most exported products in month'"
                    ref="pieChart"
                />
            </div>
        </div>
    </div>
</template>

<script>
import BarChart from "../components/BarChart.vue";
import LineChart from "../components/LineChart.vue";
import PieChart from "../components/PieChart.vue";

export default {
    components: {
        BarChart,
        PieChart,
    },
    data() {
        return {
            orders_month: [],
            orders_count: [],
            items_month: [],
            items_count: [],
        };
    },
    methods: {
        async getOrdersAndContainerFromLastFewMonth() {
            try {
                const response = await this.$http.get(
                    "/api/orders-last-few-months"
                );
                this.orders_month = response.body.months;
                this.orders_count = [
                    {
                        label: "Orders quantity",
                        backgroundColor: "#f87979",
                        data: response.body.orders_count,
                    },
                    {
                        label: "Containers quantity",
                        backgroundColor: "#49942C",
                        data: response.body.containers_count,
                    },
                ];
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        },

        async getItemsfromLastFewMonth() {
            const response = await this.$http.get("/api/items-last-few-months");

            const itemsData = response.body.items_data;

            const labels = itemsData.map((item) => item.description);
            const data = itemsData.map((item) => item.total_quantity);

            this.$refs.pieChart.updateChartData(labels, [
                {
                    backgroundColor: itemsData.map((item) =>
                        this.intToRGB(this.hashCode(item.description))
                    ),
                    data: data,
                },
            ]);
        },

        hashCode(str) {
            let hash = 0;
            for (let i = 0; i < str.length; i++) {
                hash = str.charCodeAt(i) + ((hash << 5) - hash);
            }
            return hash;
        },

        intToRGB(i) {
            const c = (i & 0x00ffffff).toString(16).toUpperCase();
            return "#" + "00000".substring(0, 6 - c.length) + c;
        },
    },
    mounted: async function () {
        this.getOrdersAndContainerFromLastFewMonth();
        this.getItemsfromLastFewMonth();
    },
};
</script>
