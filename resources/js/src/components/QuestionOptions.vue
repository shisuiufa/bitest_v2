<template>
    <div class="options d-flex flex-column gap-2">
        <template v-if="this.selectedQuestion?.type === QuestionType.Open" class="options__item">
            <Textarea v-model="this.userAnswer.openAnswer" class="w-100" autoResize rows="5" cols="30"/>
        </template>
        <template v-else>
            <div v-for="option in this.selectedQuestion?.options" :key="option.id"
                 class="options__wrap-check d-flex gap-2 align-items-center p-3 rounded"
                 style="background-color: var(--surface-hover)">
                <Checkbox v-model="this.userAnswer.selectedAnswers" :inputId="'answer-' + option.id"
                          :value="option.id"/>
                <label :for="'answer-' + option.id" class="ml-2"> {{ option.name }} </label>
            </div>
        </template>
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import {useLaravel} from "@/composables/useLaravel.ts";
import * as toast from "@/composables/useNotifications.ts";
import {QuestionType} from "@/models/question.ts";

const {test} = useLaravel();

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
        QuestionType() {
            return QuestionType
        },
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
        'userAnswer.openAnswer': {
            handler: function (newQuestion) {
                this.updateAnswerDb();
            },
        }
    },
    methods: {
        ...mapActions(["updateUserAnswer"]),
        async updateAnswerDb() {
            await test
                .updateAnswers(this.testId, this.testUserId, {
                    questionId: this.selectedQuestion?.id,
                    answer: this.userAnswer,
                })
                .then(() => {
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

