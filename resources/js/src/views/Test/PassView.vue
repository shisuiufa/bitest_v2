<template>
    <div class="test">
        <div class="row mb-3 align-items-center">
            <div class="col-9">
                <question-switcher
                    v-if="this.testUser.questions"
                    :selectedQuestion="this.selectedQuestion"
                    :questions="this.testUser.questions"
                    :testId="this.testUser.id"
                    @select-question="(item) => this.selectedQuestion = item"
                    class="test__questions">
                </question-switcher>
            </div>
            <div class="col-1 text-end">
                <ui-timer></ui-timer>
            </div>
            <div class="col-2 text-end">
                <ui-button @click="this.modalShow = !this.modalShow">Завершить</ui-button>
            </div>
        </div>
        <div class="row" v-if="selectedQuestion">
            <div class="col-12 mb-3">
                <question-display :testId="this.testUser?.id" :selectedQuestion="this.selectedQuestion"></question-display>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <ui-button @click="this.nextQuestion" class="test__btn next-question">Следующий</ui-button>
            </div>
        </div>
    </div>
    <modal-end-test :list="questionsUnanswered"
                    :testPassed="testPassed"
                    :loading="testLoading"
                    @post-test="this.postTest(this.$route.params.id)"
                    @close-modal="this.modalShow = !this.modalShow"
                    v-if="this.modalShow">
    </modal-end-test>
</template>

<script>
import QuestionSwitcher from "@/components/QuestionSwitcher.vue";
import QuestionDisplay from "@/components/QuestionDisplay.vue";
import UiButton from "@/components/UI/UiButton.vue";
import UiTimer from "@/components/UiTimer.vue";
import ModalEndTest from "@/components/modals/ModalEndTest.vue";
import {mapActions, mapGetters} from "vuex";
import axios from "axios";

export default {
    name: 'PassView',
    components: {
        QuestionSwitcher,
        QuestionDisplay,
        UiButton,
        UiTimer,
        ModalEndTest,
    },
    computed: {
        ...mapGetters(['getAnswersByQuestionId', 'getUserAnswersByTestId']),
        questionsUnanswered() {
            let unanswered = [];
            for (let i = 0; i < this.testUser.questions.length; i++) {
                let present = false;
                const question = this.testUser.questions[i];
                const answers = this.getAnswersByQuestionId([this.testUser?.id, question.id])

                if (typeof answers === 'object') {
                    present = true;
                }
                if (!present) {
                    const newObj = {
                        idx: i + 1,
                        question: question,
                    }
                    unanswered.push(newObj);
                }
            }
            return unanswered;
        }
    },
    data() {
        return {
            testUser: {
                id: null,
                questions: [],
            },
            selectedQuestion: null,
            idxSelectedQuestion: 0,
            testPassed: false,
            testLoading: false,
            modalShow: false,
        }
    },
    mounted() {
        this.getTest(this.$route.params.id);
    },
    methods: {
        ...mapActions(['clearCache']),
        checkAnswers() {
            let testValidated = false;
            for (let i = 0; i < this.testUser.questions.length; i++) {
                const questionId = this.testUser.questions[i].id;
                let questionResolved = false;

                const answers = this.getAnswersByQuestionId([this.testUser.id, questionId])

                if (typeof answers === 'object') {
                    testValidated = true;
                    questionResolved = true;
                }

                if (!questionResolved) {
                    testValidated = false;
                    this.selectedQuestion = this.testUser.questions[i];
                    break;
                }
            }
            if (testValidated) {
                this.selectedQuestion = this.testUser.questions[0];
            }
        },
        nextQuestion() {
            if (!this.selectedQuestion) {
                this.selectedQuestion = this.testUser.questions[0];
            } else {
                const indexQuestion = this.testUser.questions.findIndex(question => question.id === this.selectedQuestion.id);
                if (this.testUser.questions[indexQuestion + 1]) {
                    this.selectedQuestion = this.testUser.questions[indexQuestion + 1];
                } else {
                    this.selectedQuestion = this.testUser.questions[0];
                }
            }
        },
        getTest(testId) {
            axios.get(`/api/tests/${testId}/pass`)
                .then(res => {
                    const test = res.data.data;
                    this.testUser.id = test.id;
                    this.testUser.questions = test.questions;
                    this.checkAnswers();
                })
        },
        postTest(testId) {
            const userAnswers = this.getUserAnswersByTestId(this.testUser.id);
            this.testLoading = true;
            axios.post(`/api/tests/${testId}/pass`, {answers: userAnswers})
                .then(res => {
                   this.testPassed = true;
                })
                .finally(res => {
                    this.testLoading = false;
                })
        },
    },
}
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
</style>
