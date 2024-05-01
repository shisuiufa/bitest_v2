<template>
    <div class="answers content">
        <div class="row">
            <div class="col-12">
                <p>Вы ответили на {{ answerCounter }} из {{ questionCounter }} заданных вопросов.</p>
            </div>
            <div class="col-12">
                <h4 class="mb-3">Ваши ответы:</h4>
            </div>
            <div class="col-12">
                <div class="answers__list d-flex flex-column gap-3">
                    <ListAnswersItem
                        v-for="(answer, idx) in answers" :key="idx"
                        :item="answer"
                        :number="idx + 1"
                        class="answers__item"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ListAnswersItem from "@/components/ListAnswersItem.vue";

export default {
    name: 'ListAnswers',
    components: {ListAnswersItem},
    props: {
        answerCounter: {
            required: true,
            type: Number
        },
        questionCounter: {
            required: true,
            type: Number
        },
        answers: {
            required: true,
            type: Object,
        }
    },
}
</script>
