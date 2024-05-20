<script>
import { useLaravel } from "@/composables/useLaravel.ts";
import { mapActions } from "vuex";
const { auth } = useLaravel()
export default {
    name: "SignInForm",
    data() {
        return {
            form: {
                email: '',
                password: '',
                errors: null,
            },
        }
    },
    methods: {
        ...mapActions(["login"]),
        async submit() {
            await auth.login(this.form)
                .then(() => {
                    this.login();
                })
                .catch((err) => {
                    if (
                        err.response.status === 403 ||
                        err.response.status === 422
                    ) {
                        this.form.errors = err.response.data.errors;
                        console.log()
                    }
                })
        }
    }
}
</script>

<template>
    <form method="post" @submit.prevent="this.submit">
        <div class="input-wrap">
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
            <label class="auth__label" for="login"
            >Логин:</label
            >
            <div class="auth__icon">
                <svg
                    enable-background="new 0 0 100 100"
                    version="1.1"
                    viewBox="0 0 100 100"
                    xml:space="preserve"
                    xmlns="http://www.w3.org/2000/svg"
                >
                                    <g transform="translate(0 -952.36)">
                                        <path
                                            d="m17.5 977c-1.3 0-2.4 1.1-2.4 2.4v45.9c0 1.3 1.1 2.4 2.4 2.4h64.9c1.3 0 2.4-1.1 2.4-2.4v-45.9c0-1.3-1.1-2.4-2.4-2.4h-64.9zm2.4 4.8h60.2v1.2l-30.1 22-30.1-22v-1.2zm0 7l28.7 21c0.8 0.6 2 0.6 2.8 0l28.7-21v34.1h-60.2v-34.1z"
                                        ></path>
                                    </g>
                    <rect
                        class="st0"
                        width="100"
                        height="100"
                    ></rect>
                                </svg>
            </div>
        </div>
        <div class="input-wrap">
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
            label="Войти"
            class="p-primary mt-3"
        />
    </form>
</template>

