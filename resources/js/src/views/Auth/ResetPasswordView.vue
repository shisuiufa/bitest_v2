<template>
    <div class="auth__form">
        <ResetPasswordForm/>
    </div>
</template>

<script>
import ResetPasswordForm from "@/components/form/ResetPasswordForm.vue";
export default {
    name: "ResetPasswordView",
    components: {
        ResetPasswordForm
    }
}
</script>
