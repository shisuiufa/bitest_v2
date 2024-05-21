<script>
import { useLaravel } from "@/composables/useLaravel.ts";
import * as toast from "@/composables/useNotifications.ts";
const { auth } = useLaravel()
export default {
    name: "ResetForm",
    data() {
        return {
            form: {
                email: '',
            },
        }
    },
    methods: {
        async submit() {
            await auth.sendResetLinkEmail(this.form.email)
                .then(() => {
                    toast.success('Ссылка для сброса пароля была успешно отправлена',
                        'Пожалуйста, проверьте вашу почту и следуйте инструкциям для сброса пароля.')
                })
                .catch((err) => {
                    toast.error('Ошибка', err.response.data.message)
                })
        }
    }
}
</script>

<template>
    <form method="post" @submit.prevent="this.submit">
        <h3>Сброс пароля</h3>
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
            label="Сбросить"
            class="p-primary mt-3"
        />
    </form>
</template>

