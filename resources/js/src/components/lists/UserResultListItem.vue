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
        return{
            answer: null,
        }
    },
    mounted() {
        this.answer = JSON.parse(this.item?.answer);
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
    <div class="col-12 mb-4 pb-4 border-bottom">
        <div class="d-flex align-items-center">
            <div class="d-flex flex-wrap gap-5 w-100">
                <Image v-show="item.question.image" src="https://klike.net/uploads/posts/2023-01/1675065366_4-1.jpg" alt="Image" width="250" preview />
                <div class="d-flex flex-column gap-2">
                    <h5>{{ item.question.name }}</h5>
                    <p v-if="this.answer?.openAnswer">
                        Ответ пользователя: {{ this.answer?.openAnswer }}
                    </p>
                </div>
                <div class="d-flex flex-column justify-content-between ms-auto">
                    <Tag
                        :value="answerStatusLabel(item.correct)"
                        :severity="answerStatusClass(item.correct)"
                    />
                    <div class="d-flex gap-3" v-if="item.correct === null">
                        <Button @click="this.$emit('success', [$event, item.id])" size="small" label="Верно" severity="success" icon="pi pi-check" iconPos="right" />
                        <Button @click="this.$emit('danger', [$event, item.id])" size="small" label="Неверно" severity="danger" icon="pi pi-times" iconPos="right" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
