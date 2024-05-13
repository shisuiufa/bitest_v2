<template>
    <panel header="Список результатов">
        <TableResult :items="this.results"/>
    </panel>
</template>

<script>
import TableResult from "@/components/tables/TableResult.vue";
import {useLaravel} from "@/composables/useLaravel.ts";
import * as toast from "@/composables/useNotifications.ts";
const {result} = useLaravel();

export default {
    name: 'ResultIndexView',
    components: {TableResult},
    data() {
        return {
            results: [],
        }
    },
    mounted() {
        this.getResult();
    },
    methods: {
        async getResult() {
            await result.index()
                .then((res) => {
                    this.results = res.data;
                })
                .catch((err) => {
                    toast.error('Ошибка загрузки результатов тестов',
                        err.response.data.message)
                })
        }
    }
}
</script>
