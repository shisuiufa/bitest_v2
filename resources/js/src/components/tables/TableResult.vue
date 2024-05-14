<template>
    <DataTable
        v-model:filters="filters"
        paginator
        :rows="5"
        :rowsPerPageOptions="[5, 10, 20, 50]"
        :value="items"
        filterDisplay="menu"
    >
        <Column filterField="title" field="title" header="Тест" sortable>
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
            <template #filter="{ filterModel }">
                <InputText
                    v-model="filterModel.value"
                    placeholder="Поиск по тесту"
                />
            </template>
        </Column>
        <Column field="test_user.status" header="Статус" sortable>
            <template #body="{ data }">
                <Tag
                    :value="testStatusLabel(data.test_user.status)"
                    :severity="testStatusClass(data.test_user.status)"
                />
            </template>
            <template #filter="{ filterModel }">
                <Dropdown
                    v-model="filterModel.value"
                    :options="testStatusOptions"
                    placeholder="Select One"
                    class="p-column-filter"
                    showClear
                >
                    <template #value="slotProps">
                        {{ testStatusLabel(slotProps.value) }}
                    </template>
                    <template #option="slotProps">
                        <Tag
                            :value="testStatusLabel(slotProps.option)"
                            :severity="testStatusClass(slotProps.option)"
                        />
                    </template>
                </Dropdown>
            </template>
        </Column>
        <Column
            field="test_user.attempt"
            header="Попытка"
            class="text-center"
            sortable
        >
            <template #body="{ data }">
                <div class="p-tag p-component">
                    {{ data.test_user.attempt }}
                </div>
            </template>
        </Column>
        <Column
            field="test_user.score"
            header="Балл"
            class="text-center"
            sortable
        >
            <template #body="{ data }">
                <div
                    class="p-tag p-component"
                    :class="scoreStockClass(data.test_user.score)"
                >
                    <span class="p-tag-value">
                        {{ data.test_user.score ? data.test_user.score : "-" }}
                    </span>
                </div>
            </template>
        </Column>
        <Column
            field="test_user.percent"
            header="Процент"
            class="text-center"
            sortable
        >
            <template #body="{ data }">
                <div
                    class="p-tag p-component"
                    :class="percentStockClass(data.test_user?.percent)"
                >
                    <span class="p-tag-value">
                        {{
                            data.test_user.percent
                                ? data.test_user.percent
                                : "-"
                        }}
                    </span>
                </div>
            </template>
        </Column>
        <Column>
            <template #body="slotProps">
                <Button
                    class="p-button-sm rounded-circle"
                    icon="pi pi-search"
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

<script lang="ts">
import { testStatusLabel, testStatusClass } from "@/utils/enum.ts";
import { ref } from "vue";
import { FilterMatchMode, FilterOperator } from "primevue/api";
import { TestStatus } from "@/models/test.ts";

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
        TestStatus,
        scoreStockClass(data) {
            switch (true) {
                case data === null:
                    break;
                case data >= 4:
                    return "p-tag-success";
                case data === 3:
                    return "p-tag-warning";
                case data < 3:
                    return "p-tag-danger";
            }
        },
        percentStockClass(data) {
            switch (true) {
                case data === null:
                    break;
                case data >= 68:
                    return "p-tag-success";
                case data >= 56:
                    return "p-tag-warning";
                case data < 56:
                    return "p-tag-danger";
            }
        },
        initListStatuses() {
            this.testStatusOptions = Object.keys(TestStatus).map(
                (key: keyof typeof TestStatus) => TestStatus[key],
            );
        },
    },
    created() {
        this.initListStatuses();
    },
    data() {
        return {
            filters: ref({
                title: {
                    operator: FilterOperator.AND,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                "test_user.status": {
                    operator: FilterOperator.OR,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.EQUALS },
                    ],
                },
            }),
            testStatusOptions: [],
        };
    },
};
</script>
