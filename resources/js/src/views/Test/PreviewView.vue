<template>
    <div class="preview">
        <div class="row align-items-center">
            <div class="order-2 order-lg-1 col-lg-6">
                <h2 class="preview__title">{{ this.test.title }}</h2>
                <p class="preview__desc">{{ this.test.desc }}</p>
                <div class="preview__meta">
                    <div class="preview__attempts">
                        {{ messageAttempts }}
                    </div>
                    <div class="preview__time">
                        {{ messageTime }}
                    </div>
                </div>
                <ButtonLink
                    v-if="!this.test.attempts || !this.userResult || this.userResult?.attempt < this.test.attempts"
                    :to="{ name: 'pass-test', params: { id: $route.params.id } }"
                    class="preview__btn btn btn-lg"
                >
                    {{ startButtonText }}
                </ButtonLink>
                <ButtonLink
                    v-else
                    class="preview__btn btn btn-lg"
                    :to="{ name: 'pass-test', params: { id: $route.params.id } }"
                >
                    Результаты
                </ButtonLink>
            </div>
            <div class="order-1 mb-4 order-lg-2 col-lg-6 mb-lg-0">
                <div class="preview__wrap">
                    <img draggable="false" class="preview__img" :src="this.test.image" :alt="this.test.title">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import ButtonLink from "@/components/UI/ButtonLink.vue";

export default {
    name: 'TestPreviewView',
    components: {ButtonLink},
    data() {
        return {
            test: {
                title: '',
                desc: '',
                attempts: 0,
                time: 0,
                image: '',
            },
            userResult: null,
        }
    },
    computed: {
        messageAttempts() {
            if (this.test.attempts) {
                if (this.userResult === null) {
                    return `Количество попыток: 0 / ${this.test.attempts}`
                } else if (this.userResult.attempt < this.test.attempts) {
                    return `Количество попыток: ${this.userResult.attempt} / ${this.test.attempts}`
                } else {
                    return 'Вы изчерпали все попытки на прохождение теста';
                }
            }
        },
        messageTime() {
            if (!this.userResult || this.userResult?.attempt < this.test.attempts) {
                if (this.test.time) {
                    return `Время на тест: ${this.test.time} минут`;
                } else {
                    return 'Время на тест: неограниченно';
                }
            }
        },
        startButtonText() {
            if (this.userResult?.status === 'ongoing') {
                return "Продолжить";
            } else {
                return "Начать";
            }
        }
    },
    mounted() {
        this.getTest(this.$route.params.id);
    },
    methods: {
        getTest(testId) {
            axios.get(`/api/tests/${testId}`)
                .then(res => {
                    const test = res.data.data;
                    this.test.title = test.title;
                    this.test.desc = test.desc;
                    this.test.attempts = test.attempts;
                    this.test.time = test.time;
                    this.test.image = test.image;
                    this.userResult = test.user_result;
                })
        }
    }
}
</script>

<style scoped lang="scss">
.preview {
    &__title {
        color: var(--main-color);
        font-size: 30px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    &__desc {
        color: var(--main-color);
        font-size: 15px;
        font-weight: 400;
        margin-bottom: 20px;
    }

    &__meta {
        color: var(--main-color);
        margin-bottom: 20px;
    }

    &__wrap {
        width: 100%;
        height: 600px;
        overflow: hidden;
        border-radius: 15px;
        position: relative;
        background-color: #2B2E33;
    }

    &__img {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: inherit;
        object-fit: cover;
        -khtml-user-select: none;
        -o-user-select: none;
        -moz-user-select: none;
        -webkit-user-select: none;
        user-select: none;
    }

    &__btn {
        color: #fff;
        background-color: var(--brand-color);
        transition: all 0.2s ease;

        &:hover {
            background-color: #000;
        }
    }
}
</style>
