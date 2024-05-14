<template>
    <DataTable
        paginator
        :rows="5"
        :rowsPerPageOptions="[5, 10, 20, 50]"
        :value="items"
    >
        <Column header="Тест">
            <template #body="{ data }">
                <div class="d-flex gap-2 align-items-center">
                    <img
                        :alt="data.title"
                        :src="data.image"
                        class="rounded-circle"
                        style="width: 45px; height: 45px"
                    />
                    <span>{{ data.title }}</span>
                </div>
            </template>
        </Column>
        <Column header="Статус">
            <template #body="{ data }">
                <Tag
                    :value="testStatusLabel(data.test_user.status)"
                    :severity="testStatusClass(data.test_user.status)"
                />
            </template>
        </Column>
        <Column field="test_user.attempt" header="Попытка"></Column>
        <Column field="test_user.score" header="Балл">
            <template #body="{ data }">
                <template v-if="data.test_user.score">
                    {{ data.test_user.score }}
                </template>
                <template v-else> - </template>
            </template>
        </Column>
        <Column field="test_user.percent" header="Процент">
            <template #body="{ data }">
                <template v-if="data.test_user.percent">
                    {{ data.test_user.percent }}
                </template>
                <template v-else> - </template>
            </template>
        </Column>
        <Column>
            <template #body="slotProps">
                <Button
                    class="p-button-sm py-1 px-2 rounded"
                    icon="pi pi-chevron-right"
                    iconPos="right"
                    label="Открыть"
                    @click="
                        $router.push({
                            name: 'show-result',
                            params: { id: slotProps.data.id },
                        })
                    "
                />
            </template>
        </Column>
    </DataTable>
</template>

<script>
import { testStatusLabel, testStatusClass } from "@/utils/enum.ts";
export default {
    name: "TableResultApp",
    props: {
        items: {
            type: Array,
            required: true,
        },
    },
    methods: {
        testStatusClass,
        testStatusLabel,
    },
};
</script>
