<template>
    <div class="profile">
        <div class="row">
            <div class="col-4">
                <Panel class="d-flex flex-column align-items-center justify-content-center h-100">
                    <SelectAvatar @select-image="(avatar) => this.user.avatar = avatar" :image="this.user.avatar" class="mb-3"/>
                </Panel>
            </div>
            <div class="col-8">
                <Panel class="p-0 p-md-5">
                    <template #header>
                        <div class="d-flex w-100 justify-content-end">
                            <Tag value="Пользователь"/>
                        </div>
                    </template>
                    <form method="POST" class="d-flex flex-column gap-3">
                        <div class="d-flex flex-column gap-2">
                            <label class="label" for="firstName">Имя</label>
                            <InputText id="firstName" type="text" v-model="this.user.first_name"/>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <label class="label" for="lastName">Фамилия</label>
                            <InputText id="lastName" type="text" v-model="this.user.last_name"/>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <label class="label" for="middleName">Отчество</label>
                            <InputText id="middleName" type="text" v-model="this.user.middle_name"/>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <label class="label" for="email">Email</label>
                            <InputText id="email" type="email" v-model="this.user.email"/>
                        </div>
                        <div class="d-flex justify-content-center w-100 gap-2">
                            <Button class="p-primary" label="Сохранить" @click="console.log(this.user)"/>
                            <Button class="p-primary" label="Изменить пароль"/>
                        </div>
                    </form>
                </Panel>
            </div>
        </div>
    </div>
</template>

<script>
import SelectAvatar from "@/components/UI/SelectAvatar.vue";
import {mapGetters} from "vuex";

export default {
    name: "ProfileView",
    components: {
        SelectAvatar
    },
    computed: {
        ...mapGetters(["user"]),
    },
};
</script>
