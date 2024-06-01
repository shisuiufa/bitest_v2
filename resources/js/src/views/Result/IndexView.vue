<template>
    <panel header="Список результатов">
        <TableResult :items="this.results" v-if="this.results && this.results.length" />
        <TableSkeleton v-else/>
    </panel>
</template>

<script>
import TableResult from "@/components/tables/TableResult.vue";
import { useLaravel } from "@/composables/useLaravel.ts";
import * as toast from "@/composables/useNotifications.ts";
import TableSkeleton from "@/components/tables/TableSkeleton.vue";
const { result } = useLaravel();

export default {
    name: "ResultIndexView",
    components: {TableSkeleton, TableResult },
    data() {
        return {
            results: [],
        };
    },
    mounted() {
        this.getResult();
    },
    methods: {
        async getResult() {
            await result
                .index()
                .then((res) => {
                    this.results = res.data;
                })
                .catch((err) => {
                    toast.error(
                        "Ошибка загрузки результатов тестов",
                        err.response.data.message,
                    );
                });
        },
    },
};
</script>
