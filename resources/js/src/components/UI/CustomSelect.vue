<template>
    <div class="select" @blur="open = false" :tabindex="tabindex">
        <div class="select__header" @click="open = !open">
            <span>
                {{ this.selected?.name }}
            </span>
            <i class="bi bi-chevron-down"></i>
        </div>
        <div v-if="this.open" class="select__menu">
            <div
                class="select__item"
                v-for="(option, index) of this.item.options"
                :key="index"
                :class="{ select__item_active: option === this.selected }"
                @click="
                    this.selected = option;
                    open = false;
                    $emit('select-item', option);
                "
            >
                {{ option.name }}
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";

export default {
    name: "CustomSelect",
    props: {
        item: {
            options: {
                type: Array,
                required: true,
            },
        },
        tabindex: {
            type: Number,
            required: false,
            default: 0,
        },
    },
    data() {
        return {
            selected: ref(null),
            open: false,
        };
    },
    watch: {
        "item.selected": {
            immediate: true,
            handler(newVal) {
                if (newVal === null) {
                    this.selected = this.item.options[0];
                    this.$emit("select-item", this.item.options[0]);
                } else {
                    this.selected = newVal;
                }
            },
        },
    },
    emits: ["select-item"],
};
</script>

<style scoped lang="scss">
.select {
    position: relative;
    cursor: pointer;
    color: var(--main-color);
    font-size: 14px;
    &__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border: 1px solid var(--border-color);
        font-weight: 600;
        background-color: var(--body-bg);
        border-radius: 8px;
        span,
        i {
            transition: inherit;
        }
        &:hover {
            border: 1px solid var(--border-color-two);
        }
        &_active {
            border: 1px solid var(--border-color-two);
            i {
                transform: rotate(180deg);
            }
        }
    }
    &__menu {
        position: absolute;
        top: 45px;
        left: 0;
        z-index: 3;
        width: 100%;
        max-height: 250px;
        overflow: auto;
        border-radius: 8px;
        background-color: var(--body-bg);
        padding: 10px;
        border: 1px solid var(--border-color);
    }
    &__item {
        padding: 10px;
        border-radius: 8px;
        font-weight: 600;
        margin-bottom: 5px;
        &:hover,
        &_active {
            background-color: var(--button-bg);
        }
    }
}
</style>
