<template>
    <main class="wrap-auth">
        <div class="container">
            <div class="auth mx-auto">
                <div class="auth__img">
                    <img src="/public/images/auth.png" alt="Вход в систему"/>
                </div>
                <div class="auth__form">
                    <h3 class="auth__title">Вход в систему</h3>
                    <form method="post">
                        <div class="input-wrap">
                            <input v-model="this.form.email" type="email" name="email" id="email" class="auth__input"
                                   autocomplete="off"
                                   placeholder="Email" required/>
                            <label class="auth__label" for="login">Логин:</label>
                            <div class="auth__icon">
                                <svg enable-background="new 0 0 100 100" version="1.1" viewBox="0 0 100 100"
                                     xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                                <g transform="translate(0 -952.36)">
                                    <path
                                        d="m17.5 977c-1.3 0-2.4 1.1-2.4 2.4v45.9c0 1.3 1.1 2.4 2.4 2.4h64.9c1.3 0 2.4-1.1 2.4-2.4v-45.9c0-1.3-1.1-2.4-2.4-2.4h-64.9zm2.4 4.8h60.2v1.2l-30.1 22-30.1-22v-1.2zm0 7l28.7 21c0.8 0.6 2 0.6 2.8 0l28.7-21v34.1h-60.2v-34.1z">
                                    </path>
                                </g>
                                    <rect class="st0" width="100" height="100"></rect>
                            </svg>
                            </div>
                        </div>
                        <div class="input-wrap">
                            <input v-model="this.form.password" type="password" name="password" id="password"
                                   class="auth__input" autocomplete="off"
                                   placeholder="Пароль" required/>
                            <label class="auth__label" for="password">Пароль:</label>
                            <div class="auth__icon">
                                <svg enable-background="new 0 0 24 24" version="1.1" viewBox="0 0 24 24"
                                     xml:space="preserve"
                                     xmlns="http://www.w3.org/2000/svg">
                                <rect class="st0" width="24" height="24"></rect>
                                    <path class="st1" d="M19,21H5V9h14V21z M6,20h12V10H6V20z"></path>
                                    <path class="st1"
                                          d="M16.5,10h-1V7c0-1.9-1.6-3.5-3.5-3.5S8.5,5.1,8.5,7v3h-1V7c0-2.5,2-4.5,4.5-4.5s4.5,2,4.5,4.5V10z">
                                </path>
                                    <path class="st1"
                                          d="m12 16.5c-0.8 0-1.5-0.7-1.5-1.5s0.7-1.5 1.5-1.5 1.5 0.7 1.5 1.5-0.7 1.5-1.5 1.5zm0-2c-0.3 0-0.5 0.2-0.5 0.5s0.2 0.5 0.5 0.5 0.5-0.2 0.5-0.5-0.2-0.5-0.5-0.5z">
                                </path>
                            </svg>
                            </div>
                        </div>
                        <ul v-if="this.errors" class="auth__errors">
                            <li v-for="error in this.errors">
                                {{ typeof error === "object" ? error.join(', ') : error }}
                            </li>
                        </ul>
                        <ui-button @click="this.submitForm()" :class="this.errors ? '' : 'mt-4'">Войти</ui-button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import UiButton from "@/components/UI/UiButton.vue";
import axios from "axios";
import {mapActions} from "vuex";

export default {
    name: "LoginView",
    components: {UiButton},
    data() {
        return {
            form: {
                email: '',
                password: '',
            },
            errors: null,
        }
    },
    methods: {
        ...mapActions(['login']),
        submitForm() {
            this.errors = null;
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/login', this.form)
                    .then(res => {
                        this.login();
                    })
                    .catch(err => {
                        if (err.response.status === 403 || err.response.status === 422) {
                            this.errors = err.response.data.errors;
                        }
                    })
            });
        }
    }
}
</script>

<style scoped lang="scss">
.wrap-auth {
    width: 100%;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #1d1e23;
}

.auth {
    width: 100%;
    max-width: 650px;
    display: flex;
    overflow: hidden;
    border-radius: 15px;

    &__img {
        width: 40%;
        overflow: hidden;

        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }

    &__form {
        width: 60%;
        background: #fff;
        display: flex;
        justify-content: center;
        flex-direction: column;
        padding: 45px;
        text-align: center;
    }

    &__title {
        font-size: 1.5rem;
        letter-spacing: 2px;
        margin-bottom: 20px;
        color: #000;
    }

    .input-wrap {
        position: relative;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    &__input {
        display: block;
        width: calc(100% - 44px);
        height: 56px;
        border: none;
        outline: none;
        padding: 20px 0;
        font-size: 16px;
        margin-left: auto;
        border-bottom: 1px solid #1d1e23;

        &:focus {
            border-bottom: 1px solid var(--brand-color);
        }
    }

    &__icon {
        position: absolute;
        top: 0;
        left: 0;
        width: 44px;
        height: 56px;
        display: flex;
        opacity: 0.15;

        svg {
            width: 30px;
            height: 30px;
            margin: auto;
            fill: none;

            path {
                fill: #000;
            }
        }
    }

    &__label {
        position: absolute;
        top: calc(50% - 7px);
        left: 0;
        opacity: 0;
        transition: all 0.3s ease;
        padding-left: 44px;
        font-size: 12.5px;
    }

    &__input:not(:placeholder-shown) {
        padding: 28px 0px 12px 0px;
    }

    &__input:not(:placeholder-shown) + &__label {
        transform: translateY(-12px);
        opacity: 0.7;
    }

    &__input:valid:not(:placeholder-shown) {
        border-bottom: 1px solid var(--brand-color) !important;
    }

    &__input:valid:not(:placeholder-shown) + &__label + &__icon {
        opacity: 1;

        svg {
            path {
                fill: var(--brand-color);
            }
        }
    }

    &__input:not(:valid):not(:focus) + &__label + &__icon {
        animation-name: shake-shake;
        animation-duration: 0.3s;
    }

    &__errors {
        list-style: none;
        background-color: #5d5d5d;
        color: #fff;
        padding: 20px;
        border-radius: 5px;
        margin-top: 20px;
        opacity: 0.9;
    }

    $displacement: 3px;

    @keyframes shake-shake {
        0% {
            transform: translateX(-$displacement);
        }

        20% {
            transform: translateX($displacement);
        }

        40% {
            transform: translateX(-$displacement);
        }

        60% {
            transform: translateX($displacement);
        }

        80% {
            transform: translateX(-$displacement);
        }

        100% {
            transform: translateX(0px);
        }
    }

    input:-webkit-autofill {
        -webkit-background-clip: text;
    }
}

@media screen and (max-width: 670px) {
    .auth {
        &__img {
            display: none;
        }

        &__form {
            width: 100%;
            padding: 30px 20px;
        }
    }
}
</style>
