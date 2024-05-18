<template>
    <panel>
        <div class="preview p-2 p-md-4">
            <div class="row align-items-center">
                <div class="order-2 order-lg-1 col-lg-6">
                    <h2 class="preview__title">{{ this.test.title }}</h2>
                    <p class="preview__desc fw-normal fs-6">
                        {{ this.test.desc }}
                    </p>
                    <div class="preview__meta">
                        <p
                            class="preview__attempts mb-1 fw-normal fs-6"
                        >
                            {{ messageAttempts }}
                        </p>
                        <p
                            class="preview__time mb-0 fw-normal fs-6"
                        >
                            {{ messageTime }}
                        </p>
                    </div>
                    <Button
                        v-show="startButtonText"
                        @click="startTest"
                        :label="startButtonText"
                    />
                </div>
                <div class="order-1 mb-4 order-lg-2 col-lg-6 mb-lg-0">
                    <div class="preview__wrap">
                        <img
                            class="preview__img"
                            :src="this.test.image"
                            :alt="this.test.title"
                        />
                    </div>
                </div>
            </div>
        </div>
    </panel>
</template>

<script>
import { TestStatus } from "@/models/test.ts";
import { useLaravel } from "@/composables/useLaravel.ts";
import * as toast from "@/composables/useNotifications.ts";
const { test } = useLaravel();

export default {
    name: "TestPreviewView",
    data() {
        return {
            test: {
                id: "",
                title: "",
                desc: "",
                attempts: null,
                time: 0,
                image: "",
            },
            userResult: null,
            modalShow: false,
        };
    },
    computed: {
        messageAttempts() {
            if (this.test.attempts === null) {
                return "Количество попыток: неограничено";
            }
            if (
                !this.userResult ||
                this.userResult?.attempt < this.test.attempts ||
                (this.userResult?.attempt === this.test.attempts &&
                    this.userResult.status === TestStatus.Ongoing)
            ) {
                const result = this.userResult?.attempt
                    ? this.test.attempts - this.userResult?.attempt ?? 0
                    : this.test.attempts;
                return `Количество доступных попыток: ${result === 0 ? 1 : result}`;
            } else {
                return "Вы изчерпали все попытки на прохождение теста";
            }
        },
        messageTime() {
            if (
                !this.userResult ||
                !this.test.attempts ||
                this.userResult?.attempt < this.test.attempts ||
                (this.userResult?.attempt === this.test.attempts &&
                    this.userResult.status === TestStatus.Ongoing)
            ) {
                if (this.test.time) {
                    return `Время на тест: ${this.test.time / 60} мин`;
                } else {
                    return "Время на тест: неограниченно";
                }
            }
        },
        startButtonText() {
            if (this.userResult?.status === TestStatus.Ongoing) {
                return "Продолжить";
            } else if (
                !this.test.attempts ||
                !this.userResult ||
                this.userResult?.attempt < this.test.attempts
            ) {
                return "Начать";
            }
        },
    },
    mounted() {
        this.getTest(this.$route.params.id);
    },
    methods: {
        async getTest(testId) {
            await test
                .show(testId)
                .then((res) => {
                    const test = res.data;
                    this.test.id = test.id;
                    this.test.title = test.title;
                    this.test.desc = test.desc;
                    this.test.attempts = test.attempts;
                    this.test.time = test.time_complete;
                    this.test.image = test.image;
                    this.userResult = test.user_result;
                })
                .catch((err) => {
                    toast.error(
                        "Ошибка загрузки теста",
                        err.response.data.message,
                    );
                });
        },
        async startTest() {
            await test
                .start(this.test.id)
                .then((res) => {
                    const userTestId = res.data.test_user_id;
                    this.$router.push({
                        name: "pass-test",
                        params: { id: this.test.id, testUserId: userTestId },
                    });
                })
                .catch((err) => {
                    toast.error("Ошибка", err.response.data.message);
                });
        },
    },
};
</script>

<style scoped lang="scss">
.preview {
    &__title {
        color: var(--surface-900);
        font-size: 30px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    &__desc {
        color: var(--surface-700);
        font-size: 15px;
        font-weight: 400;
        margin-bottom: 20px;
    }

    &__meta {
        color: var(--surface-700);
        margin-bottom: 20px;
    }

    &__wrap {
        width: 100%;
        height: 600px;
        overflow: hidden;
        border-radius: 10px;
        position: relative;
        background-color: #2b2e33;
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
