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
                <UiButton @click="startTest" v-show="startButtonText">
                    {{ startButtonText }}
                </UiButton>
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
import {TestStatus} from "@/models/test.ts";
import UiButton from "@/components/UI/UiButton.vue";
import StartTestModal from "@/components/modals/StartTestModal.vue";

export default {
    name: 'TestPreviewView',
    components: {StartTestModal, UiButton},
    data() {
        return {
            test: {
                id: '',
                title: '',
                desc: '',
                attempts: null,
                time: 0,
                image: '',
            },
            userResult: null,
            modalShow: false,
        }
    },
    computed: {
        messageAttempts() {
            if (this.test.attempts === null) {
                return 'Количество попыток: неограничено';
            } else if (
                this.userResult?.attempt < this.test.attempts ||
                this.userResult?.attempt === this.test.attempts &&
                this.userResult.status === TestStatus.Ongoing
            ) {
                const result = this.test.attempts - this.userResult.attempt;
                return `Количество доступных попыток: ${result === 0 ? 1 : result}`
            } else {
                return 'Вы изчерпали все попытки на прохождение теста';
            }
        },
        messageTime() {
            if (
                !this.test.attempts ||
                this.userResult?.attempt < this.test.attempts ||
                this.userResult?.attempt === this.test.attempts &&
                this.userResult.status === TestStatus.Ongoing
            ) {
                if (this.test.time) {
                    return `Время на тест: ${this.test.time / 60} мин`;
                } else {
                    return 'Время на тест: неограниченно';
                }
            }
        },
        startButtonText() {
            if (this.userResult?.status === TestStatus.Ongoing) {
                return "Продолжить";
            } else if (!this.test.attempts || this.userResult?.attempt < this.test.attempts) {
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
                    this.test.id = test.id;
                    this.test.title = test.title;
                    this.test.desc = test.desc;
                    this.test.attempts = test.attempts;
                    this.test.time = test.time_complete;
                    this.test.image = test.image;
                    this.userResult = test.user_result;
                })
        },
        startTest() {
            axios.post(`/api/tests/${this.test.id}/test_user`)
                .then(res => {
                    const userTestId = res.data.data.test_user_id;
                    this.$router.push({name: 'pass-test', params: {'id': this.test.id, 'testUserId': userTestId}});
                })
                .catch(err => {
                    console.log(err)
                })
        },
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
