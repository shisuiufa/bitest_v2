<template>
    <div class="wrap-table">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Тест</th>
                <th scope="col">Статус</th>
                <th scope="col">Проверенные</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <TableTestsItem @delete-test="testId => this.$emit('delete-test', testId)"
                            :test="test"
                            v-for="(test, index) in tests" :key="index">
            </TableTestsItem>
            </tbody>
        </table>
    </div>
</template>

<script>
import TableTestsItem from "@/components/TableTestsItem.vue";
import UiButton from "./UI/UiButton.vue";

export default {
    name: "TableTests",
    props: {
        tests: {
            type: Array,
            required: true,
        }
    },
    components: {
        UiButton,
        TableTestsItem
    },
    emits: ['delete-test'],
}
</script>

<style lang="scss">
.wrap-table {
    //overflow: auto;
    //max-height: 450px;
    scroll-behavior: smooth;
}

.table {
    margin: 0;

    thead {
        position: sticky;
        top: 0;
        z-index: 1;
        color: var(--main-color);
    }

    tbody {
        color: var(--main-color);
    }

    th, td {
        color: inherit;
        background-color: inherit;
        vertical-align: middle;
    }

    .media {
        display: flex;
        align-items: center;
        gap: 10px;

        &__img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            overflow: hidden;

            img {
                object-fit: cover;
                width: inherit;
                height: inherit;
            }
        }

    }

    .badge-dot {
        font-size: inherit;
        font-weight: 400;
        padding-right: 0;
        padding-left: 0;
        text-transform: none;
        background: transparent;
        color: var(--main-color);

        i {
            display: inline-block;
            width: 0.375rem;
            height: 0.375rem;
            margin-right: 0.375rem;
            vertical-align: middle;
            border-radius: 50%;
        }
    }

    .progress {
        width: 130px;
        height: 3px;
        margin: 0;
    }
}

.dropdown {
    position: relative;

    &__btn {
        position: relative;
        width: 36px;
        height: 36px;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: all .3s ease;
        border-radius: 5px;

        &:hover {
            box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 3px 0px, rgba(0, 0, 0, 0.06) 0px 1px 2px 0px;
        }
    }

    &__menu {
        z-index: 200;
        position: absolute;
        right: 0;
        width: 200px;
        border-radius: 16px;
        background-color: var(--body-bg);
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    &__list {
        list-style: none;
        padding: 10px 12px;
        margin: 0;
    }

    &__item {
        color: var(--main-color);
        text-decoration: none;
        display: flex;
        gap: 5px;
        transition: all .3s ease;
        font-size: 13px;
        cursor: pointer;
        padding: 10px 12px;
        border-radius: 8px;
        margin-bottom: 5px;

        &:hover {
            background-color: var(--button-bg);
        }
    }

}
</style>
