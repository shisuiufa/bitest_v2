<script>
import {LineChart} from "vue-chart-3";
import {Chart, registerables} from "chart.js";

Chart.register(...registerables);
export default {
    name: "CleanLineChart",
    props: {
        data: {
            required: true,
            type: Array,
        },
    },
    components: {LineChart},
    setup(props) {
        const data = {
            labels: props.data.map((_, index) => index + 1),
            datasets: [{
                label: '',
                data: props.data,
                fill: true,
                borderColor: 'rgba(61, 59, 255, 1)',
                backgroundColor: 'rgba(61, 59, 255, 0.2)'
            }]
        };

        const options = {
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    display: false
                },
                y: {
                    display: false,
                    beginAtZero: true
                }
            },
            elements: {
                point: {
                    radius: 0
                }
            }
        };

        return {data, options};
    },
}
</script>

<template>
    <line-chart :chartData="data" :options="options"/>
</template>

<style scoped lang="scss">

</style>
