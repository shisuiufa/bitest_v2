<template>
    <div class="row">
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
        answers: {
            required: true,
            type: Object,
        },
    },
};
</script>
