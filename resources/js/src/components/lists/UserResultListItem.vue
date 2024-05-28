<script>
import {answerStatusLabel, answerStatusClass} from "@/utils/enum.ts";

export default {
    name: "UserResultListItem",
    props: {
        item: {
            type: Object,
            required: true,
        }
    },
    data() {
        return {
            answer: null,
        }
    },
    computed: {
        answer() {
            return JSON.parse(this.item?.answer);
        }
    },
    methods: {
        answerStatusLabel, answerStatusClass
    },
    emits: [
        'success', 'danger'
    ]
}
</script>

<template>
    <div class="col-12">
        <Tag
            :value="answerStatusLabel(item.correct)"
            :severity="answerStatusClass(item.correct)"
            class="d-block d-md-none mb-2 mb-md-0"
        />
        <div class="d-md-flex flex-wrap gap-md-3">
            <Image v-show="item.question.image"
                   :src="item.question.image"
                   alt="Image" width="200" preview
                   class="mb-2 mb-md-0"
            />
            <div class="d-flex flex-column gap-2">
                <h5  class="mb-2 mb-md-0">{{ item.question.name }}</h5>
                <p v-if="answer?.openAnswer" class="mb-2 mb-md-0">
                    Ответ пользователя: {{ answer?.openAnswer }}
                </p>
                <div v-else-if="answer?.selectedAnswers" v-for="option of item.question.options" :key="option.id"
                     class="d-flex gap-2 align-items-center cursor-none mb-2 mb-md-0">
                    <Checkbox v-model="answer.selectedAnswers"
                              :inputId="option.id"
                              name="option"
                              :value="option.id"
                    />
                    <label :for="option.id">{{ option.name }}</label>
                </div>
            </div>
            <div class="d-flex gap-2 flex-column justify-content-between ms-auto">
                <Tag
                    :value="answerStatusLabel(item.correct)"
                    :severity="answerStatusClass(item.correct)"
                    class="d-none d-md-block"
                />
                <div class="d-flex gap-3" v-if="item.correct === null">
                    <Button @click="this.$emit('success', [$event, item.id])" size="small" label="Верно"
                            severity="success" icon="pi pi-check" iconPos="right"/>
                    <Button @click="this.$emit('danger', [$event, item.id])" size="small" label="Неверно"
                            severity="danger" icon="pi pi-times" iconPos="right"/>
                </div>
            </div>
        </div>
    </div>
</template>
