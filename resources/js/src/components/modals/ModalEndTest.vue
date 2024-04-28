<template>
    <div @click.self="this.closeModal()" class="modal">
        <div class="modal__box">
            <div class="modal__header">
                <h3 class="modal__title">
                    <template v-if="testPassed">
                        Тест завершен!
                    </template>
                    <template v-else-if="testError">
                        {{ messages }}
                    </template>
                    <template v-else>
                        Завершение теста
                    </template>
                </h3>
                <div v-show="!loading && !testPassed && !testError" @click="this.closeModal()" class="modal__close">
                    <i class="bi bi-x-lg"></i>
                </div>
            </div>
            <div class="modal__content">
                <div class="modal__info">
                    <template v-if="testPassed || testError">
                        <p class="modal__desc">
                            Нажмите кнопку ниже, чтобы узнать свои результаты и оценку.
                        </p>
                    </template>
                    <template v-else>
                        <p class="modal__desc">
                            Вы уверены, что хотите завершить тест?
                        </p>
                        <template v-if="list.length > 0">
                            <p class="modal__desc">
                                Вы не ответили на вопросы:
                            </p>
                            <ul class="modal__list">
                                <li v-for="item in list" :key="item.idx">
                                    {{ item.idx + ") " + item.question.name }}
                                </li>
                            </ul>
                        </template>
                    </template>
                </div>

            </div>
            <div class="modal__footer">
                <ui-button v-if="!testError && !loading && !testPassed" @click="this.$emit('post-test')">Завершить</ui-button>
                <button-link v-else-if="!loading && testPassed || testError" :to="{name: 'show-result', params: {id: testId}}">
                    Результаты
                </button-link>
                <button-spinner v-else>
                    Загрузка...
                </button-spinner>
            </div>
        </div>
    </div>
</template>

<script>
import UiButton from "@/components/UI/UiButton.vue";
import ButtonSpinner from "@/components/UI/ButtonSpinner.vue";
import ButtonLink from "@/components/UI/ButtonLink.vue";
import {mapGetters} from "vuex";

export default {
    name: "ModalEndTest",
    components: {
        UiButton,
        ButtonSpinner,
        ButtonLink
    },
    props: {
        testId: {
          type: Number,
          required: true,
        },
        list: {
            type: Array,
            required: false,
        },
        loading: {
            type: Boolean,
            required: true,
        },
        testPassed: {
            type: Boolean,
            required: true,
        },
        testError: {
            type: Boolean,
            required: true,
        },
        messages: {
            type: String,
            required: false,
        }
    },
    computed: {
        ...mapGetters(["getAnswersByQuestionId"]),
    },
    methods: {
        closeModal() {
            if (this.loading || this.testPassed|| this.testError) {
                return;
            }
            this.$emit('close-modal')
        },
    },
    emits: ['close-modal', 'post-test'],
}
</script>

<style scoped lang="scss">
.modal {
    position: fixed;
    top: 0;
    left: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);

    &__box {
        position: relative;
        background-color: var(--body-bg-secondary);
        width: 100%;
        max-width: 500px;
        border-radius: 10px;
        padding: 20px 30px;
    }

    &__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 25px;
    }

    &__close {
        cursor: pointer;
        color: var(--main-color);
        opacity: 0.5;
        transition: opacity .2s ease;

        &:hover {
            opacity: 1;
        }
    }

    &__content {
        padding: 10px 0;
        font-size: 15px;
    }

    &__title {
        font-size: inherit;
        margin-bottom: 0;
    }

    &__desc {
        font-size: inherit;
        margin-bottom: 10px;
    }

    &__list {
        list-style: none;
        color: var(--main-color);
        font-size: inherit;
        margin: 0 0 10px 0;
        padding: 0;
        max-height: 200px;
        overflow: auto;
        border-radius: 5px;
        background-color: var(--button-bg-second);

        li {
            padding: 10px 10px 0 10px;

            &:last-child {
                padding: 10px 10px;
            }
        }
    }

    &__footer {
        width: 100%;
        text-align: end;
    }
}
</style>
