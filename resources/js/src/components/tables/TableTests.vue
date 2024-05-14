<template>
    <Toolbar class="mb-3">
        <template #start>
            <router-link
                :to="{ name: 'create-test' }"
                class="text-decoration-none"
            >
                <Button
                    label="Создать"
                    icon="pi pi-plus"
                    class="btn btn-success d-flex align-items-center gap-2 me-2 text-white"
                    unstyled
                />
            </router-link>
            <Button
                class="btn btn-danger d-flex gap-2 align-items-center"
                label="Удалить"
                icon="pi pi-trash"
                @click="confirmDeleteSelected"
                :disabled="!selectedTests || !selectedTests.length"
                unstyled
            />
        </template>
    </Toolbar>
    <DataTable
        v-model:filters="filters"
        v-model:selection="selectedTests"
        paginator
        :rows="5"
        :rowsPerPageOptions="[5, 10, 20, 50]"
        :value="tests"
        filterDisplay="menu"
    >
        <Column selectionMode="multiple" :exportable="false"></Column>
        <Column filterField="title" field="title" header="Тест" sortable>
            <template #body="{ data }">
                <div class="d-flex gap-2 align-items-center">
                    <img
                        :alt="data?.title"
                        :src="data?.image"
                        class="rounded-circle"
                        style="width: 45px; height: 45px"
                    />
                    <span>{{ data.title }}</span>
                </div>
            </template>
            <template #filter="{ filterModel }">
                <InputText
                    v-model="filterModel.value"
                    placeholder="Поиск по названию"
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
        <Column
            :showFilterMenu="false"
            field="checked_progress.progress_percentage"
            header="Проверенные"
            sortable
        >
            <template #body="{ data }">
                <ProgressBar
                    :value="data?.checked_progress.progress_percentage"
                />
            </template>
            <template #filter="{ filterModel }">
                <Slider v-model="filterModel.value" range class="m-3"></Slider>
                <div
                    class="flex align-items-center justify-content-between px-2"
                >
                    <span>{{
                        filterModel.value ? filterModel.value[0] : 0
                    }}</span>
                    <span>{{
                        filterModel.value ? filterModel.value[1] : 100
                    }}</span>
                </div>
            </template>
        </Column>
        <Column
            header="Дата создания"
            field="created_at"
            dataType="date"
            sortable
        >
            <template #body="{ data }">
                {{ formatDate(data?.created_at) }}
            </template>
        </Column>
        <Column>
            <template #body="{ data }">
                <Button
                    class="rounded"
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
                            <span :class="item.icon" />
                            {{ item.label }}
                        </a>
                    </template>
                </Menu>
            </template>
        </Column>
    </DataTable>
</template>

<script>
import { ref } from "vue";
import { FilterMatchMode, FilterOperator } from "primevue/api";
import { testPublishedLabel, testPublishedClass } from "@/utils/enum.ts";
import { formatDate } from "@/utils/date.ts";
export default {
    name: "TableTests",
    props: {
        tests: {
            type: Array,
            required: true,
        },
        filters: {
            type: Object,
            required: false,
        },
    },
    methods: {
        testPublishedClass,
        formatDate,
        testPublishedLabel,
        clearFilter() {
            this.$emit("clear-filters");
        },
        confirmDeleteSelected() {
            this.selectedTests.forEach((test) =>
                this.$emit("delete-test", test.id),
            );
        },
        toggle(event, testId) {
            this.selectedTest = testId;
            this.$refs.menu.toggle(event);
        },
    },
    data() {
        return {
            selectedTests: [],
            selectedTest: null,
            items: [
                {
                    label: "Редактировать",
                    icon: "pi pi-pencil",
                    command: () => {
                        this.$router.push({
                            name: "edit-test",
                            params: { id: this.selectedTest },
                        });
                    },
                },
                {
                    label: "Статистика",
                    icon: "pi pi-chart-bar",
                    command: () => {
                        this.$router.push({
                            name: "statistics",
                            params: { id: this.selectedTest },
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
                global: { value: null },
                title: {
                    operator: FilterOperator.AND,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                date: {
                    operator: FilterOperator.AND,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.DATE_IS },
                    ],
                },
                status: { value: null, matchMode: FilterMatchMode.EQUALS },
            }),
        };
    },
    emits: ["delete-test", "clear-filters"],
};
</script>
