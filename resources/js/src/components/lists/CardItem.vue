<template>
    <article class="card">
        <div class="card__wrap-picture">
            <img
                class="card__picture"
                :src="this.test.image"
                :alt="this.test.title"
            />
        </div>
        <div class="card__content">
            <router-link :to="''" class="card__category">
                Категория
            </router-link>
            <h3 class="card__title">{{ this.test.title }}</h3>
            <div class="card__meta">
                <span class="card__author">
                    <router-link
                        :to="{
                            name: 'authors',
                            params: { id: this.test.author.id },
                        }"
                        class="card__author_link"
                    >
                        {{
                            this.test.author.last_name +
                            " " +
                            this.test.author.first_name
                        }}
                    </router-link>
                </span>
                -
                <span class="card__date">
                    {{ formatDate(test.created_at) }}</span
                >
            </div>
            <router-link :to="{ name: 'tests', params: { id: this.test.id } }">
                <Button class="p-primary p-button-sm" label="Перейти" />
            </router-link>
        </div>
    </article>
</template>

<script>
import {formatDate} from "@/utils/date.ts";

export default {
    name: "CardItem",
    props: {
        test: {
            type: Object,
            required: true,
        },
    },
    methods: {
        formatDate,
    },
};
</script>

<style scoped lang="scss">
.card {
    position: relative;
    border-radius: 10px;
    border: none;
    padding: 20px;
    height: 100%;
    min-height: 300px;
    overflow: hidden;
    background-color: transparent;

    &:hover &__picture  {
        transform: scale(1.2);
    }

    &__wrap-picture {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1;
        width: 100%;
        height: 100%;
        &::after {
            position: absolute;
            top: 0;
            left: 0;
            width: inherit;
            height: inherit;
            content: '';
            background-color: rgba(29, 30, 35, 0.4);
        }
    }

    &__picture {
        width: inherit;
        height: inherit;
        object-fit: cover;
        transition: all 0.3s ease;
    }

    &__content {
        position: relative;
        z-index: 2;
        width: 100%;
        padding: 22px 20px;
        margin-top: auto;
    }

    &__category {
        font-size: 11px;
        color: #fff;
        padding: 3px 7px;
        background-color: rgba(0, 0, 0, 0.7);
        line-height: 13px;
        font-weight: 500;
        text-transform: uppercase;
        pointer-events: auto;
        text-decoration: none;
    }

    &__title {
        color: #fff;
        margin: 5px 0;
        font-size: 20px;
        font-weight: 600;
        line-height: 34px;
        text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);
    }

    &__meta {
        font-size: 13px;
        color: #fff;
        margin-bottom: 10px;
        font-weight: 500;
    }

    &__author {
        &_link {
            text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);
            color: #fff;
            text-decoration: none;
        }
    }
}
</style>
