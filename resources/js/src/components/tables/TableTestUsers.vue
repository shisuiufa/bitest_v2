<template>
    <DataTable
        v-model:filters="filters"
        paginator
        :rows="5"
        :rowsPerPageOptions="[5, 10, 20, 50]"
        :value="items"
        filterDisplay="menu"
    >
        <Column field="user_full_name" header="Пользователь" sortable>
            <template #body="{ data }">
                <div class="d-flex gap-2 align-items-center">
                    <img
                        v-show="data?.user_avatar"
                        :alt="data?.title"
                        :src="data?.user_avatar"
                        class="rounded-circle"
                        style="width: 45px; height: 45px"
                    />
                    <span>{{ data.user_full_name }}</span>
                </div>
            </template>
            <template #filter="{ filterModel }">
                <InputText
                    v-model="filterModel.value"
                    type="text"
                    class="p-column-filter"
                    placeholder="Введите пользователя"
                />
            </template>
        </Column>
        <Column header="Статус" field="status" sortable>
            <template #body="{ data }">
                <Tag
                    :value="testStatusLabel(data?.status)"
                    :severity="testStatusClass(data?.status)"
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
            field="test_end_at"
            header="Дата завершения"
            dataType="date"
            sortable
        >
            <template #body="{ data }">
                {{ formatDate(data?.test_end_at) }}
            </template>
        </Column>
        <Column header="">
            <template #body="{ data }">
                <Button
                    class="p-button-sm rounded-circle p-primary"
                    icon="pi pi-search"
                    @click="
                    this.selected.user = data.user_id;
                    this.selected.try = null;
                    this.openStatUser();
                    this.getTotalAttempts()"
                />
            </template>
        </Column>
    </DataTable>
    <Dialog v-model:visible="visible" modal header="Результат пользователя" :style="{ width: '80vw' }">
        <DataView :value="this.result.answers">
            <template #header>
                <Dropdown @change="openStatUser()"
                          v-model="this.selected.try"
                          :options="this.attempts"
                          placeholder="Попытка пользователя"
                          optionValue="value"
                          optionLabel="label"/>
            </template>
            <template #list="slotProps">
                <UserResultList @updateValue="openStatUser()" :items="slotProps.items"/>
            </template>
        </DataView>
    </Dialog>
</template>

<script lang="ts">
import {ref} from "vue";
import UserResultList from "@/components/lists/UserResultList.vue";
import {testStatusLabel, testStatusClass} from "@/utils/enum.ts";
import {FilterMatchMode, FilterOperator} from "primevue/api";
import {formatDate} from "@/utils/date.ts";
import {TestStatus} from "@/models/test.ts";
import {useLaravel} from "@/composables/useLaravel.ts";

const {testStatistics: statistics} = useLaravel();

export default {
    name: "TableTestUsers",
    components: {UserResultList},
    props: {
        items: {
            type: Array,
            required: true,
        },
        filters: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            testStatusOptions: [],
            filters: ref({
                global: {value: null},
                user_full_name: {
                    operator: FilterOperator.AND,
                    constraints: [
                        {value: null, matchMode: FilterMatchMode.STARTS_WITH},
                    ],
                },
                test_end_at: {
                    operator: FilterOperator.AND,
                    constraints: [
                        {value: null, matchMode: FilterMatchMode.DATE_IS},
                    ],
                },
                status: {
                    operator: FilterOperator.OR,
                    constraints: [
                        {value: null, matchMode: FilterMatchMode.EQUALS},
                    ],
                },
            }),
            visible: false,
            result: null,
            attempts: [],
            selected: {
                user: null,
                test: this.$route?.params.id,
                try: null,
            }
        };
    },
    created() {
        this.initListStatuses();
    },
    methods: {
        formatDate,
        testStatusClass,
        testStatusLabel,
        TestStatus,
        initListStatuses() {
            this.testStatusOptions = Object.keys(TestStatus).map(
                (key: keyof typeof TestStatus) => TestStatus[key],
            );
        },
        async openStatUser() {
            await statistics
                .show(this.selected.test, this.selected.user, {try: this.selected.try })
                .then(res => {
                    this.visible = true
                    this.result = res.data;
                })
        },
        async getTotalAttempts(){
            await statistics
                .totalAttempts(this.selected.test, this.selected.user)
                .then(res => {
                    const items = [];
                    for (let i = 1; i <= res.data; i++) {
                        items.push({value: i, label: 'Попытка: ' + i})
                    }
                    this.selected.try = res.data;
                    this.attempts = items;
                })
        }
    },

};
</script>
