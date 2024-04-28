<template>
    <div class="row">
        <div class="col-12 mb-3">
            <form class="d-flex" @submit.prevent="this.getData()">
                <input class="form-control rounded-end-0" name="search" type="text" v-model="this.search"
                       placeholder="Поиск...">
                <button class="btn btn-primary rounded-start-0" type="submit">Найти</button>
            </form>
        </div>
        <div class="col-12 mb-3">
            <table-tests @delete-test="testId => deleteTest(testId)" v-if="this.tests.length > 0"
                         :tests="this.tests"></table-tests>
        </div>
        <div class="col-12 mb-3">
            <div class="col-12" v-if="pagination.last_page !== 1">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li @click.prevent="getData(pagination.current_page - 1)" class="page-item"
                            v-if="pagination.current_page !== 1"><a class="page-link" href="#">Назад</a></li>
                        <li :class="link.active ? 'active' : ''" class="page-item" v-for="link in pagination.links">
                            <template v-if="Number(link.label)
                        && (pagination.current_page - link.label < 2
                        && pagination.current_page - link.label > -2) || Number(link.label) === 1 || Number(link.label) === pagination.last_page ">
                                <a @click.prevent="getData(link.label)" class="page-link" href="#">{{
                                        link.label
                                    }}</a>
                            </template>
                            <template v-if="Number(link.label) &&
                        pagination.current_page !== 3 &&
                        pagination.current_page - link.label === 2 ||
                        Number(link.label) &&
                        pagination.current_page !== pagination.last_page -2 &&
                        pagination.current_page - link.label === -2">
                                <a class="page-link">...</a>
                            </template>
                        </li>
                        <li @click.prevent="getData(pagination.current_page + 1)" class="page-item"
                            v-if="pagination.current_page !== pagination.last_page"><a class="page-link"
                                                                                       href="#">Вперед</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="col-12">
            <router-link class="btn btn-primary" :to="{name: 'create-test'}">Создать</router-link>
        </div>
    </div>
</template>

<script>
import TableTests from "@/components/TableTests.vue";
import UiButton from "@/components/UI/UiButton.vue";
import axios from "axios";
import {mapActions, mapGetters} from "vuex";

export default {
    name: "IndexView",
    components: {
        UiButton,
        TableTests
    },
    computed: {
        ...mapGetters(['user']),
    },

    mounted() {
        this.getData();
    },
    data() {
        return {
            tests: [],
            search: '',
            pagination: [],
        }
    },
    methods: {
        ...mapActions(['clearStateTest']),
        getData(page = 1) {
            axios.get(`/api/moder/tests/created-by/${this.user.id}`, {
                params: {
                    'page': page,
                    'per_page': 5,
                    'search': this.search,
                }
            })
                .then((res) => {
                    this.tests = res.data.data;
                    this.pagination = res.data.meta;
                })
                .catch(err => {
                    console.log(err)
                })
        },
        deleteTest(testId) {
            axios.delete(`/api/moder/tests/${testId}`)
                .then(res => {
                    this.clearStateTest(testId);
                    this.getData();
                })
                .catch(err => {
                    console.log(err)
                })
        }
    },

}

</script>

<style scoped lang="scss">

</style>
