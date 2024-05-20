<template>
    <div class="row">
        <div class="col-12">
            <p>
                Вы ответили на {{ this.answerCounter}} из {{ this.questionCounter}} вопросов
            </p>
        </div>
        <div class="col-12">
            <ListAnswersItem
                v-for="(answer, idx) in answers"
                :key="idx"
                :item="answer"
                :number="idx + 1"
                class="answers__item"
            />
        </div>
    </div>
</template>

<script>
import ListAnswersItem from "@/components/lists/ListAnswersItem.vue";

export default {
    name: "ListAnswers",
    components: {ListAnswersItem},
    props: {
        answerCounter: {
            required: true,
            type: Number,
        },
        questionCounter: {
            required: true,
            type: Number,
        },
        answers: {
            required: true,
            type: Object,
        },
    },
};
</script>
