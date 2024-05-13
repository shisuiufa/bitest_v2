<template>
    <line-chart :chartData="testData" :options="options"/>
</template>

<script>
import {LineChart} from 'vue-chart-3';
import {Chart, registerables} from "chart.js";

Chart.register(...registerables);

export default {
    name: 'CustomLineChart',
    components: {LineChart},
    props: {
        titleChart: {
            required: true,
            type: String
        },
        data: {
            required: true,
            type: Array,
        }
    },
    setup(props) {
        const testData = {
            labels: props.data?.labels,
            datasets: [
                {
                    label: 'test',
                    data: props.data?.datasets,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                    ],
                    borderWidth: 2,
                    tension: 0
                },
            ],

        };

        const options = {
            responsive: true,
            plugins: {
                legend: {
                    display: false,
                },
            },
            ticks: {
                precision: 0,
            },
        };

        return {testData, options};
    },
};
</script>
