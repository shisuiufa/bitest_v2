<template>
    <div class="row my-3">
        <div class="col-12">
            <div class="d-block d-md-flex justify-content-between">
                <Dropdown @change="getTests()"
                          class="w-100 w-md-auto mb-2 mb-md-0"
                          v-model="this.filter"
                          :options="filters"
                          optionLabel="name"
                          optionValue="value" placeholder="Выбрать фильтр"
                />
                <InputGroup class="w-100 w-md-auto">
                    <Button icon="pi pi-search" @click="getTests()"/>
                    <InputText placeholder="Поиск теста..." @keydown.enter="getTests" v-model="search"/>
                </InputGroup>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <card-list :tests="this.tests"></card-list>
        </div>
        <div class="col-12 text-center">
            <Button class="load-more"
                    @click="getTests(this.pagination.current_page + 1)"
                    v-if="this.pagination && this.pagination.current_page < this.pagination.last_page" unstyled
            >
                <i class="bi bi-arrow-clockwise"></i>
                Загрузить ещё
            </Button>
        </div>
    </div>
</template>

<script>
import UiButton from "@/components/UI/UiButton.vue";
import CardList from "@/components/lists/CardList.vue";
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
                {id: 1, name: "Сначала новые", value: TestFilter.New},
                {id: 2, name: "По категории", value: TestFilter.Category},
                {id: 3, name: "По алфавиту", value: TestFilter.Name},
                {id: 4, name: "По автору", value: TestFilter.Author},
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
                    filter: {
                        search: this.search,
                        sort_by: this.filter,
                    },
                })
                .then((res) => {
                    this.pagination = res.meta;

                    if (this.pagination.current_page === 1) {
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
    color: #fff;
    font-weight: 600;
    background-color: var(--primary-color);
    border: 1px solid var(--surface-border);
    padding: 10px 15px;
    border-radius: 5px;
    transition: all .5s ease;

    &:hover {
        color: #fff;
        background-color: var(--primary-color);
        transform: scale(1.05)
    }
}
</style>
