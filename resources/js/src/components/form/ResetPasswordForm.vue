<script>
import { useLaravel } from "@/composables/useLaravel.ts";
import * as toast from "@/composables/useNotifications.ts";
import {mapActions} from "vuex";
const { auth } = useLaravel()
export default {
    name: "ResetPasswordForm",
    data() {
        return {
            form: {
                email: '',
                token: '',
                password: '',
                password_confirmation: ''
            },
        }
    },
    methods: {
        ...mapActions(["login"]),
        async submit() {
            await auth.resetPassword(this.form)
                .then(() => {
                    this.login()
                    toast.success('Пароль успешно сброшен!')
                })
                .catch((err) => {
                    console.log(err)
                    toast.error('Ошибка', err.response.data.message)
                })
        }
    },
    mounted() {
        this.form.email = this.$route.query.email;
        this.form.token = this.$route.params.token;
    }
}
</script>

<template>
    <form method="post" @submit.prevent="this.submit">
        <h3>Сброс пароля</h3>
        <div class="input-wrap">
            <input
                v-model="this.form.password"
                type="password"
                name="password"
                id="password"
                class="auth__input"
                autocomplete="off"
                placeholder="Новый пароль"
                required
            />
            <label class="auth__label" for="password"
            >Пароль:</label
            >
            <div class="auth__icon">
                <svg
                    enable-background="new 0 0 24 24"
                    version="1.1"
                    viewBox="0 0 24 24"
                    xml:space="preserve"
                    xmlns="http://www.w3.org/2000/svg"
                >
                                    <rect
                                        class="st0"
                                        width="24"
                                        height="24"
                                    ></rect>
                    <path
                        class="st1"
                        d="M19,21H5V9h14V21z M6,20h12V10H6V20z"
                    ></path>
                    <path
                        class="st1"
                        d="M16.5,10h-1V7c0-1.9-1.6-3.5-3.5-3.5S8.5,5.1,8.5,7v3h-1V7c0-2.5,2-4.5,4.5-4.5s4.5,2,4.5,4.5V10z"
                    ></path>
                    <path
                        class="st1"
                        d="m12 16.5c-0.8 0-1.5-0.7-1.5-1.5s0.7-1.5 1.5-1.5 1.5 0.7 1.5 1.5-0.7 1.5-1.5 1.5zm0-2c-0.3 0-0.5 0.2-0.5 0.5s0.2 0.5 0.5 0.5 0.5-0.2 0.5-0.5-0.2-0.5-0.5-0.5z"
                    ></path>
                                </svg>
            </div>
        </div>
        <div class="input-wrap">
            <input
                v-model="this.form.password_confirmation"
                type="password"
                name="passwordConfirmation"
                id="passwordConfirmation"
                class="auth__input"
                autocomplete="off"
                placeholder="Подтверждение пароля"
                required
            />
            <label class="auth__label" for="passwordConfirmation"
            >Подтверждение пароля:</label
            >
            <div class="auth__icon">
                <svg
                    enable-background="new 0 0 24 24"
                    version="1.1"
                    viewBox="0 0 24 24"
                    xml:space="preserve"
                    xmlns="http://www.w3.org/2000/svg"
                >
                                    <rect
                                        class="st0"
                                        width="24"
                                        height="24"
                                    ></rect>
                    <path
                        class="st1"
                        d="M19,21H5V9h14V21z M6,20h12V10H6V20z"
                    ></path>
                    <path
                        class="st1"
                        d="M16.5,10h-1V7c0-1.9-1.6-3.5-3.5-3.5S8.5,5.1,8.5,7v3h-1V7c0-2.5,2-4.5,4.5-4.5s4.5,2,4.5,4.5V10z"
                    ></path>
                    <path
                        class="st1"
                        d="m12 16.5c-0.8 0-1.5-0.7-1.5-1.5s0.7-1.5 1.5-1.5 1.5 0.7 1.5 1.5-0.7 1.5-1.5 1.5zm0-2c-0.3 0-0.5 0.2-0.5 0.5s0.2 0.5 0.5 0.5 0.5-0.2 0.5-0.5-0.2-0.5-0.5-0.5z"
                    ></path>
                                </svg>
            </div>
        </div>
        <Button
            type="submit"
            @submit.prevent
            label="Сбросить"
            class="p-primary mt-3"
        />
    </form>
</template>

