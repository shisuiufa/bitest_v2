<template>
    <div class="profile">
        <div class="row">
            <div class="col-4">
                <Panel class="h-100 p-0 p-md-5">
                    <div class="d-flex flex-column align-items-center">
                        <SelectAvatar @select-image="(avatar) => this.form.avatar = avatar" :image="this.form.avatar"
                                      class="mb-3"/>
                        <h5 class="">{{ this.user.last_name + " " + this.user.first_name }}</h5>
                        <p class="">{{ this.user.email }}</p>
                        <Tag :value="activeRole.name"/>
                    </div>
                </Panel>
            </div>
            <div class="col-8">
                <Panel header="Основная информация" class="p-0 p-md-5">
                    <form method="POST" class="d-flex flex-column gap-3">
                        <div class="d-flex flex-column gap-2">
                            <label class="label" for="lastName">Фамилия</label>
                            <InputText id="lastName" type="text" v-model="this.form.last_name"
                                       placeholder="Введите вашу фамилию"
                                       :invalid="v$.form.last_name.$invalid"
                            />
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <label class="label" for="firstName">Имя</label>
                            <InputText id="firstName" type="text" v-model="this.form.first_name"
                                       placeholder="Введите ваше имя"
                                       :invalid="v$.form.first_name.$invalid"
                            />
                            <div class="input-errors">
                                <div class="error-msg">{{ v$.form.first_name?.$silentErrors.$message }}</div>
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <label class="label" for="middleName">Отчество</label>
                            <InputText id="middleName" type="text" v-model="this.form.middle_name"
                                       placeholder="Введите ваше отчество"
                                       :invalid="v$.form.middle_name.$invalid"
                            />
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <label class="label" for="email">Email</label>
                            <InputText id="email" type="email" v-model="this.form.email"
                                       placeholder="Введите ваш email"
                                       :invalid="v$.form.email.$invalid"
                            />
                        </div>
                        <div class="d-flex justify-content-center w-100 gap-2">
                            <Button class="p-primary" label="Сохранить" @click="this.submit"/>
                            <Button class="p-primary" label="Изменить пароль" @click="visible = true"/>
                        </div>
                    </form>
                </Panel>
            </div>
        </div>
    </div>
    <Dialog v-model:visible="visible" header="Изменить пароль" :style="{ width: '25rem' }">
        <div class="d-flex flex-column gap-3">
            <div class="d-flex flex-column gap-2">
                <label class="label" for="currentPassword">Текущий пароль</label>
                <InputText id="currentPassword" type="password" v-model="this.current_password"
                           placeholder="Новый пароль"
                           :invalid="v$.form.last_name.$invalid"
                />
            </div>
            <div class="d-flex flex-column gap-2">
                <label class="label" for="password">Новый пароль</label>
                <InputText id="password" type="password" v-model="this.password"
                           placeholder="Новый пароль"
                           :invalid="v$.form.last_name.$invalid"
                />
            </div>
            <div class="d-flex flex-column gap-2">
                <label class="label" for="passwordConfirmation">Подтверждение пароля</label>
                <InputText id="passwordConfirmation" type="password" v-model="this.password_confirmation"
                           placeholder="Подтверждение пароля"
                           :invalid="v$.form.last_name.$invalid"
                />
            </div>
            <div class="text-center">
                <Button class="p-primary" label="Изменить" @click="this.submitPassword()"/>
            </div>
        </div>
    </Dialog>
</template>

<script>
import {useLaravel} from "@/composables/useLaravel.ts";
import * as toast from "@/composables/useNotifications.ts";
import SelectAvatar from "@/components/UI/SelectAvatar.vue";
import {mapActions, mapGetters} from "vuex";
import {useVuelidate} from '@vuelidate/core'
import {required, email} from '@vuelidate/validators'
const {user} = useLaravel();

export default {
    name: "ProfileView",
    components: {
        SelectAvatar
    },
    setup() {
        return {v$: useVuelidate()}
    },
    computed: {
        ...mapGetters(["user"]),
        activeRole(){
            return this.user.roles.sort((a, b) => a.id - b.id)[0];
        }
    },
    data() {
        return {
            form: {},
            current_password: null,
            password: null,
            password_confirmation: null,
            visible: false,
        }
    },
    mounted() {
        this.form.first_name = this.user.first_name;
        this.form.last_name = this.user.last_name;
        this.form.middle_name = this.user.middle_name;
        this.form.email = this.user.email;
        this.form.avatar = this.user.avatar;
    },
    validations() {
        return {
            form: {
                first_name: {required},
                last_name: {required},
                middle_name: {required},
                email: {required, email}
            }
        }
    },
    methods: {
        ...mapActions(["updateState"]),
        async submit() {
            await user.update(this.form)
                .then(() => {
                    this.updateState();
                    toast.success('Профиль обновлен!')
                })
                .catch((err) => {
                    toast.error('Ошибка, попробуйте еще раз!',  err.response.data.message)
                })
        },
        async submitPassword(){
            await user.updatePassword(this.current_password, this.password, this.password_confirmation)
                .then(() => {
                    toast.success('Пароль обновлен!')
                    this.visible = false;
                })
                .catch((err) => {
                    toast.error('Ошибка!',  err.response.data.message)
                })
        }
    },
};
</script>
