<template>
    <form method="post" @submit.prevent="this.submit" class="d-flex flex-column gap-2">
        <div class="d-flex flex-column gap-2">
            <label class="label" for="name">Имя</label>
            <InputText id="name" type="text" v-model="this.form.first_name"
                       placeholder="Имя"
            />
        </div>
        <div class="d-flex flex-column gap-2">
            <label class="label" for="lastName">Фамилия</label>
            <InputText id="lastName" type="text" v-model="this.form.last_name"
                       placeholder="Фамилия"
            />
        </div>
        <div class="d-flex flex-column gap-2">
            <label class="label" for="middleName">Отчество</label>
            <InputText id="middleName" type="text" v-model="this.form.middle_name"
                       placeholder="Отчество"
            />
        </div>
        <div class="d-flex flex-column gap-2">
            <label class="label" for="email">Email</label>
            <InputText id="email" type="email" v-model="this.form.email"
                       placeholder="email"
            />
        </div>
        <div class="d-flex flex-column gap-2">
            <label for="roles">Роль</label>
            <MultiSelect v-model="this.form.roles"
                         :options="this.roles"
                         optionLabel="name"
                         optionValue="value"
                         inputId="roles"
                         placeholder="Роль"
                         :maxSelectedLabels="3" />
        </div>
        <div class="d-flex flex-column gap-2">
            <label class="label" for="password">Пароль</label>
            <InputText id="password" type="password" v-model="this.form.password"
                       placeholder="Пароль"
            />
        </div>
        <div class="d-flex flex-column gap-2">
            <label class="label" for="confirmation">Подтверждение пароля</label>
            <InputText id="confirmation" type="password" v-model="this.form.password_confirmation"
                       placeholder="Подтверждение пароля"
            />
        </div>
        <Button class="p-primary mt-2"
                @click="this.submit"
                :label="this.eventForm === 'add' ? 'Создать' : 'Обновить'"
        />
    </form>
</template>

<script>
import {Role} from "@/models/user.ts";
import {userRoleLabel} from "@/utils/enum.ts";
import {useLaravel} from "@/composables/useLaravel.ts";
import * as toast from "@/composables/useNotifications.ts";
import {RoleValue} from "@/models/user.ts";
const {user} = useLaravel();

export default {
    name: "CreateUserForm",
    props: ['eventForm', 'userId'],
    computed: {
        Role,
        userRoleLabel
    },
    data() {
        return {
            form: {
                first_name: '',
                last_name: '',
                middle_name: '',
                roles: [RoleValue.User],
                email: '',
                password: '',
                password_confirmation: '',
            },
            roles: [
                {
                    name: userRoleLabel(Role.User),
                    value: RoleValue.User
                },
                {
                    name: userRoleLabel(Role.Moderator),
                    value: RoleValue.Moderator
                },
                {
                    name: userRoleLabel(Role.Admin),
                    value: RoleValue.Admin
                }
            ]
        }
    },
    mounted() {
        if(this.eventForm !== 'add' && this.userId != null){
            this.getData();
        }
    },
    methods: {
        async submit(){
            if(this.eventForm === 'add') {
                await user.store(this.form)
                    .then(() => {
                        toast.success('Пользователь создан!')
                        this.$emit('updateDate')
                        this.form = {
                            first_name: '',
                            last_name: '',
                            middle_name: '',
                            roles: [RoleValue.User],
                            email: '',
                            password: '',
                            password_confirmation: '',
                        }
                    })
                    .catch((err) => {
                        toast.error('Ошибка!',  err.response.data.message)
                    })
            } else {
                await user.update(this.form)
                    .then(() => {
                        toast.success('Данные пользователя обновлены!')
                        this.$emit('updateDate')
                        this.form = {
                            first_name: '',
                            last_name: '',
                            middle_name: '',
                            roles: [RoleValue.User],
                            email: '',
                            password: '',
                            password_confirmation: '',
                        }
                    })
                    .catch((err) => {
                        console.log(err)
                        toast.error('Ошибка!',  err.response.data.message)
                    })
            }
        },
        async getData() {
            await user.show(this.userId)
                .then((res) => {
                    const data = res.data;
                    const roleIds = data.roles.map(role => role.id);
                    this.form = {
                        id: data.id,
                        first_name: data.first_name,
                        last_name: data.last_name,
                        middle_name: data.middle_name,
                        roles: roleIds,
                        email: data.email,
                    }
                })
                .catch((err) => {
                    toast.error('Ошибка!',  err.response.data.message)
                })
        }
    }
}
</script>

