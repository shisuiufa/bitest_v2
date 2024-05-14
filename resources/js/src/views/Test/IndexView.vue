<template>
    <panel header="Список созданных тестов">
        <div class="row">
            <div class="col-12 mb-3">
                <table-tests
                    @clear-filters="this.initFilters()"
                    @delete-test="testId => deleteTest(testId)"
                    v-model:filters="this.filters"
                    :tests="this.tests"
                >
                </table-tests>
            </div>
        </div>
    </panel>
</template>

<script lang="ts">
import TableTests from "@/components/tables/TableTests.vue";
import {mapActions, mapGetters} from "vuex";
import * as toast from "@/composables/useNotifications.ts";
import {useLaravel} from "@/composables/useLaravel";

const {test} = useLaravel();
export default {
    name: "IndexView",
    components: {
        TableTests
    },
    computed: {
        ...mapGetters(['user']),
    },
    mounted() {
        this.fetchTestData();
    },
    data() {
        return {
            tests: [],
            pagination: [],
            filters: {}
        }
    },
    created() {
        this.initFilters()
    },
    methods: {
        ...mapActions(['clearStateTest']),
        async fetchTestData(page = 1) {
            await test.getTestsAuthor(this.user.id, {
                'page': page,
                'per_page': 5,
            })
                .then((res) => {
                    this.tests = res.data
                })
                .catch((err) => {
                    toast.error('Ошибка загрузки тестов', 'К сожалению, мы не смогли загрузить ваши тесты. Пожалуйста, попробуйте еще раз.')
                })
        },
        async deleteTest(testId) {
           await test.destroy(testId)
                .then((res) => {
                    this.clearStateTest(testId);
                    this.fetchTestData();
                    toast.success('Тест удален!')
                })
                .catch((err) => {
                    toast.error('Ошибка при удалении теста!', err.data)
                })
        },
        initFilters() {
            this.filters = {
                global: {value: null},
            };
        },
    },
}
</script>
