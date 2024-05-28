<template>
    <div class="test__question question">
        <div class="row">
            <div class="mb-2 mb-md-0 col-12 col-md-6" v-if="selectedQuestion?.image">
                <h4 class="question__title mb-3 d-block d-md-none">
                    {{ selectedQuestion.name }}
                </h4>
                <Image class="w-100 question__image" preview>
                    <template #indicatoricon>
                        <i class="pi pi-search"></i>
                    </template>
                    <template #image>
                        <img class="w-100" :src="selectedQuestion.image" :alt="selectedQuestion.name" />
                    </template>
                    <template #preview="slotProps">
                        <img :src="selectedQuestion.image" :alt="selectedQuestion.name" :style="slotProps.style" @click="slotProps.onClick" />
                    </template>
                </Image>
            </div>
            <div class="col-12" :class="selectedQuestion?.image ? 'col-md-6' : 'col-md-12'">
                <h4 :class="!selectedQuestion?.image ? 'd-block' : 'd-none'" class="question__title mb-3 d-md-block">
                    {{ selectedQuestion.name }}
                </h4>
                <p class="question__desc">
                    {{ questionDesc }}
                </p>
                <question-options
                    v-if="this.selectedQuestion"
                    :testId="this.testId"
                    :testUserId="this.testUserId"
                    :selectedQuestion="this.selectedQuestion"
                    @testError="(item) => $emit('testError', item)"
                    class="question__options"
                >
                </question-options>
            </div>
        </div>
    </div>
</template>

<script>
import QuestionOptions from "@/components/QuestionOptions.vue";
import {QuestionType} from "@/models/question.ts";

export default {
    name: "QuestionDisplay",
    components: {
        QuestionOptions,
    },
    props: {
        testId: {
            required: true,
        },
        testUserId: {
            required: true,
        },
        selectedQuestion: {
            type: Object,
            required: true,
        },
    },
    computed: {
        questionDesc() {
            return this.selectedQuestion.type === QuestionType.Open
                ? "Введите ваш ответ:"
                : "Варианты ответов:";
        },
    },
    emits: ["testError"],
};
</script>

<style scoped lang="scss">
.question {
    &__title,
    &__options,
    &__desc {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        color: var(--surface-900);
    }

    &__title {
        line-height: 1.2;
    }

    &__desc {
        margin-bottom: 10px;
    }

    &__wrap-img {
        width: 100%;
        height: 550px;
        overflow: hidden;
        border-radius: 15px;
        background-color: #1a1919;
    }
}
</style>
