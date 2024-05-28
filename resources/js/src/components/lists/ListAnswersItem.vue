<template>
    <div class="col-12 mb-4 pb-4 border-bottom">
        <div class="d-flex align-items-center">
            <div class="d-flex flex-wrap gap-4 w-100">
                <Image v-show="item.question.image" :src="item.question.image" alt="Image" width="250" preview />
                <div class="d-flex flex-column gap-2">
                    <h5>{{ item.question.name }}</h5>
                    <p v-if="answer?.openAnswer">
                        Ваш ответ: {{ answer?.openAnswer }}
                    </p>
                    <div v-else-if="answer?.selectedAnswers" v-for="option of item.question.options" :key="option.id"
                         class="d-flex gap-2 align-items-center cursor-none">
                        <Checkbox v-model="answer.selectedAnswers"
                                  :inputId="option.id"
                                  name="option"
                                  :value="option.id"
                        />
                        <label :for="option.id">{{ option.name }}</label>
                    </div>
                </div>
                <div class="d-flex flex-column justify-content-between ms-auto">
                    <Tag
                        :value="answerStatusLabel(item.correct)"
                        :severity="answerStatusClass(item.correct)"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {answerStatusLabel, answerStatusClass} from "@/utils/enum.ts";
export default {
    name: "ListAnswersItem",
    props: {
        item: {
            type: Object,
            required: true,
        },
        number: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            answer: null,
        };
    },
    mounted() {
        this.answer = JSON.parse(this.item.answer);
    },
    methods: {
        answerStatusLabel,
        answerStatusClass,
    },
};
</script>

