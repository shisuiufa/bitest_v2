<template>
    <div class="d-grid mt-5">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-3">
                <Panel header="Количество тестов">
                    <div class="d-flex justify-content-between gap-2">
                        <div class="d-flex flex-column justify-content-between align-items-center">
                            <span class="fs-3 fw-bolder text-color">
                                6
                            </span>
                            <span class="text-primary fs-5">
                                +2
                            </span>
                        </div>
                        <div class="d-flex align-items-end">
                            <CleanLineChart style="max-height: 85px" :data="[65, 59, 80, 81, 56, 55, 40]"/>
                        </div>
                    </div>
                </Panel>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <Panel header="Пройдено тестов">
                    <div class="d-flex justify-content-between gap-2">
                        <div class="d-flex flex-column justify-content-between align-items-center">
                            <span class="fs-3 fw-bolder text-color">
                                54
                            </span>
                            <span class="text-primary fs-5">
                                +14
                            </span>
                        </div>
                        <div class="d-flex align-items-end">
                            <CleanLineChart style="max-height: 85px" :data="[1, 40, 50, 81, 56, 55, 40]"/>
                        </div>
                    </div>
                </Panel>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <Panel header="Пользователи">
                    <div class="d-flex justify-content-between gap-2">
                        <div class="d-flex flex-column justify-content-between align-items-center">
                            <span class="fs-3 fw-bolder text-color">
                                11
                            </span>
                            <span class="text-primary fs-5">
                                +4
                            </span>
                        </div>
                        <div class="d-flex align-items-end">
                            <CleanLineChart style="max-height: 85px" :data="[0, 50, 51, 60, 90, 95, 102]"/>
                        </div>
                    </div>
                </Panel>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <Panel header="Посещаемость">
                    <div class="d-flex justify-content-between gap-2">
                        <div class="d-flex flex-column justify-content-between align-items-center">
                            <span class="fs-3 fw-bolder text-color">
                                86
                            </span>
                            <span class="text-primary fs-5">
                                +86
                            </span>
                        </div>
                        <div class="d-flex align-items-end">
                            <CleanLineChart style="max-height: 85px" :data="[1, 17, 42, 56, 75, 80, 86]"/>
                        </div>
                    </div>
                </Panel>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 col-md-6">
                <Panel>
                    <template #header>
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <span class="fw-bolder text-color">Пользователи</span>
                            <div class="d-flex gap-1">
                                <Button icon="pi pi-external-link" class="p-button-sm fs-6 rounded" label="Добавить" @click="addUser"  />
                                <Button icon="pi pi-external-link" class="p-button-sm fs-6 rounded" label="Экспорт"  @click="exportUsers"/>
                            </div>
                        </div>
                    </template>
                    <TableUsers :users="this.users"
                                @editUser="(userId) => {selectedUser = userId; this.editUser();}"/>
                </Panel>
            </div>
            <div class="col-12 col-md-6">
                <Panel>
                    <template #header>
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <span class="fw-bolder text-color">Тесты</span>
                            <Button icon="pi pi-external-link" class="p-button-sm fs-6 rounded" label="Экспорт" @click="exportTests"  />
                        </div>
                    </template>
                    <TableTestsClean/>
                </Panel>
            </div>
        </div>
        <div class="row my-5">
            <div class="col-12">
                <Panel header="Настройки">
                    <SettingForm />
                </Panel>
            </div>
        </div>
    </div>
    <Dialog v-model:visible="visible" :header="this.eventForm === 'add' ? 'Создать' : 'Обновить'" :style="{ width: '25rem' }">
       <CreateUserForm :userId="selectedUser" @updateDate="this.getUsers(); this.visible = false;" :eventForm="this.eventForm" />
    </Dialog>
</template>

<script>
import CleanLineChart from "@/components/charts/CleanLineChart.vue";
import TableUsers from "@/components/tables/TableUsers.vue";
import TableTestsClean from "@/components/tables/TableTestsClean.vue";
import CreateUserForm from "@/components/form/CreateUserForm.vue";
import { useLaravel } from "@/composables/useLaravel.ts";
import * as toast from "@/composables/useNotifications.ts";
import SettingForm from "@/components/form/SettingForm.vue";
const { user, test } = useLaravel();
export default {
    name: "IndexView",
    components: {SettingForm, TableTestsClean, CleanLineChart, TableUsers, CreateUserForm},
    data() {
        return {
            visible: false,
            eventForm: 'add',
            users: [],
            selectedUser: null,
        }
    },
    mounted() {
        this.getUsers();
    },
    methods: {
        addUser(){
            this.visible = true;
            this.eventForm = 'add';
        },
        editUser(){
            this.visible = true;
            this.eventForm = 'edit';
        },
        async getUsers(){
            await user.index()
                .then((res) => {
                    this.users = res.data
                    toast.success('Пользователи загружены!')
                })
        },
        async exportUsers(){
            await user.exportExcel()
                .then((res) => {
                    const url = window.URL.createObjectURL(new Blob([res]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'users.xlsx');
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                })
        },
        async exportTests(){
            await test.exportExcel()
                .then((res) => {
                    const url = window.URL.createObjectURL(new Blob([res]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'tests.xlsx');
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                })
        }

    }
}
</script>
