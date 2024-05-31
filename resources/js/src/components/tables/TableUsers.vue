<template>
    <DataTable
        v-model:filters="filters"
        paginator
        :rows="5"
        :rowsPerPageOptions="[5, 10, 20, 50]"
        :value="users"
        filterDisplay="menu"
    >
        <Column field="full_name" header="Пользователь" sortable>
            <template #body="{ data }">
                <div class="d-flex gap-2 align-items-center">
                    <img
                        v-show="data?.avatar"
                        :alt="data?.full_name"
                        :src="data?.avatar"
                        class="rounded-circle object-fit-cover"
                        style="width: 45px; height: 45px"
                    />
                    <span>{{ data.full_name }}</span>
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
        <Column header="Роль" field="roles" sortable>
            <template #body="{ data }">
                <Tag
                    :value="activeRole(data.roles)"
                />
            </template>
        </Column>
        <Column header="">
            <template #body="{ data }">
                <Button
                    class="p-button-sm rounded-circle p-primary"
                    icon="pi pi-pen-to-square"
                    @click="this.$emit('editUser', data.id)"
                />
            </template>
        </Column>
    </DataTable>
</template>

<script>
import {ref} from "vue";
import {FilterMatchMode, FilterOperator} from "primevue/api";

export default {
    name: "TableUsers",
    props: ['users'],
    data(){
        return {
            selected: {},
            filters: ref({
                global: {value: null},
                full_name: {
                    operator: FilterOperator.AND,
                    constraints: [
                        {value: null, matchMode: FilterMatchMode.STARTS_WITH},
                    ],
                },
                roles: {
                    operator: FilterOperator.OR,
                    constraints: [
                        {value: null, matchMode: FilterMatchMode.EQUALS},
                    ],
                },
            }),
        }
    },
    methods: {
        activeRole(roles){
            return roles.sort((a, b) => a.id - b.id)[0].name;
        }
    },
    emits: ['editUser']
}
</script>
