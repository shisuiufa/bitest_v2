<template>
    <div class="filters row justify-content-between align-items-center">
        <div class="col-3">

        </div>
        <div class="col col-lg-2">
            <custom-select
                @select-item="(item) => this.filters.selected = item"
                class="filters__select"
                :item="this.filters">
            </custom-select>
        </div>
    </div>
</template>

<script>
import CustomSelect from "@/components/UI/CustomSelect.vue";
import {TestFilter} from "@/models/test.ts";
import {ref} from "vue";

export default {
    name: 'FilterNav',
    components: {
        CustomSelect
    },
    data() {
        return {
            input: {
                label: 'Поиск...',
                icon: '<i class="bi bi-search" data-v-8f3ed631=""></i>',
            },
            filters: {
                selected: ref(null),
                options: [
                    {id: 1, name: 'Новые', value: TestFilter.New},
                    {id: 2, name: 'Категория', value: TestFilter.Category},
                    {id: 3, name: 'Тест', value: TestFilter.Name},
                    {id: 4, name: 'Автор', value: TestFilter.Author}
                ]
            }
        }
    },
    watch: {
        'filters.selected': {
            handler(newValue, oldValue) {
                this.$emit('selectedFilter', newValue.value);
            }
        }
    },
    emits: ['selectedFilter'],
}
</script>

<style lang="scss">
.filters {
    &__search {
        .input {
            border-radius: 16px;
        }
    }

    &__select {

    }
}
</style>
