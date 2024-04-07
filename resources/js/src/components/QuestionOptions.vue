<template>
    <div class="options">
        <div v-if="this.selectedQuestion.type === 0" class="options__item">
            <input type="text" class="form-control options__input"
                   v-model="this.userAnswer.openAnswer"
                   @blur="updateAnswerDb">
        </div>
        <div v-else
             v-for="option in this.selectedQuestion.options"
             :key="option.id" class="options__item">
            <div class="form-check">
                <input v-if="this.selectedQuestion.type === 1"
                       name="option" type="radio"
                       :value="option.id" class="form-check-input options__check-input"
                       :id="'option-' + option.id"
                       @change="addToSelectedAnswers(option.id)"
                       :checked="this.userAnswer.selectedAnswers.find(answer => answer === option.id)"
                >
                <input v-if="this.selectedQuestion.type === 2"
                       name="option" type="checkbox"
                       :value="option.id" class="form-check-input options__check-input"
                       :id="'option-' + option.id"
                       v-model="this.userAnswer.selectedAnswers"
                >
                <label class="form-check-label" :for="'option-' + option.id">
                    {{ option.name }}
                </label>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import axios from "axios";

export default {
    name: "QuestionOptions",
    data() {
        return {
            userAnswer: {
                openAnswer: '',
                selectedAnswers: [],
            },
            selectedQuestionUpdated: false,
        }
    },
    props: {
        testId: {
            required: true
        },
        testUserId: {
            required: true
        },
        selectedQuestion: {
            type: Object,
            required: true,
        }
    },
    computed: {
        ...mapGetters(['getAnswersByQuestionId']),
    },
    watch: {
        selectedQuestion: {
            handler: function (newQuestion) {
                this.selectedQuestionUpdated = true;
                const answers = this.getAnswersByQuestionId([this.testId, newQuestion.id]);

                if (typeof answers === 'object') {
                    this.userAnswer.openAnswer = answers.userAnswer.openAnswer;
                    this.userAnswer.selectedAnswers = answers.userAnswer.selectedAnswers;
                } else {
                    this.userAnswer.openAnswer = '';
                    this.userAnswer.selectedAnswers = [];
                }
            },
            immediate: true,
        },
        userAnswer: {
            handler: function (newQuestion) {
                if (!this.selectedQuestionUpdated) {
                    if(!this.userAnswer.openAnswer){
                        this.updateAnswerDb()
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
        ...mapActions(['updateUserAnswer']),
        addToSelectedAnswers(optionId) {
            this.userAnswer.selectedAnswers = [optionId];
        },
        updateAnswerDb() {
            axios.post(`/api/tests/${this.testId}/test_user/${this.testUserId}/answers`,
                {
                    questionId: this.selectedQuestion?.id,
                    answer: this.userAnswer,
                })
                .then(res => {
                    this.updateUserAnswer([this.testId, this.selectedQuestion?.id, this.userAnswer.openAnswer, this.userAnswer.selectedAnswers])
                })
                .catch(err => {
                    if (err.response.status === 403) {
                        this.$emit('testError', err.response.data)
                    }
                })
        }
    },
    emits: ['testError'],
}
</script>

<style scoped lang="scss">
.options {
    &__item {
        font-size: 17px;
        margin-bottom: 10px;
    }

    &__check-input:checked {
        background-color: var(--brand-color);
        border-color: var(--brand-color);
    }

    &__input:focus {
        border-color: var(--border-color-focus);
        box-shadow: none;
    }
}
</style>
