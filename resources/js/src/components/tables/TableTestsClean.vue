<template>
    <DataTable
        v-model:filters="filters"
        paginator
        :rows="5"
        :rowsPerPageOptions="[5, 10, 20, 50]"
        :value="tests"
        filterDisplay="menu"
    >
        <Column field="full_name" header="Тест" sortable>
            <template #body="{ data }">
                <div class="d-flex gap-2 align-items-center">
                    <img
                        v-show="data?.image"
                        :alt="data?.title"
                        :src="data?.image"
                        class="rounded-circle object-fit-cover"
                        style="width: 45px; height: 45px"
                    />
                    <span>{{ data.title }}</span>
                </div>
            </template>
            <template #filter="{ filterModel }">
                <InputText
                    v-model="filterModel.value"
                    type="text"
                    class="p-column-filter"
                    placeholder="Название теста"
                />
            </template>
        </Column>
        <Column
            :showFilterMenu="false"
            field="published"
            header="Статус"
            sortable
        >
            <template #body="{ data }">
                <Tag
                    :value="testPublishedLabel(Boolean(data?.published))"
                    :severity="testPublishedClass(data?.published)"
                />
            </template>
        </Column>
        <Column>
            <template #body="{ data }">
                <Button
                    class="rounded p-primary"
                    type="button"
                    icon="pi pi-ellipsis-h"
                    @click="toggle($event, data.id)"
                    aria-haspopup="true"
                    aria-controls="overlay_menu"
                />
                <Menu ref="menu" id="overlay_menu" :model="items" :popup="true">
                    <template #item="{ item }">
                        <a
                            :href="item.url"
                            class="d-flex gap-2 align-items-center p-2 dropdown-item cursor-pointer text-decoration-none"
                        >
                            <span :class="item.icon"/>
                            {{ item.label }}
                        </a>
                    </template>
                </Menu>
            </template>
        </Column>
    </DataTable>
</template>

<script>
import { useLaravel } from "@/composables/useLaravel.ts";
import {ref} from "vue";
import {FilterMatchMode, FilterOperator} from "primevue/api";
import {testPublishedLabel, testPublishedClass} from "@/utils/enum.ts";
import * as toast from "@/composables/useNotifications.ts";
const { test } = useLaravel();
export default {
    name: "TableTestsClean",
    data(){
        return {
            tests: [],
            selectedTest: null,
            items: [
                {
                    label: "Редактировать",
                    icon: "pi pi-pencil",
                    command: () => {
                        this.$router.push({
                            name: "edit-test",
                            params: {id: this.selectedTest},
                        });
                    },
                },
                {
                    label: "Статистика",
                    icon: "pi pi-chart-bar",
                    command: () => {
                        this.$router.push({
                            name: "statistics",
                            params: {id: this.selectedTest},
                        });
                    },
                },
                {
                    label: "Удалить",
                    icon: "pi pi-trash",
                    command: () => {
                        this.$emit("delete-test", this.selectedTest);
                    },
                },
            ],
            filters: ref({
                global: {value: null},
                title: {
                    operator: FilterOperator.AND,
                    constraints: [
                        {value: null, matchMode: FilterMatchMode.STARTS_WITH},
                    ],
                },
                status: {
                    operator: FilterOperator.OR,
                    constraints: [
                        {value: null, matchMode: FilterMatchMode.EQUALS},
                    ],
                },
            }),
        }
    },
    mounted() {
        this.getUsers();
    },
    methods: {
        testPublishedClass,
        testPublishedLabel,
        async getUsers(){
            await test.index()
                .then((res) => {
                    this.tests = res.data
                    toast.success('Тесты загружены!')
                })
        },
        toggle(event, testId) {
            this.selectedTest = testId;
            this.$refs.menu.toggle(event);
        },
    }
}
</script>
