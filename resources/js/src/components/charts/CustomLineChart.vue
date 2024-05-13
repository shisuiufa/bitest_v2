<template>
    <line-chart :chartData="testData" :options="options" />
</template>

<script>
import { LineChart  } from 'vue-chart-3';
import { Chart, registerables } from "chart.js";
Chart.register(...registerables);

export default {
    name: 'CustomLineChart',
    components: { LineChart },
    props: {
        titleChart: {
            required: true,
            type: String
        }
    },
    setup(props) {
        const testData = {
            labels: ['Paris', 'Nîmes', 'Toulon', 'Perpignan', 'Autre'],
            datasets: [
                {
                    label: 'test',
                    data: [30, 40, 60, 70, 5],
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                    ],
                    borderWidth: 1
                },
            ],
        };

        const options = {
            responsive: true,
            plugins: {
                legend: {
                    display: false,
                },
            }
        };

        return { testData, options };
    },
};
</script>
