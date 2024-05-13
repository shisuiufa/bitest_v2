<template>
    <DataTable paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" :value="items">
        <Column header="Пользователь">
            <template #body="{ data }">
                <div class="d-flex gap-2 align-items-center">
                    <img v-show="data?.user_avatar" :alt="data?.title" :src="data?.user_avatar" class="rounded-circle"
                         style="width: 45px; height: 45px;"/>
                    <span>{{ data.user_full_name }}</span>
                </div>
            </template>
        </Column>
        <Column header="Статус">
            <template #body="{ data }">
                <Tag :value="testStatusLabel(data?.status)" :severity="testStatusClass(data?.status)" />
            </template>
        </Column>
        <Column field="updated_at" header="Дата завершения">
            <template #body="{ data }">
                {{ localDate(data?.created_at) }}
            </template>
            <template #filter="{ filterModel }">
                <Calendar v-model="filterModel.value" dateFormat="mm/dd/yy" placeholder="mm/dd/yyyy" mask="99/99/9999" />
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

<script>
import { testStatusLabel, testStatusClass } from "@/utils/enum.ts";
import { localDate } from "@/utils/date.ts";
export default {
    name: "TableTestUsers",
    props: {
        items: {
            type: Array,
            required: true,
        }
    },
    methods: {
        testStatusClass,
        localDate,
        testStatusLabel,
    }
}
</script>

