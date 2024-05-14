<template>
    <div class="result" v-if="this.result">
        <div class="row mb-3">
            <div class="col-12">
                <div
                    class="result__header-wrap"
                    :style="`background-image: url('${this.result.image}')`"
                >
                    <div class="result__header">
                        <h1
                            class="result__title text-white text-uppercase mb-2 text-center"
                        >
                            {{ this.result.title }}
                        </h1>
                        <div class="d-flex gap-4 mb-2">
                            <p
                                class="text-white mb-0 fs-5 text-center"
                                v-if="this.result.percent"
                            >
                                Процент выполнения заданий:
                                {{ this.result.percent }}% <br />
                                Оценка: {{ this.result.score }}
                            </p>
                            <p class="text-white mb-0 fs-5" v-else>
                                Тест еще не проверен
                            </p>
                        </div>
                        <UiButton @click="showAnswers" class="fs-6"
                            >Ваши ответы</UiButton
                        >
                    </div>
                </div>
            </div>
        </div>
        <transition name="slide-fade">
            <div class="row pb-5" ref="answers" v-show="visibilityAnswers">
                <div class="col-12">
                    <ListAnswers
                        :answerCounter="this.result?.answerCounter"
                        :questionCounter="this.result.questionCounter"
                        :answers="this.result?.answers"
                    />
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import UiButton from "@/components/UI/UiButton.vue";
import ListAnswers from "@/components/ListAnswers.vue";
import { useLaravel } from "@/composables/useLaravel.ts";
import * as toast from "@/composables/useNotifications.ts";
const { result } = useLaravel();

export default {
    name: "ResultShowView",
    components: { ListAnswers, UiButton },
    data() {
        return {
            result: {},
            visibilityAnswers: false,
        };
    },
    methods: {
        async getResult() {
            await result
                .show(this.$route.params.id)
                .then((res) => {
                    this.result = res.data;
                })
                .catch((err) => {
                    toast.error(
                        "Ошибка загрузки результатов теста",
                        err.response.data.message,
                    );
                });
        },
        showAnswers() {
            this.visibilityAnswers = true;
            this.$nextTick(() => {
                const answersElement = this.$refs.answers;
                const currentPosition = window.scrollY;
                const targetPosition =
                    answersElement.getBoundingClientRect().top +
                    currentPosition -
                    60;
                window.scrollTo({
                    top: targetPosition,
                    behavior: "smooth",
                });
            });
        },
    },
    mounted() {
        this.getResult();
    },
};
</script>

<style scoped lang="scss">
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}

.result {
    &__header-wrap {
        width: 100%;
        height: calc(100vh - 190px);
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
        border-radius: 10px;
        overflow: hidden;
    }

    &__header {
        width: 50%;
        height: 100%;
        background-color: #ffffff10;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 5px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
}
</style>
