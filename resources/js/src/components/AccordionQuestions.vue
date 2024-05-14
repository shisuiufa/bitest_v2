<template>
    <div v-if="dataLoad && questions.length > 0" class="col-12 mb-3">
        <div class="d-flex gap-2 align-items-center">
            <QuestionSwitcher
                @select-question="(item) => (selectedQuestion = item)"
                :selectedQuestion="selectedQuestion"
                :questions="questions"
            >
            </QuestionSwitcher>
        </div>
    </div>
    <div class="col-12" v-if="dataLoad">
        <QuestionCreationForm
            :questionsId="this.questions.map((question) => question.id)"
            :selectedQuestion="selectedQuestion"
            @add-question="addQuestion"
            @edit-question="editQuestion"
            @delete-question="deleteQuestion"
        >
        </QuestionCreationForm>
    </div>
</template>

<script>
import CustomSelect from "@/components/UI/CustomSelect.vue";
import QuestionSwitcher from "@/components/QuestionSwitcher.vue";
import QuestionCreationForm from "@/components/QuestionCreationForm.vue";
import { ref } from "vue";
import { mapActions, mapGetters } from "vuex";

export default {
    name: "AccordionQuestions",
    components: { QuestionCreationForm, QuestionSwitcher, CustomSelect },
    data() {
        return {
            dataLoad: false,
            selectedQuestion: ref(null),
            questions: [],
        };
    },
    computed: {
        ...mapGetters(["cachedTest"]),
    },
    props: {
        testId: {
            required: false,
        },
        testQuestions: {
            required: true,
        },
        event: {
            type: String,
            required: true,
        },
    },
    mounted() {
        let questions = this.testQuestions;
        const cachedTest = this.cachedTest.find((item) => {
            if (
                item[this.event] &&
                !this.testId &&
                item[this.event].questions
            ) {
                return item[this.event];
            }
            if (
                item[this.event] &&
                item[this.event].id === this.testId &&
                item[this.event].questions
            ) {
                return item[this.event];
            }
        });

        questions = cachedTest ? cachedTest[this.event].questions : questions;

        this.questions = questions ?? [];
        this.dataLoad = true;
    },

    methods: {
        ...mapActions(["updateQuestions"]),
        addQuestion(item) {
            this.questions.push(item);
        },
        editQuestion(newQuestion) {
            const targetIndex = this.questions.findIndex(
                (question) => question.id === newQuestion.id,
            );
            if (targetIndex !== -1) {
                this.questions[targetIndex] = newQuestion;
                this.selectedQuestion = null;
            }
        },
        deleteQuestion(questionId) {
            const targetIndex = this.questions.findIndex(
                (question) => question.id === questionId,
            );
            if (targetIndex !== -1) {
                this.questions.splice(targetIndex, 1);
                this.selectedQuestion = null;
            }
        },
    },
    watch: {
        questions: {
            deep: true,
            handler(newQuestions, oldValue) {
                this.updateQuestions([this.event, newQuestions, this.testId]);
            },
        },
    },
};
</script>

<style scoped lang="scss">
.add-question {
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 34px;
    height: 34px;
    font-size: 30px;
    color: #2dce89;
    border: 1px solid #2dce89;
    border-radius: 50%;
}
</style>
