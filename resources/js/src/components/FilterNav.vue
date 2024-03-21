<template>
    <div class="filters row justify-content-between align-items-center">
        <div class="col-3">

        </div>
        <div class="col col-lg-2">
            <custom-select @select-item="(item) => this.filters.selected = item" class="filters__select" :item="this.filters"></custom-select>
        </div>
    </div>
</template>

<script>
import CustomSelect from "@/components/UI/CustomSelect.vue";
import {ref} from "vue";
export default {
    name: 'FilterNav',
    components: {
        CustomSelect
    },
    data(){
        return {
            input: {
                label: 'Поиск...',
                icon: '<i class="bi bi-search" data-v-8f3ed631=""></i>',
            },
            filters: {
                selected: ref(null),
                options: [
                    {id: 1, name: 'Новые', value: 'date'},
                    {id: 2, name: 'Категория', value: 'category'},
                    {id: 3, name: 'Тест', value: 'test'},
                    {id: 4, name: 'Автор', value: 'author'}
                ]
             }
        }
    },
}
</script>

<style lang="scss">
    .filters{
        &__search {
            .input {
                border-radius: 16px;
            }
        }
        &__select {

        }
    }
</style>
