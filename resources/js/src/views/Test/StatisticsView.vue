<template>
    <div class="row mb-2">
        <div class="col-12 col-md-4 mb-2 mb-md-0">
            <Panel header="Прохождение теста" toggleable :collapsed="true">
                <CustomLineChart
                    v-if="statistics?.monthly_stats"
                    :data="statistics?.monthly_stats"
                />
            </Panel>
        </div>
        <div class="col-12 col-md-4 mb-2 mb-md-0">
            <Panel
                header="Процентное распределение"
                toggleable
                :collapsed="true"
            >
                <CustomPieChart
                    v-if="statistics?.passing_percentage"
                    :data="statistics?.passing_percentage"
                />
            </Panel>
        </div>
        <div class="col-12 col-md-4">
            <Panel
                header="Время на прохождение теста"
                toggleable
                :collapsed="true"
            >
                <CustomBarChart
                    v-if="statistics?.passing_duration"
                    :data="statistics?.passing_duration"
                />
            </Panel>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <Panel header="Список пользователей">
                <TableTestUsers
                    v-model:filters="filters"
                    @updateValue="this.getData()"
                    :items="statistics?.users"
                />
            </Panel>
        </div>
    </div>
</template>

<script>
import TableTestUsers from "@/components/tables/TableTestUsers.vue";
import CustomLineChart from "@/components/charts/CustomLineChart.vue";
import CustomPieChart from "@/components/charts/CustomPieChart.vue";
import CustomBarChart from "@/components/charts/CustomBarChart.vue";
import { useLaravel } from "@/composables/useLaravel.ts";
import * as toast from "@/composables/useNotifications.ts";
const { testStatistics: statistics } = useLaravel();

export default {
    name: "StatisticsView",
    components: {
        CustomBarChart,
        CustomPieChart,
        CustomLineChart,
        TableTestUsers,
    },
    data() {
        return {
            statistics: [],
            filters: {},
            testId: this.$route?.params.id,
        };
    },
    mounted() {
        this.getData();
    },
    created() {
        this.initFilters();
    },
    methods: {
        async getData() {
            await statistics
                .index(this.testId)
                .then((res) => {
                    this.statistics = res.data;
                })
                .catch((err) => {
                    toast.error(
                        "Ошибка при загрузки статистики",
                        err.response.data.message,
                    );
                });
        },
        initFilters() {
            this.filters = {
                global: { value: null },
                date: { value: null },
            };
        },
    },
};
</script>
