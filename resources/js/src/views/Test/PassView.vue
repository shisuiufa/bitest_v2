<template>
    <panel v-if="!testError && !testPassed" class="p-3">
        <div class="test">
            <div class="row mb-3 align-items-center justify-content-between">
                <div class="col-9">
                    <question-switcher
                        v-if="this.test.questions"
                        :selectedQuestion="this.selectedQuestion"
                        :questions="this.test.questions"
                        :testId="this.test.id"
                        @select-question="
                            (item) => (this.selectedQuestion = item)
                        "
                        class="test__questions"
                    >
                    </question-switcher>
                </div>
                <div class="col-1 text-end" v-if="this.test.time > 0">
                    <ui-timer
                        @endTime="this.postTest"
                        :seconds="this.test.time"
                    ></ui-timer>
                </div>
                <div class="col-2 text-end">
                    <ui-button @click="this.modalShow = !this.modalShow"
                        >Завершить</ui-button
                    >
                </div>
            </div>
            <div class="row" v-if="selectedQuestion">
                <div class="col-12 mb-3">
                    <question-display
                        :testId="this.test?.id"
                        :testUserId="this.testUser.id"
                        :selectedQuestion="this.selectedQuestion"
                        @testError="(item) => this.endTest(item)"
                    >
                    </question-display>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <ui-button
                        @click="this.nextQuestion"
                        class="test__btn next-question"
                        >Следующий</ui-button
                    >
                </div>
            </div>
        </div>
    </panel>
    <div class="background" v-else></div>
    <modal-end-test
        :list="questionsUnanswered"
        :testPassed="testPassed"
        :loading="testLoading"
        :testError="testError"
        :messages="messages"
        :testId="this.test.id"
        @post-test="this.postTest()"
        @close-modal="this.modalShow = !this.modalShow"
        v-if="this.modalShow"
    >
    </modal-end-test>
</template>

<script>
import QuestionSwitcher from "@/components/QuestionSwitcher.vue";
import QuestionDisplay from "@/components/QuestionDisplay.vue";
import UiButton from "@/components/UI/UiButton.vue";
import UiTimer from "@/components/UiTimer.vue";
import ModalEndTest from "@/components/modals/ModalEndTest.vue";
import { mapActions, mapGetters } from "vuex";
import { useLaravel } from "@/composables/useLaravel.ts";
const { test } = useLaravel();

export default {
    name: "PassView",
    components: {
        QuestionSwitcher,
        QuestionDisplay,
        UiButton,
        UiTimer,
        ModalEndTest,
    },
    computed: {
        ...mapGetters(["getAnswersByQuestionId", "getUserAnswersByTestId"]),
        questionsUnanswered() {
            let unanswered = [];
            for (let i = 0; i < this.test.questions.length; i++) {
                let present = false;
                const question = this.test.questions[i];
                const answers = this.getAnswersByQuestionId([
                    this.test?.id,
                    question.id,
                ]);

                if (typeof answers === "object") {
                    present = true;
                }
                if (!present) {
                    const newObj = {
                        idx: i + 1,
                        question: question,
                    };
                    unanswered.push(newObj);
                }
            }
            return unanswered;
        },
    },
    data() {
        return {
            test: {
                id: null,
                questions: [],
                time: null,
            },
            testUser: {
                id: null,
            },
            selectedQuestion: null,
            idxSelectedQuestion: 0,
            testPassed: false,
            testLoading: false,
            testError: false,
            modalShow: false,
            messages: "",
        };
    },
    mounted() {
        this.getTestUser(this.$route.params.id, this.$route.params.testUserId);
    },
    methods: {
        ...mapActions(["clearAnswersTest"]),
        checkAnswers() {
            let testValidated = false;
            for (let i = 0; i < this.test.questions.length; i++) {
                const questionId = this.test.questions[i].id;
                let questionResolved = false;

                const answers = this.getAnswersByQuestionId([
                    this.test.id,
                    questionId,
                ]);
                if (typeof answers === "object") {
                    testValidated = true;
                    questionResolved = true;
                }

                if (!questionResolved) {
                    testValidated = false;
                    this.selectedQuestion = this.test.questions[i];
                    break;
                }
            }
            if (testValidated) {
                this.selectedQuestion = this.test.questions[0];
            }
        },
        nextQuestion() {
            if (!this.selectedQuestion) {
                this.selectedQuestion = this.test.questions[0];
            } else {
                const indexQuestion = this.test.questions.findIndex(
                    (question) => question.id === this.selectedQuestion.id,
                );
                if (this.test.questions[indexQuestion + 1]) {
                    this.selectedQuestion =
                        this.test.questions[indexQuestion + 1];
                } else {
                    this.selectedQuestion = this.test.questions[0];
                }
            }
        },
        async getTestUser(testId, testUserId) {
            await test
                .getTestUser(testId, testUserId)
                .then((res) => {
                    const test = res.data;
                    this.test.id = test.id;
                    this.testUser.id = test.test_user_id;
                    this.test.questions = test.questions;
                    this.test.time = test.time;
                    this.checkAnswers();
                })
                .catch((err) => {
                    if (err.response.status === 403) {
                        this.endTest(err.response.data);
                    }
                });
        },
        async postTest() {
            this.testLoading = true;
            await test
                .updateTestUser(this.test.id, this.testUser.id)
                .then((res) => {
                    this.testPassed = true;
                    this.clearAnswersTest(this.test.id);
                })
                .catch((err) => {
                    if (err.response.status === 403) {
                        this.endTest(err.response.data);
                    }
                })
                .finally((res) => {
                    this.testLoading = false;
                });
        },
        endTest(messages) {
            this.modalShow = true;
            this.testError = true;
            this.messages = messages;
        },
    },
};
</script>

<style scoped lang="scss">
.test {
    &__title {
        color: var(--main-color);
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 20px;
    }
}

.background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background-color: var(--body-bg);
    overflow: hidden;
    z-index: 1000;
}
</style>
