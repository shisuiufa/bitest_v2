<template>
    <PieChart :chartData="testData" :options="options" />
</template>

<script>
import { PieChart } from "vue-chart-3";
import { Chart, registerables } from "chart.js";
Chart.register(...registerables);

export default {
    name: "CustomPieChart",
    components: { PieChart },
    props: {
        titleChart: {
            required: true,
            type: String,
        },
        data: {
            required: true,
            type: Array,
        },
    },
    setup(props) {
        const testData = {
            labels: props.data?.labels,
            datasets: [
                {
                    label: props.label,
                    data: props.data?.datasets,
                    backgroundColor: [
                        "rgb(255, 99, 132)",
                        "rgb(54, 162, 235)",
                        "rgb(255, 205, 86)",
                    ],
                    borderAlign: "center",
                    hoverOffset: 4,
                },
            ],
        };

        const options = {
            responsive: true,
            plugins: {
                legend: {
                    position: "right",
                },
            },
        };

        return { testData, options };
    },
};
</script>
