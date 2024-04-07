<template>
    <div class="test__question question">
        <div class="row">
            <div class="col-12">
                <h4 class="question__title mb-3">{{ selectedQuestion.name }}</h4>
            </div>
        </div>
        <div class="row">
            <div :class="selectedQuestion?.img ? 'col-6' : 'col-12'">
                <p class="question__desc">
                    {{ questionDesc }}
                </p>
                <question-options v-if="this.selectedQuestion"
                                  :testId="this.testId"
                                  :testUserId="this.testUserId"
                                  :selectedQuestion="this.selectedQuestion"
                                  @testError = "item => $emit('testError', item)"
                                  class="question__options">
                </question-options>
            </div>
            <div class="col-6" v-if="selectedQuestion?.image">
                <div class="question__wrap-img">
                    <img class="question__img" :src="selectedQuestion.image" :alt="selectedQuestion.name">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import QuestionOptions from "@/components/QuestionOptions.vue";

export default {
    name: 'QuestionDisplay',
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
        }
    },
    computed: {
        questionDesc() {
            return this.selectedQuestion.type > 0 ? 'Варианты ответов:' : 'Введите ваш ответ:';
        }
    },
    emits: ['testError'],
}
</script>

<style scoped lang="scss">
.question {
    &__title, &__options, &__desc {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    &__title {
        line-height: 1.2;

    }

    &__desc {
        color: var(--main-color);
        margin-bottom: 10px;
    }

    &__wrap-img {
        width: 100%;
        height: 100%;
        overflow: hidden;
        border-radius: 15px;
    }

    &__img {
        width: inherit;
        height: inherit;
        object-fit: cover;
        transition: all 0.3s ease;

        &:hover {
            transform: scale(1.2);
        }
    }
}
</style>
