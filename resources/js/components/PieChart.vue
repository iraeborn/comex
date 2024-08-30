<template>
    <div>
        <h2>{{ title }}</h2>
        <Pie
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
import { Pie } from "vue-chartjs/legacy";

import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    CategoryScale,
} from "chart.js";

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale);

export default {
    name: "PieChart",
    components: {
        Pie,
    },
    props: {
        chartId: {
            type: String,
            default: "pie-chart",
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
            default: "pieChart",
        },
    },
    data() {
        return {
            chartData: {
                labels: [],
                datasets: [],
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
        label(newLabel) {
            this.updateChartData(newLabel, this.ordersCount);
        },
        data(newData) {
            this.updateChartData(newData, this.data);
        },
    },
};
</script>
