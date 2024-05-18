<template>
    <div class="row my-3">
        <div class="col-12">
            <Dropdown @change="getTests()" v-model="this.filter" :options="filters" optionLabel="name"
                      optionValue="value" placeholder="Выбрать фильтр"
                      style="width: 250px"/>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <card-list :tests="this.tests"></card-list>
        </div>
        <div class="col-12 text-center">
            <ui-button
                v-if="
                    this.pagination &&
                    this.pagination.current_page < this.pagination.last_page
                "
                class="load-more"
                @click="getTests(this.pagination.current_page + 1)"
            >
                <i class="bi bi-arrow-clockwise"></i>
                Загрузить ещё
            </ui-button>
        </div>
    </div>
</template>

<script>
import UiButton from "@/components/UI/UiButton.vue";
import CardList from "@/components/CardList.vue";
import {useLaravel} from "@/composables/useLaravel.ts";
import * as toast from "@/composables/useNotifications.ts";
import {TestFilter} from "@/models/test.ts";

const {test} = useLaravel();

export default {
    name: "HomeView",
    components: {
        CardList,
        UiButton,
    },
    data() {
        return {
            tests: [],
            search: "",
            filter: null,
            pagination: null,
            filters: [
                {id: 1, name: "Новые", value: TestFilter.New},
                {id: 2, name: "Категория", value: TestFilter.Category},
                {id: 3, name: "Тест", value: TestFilter.Name},
                {id: 4, name: "Автор", value: TestFilter.Author},
            ],
        };
    },
    mounted() {
        this.getTests();
        this.filter = this.filters[0];
    },
    methods: {
        async getTests(page = 1) {
            await test
                .index({
                    page: page,
                    per_page: 6,
                    search: this.search,
                    filter: this.filter,
                })
                .then((res) => {
                    this.pagination = res.data.meta;
                    if (page === 1) {
                        this.tests = res.data;
                    } else {
                        this.tests = this.tests.concat(res.data);
                    }
                })
                .catch((err) => {
                    toast.error(
                        "Возникла ошибка при загрузки тестов",
                        err.response.data.message,
                    );
                });
        },
    },
};
</script>

<style lang="scss">
.load-more {
    color: var(--main-color);
    font-weight: 600;
    background-color: transparent;
    border: 1px solid var(--border-color);

    &:hover {
        color: #fff;
        background-color: var(--brand-color);
        border: 1px solid transparent;
    }
}
</style>
