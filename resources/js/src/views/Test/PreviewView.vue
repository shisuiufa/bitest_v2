<template>
    <div class="preview">
        <div class="row align-items-center">
            <div class="order-2 order-lg-1 col-lg-6">
                <h2 class="preview__title">{{ this.test.title }}</h2>
                <p class="preview__desc">{{ this.test.desc }}</p>
                <div class="preview__meta">
                    <div class="preview__attempts">
                        <template v-if="this.test.attempts">
                            Количество попыток: 1 / {{ this.test.attempts }}
                        </template>
                        <template v-else>
                            Количество попыток: неограниченно
                        </template>

                    </div>
                    <div class="preview__time">
                        <template v-if="this.test.time">
                            Время на тест: {{ this.test.time }} минут
                        </template>
                        <template v-else>
                            Время на тест: неограниченно
                        </template>
                    </div>
                </div>
                <router-link :to="{ name: 'pass-test', params: { id: $route.params.id } }"
                             class="preview__btn btn btn-lg">Начать
                </router-link>
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

export default {
    name: 'TestPreviewView',
    data() {
        return {
            test: {
                title: '',
                desc: '',
                attempts: 0,
                time: 0,
                image: '',
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

                    if (!Boolean(test.published)) {
                        this.$router.push({name: 'home'})
                    }
                    this.test.title = test.title;
                    this.test.desc = test.desc;
                    this.test.attempts = test.attempts;
                    this.test.time = test.time;
                    this.test.image = test.image;
                })
                .catch(err => {
                    console.log(err)
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
        overflow: hidden;
        border-radius: 15px;
    }

    &__img {
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
