<template>
    <DataTable
        v-model:filters="filters"
        paginator
        :rows="5"
        :rowsPerPageOptions="[5, 10, 20, 50]"
        :value="items"
        filterDisplay="menu">
        <Column field="user_full_name" header="Пользователь" sortable>
            <template #body="{ data }">
                <div class="d-flex gap-2 align-items-center">
                    <img v-show="data?.user_avatar" :alt="data?.title" :src="data?.user_avatar" class="rounded-circle"
                         style="width: 45px; height: 45px;"/>
                    <span>{{ data.user_full_name }}</span>
                </div>
            </template>
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text" class="p-column-filter" placeholder="Введите пользователя" />
            </template>
        </Column>
        <Column header="Статус" field="status" sortable>
            <template #body="{ data }">
                <Tag :value="testStatusLabel(data?.status)" :severity="testStatusClass(data?.status)" />
            </template>
            <template #filter="{ filterModel }">
                <Dropdown v-model="filterModel.value" :options="testStatusOptions" placeholder="Select One" class="p-column-filter" showClear>
                    <template #value="slotProps">
                        {{ testStatusLabel(slotProps.value) }}
                    </template>
                    <template #option="slotProps">
                        <Tag :value="testStatusLabel(slotProps.option)" :severity="testStatusClass(slotProps.option)" />
                    </template>
                </Dropdown>
            </template>
        </Column>
        <Column field="test_end_at" header="Дата завершения" dataType="date" sortable>
            <template #body="{ data }">
                {{ formatDate(data?.test_end_at) }}
            </template>
        </Column>
        <Column header="">
            <template #body="">
                <Button class="p-button-sm py-1 px-2 rounded"
                        iconPos="right" label="Проверить"
                        @click="" />
            </template>
        </Column>
    </DataTable>
</template>

<script lang="ts">
import {ref} from "vue";
import { testStatusLabel, testStatusClass } from "@/utils/enum.ts";
import { formatDate } from "@/utils/date.ts";
import { TestStatus } from "@/models/test.ts";
import {FilterMatchMode, FilterOperator} from "primevue/api";


export default {
    name: "TableTestUsers",
    computed: {
        TestStatus() {
            return TestStatus
        }
    },
    props: {
        items: {
            type: Array,
            required: true,
        },
        filters: {
            type: Object,
            required: true,
        }
    },
    data(){
        return {
            testStatusOptions: [],
            filters: ref({
                global: {value: null},
                user_full_name: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
                test_end_at: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.DATE_IS }] },
                status: { operator: FilterOperator.OR, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
            }),
        }
    },
    created() {
        this.initListStatuses()
    },
    methods: {
        formatDate,
        testStatusClass,
        testStatusLabel,
        initListStatuses(){
           this.testStatusOptions = Object.keys(TestStatus).map((key: keyof typeof TestStatus) => (
                TestStatus[key]
            ));
        }
    }
}
</script>

