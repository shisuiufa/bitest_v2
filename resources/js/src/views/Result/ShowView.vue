<template>
    <div class="result" v-if="this.result">
        <div class="row mb-3">
            <div class="col-12">
                <div
                    class="result__header-wrap"
                    :style="`background-image: url('${this.result.image}')`"
                >
                    <div class="result__header w-100 w-md-50">
                        <h1
                            class="result__title text-white text-uppercase mb-2 text-center"
                        >
                            {{ this.result.title }}
                        </h1>
                        <div class="d-flex gap-4 mb-2">
                            <p
                                class="text-white mb-0 fs-5 text-center"
                                v-if="this.result.percent !== null"
                            >
                                Процент выполнения заданий:
                                {{ this.result.percent }}% <br/>
                                Оценка: {{ this.result.score }}
                            </p>
                            <p class="text-white mb-0 fs-5" v-else>
                                Тест еще не проверен
                            </p>
                        </div>
                        <Button v-if="this.result.answerCounter > 0"
                                @click="visibilityAnswers = true"
                                class="p-primary p-button-sm fs-6"
                                label="Ваши ответы"/>
                    </div>
                </div>
            </div>
        </div>
        <Dialog v-model:visible="visibilityAnswers" modal class="mx-2 mx-md-0 w-100 w-md-50">
            <template #header>
                <div class="inline-flex align-items-center justify-content-center gap-2">
                    <h4 class="mb-2">Ваши ответы</h4>
                    <p>Вы ответили на {{ this.result.answerCounter }} из {{ this.result.questionCounter }} вопросов</p>
                </div>
            </template>
            <ListAnswers
                :answers="this.result?.answers"
            />
        </Dialog>
    </div>
</template>

<script>
import UiButton from "@/components/UI/UiButton.vue";
import ListAnswers from "@/components/lists/ListAnswers.vue";
import {useLaravel} from "@/composables/useLaravel.ts";
import * as toast from "@/composables/useNotifications.ts";

const {result} = useLaravel();

export default {
    name: "ResultShowView",
    components: {ListAnswers, UiButton},
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
    },
    mounted() {
        this.getResult();
    },
};
</script>

<style scoped lang="scss">
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
