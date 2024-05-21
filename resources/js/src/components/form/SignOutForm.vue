<script>
import { useLaravel } from "@/composables/useLaravel.ts";
import {mapActions} from "vuex";

const { auth } = useLaravel()
export default {
    name: "SignOutForm",
    data() {
        return {
            form: {
                name: '',
                lastname: '',
                middleName: '',
                email: '',
                password: '',
                errors: null,
            },
        }
    },
    methods: {
        ...mapActions(["login"]),
        async submit() {
            await auth.register(this.form)
                .then(() => {
                   this.login()
                })
                .catch((err) => {
                    if (
                        err.response.status === 403 ||
                        err.response.status === 422
                    ) {
                        this.form.errors = err.response.data.errors;
                        console.log(this.form.errors)
                    }
                })
        }
    }
}
</script>

<template>
    <form method="post" @submit.prevent="this.submit">
        <h3>
            Регистрация
        </h3>
        <div class="input-wrap input-wrap_icon_not">
            <input
                v-model="this.form.name"
                type="text"
                name="firstName"
                id="firstName"
                class="auth__input"
                autocomplete="off"
                placeholder="Имя"
                required
            />
            <label class="auth__label" for="login">Имя:</label>
        </div>
        <div class="input-wrap input-wrap_icon_not">
            <input
                v-model="this.form.lastname"
                type="text"
                name="lastName"
                id="lastName"
                class="auth__input"
                autocomplete="off"
                placeholder="Фамилия"
                required
            />
            <label class="auth__label" for="login">Фамилия:</label>
        </div>
        <div class="input-wrap input-wrap_icon_not">
            <input
                v-model="this.form.middleName"
                type="text"
                name="middleName"
                id="middleName"
                class="auth__input"
                autocomplete="off"
                placeholder="Отчество"
                required
            />
            <label class="auth__label" for="login">Отчество:</label>
        </div>
        <div class="input-wrap input-wrap_icon_not">
            <input
                v-model="this.form.email"
                type="email"
                name="email"
                id="email"
                class="auth__input"
                autocomplete="off"
                placeholder="Email"
                required
            />
            <label class="auth__label" for="login">Логин:</label>
        </div>
        <div class="input-wrap input-wrap_icon_not">
            <input
                v-model="this.form.password"
                type="password"
                name="password"
                id="password"
                class="auth__input"
                autocomplete="off"
                placeholder="Пароль"
                required
            />
            <label class="auth__label" for="password">Пароль:</label>
        </div>
        <div class="input-wrap input-wrap_icon_not">
            <input
                v-model="this.form.password_confirmation"
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                class="auth__input"
                autocomplete="off"
                placeholder="Подтверждение пароля"
                required
            />
            <label class="auth__label" for="password_confirmation">Подтверждение пароля:</label>
        </div>
        <ul v-if="this.form.errors" class="auth__errors">
            <li v-for="error in this.form.errors">
                {{
                    typeof error === "object"
                        ? error.join(", ")
                        : error
                }}
            </li>
        </ul>
        <Button
            type="submit"
            @submit.prevent
            label="Зарегистрироваться"
            class="p-primary mt-3"
        />
    </form>
</template>

