<template>
    <div class="answer rounded p-3 bg-light bg-gradient d-flex flex-column gap-2">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="answer__title fw-medium">{{ number }}) {{ item?.question.name }}</h5>
            <div class="bg-gradient w-auto px-2 py-1 rounded text-white" :class="computedClass">
                {{ computedText }}
            </div>
        </div>
        <div class="answer__image rounded" v-show="item?.question.image">
            <img :src="item?.question.image" :alt="item?.question.name">
        </div>
        <div class="answer__options" v-if="item.question.type">
            <p class="mb-2">Варианты ответа:</p>
            <div class="form-check d-flex gap-2"
                 v-for="(option, idx) in item?.question.options" :key="idx">
                <input class="form-check-input opacity-100" type="checkbox" :id="idx"
                       :checked="checkedAnswer(option.id)" disabled>
                <label class="form-check-label fw-medium" for="checkPublish">
                    {{ option.name }}
                </label>
            </div>
        </div>
        <p v-else>
          Ваш ответ:  {{ this.answer?.openAnswer }}
        </p>
    </div>
</template>

<script>
export default {
    name: 'ListAnswersItem',
    props: {
        item: {
            type: Object,
            required: true,
        },
        number: {
            type: Number,
            required: true,
        }
    },
    data() {
        return {
            answer: null
        }
    },
    computed: {
        computedClass() {
            switch (this.item.correct) {
                case true:
                    return 'bg-success';
                case false:
                    return 'bg-danger';
                default:
                    return 'bg-warning';
            }
        },
        computedText() {
            switch (this.item.correct) {
                case true:
                    return 'Ответ верный';
                case false:
                    return 'Ответ не верный';
                default:
                    return 'Ответ на рассмотрении';
            }
        }
    },
    mounted() {
        this.answer = JSON.parse(this.item.answer);
    },
    methods: {
        checkedAnswer(id) {
            return this.answer?.selectedAnswers.some(answer => {
                return answer === id
            })
        }
    },
}
</script>

<style scoped lang="scss">
.answer {
    &__image {
        height: 100%;
        max-height: 450px;
        background-color: #1a1919;

        img {
            width: 100%;
            height: inherit;
            max-height: inherit;
            object-fit: contain;
        }
    }
}
</style>
