<template>
    <div>
        <h2>{{ title }}</h2>
        <Bar
            :chart-options="chartOptions"
            :chart-data="chartData"
            :chart-id="chartId"
            :dataset-id-key="datasetIdKey"
            :plugins="plugins"
            :css-classes="cssClasses"
            :styles="styles"
            :width="width"
            :height="height"
        />
    </div>
</template>

<script>
import { Bar } from "vue-chartjs/legacy";

import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
);

export default {
    name: "BarChart",
    components: {
        Bar,
    },
    props: {
        chartId: {
            type: String,
            default: "bar-chart",
        },
        datasetIdKey: {
            type: String,
            default: "label",
        },
        width: {
            type: Number,
            default: 400,
        },
        height: {
            type: Number,
            default: 400,
        },
        cssClasses: {
            default: "",
            type: String,
        },
        styles: {
            type: Object,
            default: () => {},
        },
        plugins: {
            type: Array,
            default: () => [],
        },
        label: {
            type: Array,
            default: () => [],
        },
        data: {
            type: Array,
            default: () => [],
        },
        title: {
            type: String,
            default: "barChart",
        },
    },
    data() {
        return {
            chartData: {
                labels: this.label,
                datasets: [
                    {
                        label: "Data One",
                        backgroundColor: "#f87979",
                        data: this.data,
                    },
                ],
            },
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false,
            },
        };
    },
    methods: {
        updateChartData(label, data) {
            this.chartData = {
                labels: label,
                datasets: data,
            };
        },
    },
    watch: {
        label(newMonths) {
            this.updateChartData(newMonths, this.ordersCount);
        },
        data(newOrdersCount) {
            this.updateChartData(this.label, newOrdersCount);
        },
    },
};
</script>
