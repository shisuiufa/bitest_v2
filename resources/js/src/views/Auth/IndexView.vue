<template>
    <div class="auth__form">
        <SignInForm v-if="showForm === 'login'"/>
        <ResetForm v-else-if="showForm === 'reset'"/>
        <SignOutForm v-else/>

        <div class="d-flex mt-3">
            <Button v-if="showForm === 'register' || showForm === 'reset'"
                    @click="showForm = 'login'" class="auth__link d-inline-block"
                    label="Войти в аккаунт"
                    link/>
            <Button v-if="showForm !== 'reset' && showForm !== 'register'"
                    @click="showForm = 'reset'" class="auth__link d-inline-block"
                    label="Забыл пароль"
                    link/>

            <Button v-if="showForm !== 'register'"
                    @click="showForm = 'register'" class="auth__link d-inline-block"
                    label="Создать аккаунт"
                    link/>
        </div>
    </div>
</template>

<script>
import SignOutForm from "@/components/form/SignOutForm.vue";
import SignInForm from "@/components/form/SignInForm.vue";
import ResetForm from "@/components/form/SendEmailForm.vue";
import {ref} from "vue";

export default {
    name: "IndexView",
    data() {
        return {
            showForm: ref('login'),
        };
    },
    components: {
        SignOutForm,
        SignInForm,
        ResetForm
    },
};
</script>
