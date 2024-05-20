<template>
    <div class="options">
        <div v-if="this.selectedQuestion.type === 0" class="options__item">
            <Textarea v-model="this.userAnswer.openAnswer" class="w-100" autoResize rows="5" cols="30" @blur="updateAnswerDb" />
        </div>
        <div
            v-else
            v-for="option in this.selectedQuestion.options"
            :key="option.id"
            class="options__item"
        >
            <div class="form-check">
                <input
                    v-if="this.selectedQuestion.type === 1"
                    name="option"
                    type="radio"
                    :value="option.id"
                    class="form-check-input options__check-input"
                    :id="'option-' + option.id"
                    @change="addToSelectedAnswers(option.id)"
                    :checked="
                        this.userAnswer.selectedAnswers.find(
                            (answer) => answer === option.id,
                        )
                    "
                />
                <input
                    v-if="this.selectedQuestion.type === 2"
                    name="option"
                    type="checkbox"
                    :value="option.id"
                    class="form-check-input options__check-input"
                    :id="'option-' + option.id"
                    v-model="this.userAnswer.selectedAnswers"
                />
                <label class="form-check-label" :for="'option-' + option.id">
                    {{ option.name }}
                </label>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { useLaravel } from "@/composables/useLaravel.ts";
import * as toast from "@/composables/useNotifications.ts";
const { test } = useLaravel();

export default {
    name: "QuestionOptions",
    data() {
        return {
            userAnswer: {
                openAnswer: "",
                selectedAnswers: [],
            },
            selectedQuestionUpdated: false,
        };
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
        ...mapGetters(["getAnswersByQuestionId"]),
    },
    watch: {
        selectedQuestion: {
            handler: function (newQuestion) {
                this.selectedQuestionUpdated = true;
                const answers = this.getAnswersByQuestionId([
                    this.testId,
                    newQuestion.id,
                ]);

                if (typeof answers === "object") {
                    this.userAnswer.openAnswer = answers.userAnswer.openAnswer;
                    this.userAnswer.selectedAnswers =
                        answers.userAnswer.selectedAnswers;
                } else {
                    this.userAnswer.openAnswer = "";
                    this.userAnswer.selectedAnswers = [];
                }
            },
            immediate: true,
        },
        userAnswer: {
            handler: function (newQuestion) {
                if (!this.selectedQuestionUpdated) {
                    if (!this.userAnswer.openAnswer) {
                        this.updateAnswerDb();
                    }
                } else {
                    this.selectedQuestionUpdated = false;
                }
            },
            immediate: true,
            deep: true,
        },
    },
    methods: {
        ...mapActions(["updateUserAnswer"]),
        addToSelectedAnswers(optionId) {
            this.userAnswer.selectedAnswers = [optionId];
        },
        async updateAnswerDb() {
            await test
                .updateAnswers(this.testId, this.testUserId, {
                    questionId: this.selectedQuestion?.id,
                    answer: this.userAnswer,
                })
                .then((res) => {
                    this.updateUserAnswer([
                        this.testId,
                        this.selectedQuestion?.id,
                        this.userAnswer.openAnswer,
                        this.userAnswer.selectedAnswers,
                    ]);
                })
                .catch((err) => {
                    if (err.response.status === 403) {
                        this.$emit("testError", err.response.data);
                    } else {
                        toast.error("Ошибка!", err.response.data.message);
                    }
                });
        },
    },
    emits: ["testError"],
};
</script>

<style scoped lang="scss">
.options {
    &__item {
        font-size: 17px;
        margin-bottom: 10px;
    }

    &__check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    &__input:focus {
        border-color: var(--surface-border);
        box-shadow: none;
    }
}
</style>
